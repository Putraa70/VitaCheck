<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PemesananQrController extends Controller
{
    public function show(Pemesanan $pemesanan)
    {
        $path = $pemesanan->qr_checkin; // contoh: qrcodes/BK-UNILA-2025-IZXWFR.png

        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404, 'QR code tidak ditemukan');
        }

        // Ambil data file
        $fileContent = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);

        return new Response($fileContent, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"'
        ]);
    }
}
