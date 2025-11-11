<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Ringkasan status khusus user
        $statusCounts = Pemesanan::where('pengguna_id', $user->id)
            ->selectRaw("status, COUNT(*)::int as total")
            ->groupBy('status')
            ->pluck('total', 'status');

        $kpi = [
            'total'         => (int) Pemesanan::where('pengguna_id', $user->id)->count(),
            'menunggu'      => (int) ($statusCounts['menunggu'] ?? 0),
            'terkonfirmasi' => (int) ($statusCounts['terkonfirmasi'] ?? 0),
            'check_in'      => (int) ($statusCounts['check_in'] ?? 0),
            'selesai'       => (int) ($statusCounts['selesai'] ?? 0),
            'dibatalkan'    => (int) ($statusCounts['dibatalkan'] ?? 0),
        ];

        // Pemesanan terbaru user
        $recent = Pemesanan::with(['jenisTes:id,nama', 'slotWaktu:id,tanggal'])
            ->where('pengguna_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        // Janji/slot terdekat (yang sudah terkonfirmasi atau check_in), tanggal >= hari ini
        $nextAppointment = Pemesanan::with(['jenisTes:id,nama', 'slotWaktu:id,tanggal'])
            ->where('pengguna_id', $user->id)
            ->whereIn('status', ['terkonfirmasi', 'check_in'])
            ->whereHas('slotWaktu', function ($q) {
                $q->whereDate('tanggal', '>=', Carbon::today());
            })
            ->orderByRaw('(select tanggal from slot_waktu where slot_waktu.id = pemesanans.slot_waktu_id) asc')
            ->first();

        $emailVerified = $user->hasVerifiedEmail();

        return view('dashboard', [
            'emailVerified'   => $emailVerified,
            'kpi'             => $kpi,
            'recent'          => $recent,
            'nextAppointment' => $nextAppointment,
        ]);
    }
}
