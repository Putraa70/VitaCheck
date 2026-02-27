@extends('layouts.app', ['title' => 'Beranda Akun'])

@section('content')
@php
    // ====== FALLBACK & NORMALISASI DATA (TIDAK DIUBAH) ======
    $notifications   = $notifications   ?? collect(); 
    $announcements   = $announcements   ?? collect(); 
    $results         = $results         ?? collect(); 
    $nextAppointment = $nextAppointment ?? null;       
    $kpi             = $kpi ?? ['total'=>0,'menunggu'=>0,'terkonfirmasi'=>0,'check_in'=>0,'selesai'=>0,'dibatalkan'=>0];

    // progress pendaftaran sederhana
    $hasProfile = isset(auth()->user()->name) && !empty(auth()->user()->name);
    $hasOrder   = ($kpi['total'] ?? 0) > 0;
    $hasUpload  = false; 
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
        'menunggu'      => 'bg-amber-100 text-amber-800 ring-amber-300',
        'terkonfirmasi' => 'bg-sky-100 text-sky-800 ring-sky-300',
        'check_in'      => 'bg-indigo-100 text-indigo-800 ring-indigo-300',
        'selesai'       => 'bg-emerald-100 text-emerald-800 ring-emerald-300',
        'dibatalkan'    => 'bg-rose-100 text-rose-800 ring-rose-300',
    ];

    // Warna Spesialis Anda
    $primaryColor = '#0f52ba';
    $secondaryColor = '#3f67ba'; 

    // Logika Sapaan Waktu (Selamat Pagi/Siang/Sore/Malam)
    $hour = (int)date('H');
    if ($hour >= 5 && $hour < 11) { $greeting = 'Selamat Pagi'; }
    elseif ($hour >= 11 && $hour < 15) { $greeting = 'Selamat Siang'; }
    elseif ($hour >= 15 && $hour < 18) { $greeting = 'Selamat Sore'; }
    else { $greeting = 'Selamat Malam'; }
    
    // Pesan motivasi acak (menyesuaikan tema klinik)
    $quotes = [
        "Jaga kesehatanmu. Karena kesehatan adalah kekayaan yang sesungguhnya.",
        "Setiap janji tes adalah langkah menuju masa depan yang lebih sehat.",
        "Fokus pada kesehatan hari ini, untuk vitalitas esok hari.",
        "Lakukan yang terbaik. Masa depan kesehatanmu dimulai sekarang.",
    ];
    $quote = $quotes[array_rand($quotes)];
@endphp

