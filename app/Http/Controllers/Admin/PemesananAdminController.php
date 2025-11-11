<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PerbaruiStatusPemesananRequest;
use App\Models\Pemesanan;

class PemesananAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:admin']);
    }

    public function indeks()
    {
        $pemesanan = Pemesanan::with(['pengguna', 'jenisTes', 'slotWaktu'])->latest()->paginate(20);
        return view('admin.pemesanan.indeks', compact('pemesanan'));
    }

    public function tampil(Pemesanan $pemesanan)
    {
        $pemesanan->load(['pengguna', 'jenisTes', 'slotWaktu', 'hasil', 'berkas']);
        return view('admin.pemesanan.tampil', compact('pemesanan'));
    }

    public function perbaruiStatus(PerbaruiStatusPemesananRequest $r, Pemesanan $pemesanan)
    {
        $transisi = [
            'menunggu'     => ['terkonfirmasi', 'dibatalkan'],
            'terkonfirmasi' => ['check_in', 'dibatalkan'],
            'check_in'     => ['selesai'],
            'selesai'      => [],
            'dibatalkan'   => [],
        ];
        $dari = $pemesanan->status;
        $ke = $r->validated()['status'];
        if (!in_array($ke, $transisi[$dari] ?? [])) {
            return back()->withErrors(['status' => "Transisi tidak valid dari $dari ke $ke"]);
        }
        $pemesanan->update(['status' => $ke]);
        return back()->with('sukses', 'Status diperbarui.');
    }
}
