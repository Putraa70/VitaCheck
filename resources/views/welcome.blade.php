@extends('layouts.app', ['title' => 'Akun Saya'])

@section('content')
{{-- HERO --}}
<section class="mb-8">
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
        <div class="px-6 py-8 md:px-10 md:py-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-start gap-3">
                    <svg class="h-10 w-10 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5" class="opacity-80"/>
                        <circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M6.5 18a6.5 6.5 0 0 1 11 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-semibold tracking-tight">
                            Halo, {{ auth()->user()->name }} 👋
                        </h1>
                        <p class="mt-1 text-white/80 text-sm">
                            Kelola pemesanan tes kamu dari satu tempat dengan cepat dan aman.
                        </p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('pemesanan.indeks') }}"
                       class="inline-flex items-center rounded-lg border border-white/30 bg-white/0 px-4 py-2 text-white hover:bg-white/10 transition">
                        <svg class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M3 12a9 9 0 1 0 9-9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M3 3v6h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M12 7v5l3 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Riwayat
                    </a>
                    <a href="{{ route('pemesanan.buat') }}"
                       class="inline-flex items-center rounded-lg bg-white px-4 py-2 text-indigo-700 hover:opacity-90 transition">
                        <svg class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        Pesan Tes
                    </a>
                </div>
            </div>

            @if(!$emailVerified)
                <div class="mt-4 flex items-start gap-3 rounded-xl bg-white/10 p-4">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M3 7l9 6 9-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <rect x="3" y="7" width="18" height="10" rx="2" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M12 9v3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <circle cx="12" cy="15" r="1" fill="currentColor"/>
                    </svg>
                    <div class="text-sm">
                        <p class="font-medium">Verifikasi email belum selesai</p>
                        <p class="opacity-90">Verifikasi email untuk akses penuh fitur. Kirim ulang tautan verifikasi di bawah.</p>
                        <form method="POST" action="{{ route('verification.send') }}" class="mt-2">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center rounded-lg border border-white/30 px-4 py-2 text-white hover:bg-white/10 transition">
                                Kirim Ulang Verifikasi
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- KPI --}}
@php
    $kpi = $kpi ?? ['total'=>0,'menunggu'=>0,'terkonfirmasi'=>0,'check_in'=>0,'selesai'=>0,'dibatalkan'=>0];
    $kpiCards = [
        ['title'=>'Total Pemesanan','val'=>$kpi['total'],'desc'=>'Semua waktu','icon'=>'
            <svg class="h-6 w-6 text-indigo-600" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>'],
        ['title'=>'Menunggu','val'=>$kpi['menunggu'],'desc'=>'Menunggu verifikasi','icon'=>'
            <svg class="h-6 w-6 text-amber-600" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5"/>
                <path d="M12 7v5l3 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>'],
        ['title'=>'Terkonfirmasi','val'=>$kpi['terkonfirmasi'],'desc'=>'Siap datang','icon'=>'
            <svg class="h-6 w-6 text-sky-600" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M5 12l4 4L19 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>'],
        ['title'=>'Check-in','val'=>$kpi['check_in'],'desc'=>'Sedang berjalan','icon'=>'
            <svg class="h-6 w-6 text-indigo-600" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M13 7H7v10h10v-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M13 7l4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>'],
        ['title'=>'Selesai','val'=>$kpi['selesai'],'desc'=>'Tuntas','icon'=>'
            <svg class="h-6 w-6 text-emerald-600" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M12 8v8m4-4H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>'],
    ];
@endphp

<div class="grid sm:grid-cols-2 xl:grid-cols-5 gap-4 mb-8">
    @foreach($kpiCards as $card)
        <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-5">
            <div class="flex items-center justify-between">
                <p class="text-xs text-gray-500">{{ $card['title'] }}</p>
                <div>{!! $card['icon'] !!}</div>
            </div>
            <div class="mt-2 text-2xl font-semibold">{{ number_format($card['val']) }}</div>
            <p class="mt-1 text-xs text-gray-500">{{ $card['desc'] }}</p>
        </div>
    @endforeach
</div>

@php
    $statusStyles = [
        'menunggu'      => 'bg-amber-50 text-amber-700 ring-amber-200',
        'terkonfirmasi' => 'bg-sky-50 text-sky-700 ring-sky-200',
        'check_in'      => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
        'selesai'       => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'dibatalkan'    => 'bg-rose-50 text-rose-700 ring-rose-200',
    ];
@endphp

<div class="grid xl:grid-cols-3 gap-6">
    {{-- Janji terdekat --}}
    <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-indigo-600" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M8 3v4M16 3v4M3 10h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <h3 class="font-semibold">Janji Terdekat</h3>
            </div>
            @if(($nextAppointment ?? null))
                @php $cls = $statusStyles[$nextAppointment->status] ?? 'bg-gray-50 text-gray-700 ring-gray-200'; @endphp
                <span class="inline-flex items-center rounded-full px-2.5 py-1 ring-1 text-xs {{ $cls }}">
                    {{ ucfirst(str_replace('_',' ', $nextAppointment->status)) }}
                </span>
            @endif
        </div>

        @if($nextAppointment ?? false)
            <div class="mt-3 grid gap-1">
                <p class="text-sm">
                    <span class="text-gray-500">Jenis Tes:</span>
                    <span class="font-medium">{{ $nextAppointment->jenisTes->nama ?? '-' }}</span>
                </p>
                <p class="text-sm">
                    <span class="text-gray-500">Tanggal:</span>
                    <span class="font-medium">{{ optional($nextAppointment->slotWaktu->tanggal)->translatedFormat('l, d M Y') ?? '-' }}</span>
                </p>
                @if($nextAppointment->dibayar_pada)
                    <p class="text-xs text-gray-500">Dibayar: {{ $nextAppointment->dibayar_pada->translatedFormat('d M Y H:i') }}</p>
                @endif
            </div>
            <div class="mt-4 flex gap-2">
                <a href="{{ route('pemesanan.indeks') }}" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-gray-700 hover:bg-gray-50 transition">
                    Lihat Detail
                </a>
                <a href="{{ route('pemesanan.buat') }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 transition">
                    Pesan Lagi
                </a>
            </div>
        @else
            <div class="mt-3">
                <div class="rounded-xl bg-gray-50 p-4 text-sm text-gray-600">
                    Belum ada janji terdekat. Yuk pilih slot yang sesuai jadwalmu.
                </div>
                <div class="mt-3">
                    <a href="{{ route('pemesanan.buat') }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 transition">
                        Pilih Slot
                    </a>
                </div>
            </div>
        @endif
    </div>

    {{-- Donut status pribadi (SVG ringan) --}}
    <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-6">
        <div class="flex items-center gap-2 mb-3">
            <svg class="h-5 w-5 text-indigo-600" viewBox="0 0 24 24" fill="none">
                <path d="M11 3a9 9 0 109 9h-9V3z" stroke="currentColor" stroke-width="1.5"/>
                <path d="M12 12l6.36 6.36" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            <h3 class="font-semibold">Ringkasan Status</h3>
        </div>
        @php
            $segments = [
                ['label'=>'Menunggu',      'val'=>$kpi['menunggu'],      'color'=>'#f59e0b'],
                ['label'=>'Terkonfirmasi', 'val'=>$kpi['terkonfirmasi'], 'color'=>'#0284c7'],
                ['label'=>'Check-in',      'val'=>$kpi['check_in'],      'color'=>'#4f46e5'],
                ['label'=>'Selesai',       'val'=>$kpi['selesai'],       'color'=>'#059669'],
                ['label'=>'Dibatalkan',    'val'=>$kpi['dibatalkan'],    'color'=>'#e11d48'],
            ];
            $totalSeg = max(array_sum(array_column($segments, 'val')), 1);
            $circ = 2 * M_PI * 42; $acc = 0;
        @endphp
        <div class="flex items-center gap-6">
            <svg viewBox="0 0 100 100" class="h-32 w-32">
                <circle cx="50" cy="50" r="42" fill="none" stroke="#f3f4f6" stroke-width="12"/>
                @foreach($segments as $s)
                    @php
                        $len = $circ * ($s['val'] / $totalSeg);
                        $dash = $len . ' ' . ($circ - $len);
                        $offset = $circ * ($acc / $totalSeg);
                        $acc += $s['val'];
                    @endphp
                    <circle cx="50" cy="50" r="42" fill="none"
                            stroke="{{ $s['color'] }}" stroke-width="12"
                            stroke-dasharray="{{ $dash }}"
                            stroke-dashoffset="-{{ $offset }}"
                            stroke-linecap="round"/>
                @endforeach
                <text x="50" y="54" text-anchor="middle" font-size="12" fill="#111827" font-weight="600">{{ $kpi['total'] }}</text>
            </svg>
            <ul class="text-sm space-y-1">
                @foreach($segments as $s)
                    <li class="flex items-center gap-2">
                        <span class="inline-block h-2.5 w-2.5 rounded-sm" style="background: {{ $s['color'] }}"></span>
                        <span class="text-gray-600 w-28">{{ $s['label'] }}</span>
                        <span class="font-medium">{{ $s['val'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Tabel pemesanan terbaru --}}
    <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-6 xl:col-span-2">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-indigo-600" viewBox="0 0 24 24" fill="none"><rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M3 10h18M9 4v16M15 4v16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                <h3 class="font-semibold">Pemesanan Terbaru</h3>
            </div>
            <a href="{{ route('pemesanan.indeks') }}" class="text-sm text-indigo-600 hover:underline">Lihat semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-3 py-2 text-left">Kode</th>
                        <th class="px-3 py-2 text-left">Jenis Tes</th>
                        <th class="px-3 py-2 text-left">Tanggal</th>
                        <th class="px-3 py-2 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse(($recent ?? collect()) as $p)
                        @php $cls = $statusStyles[$p->status] ?? 'bg-gray-50 text-gray-700 ring-gray-200'; @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 font-medium">{{ $p->kode }}</td>
                            <td class="px-3 py-2">{{ $p->jenisTes->nama ?? '-' }}</td>
                            <td class="px-3 py-2">{{ optional($p->slotWaktu->tanggal)->format('d M Y') ?? '-' }}</td>
                            <td class="px-3 py-2">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 ring-1 text-xs {{ $cls }}">
                                    {{ ucfirst(str_replace('_',' ', $p->status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-3 py-6 text-center text-gray-500">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <a href="{{ route('pemesanan.buat') }}"
               class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 transition">
                Buat Pemesanan Baru
            </a>
        </div>
    </div>
</div>

{{-- Aksi cepat --}}
<div class="mt-6 bg-white rounded-2xl ring-1 ring-gray-100 p-6">
    <div class="flex items-center gap-2 mb-2">
        <svg class="h-5 w-5 text-indigo-600" viewBox="0 0 24 24" fill="none"><path d="M13 3L4 14h6l-1 7 9-11h-6l1-7z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <h3 class="font-semibold">Aksi Cepat</h3>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('pemesanan.buat') }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 transition">Pesan Tes</a>
        <a href="{{ route('pemesanan.indeks') }}" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-gray-700 hover:bg-gray-50 transition">Riwayat</a>
        <a href="{{ route('profile.edit') }}" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-gray-700 hover:bg-gray-50 transition">Ubah Profil</a>
    </div>
</div>
@endsection
