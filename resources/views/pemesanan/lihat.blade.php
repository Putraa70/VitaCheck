@extends('layouts.app', ['title' => 'Detail Pemesanan'])

@section('content')
@if(session('sukses'))
  <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,2000)"
       class="mb-4 rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 px-4 py-3 text-sm">
    {{ session('sukses') }}
  </div>
@endif

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
  <div>
    <h2 class="text-xl font-semibold">Detail Pemesanan</h2>
    <p class="text-sm text-gray-600">Kode: <span class="font-mono">{{ $p->kode }}</span></p>
  </div>
  <x-secondary-button as="a" href="{{ route('pemesanan.indeks') }}">← Kembali</x-secondary-button>
</div>

@if($p->status === 'menunggu_bayar' && $p->kedaluwarsa_pada && now()->lt($p->kedaluwarsa_pada))
  <div class="mb-4 rounded-xl bg-amber-50 text-amber-800 ring-1 ring-amber-200 px-4 py-3 text-sm flex items-center justify-between">
    <div>
      Menunggu pembayaran. Batas waktu: {{ $p->kedaluwarsa_pada->format('d M Y H:i') }} WIB
    </div>
    <a href="{{ route('pemesanan.lihat',$p->kode) }}" class="underline text-amber-900">Refresh</a>
  </div>
@endif
@if($p->status === 'kedaluwarsa')
  <div class="mb-4 rounded-xl bg-rose-50 text-rose-700 ring-1 ring-rose-200 px-4 py-3 text-sm">
    Pembayaran kedaluwarsa. Silakan pesan ulang.
  </div>
@endif

@php
  $statusStyles = [
    'menunggu'        => 'bg-amber-50 text-amber-700 ring-amber-200',
    'menunggu_bayar'  => 'bg-amber-50 text-amber-700 ring-amber-200',
    'terbayar'        => 'bg-sky-50 text-sky-700 ring-sky-200',
    'terkonfirmasi'   => 'bg-sky-50 text-sky-700 ring-sky-200',
    'check_in'        => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
    'selesai'         => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    'kedaluwarsa'     => 'bg-gray-50 text-gray-700 ring-gray-200',
    'dibatalkan'      => 'bg-rose-50 text-rose-700 ring-rose-200',
  ];
  $badge = $statusStyles[$p->status] ?? 'bg-gray-50 text-gray-700 ring-gray-200';
@endphp

<div class="bg-white rounded-2xl ring-1 ring-gray-100 p-6 space-y-6">
  <dl class="grid sm:grid-cols-2 gap-6 text-sm">
    <div>
      <dt class="text-gray-500">Jenis Tes</dt>
      <dd class="mt-1 font-medium">{{ $p->jenisTes->nama ?? '-' }}</dd>
    </div>
    <div>
      <dt class="text-gray-500">Total Bayar</dt>
      <dd class="mt-1 font-medium">Rp {{ number_format((int)($p->total_bayar ?? 0),0,',','.') }}</dd>
    </div>
    <div>
      <dt class="text-gray-500">Tanggal Slot</dt>
      <dd class="mt-1">
        {{ optional($p->slotWaktu?->tanggal)->translatedFormat('l, d M Y') ?? '-' }}
        @if($p->slotWaktu?->mulai && $p->slotWaktu?->selesai)
          • {{ $p->slotWaktu->mulai }}–{{ $p->slotWaktu->selesai }}
        @endif
      </dd>
    </div>
    <div>
      <dt class="text-gray-500">Status</dt>
      <dd class="mt-1">
        <span class="inline-flex items-center rounded-full px-2.5 py-1 ring-1 text-xs {{ $badge }}">
          {{ ucfirst(str_replace('_',' ',$p->status)) }}
        </span>
      </dd>
    </div>
    <div>
      <dt class="text-gray-500">Dibuat</dt>
      <dd class="mt-1">{{ optional($p->created_at)->format('d M Y H:i') }}</dd>
    </div>
    <div>
      <dt class="text-gray-500">Waktu Dibayar</dt>
      <dd class="mt-1">{{ $p->dibayar_pada ? $p->dibayar_pada->format('d M Y H:i') : '-' }}</dd>
    </div>
  </dl>

  {{-- Antrian & QR --}}
  @if($p->nomor_antrian || $p->qr_checkin || $p->waktu_tes)
    <div class="pt-4 border-t">
      <h3 class="font-semibold mb-3">Check-in & Jadwal Tes</h3>
      <div class="grid sm:grid-cols-2 gap-6 items-center">
        <div class="space-y-2">
          <div class="text-gray-500 text-sm">Nomor Antrian</div>
          <div class="text-3xl font-extrabold tracking-wide">#{{ $p->nomor_antrian ?? '-' }}</div>

          <div class="text-gray-500 text-sm mt-4">Waktu Tes</div>
          <div class="text-base">
            {{ $p->waktu_tes ? $p->waktu_tes->format('d M Y H:i') . ' WIB' : '-' }}
          </div>

          <p class="text-xs text-gray-500 mt-2">
            Datang sesuai jadwal. Tunjukkan QR di bawah kepada petugas saat registrasi.
          </p>
        </div>
        <div class="flex items-center justify-center">
          @if($p->qr_checkin)
            <img
              src="{{ route('pemesanan.qr', $p) }}"
              alt="QR Check-in {{ $p->kode }}"
              class="w-48 h-48 object-contain"
            >
          @else
            <span class="text-gray-500 text-sm">QR belum tersedia.</span>
          @endif
        </div>

      </div>
    </div>
  @endif

  {{-- Berkas --}}
  <div class="pt-4 border-t">
    <h3 class="font-semibold mb-3">Berkas</h3>
    <ul class="space-y-2 text-sm">
      @forelse($p->berkasPemesanan as $b)
        <li class="flex items-center justify-between">
          <div>
            <div class="font-medium">{{ strtoupper($b->jenis) }}</div>
            <div class="text-gray-500">{{ $b->lokasi_berkas }}</div>
          </div>
          <a href="{{ $b->lokasi_berkas }}" target="_blank" class="text-indigo-600 hover:underline">Lihat</a>
        </li>
      @empty
        <li class="text-gray-500">Belum ada berkas.</li>
      @endforelse
    </ul>
  </div>

  {{-- Hasil Tes --}}
  <div class="pt-4 border-t">
    <h3 class="font-semibold mb-3">Hasil Tes</h3>
    @if($p->hasilTes)
      <dl class="grid sm:grid-cols-2 gap-6 text-sm">
        <div>
          <dt class="text-gray-500">Status Hasil</dt>
          <dd class="mt-1 font-medium">{{ strtoupper($p->hasilTes->status_hasil) }}</dd>
        </div>
        <div>
          <dt class="text-gray-500">Berkas Hasil</dt>
          <dd class="mt-1">
            @if($p->hasilTes->berkas_hasil)
              <a href="{{ $p->hasilTes->berkas_hasil }}" target="_blank" class="text-indigo-600 hover:underline">Unduh</a>
            @else
              <span class="text-gray-500">-</span>
            @endif
          </dd>
        </div>
        <div class="sm:col-span-2">
          <dt class="text-gray-500">Catatan</dt>
          <dd class="mt-1">{{ $p->hasilTes->catatan ?: '-' }}</dd>
        </div>
      </dl>
    @else
      <p class="text-sm text-gray-500">Belum ada hasil tes.</p>
    @endif
  </div>
</div>
@endsection