{{-- REVISI HERO SECTION: Kartu Sapaan & Statistik --}}
<section class="mb-8">
    <div class="grid lg:grid-cols-4 gap-6">
        
        {{-- 1. Kartu Sapaan (Gaya Biru Lembut/Ungu Muda seperti contoh) --}}
        <div class="lg:col-span-2 rounded-2xl p-6 shadow-xl flex items-center" style="background-color: #f7f3ff; border: 1px solid #e0d0ff;">
            
            {{-- Mengganti gambar dokter dengan ikon/avatar yang lebih sederhana atau relevan --}}
            <div class="shrink-0 mr-6">
                {{-- Placeholder untuk ilustrasi/avatar pengguna --}}
                <div class="h-20 w-20 rounded-full bg-white flex items-center justify-center border-4 border-white shadow-md">
                    <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" style="color: {{ $primaryColor }}"><circle cx="12" cy="7" r="3" stroke="currentColor" stroke-width="1.5"/><path d="M20 21a8 8 0 10-16 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-bold mb-1" style="color: #6a4c9c">{{ $greeting }}, <span style="color: {{ $primaryColor }}">{{ auth()->user()->name }}</span>!</h2>
                <p class="text-sm text-gray-600 italic mt-2">
                    "{{ $quote }}"
                </p>
                <a href="{{ route('profile.edit') }}" class="mt-3 inline-block text-xs font-semibold hover:underline" style="color: {{ $secondaryColor }}">Lengkapi Profil Anda →</a>
            </div>
        </div>

        {{-- 2. Kartu Statistik Total Appointment --}}
        <div class="rounded-2xl p-6 text-white shadow-xl relative overflow-hidden" style="background: linear-gradient(145deg, {{ $primaryColor }} 0%, {{ $secondaryColor }} 100%);">
            <p class="text-sm opacity-80 font-semibold mb-2">Total Pemesanan Tes</p>
            <h3 class="text-4xl font-extrabold">{{ number_format($kpi['total'] ?? 0, 0, ',', '.') }}</h3>
            {{-- Grafik gelombang sederhana --}}
            <svg class="absolute bottom-0 left-0 w-full h-12 opacity-30" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="#ffffff" d="M0,160L48,160C96,160,192,160,288,160C384,160,480,160,576,170.7C672,181,768,203,864,202.7C960,203,1056,181,1152,176C1248,171,1344,181,1392,186.7L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>

        {{-- 3. Kartu Statistik Selesai/Batal (Menggunakan 'Selesai' karena lebih positif) --}}
        <div class="rounded-2xl p-6 bg-white shadow-xl relative overflow-hidden border border-gray-100">
            <p class="text-sm font-semibold mb-2 text-gray-600">Tes Selesai</p>
            <h3 class="text-4xl font-extrabold" style="color: #10b981">{{ number_format($kpi['selesai'] ?? 0, 0, ',', '.') }}</h3>
            {{-- Grafik gelombang (Hijau/Emerald) --}}
            <svg class="absolute bottom-0 left-0 w-full h-12 opacity-50" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="#d1fae5" d="M0,192L48,181.3C96,171,192,149,288,165.3C384,181,480,235,576,240C672,245,768,192,864,181.3C960,171,1056,203,1152,213.3C1248,224,1344,213,1392,208L1440,203L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </div>
    
    {{-- Aksi Cepat di bawah kartu statistik (Opsional, tergantung desain) --}}
    <div class="mt-6 flex flex-wrap gap-4">
        <a href="{{ route('pemesanan.buat') }}" class="inline-flex items-center rounded-xl px-5 py-3 text-lg font-bold shadow-lg transition duration-300 hover:opacity-90 text-white" style="background-color: {{ $primaryColor }}">
            <svg class="h-6 w-6 mr-3" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
            Pesan Tes Baru Sekarang
        </a>
        <a href="{{ route('pemesanan.indeks') }}" class="inline-flex items-center rounded-xl border-2 border-gray-300 px-5 py-3 text-gray-700 text-lg font-semibold hover:bg-gray-100 transition duration-300">
            <svg class="h-6 w-6 mr-3" viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M8 3v4M16 3v4M3 10h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Lihat Semua Jadwal
        </a>
    </div>

    {{-- Peringatan Verifikasi Email (ditempatkan di bawah kartu) --}}
    @if(!auth()->user()->hasVerifiedEmail())
        <div class="mt-6 flex items-start gap-4 rounded-xl bg-amber-100 text-amber-800 p-5 border border-amber-300 shadow-md">
            <svg class="h-6 w-6 shrink-0" viewBox="0 0 24 24" fill="none"><path d="M3 7l9 6 9-6" stroke="currentColor" stroke-width="1.5"/><rect x="3" y="7" width="18" height="10" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M12 9v3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><circle cx="12" cy="15" r="1" fill="currentColor"/></svg>
            <div class="text-sm">
                <p class="font-bold text-md">Verifikasi email belum selesai</p>
                <p class="opacity-90 mt-1">Email Anda belum terverifikasi. Mohon kirim ulang tautan verifikasi untuk mengakses semua fitur layanan.</p>
                <form method="POST" action="{{ route('verification.send') }}" class="mt-3">@csrf
                    <button class="inline-flex items-center rounded-lg bg-amber-500 text-white px-4 py-2 text-xs font-bold hover:bg-amber-600 transition shadow-sm">Kirim Ulang Verifikasi</button>
                </form>
            </div>
        </div>
    @endif
</section>

