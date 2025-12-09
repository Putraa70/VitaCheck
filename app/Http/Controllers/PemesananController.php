<?php

namespace App\Http\Controllers;

use App\Http\Requests\SimpanPemesananRequest;
use App\Models\{Pemesanan, SlotWaktu, JenisTes, Pembayaran, BerkasPemesanan};
use App\Services\AntrianService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\HasilTes;

class PemesananController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /** INDEX: daftar pemesanan milik pengguna */
    public function indeks()
    {
        $pemesanan = Pemesanan::with(['jenisTes', 'slotWaktu'])
            ->where('pengguna_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('pemesanan.indeks', compact('pemesanan'));
    }

    /** FORM PEMESANAN BARU */
    public function buat()
    {
        $jenisTes = JenisTes::where('aktif', true)
            ->orderBy('nama')
            ->get();

        $slotWaktu = SlotWaktu::whereDate('tanggal', '>=', now())
            ->orderBy('tanggal')
            ->orderBy('mulai')
            ->limit(100)
            ->get();

        return view('pemesanan.buat', compact('jenisTes', 'slotWaktu'));
    }

    /** SIMPAN: fallback form lama → arahkan ke checkout */
    public function simpan(SimpanPemesananRequest $r)
    {
        return $this->checkout($r);
    }

    /**
     * CHECKOUT:
     * - Lock slot
     * - Simpan pemesanan
     * - Upload berkas
     * - Buat transaksi Midtrans (Snap)
     */
    public function checkout(SimpanPemesananRequest $r)
    {
        $HOLD_MINUTES = 15;

        [$pesan, $snapToken] = DB::transaction(function () use ($r, $HOLD_MINUTES) {
            /** 🔒 Lock slot */
            $slot  = SlotWaktu::whereKey($r->slot_waktu_id)->lockForUpdate()->firstOrFail();
            $jenis = JenisTes::whereKey($r->jenis_tes_id)->firstOrFail();

            /** Hitung sisa kuota real-time */
            $sisa = ($slot->kuota ?? 0) - ($slot->terpesan ?? 0) - ($slot->dihold ?? 0);
            if ($sisa <= 0) {
                abort(422, 'Kuota slot penuh, silakan pilih jadwal lain.');
            }

            /** Normalisasi biaya */
            $rawBiaya = is_string($jenis->biaya)
                ? preg_replace('/\D+/', '', $jenis->biaya)
                : $jenis->biaya;

            $amount = (int) $rawBiaya;
            if ($amount < 1) {
                abort(422, 'Biaya jenis tes belum valid.');
            }

            /** Simpan pemesanan baru */
            $pesan = Pemesanan::create([
                'kode'             => 'BK-UNILA-' . now()->format('Y') . '-' . Str::upper(Str::random(6)),
                'pengguna_id'      => auth()->id(),
                'jenis_tes_id'     => $jenis->id,
                'slot_waktu_id'    => $slot->id,
                'status'           => 'menunggu_bayar',
                'total_bayar'      => $amount,
                'kedaluwarsa_pada' => now()->addMinutes($HOLD_MINUTES),
            ]);

            /** Update hold kuota */
            $slot->increment('dihold');

            /** Simpan berkas (KTM / bukti bayar) jika diupload */
            foreach (['ktm', 'bukti_bayar'] as $jenisBerkas) {
                if ($r->hasFile($jenisBerkas)) {
                    $path = $r->file($jenisBerkas)->store("pemesanan/{$pesan->id}/{$jenisBerkas}", 'public');
                    BerkasPemesanan::updateOrCreate(
                        ['pemesanan_id' => $pesan->id, 'jenis' => $jenisBerkas],
                        ['lokasi_berkas' => $path]
                    );
                }
            }

            /** Param Midtrans */
            $params = [
                'transaction_details' => [
                    'order_id'     => $pesan->kode,
                    'gross_amount' => $amount,
                ],
                'customer_details' => [
                    'first_name' => auth()->user()->name,
                    'email'      => auth()->user()->email,
                ],
                'item_details' => [[
                    'id'       => $jenis->id,
                    'price'    => $amount,
                    'quantity' => 1,
                    'name'     => 'Tes: ' . $jenis->nama,
                ]],
                'expiry' => [
                    'start_time' => now()->format('Y-m-d H:i:s O'),
                    'unit'       => 'minute',
                    'duration'   => $HOLD_MINUTES,
                ],
                'callbacks' => [
                    'finish' => route('pemesanan.sukses', ['order_id' => $pesan->kode]),
                ],
            ];

            /** Snap token Midtrans */
            $snapToken = app(\App\Services\MidtransClient::class)->createSnapToken($params);

            /** Simpan pembayaran lokal */
            Pembayaran::create([
                'pemesanan_id' => $pesan->id,
                'gateway'      => 'midtrans',
                'order_id'     => $pesan->kode,
                'jumlah'       => $amount,
                'status'       => 'pending',
            ]);

            return [$pesan, $snapToken];
        });

        return view('pemesanan.bayar', compact('pesan', 'snapToken'));
    }

    /** DETAIL PEMESANAN (hanya milik user login) */
    public function lihat(string $kode)
    {
        $p = Pemesanan::with([
            'jenisTes',
            'slotWaktu',
            'berkasPemesanan',
            'hasilTes',
        ])
            ->where('kode', $kode)
            ->where('pengguna_id', auth()->id())
            ->firstOrFail();

        return view('pemesanan.lihat', compact('p'));
    }

    /**
     * SUKSES PEMBAYARAN (DEV TANPA WEBHOOK)
     */
    public function sukses(Request $r, AntrianService $antrian)
    {
        $orderId = $r->query('order_id');

        if (!$orderId) {
            abort(404, 'Order ID tidak ditemukan.');
        }

        $pemesanan = null;

        DB::transaction(function () use ($orderId, $antrian, &$pemesanan) {
            /** @var Pemesanan $pemesanan */
            $pemesanan = Pemesanan::where('kode', $orderId)
                ->where('pengguna_id', auth()->id())
                ->lockForUpdate()
                ->firstOrFail();

            $oldStatus = $pemesanan->status;

            // kalau sudah terbayar/terkonfirmasi, jangan ubah kuota lagi
            if (in_array($oldStatus, ['terbayar', 'terkonfirmasi'], true)) {
                $antrian->tetapkanAntrianDanQr($pemesanan);
                return;
            }

            /** @var SlotWaktu|null $slot */
            $slot = SlotWaktu::whereKey($pemesanan->slot_waktu_id)
                ->lockForUpdate()
                ->first();

            if ($slot && $oldStatus === 'menunggu_bayar') {
                if (!is_null($slot->dihold) && $slot->dihold > 0) {
                    $slot->decrement('dihold');
                }
                $slot->increment('terpesan');
            }

            $pemesanan->status       = 'terbayar';
            $pemesanan->dibayar_pada = now();
            $pemesanan->metode_bayar = $pemesanan->metode_bayar ?? 'midtrans_dev';
            $pemesanan->save();

            $antrian->tetapkanAntrianDanQr($pemesanan);
        });

        return redirect()
            ->route('pemesanan.lihat', $pemesanan->kode)
            ->with('sukses', 'Pembayaran berhasil, pemesanan sudah ditandai terbayar.');
    }

    /**
     * USER: lihat / download berkas pemesanan miliknya sendiri.
     */
    public function lihatBerkas(BerkasPemesanan $berkas)
    {
        // pastikan hanya pemilik pemesanan yang boleh akses
        $pemesanan = $berkas->pemesanan; // pastikan relasi ada di model BerkasPemesanan

        if (! $pemesanan || $pemesanan->pengguna_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengakses berkas ini.');
        }

        $path = $berkas->lokasi_berkas;

        if (! Storage::disk('public')->exists($path)) {
            abort(404, 'Berkas tidak ditemukan.');
        }

        // tampilkan langsung di browser (PDF / gambar)
        return response()->file(
            Storage::disk('public')->path($path)
        );

        // kalau mau force download:
        // return Storage::disk('public')->download($path);
    }

    public function lihatHasil(HasilTes $hasilTes)
    {
        // Pastikan hasil ini milik user yang sedang login
        $pemesanan = $hasilTes->pemesanan;   // pastikan relasi ada di model HasilTes

        if (! $pemesanan || $pemesanan->pengguna_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengakses berkas hasil ini.');
        }

        $path = $hasilTes->berkas_hasil;

        if (! $path || ! Storage::disk('public')->exists($path)) {
            abort(404, 'Berkas hasil tes tidak ditemukan.');
        }

        // ➜ kalau mau langsung download:
        return Storage::disk('public')->download($path);

        // kalau mau ditampilkan di browser:
        // return response()->file(Storage::disk('public')->path($path));
    }
}
