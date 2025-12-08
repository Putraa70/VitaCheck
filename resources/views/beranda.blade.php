@extends('layouts.guest', ['title' => 'Selamat Datang • VitaCheck Unila'])

@section('content')
@php
    // Skema warna utama
    $primaryColor   = '#0f52ba'; // Biru Safir
    $softBlue       = '#e3f2fd'; // Biru muda
    $darkText       = '#0f172a'; // Slate-900
    $accentGreen    = '#22c55e'; // Hijau
    $accentOrange   = '#fb923c'; // Oranye
@endphp

{{-- ======================= HERO ======================= --}}
<section class="relative bg-white mb-16 overflow-hidden">
    {{-- Background smooth + pola --}}
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-br from-sky-50 via-white to-indigo-50"></div>
        <svg class="absolute -right-24 -top-24 w-80 h-80 text-indigo-100" viewBox="0 0 200 200" fill="none">
            <circle cx="100" cy="100" r="90" fill="currentColor" />
        </svg>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            {{-- Text Content --}}
            <div>
                <div class="inline-flex items-center gap-2 mb-5 bg-white/60 backdrop-blur px-3 py-1 rounded-full border border-sky-100 shadow-sm">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full text-xs font-semibold text-white"
                          style="background-color: {{ $primaryColor }};">
                        ✓
                    </span>
                    <span class="text-[11px] font-semibold tracking-[0.15em] uppercase text-sky-700">
                        Medical Excellence • Campus Health
                    </span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight mb-5"
                    style="color: {{ $darkText }};">
                    Standar Baru
                    <span class="block mt-1" style="color: {{ $primaryColor }};">Kesehatan Kampus</span>
                </h1>

                <p class="text-base sm:text-lg text-slate-600 mb-8 max-w-xl leading-relaxed">
                    VitaCheck Unila menghadirkan layanan pemeriksaan medis terintegrasi dengan standar klinis modern.
                    Pemesanan slot, unggah berkas, hingga hasil digital — dalam satu dashboard yang aman.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-wrap gap-3 sm:gap-4 items-center">
                    @if (Route::has('login'))
                        @auth
                            {{-- Jika sudah login, arahkan ke Dashboard --}}
                            <a href="{{ url('/dashboard') }}"
                               class="inline-flex items-center justify-center px-6 sm:px-7 py-3 sm:py-3.5
                                      rounded-xl text-sm sm:text-[0.85rem] font-semibold tracking-wide
                                      text-white bg-gradient-to-r from-indigo-600 to-sky-600
                                      shadow-lg shadow-indigo-500/25 hover:shadow-xl hover:brightness-110
                                      transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <span>Masuk ke Dashboard</span>
                                <svg class="ml-2 h-5 w-5" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14"/>
                                    <path d="m13 6 6 6-6 6"/>
                                </svg>
                            </a>
                        @else
                            {{-- Jika belum login --}}
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center justify-center px-6 sm:px-7 py-3 sm:py-3.5
                                      rounded-xl text-sm sm:text-[0.85rem] font-semibold tracking-wide
                                      text-white bg-gradient-to-r from-indigo-600 to-sky-600
                                      shadow-lg shadow-indigo-500/25 hover:shadow-xl hover:brightness-110
                                      transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="mr-2 h-5 w-5" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                                    <path d="M10 17l5-5-5-5"/>
                                    <path d="M15 12H3"/>
                                </svg>
                                <span>Masuk / Login</span>
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="inline-flex items-center justify-center px-5 sm:px-6 py-3 sm:py-3.5
                                          rounded-xl text-sm font-semibold text-indigo-700 bg-white/80
                                          border border-indigo-100 hover:bg-indigo-50
                                          transition shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <span>Daftar Akun</span>
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>

                {{-- Badge kecil bawah CTA --}}
                <div class="mt-5 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/80 border border-slate-200">
                        <span class="inline-block h-2 w-2 rounded-full bg-emerald-500"></span>
                        <span>Sistem Online</span>
                    </div>
                    <span>Enkripsi data • Akses multi-perangkat</span>
                </div>
            </div>

            {{-- Visual: Foto klinik + overlay card --}}
            <div class="relative">
                {{-- Card utama berisi foto klinik --}}
                <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-white/70 bg-slate-900/5 backdrop-blur-sm">
                    <div class="aspect-[4/3] relative">
                        {{-- Placeholder Foto Klinik --}}
                        <img src="{{ asset('images/klinik.jpg') }}"
                             alt="Klinik Unila"
                             class="h-full w-full object-cover" 
                             onerror="this.src='https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'"/>

                        <div class="absolute inset-0 bg-gradient-to-tr from-slate-900/50 via-slate-900/10 to-transparent"></div>

                        <div class="absolute top-4 left-4 inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-white/90 text-slate-800 shadow-md">
                            <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500 text-white text-[10px]">✓</span>
                            Klinik Unila Terintegrasi
                        </div>
                    </div>
                </div>

                {{-- Floating card bawah --}}
                <div class="absolute -bottom-6 left-4 right-4 sm:left-auto sm:right-0 sm:w-72">
                    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 px-4 py-4 sm:px-5 sm:py-5 flex gap-3 items-start">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center text-white shadow-md"
                             style="background-color: {{ $primaryColor }};">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 12l2 2 4-4"/>
                                <circle cx="12" cy="12" r="9"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Validasi Medis Otomatis</p>
                            <p class="mt-1 text-xs text-slate-500">
                                Hasil tes diverifikasi tim medis dan dapat diakses kembali dari dashboard VitaCheck.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ======================= FEATURES ======================= --}}
