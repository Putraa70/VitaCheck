@extends('layouts.app', ['title' => 'Pesan Tes'])

@section('content')
    @php
        // Warna VitaCheck Unila
        $primaryColor = '#0f52ba';
        $secondaryColor = '#3f67ba';
    @endphp

    {{-- Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-3xl font-bold" style="color: {{ $primaryColor }}">
                Pesan Tes
            </h2>
            <p class="text-sm text-gray-600 mt-1">Pilih jenis tes dan jadwal slot yang tersedia sesuai kuota.</p>
        </div>
        <x-secondary-button as="a" href="{{ route('pemesanan.indeks') }}" class="shadow-sm">
            ← Kembali ke Daftar Pemesanan
        </x-secondary-button>
    </div>

    {{-- Error Handling --}}
    @if ($errors->any())
        <div class="mb-6 rounded-xl bg-rose-50 text-rose-700 ring-1 ring-rose-200 px-5 py-4 text-sm shadow-md">
            <div class="font-bold mb-2 flex items-center">
                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                Perhatian! Ada masalah pada isian Anda:
            </div>
            <ul class="list-disc ms-5 space-y-1">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('pemesanan.checkout') }}" enctype="multipart/form-data"
        class="bg-white rounded-2xl ring-1 ring-gray-100 p-8 space-y-8 shadow-xl">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- Kolom Kiri: Pilihan Tes & Waktu --}}
            <div class="space-y-6">
                <h3 class="text-lg font-bold pb-2 border-b" style="color: {{ $primaryColor }}; border-color: {{ $primaryColor }}33;">
                    Pilih Jenis Tes & Jadwal
                </h3>

                {{-- Jenis Tes --}}
                <div>
                    <label for="jenis_tes_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Jenis Tes <span class="text-rose-500">*</span>
                    </label>
                    <select id="jenis_tes_id" name="jenis_tes_id" required
                              class="w-full rounded-xl border-gray-300 text-base py-2.5 px-3 focus:border-transparent focus:ring-4 focus:ring-opacity-50"
                              style="focus:ring-color: {{ $primaryColor }}33; border-color: {{ $primaryColor }}55;">
                        <option value="">— Pilih jenis tes —</option>
                        @foreach($jenisTes as $j)
                            <option value="{{ $j->id }}" @selected(old('jenis_tes_id') == $j->id)>
                                {{ $j->nama }} — Rp {{ number_format($j->biaya, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_tes_id')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Slot Waktu --}}
                <div>
                    <label for="slot_waktu_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Slot Waktu <span class="text-rose-500">*</span>
                    </label>
                    <select id="slot_waktu_id" name="slot_waktu_id" required
                              class="w-full rounded-xl border-gray-300 text-base py-2.5 px-3 focus:border-transparent focus:ring-4 focus:ring-opacity-50"
                              style="focus:ring-color: {{ $primaryColor }}33; border-color: {{ $primaryColor }}55;">
                        <option value="">— Pilih slot waktu —</option>
                        @foreach($slotWaktu as $s)
                            <option value="{{ $s->id }}" @selected(old('slot_waktu_id') == $s->id)
                                    @disabled(max($s->kuota - $s->terpesan, 0) == 0)>
                                {{ $s->tanggal->translatedFormat('l, d M Y') }}
                                ({{ \Carbon\Carbon::parse($s->mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($s->selesai)->format('H:i') }})
                                — Sisa {{ max($s->kuota - $s->terpesan, 0) }}
                                @if (max($s->kuota - $s->terpesan, 0) == 0) (Penuh) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('slot_waktu_id')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Kolom Kanan: Upload Dokumen --}}
            <div class="space-y-6">
                <h3 class="text-lg font-bold pb-2 border-b" style="color: {{ $primaryColor }}; border-color: {{ $primaryColor }}33;">
                    Lampiran Dokumen
                </h3>

                <p class="text-sm text-gray-600">Dokumen akan digunakan untuk verifikasi data dan status pembayaran.</p>

                {{-- Upload KTM --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Upload KTM (opsional)
                    </label>
                    <input type="file" name="ktm" accept=".jpg,.jpeg,.png,.pdf"
                           class="w-full text-sm file:rounded-lg file:border-0 file:px-4 file:py-2.5 file:bg-gray-100 
                                  hover:file:bg-gray-200 transition cursor-pointer rounded-xl border border-gray-300 p-2 block"
                           style="--tw-text-opacity: 1; color: {{ $primaryColor }}; border-color: {{ $primaryColor }}55;">
                    @error('ktm')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Upload Bukti Pembayaran (opsional jika offline) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Upload Bukti Pembayaran (opsional)
                    </label>
                    <input type="file" name="bukti_bayar" accept=".jpg,.jpeg,.png,.pdf"
                           class="w-full text-sm file:rounded-lg file:border-0 file:px-4 file:py-2.5 file:bg-gray-100 
                                  hover:file:bg-gray-200 transition cursor-pointer rounded-xl border border-gray-300 p-2 block"
                           style="--tw-text-opacity: 1; color: {{ $primaryColor }}; border-color: {{ $primaryColor }}55;">
                    @error('bukti_bayar')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-2">Lampirkan jika Anda sudah melakukan pembayaran melalui metode transfer bank atau tunai.</p>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="pt-6 border-t border-gray-100 flex justify-end">
            <button type="submit" class="inline-flex items-center px-6 py-3 font-bold rounded-lg transition duration-200 shadow-lg text-white text-base hover:opacity-90"
                    style="background-color: {{ $primaryColor }}">
                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 12.08a2 2 0 0 0 2 1.92h9.72a2 2 0 0 0 2-1.92L23 6H6"></path></svg>
                Pesan & Lanjut ke Pembayaran
            </button>
        </div>
    </form>
@endsection