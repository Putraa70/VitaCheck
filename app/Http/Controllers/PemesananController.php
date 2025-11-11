<?php

namespace App\Http\Controllers;

use App\Http\Requests\SimpanPemesananRequest;
use App\Models\{Pemesanan, SlotWaktu, JenisTes};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PemesananController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function indeks()
    {
        $pemesanan = Pemesanan::with(['jenisTes', 'slotWaktu'])
            ->where('pengguna_id', auth()->id())
            ->latest()->paginate(10);
        return view('pemesanan.indeks', compact('pemesanan'));
    }

    public function buat()
    {
        $jenisTes = JenisTes::where('aktif', 1)->get();

        $slotWaktu = SlotWaktu::whereDate('tanggal', '>=', now())
            ->orderBy('tanggal')
            ->orderBy('mulai')
            ->limit(100)
            ->get();

        // PENTING: view-nya 'pemesanan.buat' (bukan 'create')
        // dan nama variabelnya 'slotWaktu' (camelCase) sama seperti di Blade
        return view('pemesanan.buat', compact('jenisTes', 'slotWaktu'));
    }

    public function simpan(SimpanPemesananRequest $r)
    {
        return DB::transaction(function () use ($r) {
            $slot = SlotWaktu::whereKey($r->slot_waktu_id)->lockForUpdate()->firstOrFail();
            if (!$slot->masihAdaKuota()) return back()->withErrors(['slot_waktu_id' => 'Kuota penuh']);

            $sudah = Pemesanan::where('pengguna_id', auth()->id())
                ->where('slot_waktu_id', $slot->id)
                ->whereIn('status', ['menunggu', 'terkonfirmasi', 'check_in', 'selesai'])
                ->exists();
            if ($sudah) return back()->withErrors(['slot_waktu_id' => 'Anda sudah terdaftar pada slot ini']);

            $pesan = Pemesanan::create([
                'kode' => 'BK-UNILA-' . now()->format('Y') . '-' . Str::upper(Str::random(6)),
                'pengguna_id' => auth()->id(),
                'jenis_tes_id' => $r->jenis_tes_id,
                'slot_waktu_id' => $slot->id,
                'status' => 'terkonfirmasi',
            ]);

            $slot->increment('terpesan');

            return redirect()->route('pemesanan.lihat', $pesan->kode)->with('sukses', 'Pendaftaran berhasil.');
        });
    }

    public function lihat(string $kode)
    {
        $p = Pemesanan::with(['jenisTes', 'slotWaktu', 'berkas', 'hasil'])
            ->where('kode', $kode)
            ->where('pengguna_id', auth()->id())
            ->firstOrFail();
        return view('pemesanan.lihat', compact('p'));
    }
}