<section class="mb-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-slate-900 mb-3">Fitur Utama Platform</h2>
            <p class="text-slate-600 max-w-2xl mx-auto">
                Pemesanan slot, unggah berkas, dan pemantauan hasil dalam alur yang rapi dan bersih.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-6 md:gap-8">
            <div class="bg-white rounded-2xl shadow-md border-t-4 p-6 md:p-8 hover:shadow-xl transition"
                 style="border-top-color: {{ $primaryColor }};">
                <div class="h-12 w-12 rounded-xl flex items-center justify-center mb-4 bg-sky-50 text-sky-700">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Pemesanan Terjadwal</h3>
                <p class="text-sm text-slate-600">
                    Pilih slot tes yang sesuai dengan agenda kuliah dan organisasi, tanpa antrean panjang di lokasi.
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-md border-t-4 p-6 md:p-8 hover:shadow-xl transition"
                 style="border-top-color: {{ $accentGreen }};">
                <div class="h-12 w-12 rounded-xl flex items-center justify-center mb-4 bg-emerald-50 text-emerald-600">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Hasil Digital Cepat</h3>
                <p class="text-sm text-slate-600">
                    Hasil pemeriksaan dapat diunduh dalam bentuk digital yang rapi dan dapat dilampirkan saat dibutuhkan.
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-md border-t-4 p-6 md:p-8 hover:shadow-xl transition"
                 style="border-top-color: {{ $accentOrange }};">
                <div class="h-12 w-12 rounded-xl flex items-center justify-center mb-4 bg-amber-50 text-amber-500">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h.01"/>
                        <path d="M12 16v-4"/>
                        <path d="M12 8v2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Notifikasi Status</h3>
                <p class="text-sm text-slate-600">
                    Setiap perubahan status pemesanan dikirim melalui email dan tampil jelas di dashboard Anda.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ======================= STATS (STATIC) ======================= --}}
<section class="mb-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl p-8 md:p-12 bg-gradient-to-r from-sky-50 via-white to-indigo-50 border border-sky-100/70 shadow-sm">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-slate-900 mb-2">Statistik Layanan</h2>
                <p class="text-slate-600">Angka yang terus berkembang bersama komunitas mahasiswa Universitas Lampung.</p>
            </div>

            <div class="grid sm:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-extrabold mb-2" style="color: {{ $primaryColor }};">
                        {{-- Data Static --}}
                        1,500+
                    </div>
                    <p class="text-base font-medium text-slate-700">Total Pemesanan</p>
                    <p class="text-xs text-slate-500 mt-1">Telah diproses melalui VitaCheck.</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-extrabold mb-2" style="color: {{ $primaryColor }};">
                        {{-- Data Static --}}
                        5,000+
                    </div>
                    <p class="text-base font-medium text-slate-700">Pengguna Terdaftar</p>
                    <p class="text-xs text-slate-500 mt-1">Mahasiswa & staf kampus.</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-extrabold mb-2" style="color: {{ $primaryColor }};">
                         {{-- Data Static --}}
                        12+
                    </div>
                    <p class="text-base font-medium text-slate-700">Jenis Tes</p>
                    <p class="text-xs text-slate-500 mt-1">Tersedia di klinik kampus.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ======================= HOW IT WORKS ======================= --}}