{{-- Lanjutkan dengan bagian 1) Status Pendaftaran, 2) Pemberitahuan, dst. --}}
<div class="grid lg:grid-cols-3 gap-8 mb-8">
    {{-- Status pendaftaran --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg ring-1 ring-gray-100 p-6">
        <div class="flex items-center gap-3 mb-6 border-b pb-3" style="border-color: {{ $primaryColor }}1a;">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" style="color: {{ $primaryColor }}"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5"/><path d="M12 7v5l3 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            <h3 class="font-bold text-xl" style="color: {{ $primaryColor }}">Progress Pendaftaran Anda</h3>
        </div>
        <ol class="grid sm:grid-cols-5 gap-3">
            @foreach($steps as $i => $st)
                <li class="flex flex-col items-start relative">
                    <span class="text-xs font-semibold mb-2" style="color: {{ $secondaryColor }}">LANGKAH {{ $i+1 }}</span>
                    <div class="inline-flex flex-col items-start rounded-xl px-4 py-3 text-sm transition-all duration-300 w-full
                        {{ $st['done'] ? 'bg-emerald-50 text-emerald-700 ring-2 ring-emerald-300 shadow-sm' : 'bg-gray-50 text-gray-700 ring-1 ring-gray-200' }}">
                        <div class="flex items-center gap-2">
                            @if($st['done'])
                                <svg class="h-4 w-4 text-emerald-500" viewBox="0 0 24 24" fill="none"><path d="M5 12l4 4L19 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            @else
                                <svg class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.5"/></svg>
                            @endif
                            <span class="font-medium">{{ $st['label'] }}</span>
                        </div>
                    </div>
                </li>
            @endforeach
        </ol>
        <div class="mt-6 border-t pt-4 flex flex-wrap gap-2">
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Lengkapi Profil</a>
            <a href="{{ route('pemesanan.buat') }}" class="inline-flex items-center rounded-lg px-4 py-2 text-sm text-white font-medium hover:opacity-90 transition" style="background-color: {{ $primaryColor }}">Pesan Tes</a>
            <a href="{{ route('pemesanan.indeks') }}" class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Lihat Pemesanan</a>
        </div>
    </div>

    {{-- Pemberitahuan --}}
    <div class="bg-white rounded-2xl shadow-lg ring-1 ring-gray-100 p-6">
        <div class="flex items-center justify-between mb-5 border-b pb-3" style="border-color: {{ $primaryColor }}1a;">
            <div class="flex items-center gap-3">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" style="color: {{ $primaryColor }}"><path d="M12 22a2 2 0 002-2H10a2 2 0 002 2zM18 16v-5a6 6 0 10-12 0v5l-2 2h16l-2-2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                <h3 class="font-bold text-xl" style="color: {{ $primaryColor }}">Pemberitahuan</h3>
            </div>
            <a href="{{ route('pemesanan.indeks') }}" class="text-sm font-medium hover:underline" style="color: {{ $secondaryColor }}">Lihat semua</a>
        </div>
        <div class="space-y-4 max-h-[300px] overflow-y-auto">
            @forelse($notifications as $n)
                <div class="rounded-xl bg-gray-50 border border-gray-200 p-4 hover:shadow-md transition duration-300">
                    <p class="font-bold text-sm" style="color: {{ $primaryColor }}">{{ $n['title'] ?? 'Notifikasi' }}</p>
                    @if(!empty($n['body']))
                        <p class="text-sm text-gray-600 mt-1">{{ $n['body'] }}</p>
                    @endif
                    <p class="text-xs text-gray-500 mt-2">{{ $n['time'] ?? '' }}</p>
                </div>
            @empty
                <div class="rounded-xl bg-gray-100 p-5 text-md text-gray-600 text-center border border-gray-200">
                    Belum ada pemberitahuan baru. Tetap semangat!
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- 3) Hasil Tes Terbaru + 4) Pengumuman/News --}}
<div class="grid lg:grid-cols-3 gap-8">
    {{-- Hasil tes --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg ring-1 ring-gray-100 p-6">
        <div class="flex items-center justify-between mb-5 border-b pb-3" style="border-color: {{ $primaryColor }}1a;">
            <div class="flex items-center gap-3">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" style="color: {{ $primaryColor }}"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                <h3 class="font-bold text-xl" style="color: {{ $primaryColor }}">Hasil Tes Terbaru</h3>
            </div>
            <a href="{{ route('pemesanan.indeks') }}" class="text-sm font-medium hover:underline" style="color: {{ $secondaryColor }}">Ke Riwayat Lengkap</a>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-200">
            <table class="min-w-full text-sm">
                <thead class="text-gray-700" style="background-color: {{ $primaryColor }}1a;">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Kode</th>
                        <th class="px-4 py-3 text-left font-semibold">Jenis Tes</th>
                        <th class="px-4 py-3 text-left font-semibold">Tanggal Tes</th>
                        <th class="px-4 py-3 text-left font-semibold">Kesimpulan</th>
                        <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($results as $r)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium">{{ $r->kode ?? $r['kode'] ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $r->jenisTes->nama ?? data_get($r, 'jenis_tes_nama', '-') }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ optional(optional($r->slotWaktu)->tanggal)->format('d M Y') ?? data_get($r, 'tanggal_str', '-') }}</td>
                            <td class="px-4 py-3">
                                @php $sum = $r->kesimpulan ?? $r['kesimpulan'] ?? '-'; @endphp
                                <span class="inline-flex items-center rounded-md bg-green-100 text-green-700 px-2.5 py-1 text-xs font-medium border border-green-300">{{ $sum }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @php $url = $r->file_url ?? $r['file_url'] ?? null; @endphp
                                @if($url)
                                    <a href="{{ $url }}" class="font-medium hover:underline" style="color: {{ $secondaryColor }}">Unduh PDF</a>
                                @else
                                    <span class="text-gray-500 text-xs">Belum tersedia</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-md text-gray-500 bg-gray-50">
                                Belum ada hasil tes. Hasil akan tampil di sini setelah diverifikasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('pemesanan.buat') }}" class="inline-flex items-center rounded-lg px-4 py-2 text-white font-medium hover:opacity-90 transition" style="background-color: {{ $primaryColor }}">Pesan Tes Lagi</a>
            <a href="{{ route('pemesanan.indeks') }}" class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50 transition">Semua Pemesanan</a>
        </div>
    </div>

    {{-- Pengumuman/News --}}
    <div class="bg-white rounded-2xl shadow-lg ring-1 ring-gray-100 p-6">
        <div class="flex items-center gap-3 mb-5 border-b pb-3" style="border-color: {{ $primaryColor }}1a;">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" style="color: {{ $primaryColor }}"><path d="M4 6h16v10H7l-3 3V6z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            <h3 class="font-bold text-xl" style="color: {{ $primaryColor }}">Pengumuman & Berita</h3>
        </div>
        <div class="space-y-4 max-h-[400px] overflow-y-auto">
            @forelse($announcements as $a)
                <article class="rounded-xl border border-gray-200 p-4 hover:bg-gray-50 transition duration-300">
                    <a href="{{ $a['url'] ?? '#' }}" class="font-bold hover:underline text-md" style="color: {{ $secondaryColor }}">{{ $a['title'] ?? 'Pengumuman' }}</a>
                    @if(!empty($a['excerpt']))
                        <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $a['excerpt'] }}</p>
                    @endif
                    <p class="mt-2 text-xs text-gray-500">{{ $a['date'] ?? '' }}</p>
                </article>
            @empty
                <div class="rounded-xl bg-gray-100 p-5 text-md text-gray-600 text-center border border-gray-200">
                    Belum ada pengumuman terbaru saat ini.
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Bantuan & Tips --}}
<div class="mt-8 grid md:grid-cols-2 gap-8">
    <div class="bg-white rounded-2xl shadow-lg ring-1 ring-gray-100 p-6">
        <div class="flex items-center gap-3 mb-4 border-b pb-2" style="border-color: {{ $primaryColor }}1a;">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" style="color: {{ $primaryColor }}"><path d="M12 8a3 3 0 00-3 3h2a1 1 0 112 0c0 .667-.333 1-1.5 1.5S9 13.667 9 15h2c0-.5.5-.833 1.5-1.333S14 12.667 14 11a3 3 0 00-2-3z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M12 18h.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            <h3 class="font-bold text-xl" style="color: {{ $primaryColor }}">Bantuan Cepat (FAQ)</h3>
        </div>
        <ul class="text-sm text-gray-700 space-y-3">
            <li class="border-l-2 pl-3" style="border-color: {{ $secondaryColor }}"><b>Bagaimana cara memesan tes?</b> Masuk → klik “Pesan Tes” → pilih slot → unggah berkas.</li>
            <li class="border-l-2 pl-3" style="border-color: {{ $secondaryColor }}"><b>Di mana melihat hasil?</b> Lihat di bagian “Hasil Tes Terbaru” atau buka riwayat pemesanan.</li>
            <li class="border-l-2 pl-3" style="border-color: {{ $secondaryColor }}"><b>Butuh bantuan?</b> Hubungi admin klinik fakultas atau kirim pesan via halaman kontak.</li>
        </ul>
        <div class="mt-6">
            <a href="{{ route('pemesanan.buat') }}" class="inline-flex items-center rounded-lg px-5 py-2 text-white font-medium hover:opacity-90 transition" style="background-color: {{ $primaryColor }}">Mulai Pesan Sekarang</a>
        </div>
    </div>
    <div class="rounded-2xl ring-2 shadow-lg p-6" style="background-color: {{ $primaryColor }}0d; border-color: {{ $primaryColor }}33;">
        <h3 class="font-bold text-xl" style="color: {{ $primaryColor }}">💡 Tips Penting</h3>
        <ul class="mt-4 text-md list-disc pl-5 space-y-2" style="color: {{ $primaryColor }}">
            {{-- Mengubah Markdown **...** menjadi tag <b>...</b> atau <strong>...</strong> untuk memastikan rendering bold di Blade/HTML --}}
            <li>Datang <b>10–15 menit lebih awal</b> untuk proses <b>check-in</b> yang lancar.</li>
            <li>Pastikan berkas (<b>KTM & bukti bayar</b>) terbaca jelas dan telah diunggah.</li>
            <li>Cek pengumuman secara berkala untuk jadwal layanan khusus atau hari libur.</li>
        </ul>
    </div>
</div>
   
@endsection
