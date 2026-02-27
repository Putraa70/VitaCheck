@extends('layouts.app', ['title' => 'Pemesanan Saya'])

@section('content')
    @php
        // Warna VitaCheck Unila
        $primaryColor = '#0f52ba';
        $secondaryColor = '#3f67ba';
    @endphp

    {{-- Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold" style="color: {{ $primaryColor }}">
                Riwayat Pemesanan Tes
            </h2>
            <p class="text-sm text-gray-600 mt-1">Lihat status, detail, dan riwayat pemesanan tes yang telah Anda lakukan.</p>
        </div>
        <a href="{{ route('pemesanan.buat') }}"
           class="inline-flex items-center px-6 py-3 font-bold rounded-xl transition duration-200 shadow-lg text-white text-base hover:opacity-90"
           style="background-color: {{ $primaryColor }}">
            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
            Pesan Tes Baru
        </a>
    </div>

    {{-- Daftar Pemesanan (Menggunakan Card View untuk tampilan modern) --}}
    <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-6 shadow-xl">
        
        @forelse($pemesanan as $p)
            @php
                // Logika Status untuk styling
                $statusColor = 'bg-gray-100 text-gray-700 ring-gray-200';
                $icon = 'M13 10V3L4 14h7v7l9-11h-7z';
                $statusText = ucfirst(str_replace('_',' ',$p->status));

                switch($p->status) {
                    case 'menunggu':
                        $statusColor = 'bg-amber-100 text-amber-800 ring-amber-300 border-amber-400';
                        $icon = '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>';
                        break;
                    case 'terkonfirmasi':
                        $statusColor = 'bg-sky-100 text-sky-800 ring-sky-300 border-sky-400';
                        $icon = '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/>';
                        break;
                    case 'check_in':
                        $statusColor = 'bg-indigo-100 text-indigo-800 ring-indigo-300 border-indigo-400';
                        $icon = '<path d="M17 3H7c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-11H9V5h6v3z"/>';
                        break;
                    case 'selesai':
                        $statusColor = 'bg-emerald-100 text-emerald-800 ring-emerald-300 border-emerald-400';
                        $icon = '<path d="M20 6L9 17l-5-5"/>';
                        break;
                }
            @endphp
            
            <div class="relative p-5 mb-5 rounded-xl border-2 transition duration-300 hover:shadow-md" style="border-color: {{ $primaryColor }}33;">
                
                {{-- Status Tag (Positioned Absolutely) --}}
                <span class="absolute top-0 right-0 mt-4 mr-4 inline-flex items-center px-3 py-1 text-xs font-bold rounded-full ring-1 border"
                      style="color: {{ $primaryColor }}; background-color: {{ $primaryColor }}1a;">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M19 8l-7 7-7-7"/></svg>
                    {{ $p->jenisTes->nama ?? 'Tes Tidak Diketahui' }}
                </span>

                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    
                    {{-- Kode Pemesanan --}}
                    <div class="col-span-2 sm:col-span-1">
                        <p class="text-xs font-medium text-gray-500 uppercase mb-0.5">Kode Pemesanan</p>
                        <p class="text-lg font-extrabold text-gray-900" style="color: {{ $secondaryColor }}">{{ $p->kode }}</p>
                    </div>

                    {{-- Tanggal & Waktu --}}
                    <div class="col-span-2 sm:col-span-1">
                        <p class="text-xs font-medium text-gray-500 uppercase mb-0.5">Jadwal Tes</p>
                        <p class="text-base font-semibold text-gray-800">{{ optional($p->slotWaktu?->tanggal)->translatedFormat('l, d M Y') ?? '-' }}</p>
                        <p class="text-sm text-gray-600">({{ optional($p->slotWaktu)->mulai }}–{{ optional($p->slotWaktu)->selesai }})</p>
                    </div>
                    
                    {{-- Status --}}
                    <div class="col-span-2 sm:col-span-1">
                        <p class="text-xs font-medium text-gray-500 uppercase mb-0.5">Status Saat Ini</p>
                        <span class="inline-flex items-center px-4 py-1.5 text-sm font-bold rounded-full ring-1 border shadow-sm {{ $statusColor }}">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">{!! $icon !!}</svg>
                            {{ $statusText }}
                        </span>
                    </div>

                    {{-- Aksi --}}
                    <div class="col-span-2 sm:col-span-1 flex items-end justify-start sm:justify-end mt-4 sm:mt-0">
                        <x-primary-button as="a" href="{{ route('pemesanan.lihat', $p->kode) }}"
                            class="shadow-md" style="background-color: {{ $secondaryColor }}; border-color: {{ $secondaryColor }}">
                            Lihat Detail
                        </x-primary-button>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-10 text-center text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                <p class="text-lg font-semibold mb-2">Belum ada pemesanan tes.</p>
                <p class="text-sm">Silakan buat pemesanan baru untuk melihat riwayat di sini.</p>
                <div class="mt-4">
                    <a href="{{ route('pemesanan.buat') }}"
                       class="inline-flex items-center px-5 py-2 font-medium rounded-lg transition duration-200 text-white text-sm"
                       style="background-color: {{ $primaryColor }}">
                        + Pesan Tes
                    </a>
                </div>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($pemesanan->count() > 0)
            <div class="pt-6 border-t border-gray-100 mt-6">
                {{ $pemesanan->links() }}
            </div>
        @endif
    </div>
@endsection