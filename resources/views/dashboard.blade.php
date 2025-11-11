@extends('layouts.app', ['title' => 'Beranda Akun'])

@section('content')
@php
    // ====== FALLBACK & NORMALISASI DATA ======
    $notifications   = $notifications   ?? collect(); // item: ['title','body','time','type']
    $announcements   = $announcements   ?? collect(); // item: ['title','excerpt','date','url']
    $results         = $results         ?? collect(); // item: Pemesanan + hasil (file_url, nilai, kesimpulan)
    $nextAppointment = $nextAppointment ?? null;      // Pemesanan with jenisTes & slotWaktu
    $kpi             = $kpi ?? ['total'=>0,'menunggu'=>0,'terkonfirmasi'=>0,'check_in'=>0,'selesai'=>0,'dibatalkan'=>0];

    // progress pendaftaran sederhana
    // Kalau kamu punya $registrationProgress dari controller, override saja logic ini.
    $hasProfile = isset(auth()->user()->name) && !empty(auth()->user()->name);
    $hasOrder   = ($kpi['total'] ?? 0) > 0;
    $hasUpload  = false; // ganti true jika kamu sudah kirim tanda unggah berkas dari controller
    $hasCheckin = ($kpi['check_in'] ?? 0) > 0;
    $hasResult  = ($results->count() ?? 0) > 0;

    $steps = [
        ['key'=>'profile', 'label'=>'Lengkapi Profil', 'done'=>$hasProfile],
        ['key'=>'order',   'label'=>'Pesan Tes',       'done'=>$hasOrder],
        ['key'=>'upload',  'label'=>'Unggah Berkas',   'done'=>$hasUpload],
        ['key'=>'checkin', 'label'=>'Check-in',        'done'=>$hasCheckin],
        ['key'=>'result',  'label'=>'Hasil Keluar',    'done'=>$hasResult],
    ];

    $statusStyles = [
        'menunggu'      => 'bg-amber-50 text-amber-700 ring-amber-200',
        'terkonfirmasi' => 'bg-sky-50 text-sky-700 ring-sky-200',
        'check_in'      => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
        'selesai'       => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'dibatalkan'    => 'bg-rose-50 text-rose-700 ring-rose-200',
    ];
@endphp

{{-- HERO: Apa ini? + Aksi Cepat --}}
<section class="mb-8">
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
        <div class="px-6 py-8 md:px-10 md:py-10">
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div class="max-w-2xl">
                    <h1 class="text-2xl md:text-3xl font-semibold tracking-tight">Halo, {{ auth()->user()->name }} 👋</h1>
                    <p class="mt-2 text-white/85 text-sm">
                        Ini adalah dashboard pengguna <b>VitaCheck Unila</b> — portal pemesanan & hasil tes kesehatan mahasiswa.
                        Kamu bisa memesan slot tes, unggah berkas, memantau status, dan mengunduh hasil tes.
                    </p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <a href="{{ route('pemesanan.buat') }}" class="inline-flex items-center rounded-lg bg-white px-4 py-2 text-indigo-700 hover:opacity-90 transition">
                            {{-- icon plus --}}
                            <svg class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            Pesan Tes
                        </a>
                        <a href="{{ route('pemesanan.indeks') }}" class="inline-flex items-center rounded-lg border border-white/30 bg-white/0 px-4 py-2 text-white hover:bg-white/10 transition">
                            {{-- icon calendar --}}
                            <svg class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M8 3v4M16 3v4M3 10h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            Lihat Jadwal
                        </a>
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center rounded-lg border border-white/30 bg-white/0 px-4 py-2 text-white hover:bg-white/10 transition">
                            {{-- icon user --}}
                            <svg class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="1.5"/><path d="M20 21a8 8 0 10-16 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            Lengkapi Profil
                        </a>
                    </div>
                </div>

                {{-- Kartu ringkas next appointment --}}
                <div class="w-full md:w-auto">
                    <div class="rounded-2xl bg-white/10 backdrop-blur p-4 md:w-80">
                        <div class="flex items-center gap-2">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M8 3v4M16 3v4M3 10h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            <p class="font-medium">Janji Terdekat</p>
                        </div>
                        @if($nextAppointment)
                            <div class="mt-3 text-sm">
                                <p class="opacity-90">Jenis Tes: <b>{{ $nextAppointment->jenisTes->nama ?? '-' }}</b></p>
                                <p class="opacity-90">Tanggal: <b>{{ optional($nextAppointment->slotWaktu->tanggal)->translatedFormat('l, d M Y') ?? '-' }}</b></p>
                                @php $cls = $statusStyles[$nextAppointment->status] ?? 'bg-gray-50 text-gray-700 ring-gray-200'; @endphp
                                <span class="mt-2 inline-flex items-center rounded-full px-2.5 py-1 ring-1 text-xs {{ $cls }}">
                                    {{ ucfirst(str_replace('_',' ', $nextAppointment->status)) }}
                                </span>
                            </div>
                            <div class="mt-3 flex gap-2">
                                <a href="{{ route('pemesanan.indeks') }}" class="inline-flex items-center rounded-lg border border-white/30 bg-white/0 px-3 py-1.5 text-white hover:bg-white/10 text-xs">Detail</a>
                                <a href="{{ route('pemesanan.buat') }}" class="inline-flex items-center rounded-lg bg-white px-3 py-1.5 text-indigo-700 hover:opacity-90 text-xs">Pesan Lagi</a>
                            </div>
                        @else
                            <p class="mt-3 text-sm opacity-90">Belum ada janji. Yuk jadwalkan tes pertamamu.</p>
                            <a href="{{ route('pemesanan.buat') }}" class="mt-3 inline-flex items-center rounded-lg bg-white px-3 py-1.5 text-indigo-700 hover:opacity-90 text-xs">Pilih Slot</a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Alert verifikasi --}}
            @if(!auth()->user()->hasVerifiedEmail())
                <div class="mt-4 flex items-start gap-3 rounded-xl bg-white/10 p-4">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none"><path d="M3 7l9 6 9-6" stroke="currentColor" stroke-width="1.5"/><rect x="3" y="7" width="18" height="10" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M12 9v3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><circle cx="12" cy="15" r="1" fill="currentColor"/></svg>
                    <div class="text-sm">
                        <p class="font-medium">Verifikasi email belum selesai</p>
                        <p class="opacity-90">Kirim ulang tautan verifikasi untuk mengaktifkan semua fitur.</p>
                        <form method="POST" action="{{ route('verification.send') }}" class="mt-2">@csrf
                            <button class="inline-flex items-center rounded-lg border border-white/30 px-3 py-1.5 text-white hover:bg-white/10 text-xs">Kirim Ulang Verifikasi</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- 1) Status Pendaftaran (timeline) + 2) Pemberitahuan --}}
