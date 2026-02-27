@extends('layouts.app', ['title' => 'Detail Pemesanan'])

@section('content')
    @php
        // Warna VitaCheck Unila
        $primaryColor = '#0f52ba';
        $secondaryColor = '#3f67ba';
        
        $statusStyles = [
            'menunggu'        => 'bg-amber-100 text-amber-800 ring-amber-300 border-amber-400',
            'menunggu_bayar'  => 'bg-amber-100 text-amber-800 ring-amber-300 border-amber-400',
            'terbayar'        => 'bg-sky-100 text-sky-800 ring-sky-300 border-sky-400',
            'terkonfirmasi'   => 'bg-sky-100 text-sky-800 ring-sky-300 border-sky-400',
            'check_in'        => 'bg-indigo-100 text-indigo-800 ring-indigo-300 border-indigo-400',
            'selesai'         => 'bg-emerald-100 text-emerald-800 ring-emerald-300 border-emerald-400',
            'kedaluwarsa'     => 'bg-rose-100 text-rose-800 ring-rose-300 border-rose-400',
            'dibatalkan'      => 'bg-gray-100 text-gray-700 ring-gray-200 border-gray-300',
        ];
        $badge = $statusStyles[$p->status] ?? 'bg-gray-100 text-gray-700 ring-gray-200 border-gray-300';
    @endphp

    {{-- Alert Sukses --}}
    @if(session('sukses'))
        <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3000)"
            class="mb-6 rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 px-4 py-3 text-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('sukses') }}
        </div>
    @endif

    {{-- Header dan Tombol Kembali --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-3xl font-bold" style="color: {{ $primaryColor }}">Detail Pemesanan</h1>
            <p class="text-sm text-gray-600 mt-1">Kode Transaksi: <span class="font-mono font-medium">{{ $p->kode }}</span></p>
        </div>
        <x-secondary-button as="a" href="{{ route('pemesanan.indeks') }}">← Kembali ke Daftar</x-secondary-button>
    </div>

    {{-- Peringatan Status Pembayaran --}}
    @if($p->status === 'menunggu_bayar' && $p->kedaluwarsa_pada && now()->lt($p->kedaluwarsa_pada))
        <div class="mb-6 rounded-xl bg-amber-50 text-amber-800 ring-1 ring-amber-200 px-4 py-3 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.3 16a2 2 0 001.732 3z"/></svg>
                Menunggu pembayaran. Batas waktu: <span class="font-bold">{{ $p->kedaluwarsa_pada->translatedFormat('d M Y H:i') }} WIB</span>
            </div>
            <a href="{{ route('pemesanan.checkout',$p->kode) }}" class="underline text-amber-900 font-semibold hover:text-amber-700 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-9 5h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Bayar Sekarang
            </a>
        </div>
    @elseif($p->status === 'kedaluwarsa')
        <div class="mb-6 rounded-xl bg-rose-50 text-rose-700 ring-1 ring-rose-200 px-4 py-3 text-sm flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Pembayaran kedaluwarsa. Silakan buat pemesanan ulang.
        </div>
    @endif

    <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-6 space-y-8 shadow-xl">
        {{-- Detail Utama --}}
        <dl class="grid sm:grid-cols-2 gap-x-12 gap-y-6 text-sm">
            <div>
                <dt class="text-gray-500">Jenis Tes</dt>
                <dd class="mt-1 text-base font-semibold">{{ $p->jenisTes->nama ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Total Bayar</dt>
                <dd class="mt-1 text-2xl font-extrabold" style="color: {{ $secondaryColor }}">
                    Rp {{ number_format((int)($p->total_bayar ?? 0),0,',','.') }}
                </dd>
            </div>
            <div>
                <dt class="text-gray-500">Tanggal Slot</dt>
                <dd class="mt-1 font-medium">
                    {{ optional($p->slotWaktu?->tanggal)->translatedFormat('l, d M Y') ?? '-' }}
                    @if($p->slotWaktu?->mulai && $p->slotWaktu?->selesai)
                        <span class="text-gray-500">•</span> {{ $p->slotWaktu->mulai }}–{{ $p->slotWaktu->selesai }}
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-gray-500">Status</dt>
                <dd class="mt-1">
                    <span class="inline-flex items-center rounded-full px-3 py-1 ring-1 text-xs font-semibold shadow-sm {{ $badge }}">
                        {{ ucfirst(str_replace('_',' ',$p->status)) }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-gray-500">Waktu Pemesanan</dt>
                <dd class="mt-1">{{ optional($p->created_at)->format('d M Y H:i') }} WIB</dd>
            </div>
            <div>
                <dt class="text-gray-500">Waktu Dibayar</dt>
                <dd class="mt-1">{{ $p->dibayar_pada ? $p->dibayar_pada->format('d M Y H:i') . ' WIB' : '-' }}</dd>
            </div>
        </dl>

        {{-- Antrian & QR --}}
        @if($p->nomor_antrian || $p->qr_checkin || $p->waktu_tes)
            <div class="pt-6 border-t border-gray-100">
                <h3 class="font-bold text-lg mb-4" style="color: {{ $primaryColor }}">
                    Check-in & Jadwal Tes
                </h3>
                <div class="grid sm:grid-cols-3 gap-6 items-start">
                    <div class="space-y-4 sm:col-span-2">
                        <div>
                            <div class="text-gray-500 text-sm">Nomor Antrian</div>
                            <div class="text-4xl font-extrabold tracking-wider mt-1" style="color: {{ $secondaryColor }}">
                                #{{ $p->nomor_antrian ?? 'TBA' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-gray-500 text-sm">Waktu Tes Terjadwal</div>
                            <div class="text-lg font-medium mt-1">
                                {{ $p->waktu_tes ? $p->waktu_tes->format('d M Y H:i') . ' WIB' : '-' }}
                            </div>
                        </div>
                        
                        <p class="text-xs text-gray-500 pt-2 border-t border-gray-100">
                            Mohon datang 15 menit sebelum jadwal. Tunjukkan QR Code di bawah kepada petugas saat registrasi.
                        </p>
                    </div>

                    <div class="flex items-center justify-center pt-4 sm:pt-0">
                        @if($p->qr_checkin)
                            <img
                                src="{{ route('pemesanan.qr', $p) }}"
                                alt="QR Check-in {{ $p->kode }}"
                                class="w-48 h-48 object-contain rounded-lg shadow-md border-2 border-gray-200"
                            >
                        @else
                            <div class="w-48 h-48 flex items-center justify-center bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                <span class="text-gray-500 text-sm">QR belum tersedia</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Berkas --}}
        <div class="pt-6 border-t border-gray-100">
            <h3 class="font-bold text-lg mb-4" style="color: {{ $primaryColor }}">
                Berkas Pendukung
            </h3>
            <ul class="space-y-3 text-sm">
                @forelse($p->berkasPemesanan as $b)
                    <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg ring-1 ring-gray-100">
                        <div>
                            <div class="font-semibold">{{ strtoupper($b->jenis) }}</div>
                            <div class="text-xs text-gray-500 truncate">{{ $b->lokasi_berkas }}</div>
                        </div>
                        <a href="{{ $b->lokasi_berkas }}" target="_blank" class="text-sm font-semibold hover:underline flex items-center" style="color: {{ $secondaryColor }}">
                            Lihat Berkas
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m-6-6L10 14"/></svg>
                        </a>
                    </li>
                @empty
                    <li class="text-gray-500 p-3 bg-gray-50 rounded-lg">Belum ada berkas pendukung yang diunggah.</li>
                @endforelse
            </ul>
        </div>

        {{-- Hasil Tes --}}
        <div class="pt-6 border-t border-gray-100">
            <h3 class="font-bold text-lg mb-4" style="color: {{ $primaryColor }}">
                Hasil Tes
            </h3>
            @if($p->hasilTes)
                <dl class="grid sm:grid-cols-2 gap-x-12 gap-y-4 text-sm bg-gray-50 p-4 rounded-xl ring-1 ring-gray-100">
                    <div>
                        <dt class="text-gray-500">Status Hasil</dt>
                        <dd class="mt-1 font-semibold text-lg">{{ strtoupper($p->hasilTes->status_hasil) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Berkas Hasil</dt>
                        <dd class="mt-1">
                            @if($p->hasilTes->berkas_hasil)
                                <a href="{{ $p->hasilTes->berkas_hasil }}" target="_blank" class="font-semibold hover:underline flex items-center" style="color: {{ $secondaryColor }}">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Unduh Hasil Tes
                                </a>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </dd>
                    </div>
                    <div class="sm:col-span-2 pt-2 border-t border-gray-200">
                        <dt class="text-gray-500">Catatan Petugas</dt>
                        <dd class="mt-1 italic text-gray-700">{{ $p->hasilTes->catatan ?: 'Tidak ada catatan.' }}</dd>
                    </div>
                </dl>
            @else
                <p class="text-sm text-gray-500 bg-gray-50 p-4 rounded-xl">Belum ada hasil tes yang diterbitkan.</p>
            @endif
        </div>
    </div>
@endsection