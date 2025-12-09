@extends('layouts.admin', ['title' => 'Detail Pemesanan'])

@section('breadcrumb')
  <a href="{{ route('admin.dasbor_admin') }}" class="hover:underline">Dasbor</a> /
  <a href="{{ route('admin.pemesanans.index') }}" class="hover:underline">Pemesanan</a> / Detail
@endsection

@php
    use Illuminate\Support\Facades\Storage;

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
    $badge = $statusStyles[$item->status] ?? 'bg-gray-50 text-gray-700 ring-gray-200';
@endphp

@section('content')
@if(session('sukses'))
  <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,2000)"
       class="mb-4 rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 px-4 py-3 text-sm">
    {{ session('sukses') }}
  </div>
@endif

<div class="mb-6">
  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
    <div>
      <h2 class="text-xl font-semibold">Detail Pemesanan</h2>
      <p class="text-sm text-gray-600">Kode: <span class="font-mono">{{ $item->kode }}</span></p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
      <x-secondary-button as="a" href="{{ route('admin.pemesanans.index') }}">← Daftar Pemesanan</x-secondary-button>
    </div>
  </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
  {{-- Kolom kiri: detail --}}
  <div class="lg:col-span-2 bg-white rounded-2xl ring-1 ring-gray-100 p-6 space-y-6">
    <dl class="grid sm:grid-cols-2 gap-6 text-sm">
      <div>
        <dt class="text-gray-500">Mahasiswa</dt>
        <dd class="mt-1 font-medium">{{ $item->user->name ?? '-' }}</dd>
      </div>
      <div>
        <dt class="text-gray-500">Email</dt>
        <dd class="mt-1">{{ $item->user->email ?? '-' }}</dd>
      </div>
      <div>
        <dt class="text-gray-500">Jenis Tes</dt>
        <dd class="mt-1 font-medium">{{ $item->jenisTes->nama ?? '-' }}</dd>
      </div>
      <div>
        <dt class="text-gray-500">Tanggal Slot</dt>
        <dd class="mt-1">
          @if($item->slotWaktu?->tanggal)
            {{ \Illuminate\Support\Carbon::parse($item->slotWaktu->tanggal)->translatedFormat('l, d M Y') }}
          @else
            -
          @endif
          @if(!empty($item->slotWaktu?->mulai) && !empty($item->slotWaktu?->selesai))
            • {{ $item->slotWaktu->mulai }}–{{ $item->slotWaktu->selesai }}
          @endif
        </dd>
      </div>
      <div>
        <dt class="text-gray-500">Status</dt>
        <dd class="mt-1">
          <span class="inline-flex items-center rounded-full px-2.5 py-1 ring-1 text-xs {{ $badge }}">
            {{ ucfirst(str_replace('_',' ',$item->status)) }}
          </span>
        </dd>
      </div>
      <div>
        <dt class="text-gray-500">Total Bayar</dt>
        <dd class="mt-1 font-medium">
          Rp {{ number_format((int)($item->total_bayar ?? 0),0,',','.') }}
        </dd>
      </div>
      <div>
        <dt class="text-gray-500">Waktu Dibayar</dt>
        <dd class="mt-1">
          {{ $item->dibayar_pada ? $item->dibayar_pada->format('d M Y H:i') : '-' }}
        </dd>
      </div>
      <div>
        <dt class="text-gray-500">Dibuat</dt>
        <dd class="mt-1">{{ optional($item->created_at)->format('d M Y H:i') }}</dd>
      </div>
      <div>
        <dt class="text-gray-500">Nomor Antrian</dt>
        <dd class="mt-1 font-semibold text-lg">#{{ $item->nomor_antrian ?? '-' }}</dd>
      </div>
      <div>
        <dt class="text-gray-500">Waktu Tes</dt>
        <dd class="mt-1">
          {{ $item->waktu_tes ? $item->waktu_tes->format('d M Y H:i') . ' WIB' : '-' }}
        </dd>
      </div>
    </dl>

    {{-- QR Check-in --}}
    <div class="pt-4 border-t">
      <h3 class="font-semibold mb-3">QR Check-in</h3>

      @if($item->qr_checkin)
        <div class="flex items-center gap-4">
          <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200 inline-flex">
            <img
              src="{{ route('admin.pemesanans.qr', $item) }}"
              alt="QR Check-in {{ $item->kode }}"
              class="w-44 h-44 object-contain"
            >
          </div>
          <p class="text-sm text-gray-600 max-w-xs">
            Tunjukkan QR ini langsung dari layar (HP atau komputer) saat registrasi untuk proses check-in.
            Petugas cukup memindai QR tanpa perlu mengunduh file terlebih dahulu.
          </p>
        </div>
      @else
        <p class="text-sm text-gray-500">
          Belum tersedia. QR akan dibuat otomatis saat status <b>terbayar</b> / <b>terkonfirmasi</b>.
        </p>
      @endif
    </div>

{{-- Berkas Pemesanan --}}
<div class="pt-4 border-t">
  <h3 class="font-semibold mb-3">Berkas Pemesanan</h3>

  <ul class="space-y-2 text-sm">
    @forelse($item->berkasPemesanan as $b)
      <li class="flex items-center justify-between">
        <div>
          <div class="font-medium">{{ strtoupper($b->jenis) }}</div>
          <div class="text-gray-500 text-xs break-all">
            {{ $b->lokasi_berkas }}
          </div>
        </div>

        {{-- gunakan route ke controller, bukan langsung /storage --}}
        <a href="{{ route('admin.pemesanans.berkas.show', $b) }}"
           target="_blank"
           class="text-indigo-600 hover:underline">
          Lihat
        </a>
      </li>
    @empty
      <li class="text-gray-500">Belum ada berkas.</li>
    @endforelse
  </ul>
