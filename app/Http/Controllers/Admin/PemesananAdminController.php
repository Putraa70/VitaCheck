<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\JenisTes;
use App\Models\SlotWaktu;
use App\Models\BerkasPemesanan;          // ⬅️ TAMBAH
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;   // ⬅️ TAMBAH
use App\Services\AntrianService;

class PemesananAdminController extends Controller
{
    public function indeks(Request $r)
    {
        $q       = $r->query('q');
        $status  = $r->query('status');
        $jenisId = $r->query('jenis_tes_id');
        $from    = $r->query('from');
        $to      = $r->query('to');

        $driver = DB::connection()->getDriverName();
        $like   = $driver === 'pgsql' ? 'ILIKE' : 'LIKE';

        $items = Pemesanan::query()
            ->with(['user:id,name,email', 'jenisTes:id,nama', 'slotWaktu:id,tanggal'])
            ->when($q, function ($query) use ($q, $like) {
                $query->where(function ($sub) use ($q, $like) {
                    $sub->where('kode', $like, "%{$q}%")
                        ->orWhereHas('user', fn($u) => $u->where('name', $like, "%{$q}%"))
                        ->orWhereHas('jenisTes', fn($j) => $j->where('nama', $like, "%{$q}%"));
                });
            })
            ->when($status, fn($x) => $x->where('status', $status))
            ->when($jenisId, fn($x) => $x->where('jenis_tes_id', $jenisId))
            ->when($from && $to, fn($x) => $x->whereHas('slotWaktu', fn($s) => $s->whereBetween('tanggal', [$from, $to])))
            ->when($from && !$to, fn($x) => $x->whereHas('slotWaktu', fn($s) => $s->whereDate('tanggal', '>=', $from)))
            ->when(!$from && $to, fn($x) => $x->whereHas('slotWaktu', fn($s) => $s->whereDate('tanggal', '<=', $to)))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $daftarJenis  = JenisTes::orderBy('nama')->get(['id', 'nama']);
        $daftarStatus = [
            'menunggu',
            'menunggu_bayar',
            'terbayar',
            'terkonfirmasi',
            'check_in',
            'selesai',
            'dibatalkan',
            'kedaluwarsa',
        ];

        return view('admin.pemesanans.index', compact(
            'items',
            'daftarJenis',
            'daftarStatus',
            'q',
            'status',
            'jenisId',
            'from',
            'to'
        ));
    }

    public function tampil(Pemesanan $pemesanan)
    {
        $item = $pemesanan->load([
            'user:id,name,email',
            'jenisTes:id,nama,kode',
            'slotWaktu:id,tanggal,mulai,selesai,kuota,terpesan,dihold',
            'berkasPemesanan:id,pemesanan_id,jenis,lokasi_berkas',
            'hasilTes:id,pemesanan_id,status_hasil,catatan,berkas_hasil',
        ]);

        $daftarStatus = [
            'menunggu',
            'menunggu_bayar',
            'terbayar',
            'terkonfirmasi',
            'check_in',
            'selesai',
            'dibatalkan',
            'kedaluwarsa',
        ];

        return view('admin.pemesanans.show', compact('item', 'daftarStatus'));
    }

    public function perbaruiStatus(Request $r, Pemesanan $pemesanan, AntrianService $antrian)
    {
        $data = $r->validate([
            'status'        => ['required', 'in:menunggu,menunggu_bayar,terbayar,terkonfirmasi,check_in,selesai,dibatalkan,kedaluwarsa'],
            'dibayar_pada'  => ['nullable', 'date'],
            'nomor_antrian' => ['nullable', 'integer', 'min:1'],
            'waktu_tes'     => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($data, $antrian, $pemesanan) {
            $p = Pemesanan::whereKey($pemesanan->id)
                ->lockForUpdate()
                ->firstOrFail();

            $oldStatus = $p->status;
            $newStatus = $data['status'];

            /** @var SlotWaktu|null $slot */
            $slot = SlotWaktu::whereKey($p->slot_waktu_id)
                ->lockForUpdate()
                ->first();

            if ($slot) {
                // dari MENUNGGU_BAYAR → TERBAYAR / TERKONFIRMASI
                if ($oldStatus === 'menunggu_bayar' && in_array($newStatus, ['terbayar', 'terkonfirmasi'], true)) {
                    if (!is_null($slot->dihold) && $slot->dihold > 0) {
                        $slot->decrement('dihold');
                    }
                    $slot->increment('terpesan');
                }

                // dari MENUNGGU_BAYAR → DIBATALKAN / KEDALUWARSA
                if ($oldStatus === 'menunggu_bayar' && in_array($newStatus, ['dibatalkan', 'kedaluwarsa'], true)) {
                    if (!is_null($slot->dihold) && $slot->dihold > 0) {
                        $slot->decrement('dihold');
                    }
                }

                // dari TERBAYAR/TERKONFIRMASI → DIBATALKAN/KEDALUWARSA (kuota balik)
                if (
                    in_array($oldStatus, ['terbayar', 'terkonfirmasi'], true)
                    && in_array($newStatus, ['dibatalkan', 'kedaluwarsa'], true)
                ) {

                    if (!is_null($slot->terpesan) && $slot->terpesan > 0) {
                        $slot->decrement('terpesan');
                    }
                }
            }

            $p->status = $newStatus;

            if (!empty($data['dibayar_pada'])) {
                $p->dibayar_pada = \Carbon\Carbon::parse($data['dibayar_pada']);
            } elseif (in_array($newStatus, ['terbayar', 'terkonfirmasi'], true) && is_null($p->dibayar_pada)) {
                $p->dibayar_pada = now();
            }

            $p->save();

            if (in_array($p->status, ['terbayar', 'terkonfirmasi'], true)) {
                $antrian->tetapkanAntrianDanQr($p);
            }

            if (!empty($data['nomor_antrian']) || !empty($data['waktu_tes'])) {
                $p->update([
                    'nomor_antrian' => $data['nomor_antrian'] ?? $p->nomor_antrian,
                    'waktu_tes'     => !empty($data['waktu_tes'])
                        ? \Carbon\Carbon::parse($data['waktu_tes'])
                        : $p->waktu_tes,
                ]);
            }
        });

        return back()->with('sukses', 'Status pemesanan diperbarui.');
    }

    /**
     * Tampilkan / download berkas pemesanan lewat Laravel (tanpa akses /storage langsung)
     */
    public function lihatBerkas(BerkasPemesanan $berkas)
    {
        $path = $berkas->lokasi_berkas;

        if (! Storage::disk('public')->exists($path)) {
            abort(404, 'Berkas tidak ditemukan.');
        }

        return response()->file(
            Storage::disk('public')->path($path)
        );
        // kalau mau force download:
        // return Storage::disk('public')->download($path);
    }
}