<section class="mb-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-slate-900 mb-2">4 Langkah Memesan Tes</h2>
            <p class="text-slate-600">Alur sederhana dari registrasi hingga hasil diterima.</p>
        </div>

        <div class="relative">
            {{-- Garis timeline (desktop) --}}
            <div class="hidden sm:block absolute left-0 right-0 top-8 h-[2px] bg-sky-100"></div>

            <div class="grid sm:grid-cols-4 gap-8 relative z-10">
                @foreach([
                    ['num'=>'1', 'title'=>'Registrasi Akun', 'desc'=>'Gunakan email kampus atau email aktif untuk membuat akun VitaCheck.'],
                    ['num'=>'2', 'title'=>'Pilih & Pesan', 'desc'=>'Tentukan jenis tes, slot tanggal, dan lengkapi berkas serta konfirmasi pemesanan.'],
                    ['num'=>'3', 'title'=>'Datang ke Klinik', 'desc'=>'Datang sesuai jadwal, lakukan proses pemeriksaan di Klinik Unila.'],
                    ['num'=>'4', 'title'=>'Akses Hasil', 'desc'=>'Hasil akan tersedia secara digital di dashboard dan dapat diunduh.'],
                ] as $step)
                    <div class="text-center">
                        <div class="flex items-center justify-center h-14 w-14 rounded-full mx-auto mb-4
                                    bg-white shadow-md border border-sky-100 text-lg font-bold text-slate-900">
                            {{ $step['num'] }}
                        </div>
                        <h3 class="text-sm font-semibold text-slate-900 mb-1">{{ $step['title'] }}</h3>
                        <p class="text-xs text-slate-600">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ======================= CTA ======================= --}}
<section class="mb-16">
    <div class="rounded-3xl p-8 md:p-14 text-center bg-gradient-to-r from-indigo-700 via-sky-700 to-indigo-800 text-white shadow-2xl">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Jaga Kesehatan, Fasilitasi Masa Depan</h2>
            <p class="text-sm md:text-base text-indigo-100 mb-7">
                Pastikan status kesehatan Anda terekam dengan baik untuk kebutuhan akademik, magang, beasiswa, dan karier ke depan.
            </p>

            @if (Route::has('register'))
                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center rounded-xl px-8 md:px-10 py-3.5 md:py-4
                          text-sm md:text-base font-semibold text-indigo-800 bg-white
                          shadow-lg hover:shadow-xl hover:bg-slate-50
                          transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white/70">
                    Mulai Sekarang
                    <svg class="ml-2 h-5 w-5" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"/>
                        <path d="m13 6 6 6-6 6"/>
                    </svg>
                </a>
            @endif
        </div>
    </div>
</section>

{{-- ======================= FAQ ======================= --}}
<section class="mb-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-2">Pertanyaan Umum (FAQ)</h2>
            <p class="text-slate-600 text-sm">Informasi singkat seputar pemesanan dan hasil pemeriksaan.</p>
        </div>

        <div class="space-y-3">
            @foreach([
                ['q'=>'Bagaimana cara memesan tes?', 'a'=>'Masuk ke akun, pilih jenis tes dan slot waktu, unggah berkas yang diminta, lalu konfirmasi pemesanan.'],
                ['q'=>'Berapa lama hasil tes dikirimkan?', 'a'=>'Umumnya hasil diproses 3–5 hari kerja. Notifikasi akan dikirim melalui email dan tampil di dashboard.'],
                ['q'=>'Apa berkas yang perlu diunggah?', 'a'=>'Minimal Kartu Tanda Mahasiswa (KTM) dan bukti pembayaran. Informasi detail akan tertulis di halaman pemesanan.'],
                ['q'=>'Apakah ada biaya tambahan?', 'a'=>'Biaya bergantung pada jenis tes. Rincian biaya akan muncul saat memilih jenis tes di form pemesanan.'],
            ] as $faq)
                <details class="group bg-white rounded-xl border border-slate-200 shadow-sm">
                    <summary class="flex items-center justify-between p-4 sm:p-5 cursor-pointer select-none">
                        <span class="font-semibold text-slate-900 text-sm sm:text-base">
                            {{ $faq['q'] }}
                        </span>
                        <span class="ml-3 flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 text-slate-500 group-open:bg-indigo-600 group-open:text-white transition">
                            <svg class="h-4 w-4 group-open:rotate-180 transition-transform" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 9l6 6 6-6"/>
                            </svg>
                        </span>
                    </summary>
                    <div class="px-4 sm:px-5 pb-4 sm:pb-5 border-t border-slate-100 text-xs sm:text-sm text-slate-600">
                        {{ $faq['a'] }}
                    </div>
                </details>
            @endforeach
        </div>
    </div>
</section>
@endsection