<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\JenisTes;
use App\Models\SlotWaktu;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $isAdmin = $user->can('admin'); // atau cek role sesuai implementasi kamu

        // ---------- KPI umum ----------
        $totalPemesanan = Pemesanan::count();
        $totalPengguna  = DB::table('users')->count();
        $totalJenisTes  = JenisTes::count();
        $totalSlotAktif = SlotWaktu::whereDate('tanggal', '>=', Carbon::today())->count();

        // ---------- KPI status pemesanan ----------
        $statusCounts = Pemesanan::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $map = fn($s) => (int) ($statusCounts[$s] ?? 0);

        $kpi = [
            'menunggu'      => $map('menunggu'),
            'terkonfirmasi' => $map('terkonfirmasi'),
            'check_in'      => $map('check_in'),
            'selesai'       => $map('selesai'),
            'dibatalkan'    => $map('dibatalkan'),
        ];

        // ---------- Distribusi Jenis Tes ----------
        $byJenis = Pemesanan::select('jenis_tes_id', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_tes_id')
            ->with('jenisTes:id,nama')
            ->get()
            ->map(fn($r) => [
                'label' => optional($r->jenisTes)->nama ?? 'Tidak diketahui',
                'total' => (int) $r->total,
            ]);

        // ---------- Tren 8 minggu terakhir (PostgreSQL friendly) ----------
        $start = Carbon::today()->subWeeks(7)->startOfWeek();

        $trend = Pemesanan::selectRaw("
                date_trunc('week', created_at)::date AS week_start,
                COUNT(*)::int AS total
            ")
            ->where('created_at', '>=', $start)
            ->groupBy('week_start')
            ->orderBy('week_start')
            ->get();

        // Normalisasi label minggu (8 titik)
        $labels = [];
        $trendSeries = [];
        for ($i = 0; $i < 8; $i++) {
            $w = (clone $start)->addWeeks($i);
            $labels[] = $w->translatedFormat('d M');
            $found = $trend->firstWhere('week_start', $w->format('Y-m-d'));
            $trendSeries[] = (int) ($found->total ?? 0);
        }

        // ---------- Tabel “recent” ----------
        $recent = Pemesanan::with(['jenisTes:id,nama', 'slotWaktu:id,tanggal', 'user:id,name'])
            ->latest()
            ->limit(8)
            ->get();

        // ---------- Slot mendatang ----------
        $upcomingSlots = SlotWaktu::whereDate('tanggal', '>=', now())
            ->orderBy('tanggal')
            ->limit(5)
            ->get();

        // ---------- KPI user (bukan admin) ----------
        $userKpi = [];
        if (!$isAdmin) {
            $userKpi['total']    = Pemesanan::where('pengguna_id', $user->id)->count();
            $userKpi['menunggu'] = Pemesanan::where('pengguna_id', $user->id)->where('status', 'menunggu')->count();
            $userKpi['terakhir'] = Pemesanan::where('pengguna_id', $user->id)->latest()->first();
        }

        return view('dashboard', [
            'isAdmin'        => $isAdmin,
            'totalPemesanan' => $totalPemesanan,
            'totalPengguna'  => $totalPengguna,
            'totalJenisTes'  => $totalJenisTes,
            'totalSlotAktif' => $totalSlotAktif,
            'kpi'            => $kpi,
            'byJenis'        => $byJenis,
            'labels'         => $labels,
            'trendSeries'    => $trendSeries,
            'recent'         => $recent,
            'upcomingSlots'  => $upcomingSlots,
            'userKpi'        => $userKpi,
        ]);
    }
}
