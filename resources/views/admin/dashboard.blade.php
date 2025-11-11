@extends('layouts.app', ['title' => 'Dasbor'])

@section('content')
{{-- Header --}}
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold">Dasbor</h2>
            <p class="text-sm text-gray-600">Ringkasan operasional VitaCheck Unila</p>
        </div>
        <div class="flex gap-2">
            <x-secondary-button as="a" href="{{ route('pemesanan.buat') }}">Pesan Tes</x-secondary-button>
            <x-primary-button as="a" href="{{ route('pemesanan.indeks') }}">Lihat Pemesanan</x-primary-button>
        </div>
    </div>
</div>

{{-- KPI cards (atas) --}}
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-5">
        <p class="text-sm text-gray-600">Total Pemesanan</p>
        <div class="mt-2 flex items-baseline gap-2">
            <span class="text-2xl font-semibold">{{ number_format($totalPemesanan ?? 0) }}</span>
        </div>
    </div>
    <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-5">
        <p class="text-sm text-gray-600">Pengguna</p>
        <div class="mt-2 text-2xl font-semibold">{{ number_format($totalPengguna ?? 0) }}</div>
    </div>
    <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-5">
        <p class="text-sm text-gray-600">Jenis Tes</p>
        <div class="mt-2 text-2xl font-semibold">{{ number_format($totalJenisTes ?? 0) }}</div>
    </div>
    <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-5">
        <p class="text-sm text-gray-600">Slot Aktif</p>
        <div class="mt-2 text-2xl font-semibold">{{ number_format($totalSlotAktif ?? 0) }}</div>
    </div>
</div>

{{-- Status summary --}}
<div class="grid md:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-4">
        <p class="text-xs text-gray-500 mb-1">Menunggu</p>
        <div class="text-xl font-semibold">{{ $kpi['menunggu'] ?? 0 }}</div>
    </div>
    <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-4">
        <p class="text-xs text-gray-500 mb-1">Terkonfirmasi</p>
        <div class="text-xl font-semibold">{{ $kpi['terkonfirmasi'] ?? 0 }}</div>
    </div>
    <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-4">
        <p class="text-xs text-gray-500 mb-1">Check-in</p>
        <div class="text-xl font-semibold">{{ $kpi['check_in'] ?? 0 }}</div>
    </div>
    <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-4">
        <p class="text-xs text-gray-500 mb-1">Selesai</p>
        <div class="text-xl font-semibold">{{ $kpi['selesai'] ?? 0 }}</div>
    </div>
    <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-4">
        <p class="text-xs text-gray-500 mb-1">Dibatalkan</p>
        <div class="text-xl font-semibold">{{ $kpi['dibatalkan'] ?? 0 }}</div>
    </div>
</div>

@php
    // Mapping kelas tunggal untuk menghindari cssConflict di Tailwind
    $statusStyles = [
        'menunggu'      => 'bg-amber-50 text-amber-700 ring-amber-200',
        'terkonfirmasi' => 'bg-sky-50 text-sky-700 ring-sky-200',
        'check_in'      => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
        'selesai'       => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'dibatalkan'    => 'bg-rose-50 text-rose-700 ring-rose-200',
    ];
@endphp

