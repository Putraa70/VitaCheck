<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilTes;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HasilTesController extends Controller
{
    public function store(Request $r, Pemesanan $pemesanan)
    {
        $validated = $r->validate([
            'status_hasil' => ['required', 'in:negatif,positif'],
            'catatan'      => ['nullable', 'string'],
            'berkas_hasil' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png'],
        ]);

        $data = [
            'status_hasil' => $validated['status_hasil'],
            'catatan'      => $validated['catatan'] ?? null,
        ];

        if ($r->hasFile('berkas_hasil')) {
            // contoh path di DB: "hasil/{pemesanan_id}/namafileacak.pdf"
            $data['berkas_hasil'] = $r->file('berkas_hasil')
                ->store("hasil/{$pemesanan->id}", 'public');
        }

        HasilTes::updateOrCreate(
            ['pemesanan_id' => $pemesanan->id],
            $data
        );

        $pemesanan->update(['status' => 'selesai']);

        return back()->with('sukses', 'Hasil tes disimpan & pemesanan ditandai selesai.');
    }

    // TAMPILKAN / DOWNLOAD HASIL TES
    public function show(Pemesanan $pemesanan)
    {
        $hasil = $pemesanan->hasilTes;

        if (! $hasil || ! $hasil->berkas_hasil) {
            abort(404, 'Berkas hasil tidak tersedia.');
        }

        $path = $hasil->berkas_hasil; // "hasil/{id}/namafile.pdf"

        if (! Storage::disk('public')->exists($path)) {
            abort(404, 'File hasil tidak ditemukan di storage.');
        }

        // tampilkan inline di browser
        return response()->file(Storage::disk('public')->path($path));

        // kalau mau download paksa:
        // return Storage::disk('public')->download($path);
    }
}