</div>


    {{-- Hasil Tes (tampilan) --}}
    <div class="pt-4 border-t">
      <h3 class="font-semibold mb-3">Hasil Tes</h3>
      @if($item->hasilTes)
        <dl class="grid sm:grid-cols-2 gap-6 text-sm">
          <div>
            <dt class="text-gray-500">Status Hasil</dt>
            <dd class="mt-1 font-medium">{{ strtoupper($item->hasilTes->status_hasil) }}</dd>
          </div>
          <div>
            <dt class="text-gray-500">Berkas Hasil</dt>
            <dd class="mt-1">
              @if($item->hasilTes->berkas_hasil)
                <a href="{{ $item->hasilTes->berkas_hasil }}" target="_blank" class="text-indigo-600 hover:underline">
                  Unduh
                </a>
              @else
                <span class="text-gray-500">-</span>
              @endif
            </dd>
          </div>
          <div class="sm:col-span-2">
            <dt class="text-gray-500">Catatan</dt>
            <dd class="mt-1">{{ $item->hasilTes->catatan ?: '-' }}</dd>
          </div>
        </dl>
      @else
        <p class="text-sm text-gray-500">Belum ada hasil tes.</p>
      @endif
    </div>
  </div>

  {{-- Kolom kanan: aksi --}}
  <div class="space-y-6">
    {{-- Ubah Status --}}
    <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-6 h-fit">
      <h3 class="font-semibold mb-4">Ubah Status</h3>
      <form method="POST" action="{{ route('admin.pemesanans.perbarui_status', $item) }}" class="space-y-4">
        @csrf
        <div>
          <label class="block text-sm text-gray-700">Status</label>
<select name="status" class="mt-1 w-full rounded-xl border-gray-300">
  @foreach($daftarStatus as $st)
    <option value="{{ $st }}" @selected($item->status === $st)>
      {{ ucfirst(str_replace('_',' ', $st)) }}
    </option>
  @endforeach
</select>

          @error('status') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm text-gray-700">Tanggal Bayar (opsional)</label>
          <input type="datetime-local" name="dibayar_pada"
                 value="{{ old('dibayar_pada', $item->dibayar_pada ? $item->dibayar_pada->format('Y-m-d\TH:i') : '') }}"
                 class="mt-1 w-full rounded-xl border-gray-300">
          @error('dibayar_pada') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
          <p class="text-xs text-gray-500 mt-1">
            Jika kosong & status jadi <b>terkonfirmasi</b>/<b>terbayar</b>, sistem isi otomatis sekarang.
          </p>
        </div>

        <div class="grid sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-sm text-gray-700">Nomor Antrian (opsional)</label>
            <input type="number" name="nomor_antrian" min="1"
                   value="{{ old('nomor_antrian', $item->nomor_antrian) }}"
                   class="mt-1 w-full rounded-xl border-gray-300">
          </div>
          <div>
            <label class="block text-sm text-gray-700">Waktu Tes (opsional)</label>
            <input type="datetime-local" name="waktu_tes"
                   value="{{ old('waktu_tes', $item->waktu_tes ? $item->waktu_tes->format('Y-m-d\TH:i') : '') }}"
                   class="mt-1 w-full rounded-xl border-gray-300">
          </div>
        </div>

        <x-primary-button class="w-full justify-center">Simpan Perubahan</x-primary-button>
      </form>
    </div>

    {{-- Isi Hasil Tes --}}
    <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-6 h-fit">
      <h3 class="font-semibold mb-4">Isi Hasil Tes</h3>
      <form method="POST" action="{{ route('admin.pemesanans.hasil.store', $item) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
          <label class="block text-sm text-gray-700">Status Hasil</label>
          <select name="status_hasil" class="mt-1 w-full rounded-xl border-gray-300" required>
            <option value="">— pilih —</option>
            <option value="negatif" @selected(optional($item->hasilTes)->status_hasil === 'negatif')>
              Negatif (Narkoba)
            </option>
            <option value="positif" @selected(optional($item->hasilTes)->status_hasil === 'positif')>
              Positif (Narkoba)
            </option>
          </select>
        </div>
        <div>
          <label class="block text-sm text-gray-700">Catatan (opsional)</label>
          <textarea name="catatan" rows="3"
                    class="mt-1 w-full rounded-xl border-gray-300">{{ old('catatan', optional($item->hasilTes)->catatan) }}</textarea>
        </div>
        <div>
          <label class="block text-sm text-gray-700">Berkas Hasil (opsional)</label>
          <input type="file" name="berkas_hasil" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 w-full text-sm">
          @if(optional($item->hasilTes)->berkas_hasil)
            <p class="text-xs mt-1">
              Berkas saat ini:
              <a href="{{ optional($item->hasilTes)->berkas_hasil }}" target="_blank" class="text-indigo-600 underline">
                Lihat
              </a>
            </p>
          @endif
        </div>
        <x-primary-button class="w-full justify-center">Simpan Hasil & Tandai Selesai</x-primary-button>
      </form>
    </div>
  </div>
</div>
@endsection