{{-- Grid utama: chart + recent + slot --}}
<div class="grid xl:grid-cols-3 gap-6">
    {{-- Chart: Tren Mingguan (area/line) --}}
    <div class="xl:col-span-2 bg-white rounded-2xl ring-1 ring-gray-100 p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold">Tren Pemesanan (8 Minggu)</h3>
        </div>
        <canvas id="trendChart" height="120"></canvas>
    </div>

    {{-- Chart: Distribusi jenis tes --}}
    <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold">Distribusi Jenis Tes</h3>
        </div>
        <canvas id="jenisChart" height="120"></canvas>
        <ul class="mt-4 text-sm text-gray-600 space-y-1">
            @foreach(($byJenis ?? collect()) as $row)
                <li class="flex items-center justify-between">
                    <span>{{ $row['label'] }}</span>
                    <span class="font-medium">{{ $row['total'] }}</span>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Tabel recent --}}
    <div class="xl:col-span-2 bg-white rounded-2xl ring-1 ring-gray-100 p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold">Pemesanan Terbaru</h3>
            <a class="text-sm text-indigo-600 hover:underline" href="{{ route('pemesanan.indeks') }}">Lihat semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-3 py-2 text-left">Kode</th>
                        <th class="px-3 py-2 text-left">Mahasiswa</th>
                        <th class="px-3 py-2 text-left">Jenis Tes</th>
                        <th class="px-3 py-2 text-left">Tanggal</th>
                        <th class="px-3 py-2 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse(($recent ?? collect()) as $p)
                        @php $cls = $statusStyles[$p->status] ?? 'bg-gray-50 text-gray-700 ring-gray-200'; @endphp
                        <tr>
                            <td class="px-3 py-2 font-medium">{{ $p->kode }}</td>
                            <td class="px-3 py-2">{{ $p->user->name ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $p->jenisTes->nama ?? '-' }}</td>
                            <td class="px-3 py-2">{{ optional($p->slotWaktu->tanggal)->format('d M Y') ?? '-' }}</td>
                            <td class="px-3 py-2">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 ring-1 text-xs {{ $cls }}">
                                    {{ ucfirst(str_replace('_',' ',$p->status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-3 py-6 text-center text-gray-500">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Slot mendatang --}}
    <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-6">
        <h3 class="font-semibold mb-3">Jadwal Slot Mendatang</h3>
        <ul class="space-y-3">
            @forelse(($upcomingSlots ?? collect()) as $s)
                <li class="flex items-center justify-between">
                    <div>
                        <p class="font-medium">{{ $s->tanggal->translatedFormat('l, d M Y') }}</p>
                        @if(isset($s->kuota, $s->terpesan))
                            <p class="text-xs text-gray-600">Sisa kuota: {{ max(($s->kuota - $s->terpesan), 0) }}</p>
                        @endif
                    </div>
                    <x-secondary-button as="a" href="{{ route('pemesanan.buat') }}">Pesan</x-secondary-button>
                </li>
            @empty
                <li class="text-sm text-gray-600">Belum ada slot mendatang.</li>
            @endforelse
        </ul>
    </div>
</div>

{{-- User specific highlight (opsional) --}}
@isset($userKpi['total'])
    <div class="mt-6 bg-white rounded-2xl ring-1 ring-gray-100 p-6">
        <h3 class="font-semibold mb-2">Aktivitas Saya</h3>
        <div class="flex flex-wrap items-center gap-6 text-sm">
            <div>Total pemesanan saya: <span class="font-medium">{{ $userKpi['total'] }}</span></div>
            <div>Masih menunggu: <span class="font-medium">{{ $userKpi['menunggu'] }}</span></div>
            @if($userKpi['terakhir'])
                <div>Terakhir: <span class="font-medium">{{ $userKpi['terakhir']->kode }}</span> ({{ ucfirst(str_replace('_',' ', $userKpi['terakhir']->status)) }})</div>
            @endif
        </div>
    </div>
@endisset

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Tren Mingguan
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: @json($labels ?? []),
            datasets: [{
                label: 'Pemesanan',
                data: @json($trendSeries ?? []),
                tension: 0.35,
                fill: true,
                borderWidth: 2,
                borderColor: 'rgba(79,70,229,1)',
                backgroundColor: 'rgba(79,70,229,0.12)',
                pointRadius: 3,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    // Distribusi Jenis Tes (gunakan collect() agar aman jika $byJenis berupa array biasa)
    const jenisCtx = document.getElementById('jenisChart').getContext('2d');
    const jenisLabels = @json(collect($byJenis ?? [])->pluck('label'));
    const jenisData   = @json(collect($byJenis ?? [])->pluck('total'));
    new Chart(jenisCtx, {
        type: 'doughnut',
        data: { labels: jenisLabels, datasets: [{ data: jenisData, borderWidth: 0 }] },
        options: { plugins: { legend: { position: 'bottom' } }, cutout: '60%' }
    });
</script>
@endsection