<div class="grid lg:grid-cols-3 gap-6 mb-8">
    {{-- Status pendaftaran --}}
    <div class="lg:col-span-2 bg-white rounded-2xl ring-1 ring-gray-100 p-6">
        <div class="flex items-center gap-2 mb-4">
            <svg class="h-5 w-5 text-indigo-600" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5"/><path d="M12 7v5l3 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            <h3 class="font-semibold">Status Pendaftaran Saya</h3>
        </div>
        <ol class="grid sm:grid-cols-5 gap-3">
            @foreach($steps as $i => $st)
                <li class="flex flex-col items-start">
                    <span class="text-xs text-gray-500">{{ $i+1 }}</span>
                    <div class="mt-1 inline-flex items-center gap-2 rounded-xl px-3 py-2 ring-1 text-sm
                        {{ $st['done'] ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-gray-50 text-gray-700 ring-gray-200' }}">
                        @if($st['done'])
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"><path d="M5 12l4 4L19 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                        @else
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.5"/></svg>
                        @endif
                        {{ $st['label'] }}
                    </div>
                </li>
            @endforeach
        </ol>
        <div class="mt-4 flex flex-wrap gap-2">
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">Ubah Profil</a>
            <a href="{{ route('pemesanan.buat') }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-3 py-1.5 text-sm text-white hover:bg-indigo-700">Pesan Tes</a>
            <a href="{{ route('pemesanan.indeks') }}" class="inline-flex items-center rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">Lihat Pemesanan</a>
        </div>
    </div>

    {{-- Pemberitahuan --}}
    <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-6">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-indigo-600" viewBox="0 0 24 24" fill="none"><path d="M12 22a2 2 0 002-2H10a2 2 0 002 2zM18 16v-5a6 6 0 10-12 0v5l-2 2h16l-2-2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                <h3 class="font-semibold">Pemberitahuan</h3>
            </div>
            <a href="{{ route('pemesanan.indeks') }}" class="text-sm text-indigo-600 hover:underline">Lihat semua</a>
        </div>
        <div class="space-y-3">
            @forelse($notifications as $n)
                <div class="rounded-xl border border-gray-100 p-4">
                    <p class="font-medium text-sm">{{ $n['title'] ?? 'Notifikasi' }}</p>
                    @if(!empty($n['body']))
                        <p class="text-sm text-gray-600 mt-1">{{ $n['body'] }}</p>
                    @endif
                    <p class="text-xs text-gray-500 mt-2">{{ $n['time'] ?? '' }}</p>
                </div>
            @empty
                <div class="rounded-xl bg-gray-50 p-4 text-sm text-gray-600">
                    Belum ada pemberitahuan baru.
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- 3) Hasil Tes Terbaru + 4) Pengumuman/News --}}
<div class="grid lg:grid-cols-3 gap-6">
    {{-- Hasil tes --}}
    <div class="lg:col-span-2 bg-white rounded-2xl ring-1 ring-gray-100 p-6">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-indigo-600" viewBox="0 0 24 24" fill="none"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                <h3 class="font-semibold">Hasil Tes Terbaru</h3>
            </div>
            <a href="{{ route('pemesanan.indeks') }}" class="text-sm text-indigo-600 hover:underline">Ke Riwayat</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-3 py-2 text-left">Kode</th>
                        <th class="px-3 py-2 text-left">Jenis Tes</th>
                        <th class="px-3 py-2 text-left">Tanggal</th>
                        <th class="px-3 py-2 text-left">Kesimpulan</th>
                        <th class="px-3 py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($results as $r)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 font-medium">{{ $r->kode ?? $r['kode'] ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $r->jenisTes->nama ?? data_get($r, 'jenis_tes_nama', '-') }}</td>
                            <td class="px-3 py-2">{{ optional(optional($r->slotWaktu)->tanggal)->format('d M Y') ?? data_get($r, 'tanggal_str', '-') }}</td>
                            <td class="px-3 py-2">
                                @php $sum = $r->kesimpulan ?? $r['kesimpulan'] ?? '-'; @endphp
                                <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-xs">{{ $sum }}</span>
                            </td>
                            <td class="px-3 py-2">
                                @php $url = $r->file_url ?? $r['file_url'] ?? null; @endphp
                                @if($url)
                                    <a href="{{ $url }}" class="text-indigo-600 hover:underline">Unduh PDF</a>
                                @else
                                    <span class="text-gray-500">Belum tersedia</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-6 text-center text-gray-500">
                                Belum ada hasil tes. Setelah tes selesai dan diverifikasi, hasil akan tampil di sini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex flex-wrap gap-2">
            <a href="{{ route('pemesanan.buat') }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 transition">Pesan Tes</a>
            <a href="{{ route('pemesanan.indeks') }}" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-gray-700 hover:bg-gray-50 transition">Lihat Pemesanan</a>
        </div>
    </div>

    {{-- Pengumuman/News --}}
    <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-6">
        <div class="flex items-center gap-2 mb-3">
            <svg class="h-5 w-5 text-indigo-600" viewBox="0 0 24 24" fill="none"><path d="M4 6h16v10H7l-3 3V6z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            <h3 class="font-semibold">Pengumuman & Berita</h3>
        </div>
        <div class="space-y-3">
            @forelse($announcements as $a)
                <article class="rounded-xl border border-gray-100 p-4">
                    <a href="{{ $a['url'] ?? '#' }}" class="font-medium hover:underline">{{ $a['title'] ?? 'Pengumuman' }}</a>
                    @if(!empty($a['excerpt']))
                        <p class="mt-1 text-sm text-gray-600">{{ $a['excerpt'] }}</p>
                    @endif
                    <p class="mt-2 text-xs text-gray-500">{{ $a['date'] ?? '' }}</p>
                </article>
            @empty
                <div class="rounded-xl bg-gray-50 p-4 text-sm text-gray-600">
                    Belum ada pengumuman terbaru.
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Bantuan & FAQ --}}
<div class="mt-8 grid md:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-6">
        <div class="flex items-center gap-2 mb-2">
            <svg class="h-5 w-5 text-indigo-600" viewBox="0 0 24 24" fill="none"><path d="M12 8a3 3 0 00-3 3h2a1 1 0 112 0c0 .667-.333 1-1.5 1.5S9 13.667 9 15h2c0-.5.5-.833 1.5-1.333S14 12.667 14 11a3 3 0 00-2-3z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M12 18h.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            <h3 class="font-semibold">FAQ Singkat</h3>
        </div>
        <ul class="text-sm text-gray-700 space-y-2">
            <li><b>Bagaimana cara memesan tes?</b> Masuk → klik “Pesan Tes” → pilih slot → unggah berkas.</li>
            <li><b>Di mana melihat hasil?</b> Lihat di “Hasil Tes Terbaru” atau buka riwayat pemesanan.</li>
            <li><b>Butuh bantuan?</b> Hubungi admin klinik fakultas atau kirim pesan via halaman kontak.</li>
        </ul>
        <div class="mt-3">
            <a href="{{ route('pemesanan.buat') }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 transition">Mulai Pesan</a>
        </div>
    </div>
    <div class="rounded-2xl ring-1 ring-indigo-100 bg-indigo-50 p-6">
        <h3 class="font-semibold text-indigo-900">Tips</h3>
        <ul class="mt-2 text-sm text-indigo-800 list-disc pl-5 space-y-1">
            <li>Datang 10–15 menit lebih awal untuk proses check-in.</li>
            <li>Pastikan berkas (KTM & bukti bayar) terbaca jelas.</li>
            <li>Cek pengumuman untuk jadwal layanan khusus/libur.</li>
        </ul>
    </div>
</div>
@endsection
