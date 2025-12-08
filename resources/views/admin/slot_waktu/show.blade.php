@extends('layouts.admin', ['title' => 'Detail Slot Waktu'])

@section('breadcrumb')
  <a href="{{ route('admin.dasbor_admin') }}" class="hover:underline">Dasbor</a> /
  <a href="{{ route('admin.slot-waktu.index') }}" class="hover:underline">Slot Waktu</a> / Detail
@endsection

@section('content')
<div class="mb-6">
  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
    <div>
      <h2 class="text-xl font-semibold">Detail Slot Waktu</h2>
      <p class="text-sm text-gray-600">Informasi jadwal slot.</p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
      <x-secondary-button as="a" href="{{ route('admin.dasbor_admin') }}">← Dashboard</x-secondary-button>
      <x-secondary-button as="a" href="{{ route('admin.slot-waktu.index') }}">← Daftar Slot</x-secondary-button>
      <x-primary-button   as="a" href="{{ route('admin.slot-waktu.edit', $item) }}">Ubah</x-primary-button>
      <form action="{{ route('admin.slot-waktu.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus slot ini?')">
        @csrf @method('DELETE')
        <x-secondary-button class="!text-rose-600 !ring-rose-300 hover:!bg-rose-50">Hapus</x-secondary-button>
      </form>
    </div>
  </div>
</div>

@php $sisa = max(($item->kuota - $item->terpesan), 0); @endphp

<div class="bg-white rounded-2xl ring-1 ring-gray-100 p-6 space-y-6">
  <dl class="grid sm:grid-cols-2 gap-6 text-sm">
    @if(\Illuminate\Support\Facades\Schema::hasColumn('slot_waktu','jenis_tes_id'))
      <div>
        <dt class="text-gray-500">Jenis Tes</dt>
        <dd class="mt-1 font-medium">{{ $item->jenisTes->nama ?? '-' }}</dd>
      </div>
    @endif

    <div>
      <dt class="text-gray-500">Tanggal</dt>
      <dd class="mt-1 font-medium">{{ \Illuminate\Support\Carbon::parse($item->tanggal)->translatedFormat('l, d M Y') }}</dd>
    </div>

    @if(\Illuminate\Support\Facades\Schema::hasColumn('slot_waktu','mulai'))
      <div>
        <dt class="text-gray-500">Mulai</dt>
        <dd class="mt-1">{{ $item->mulai }}</dd>
      </div>
    @endif

    @if(\Illuminate\Support\Facades\Schema::hasColumn('slot_waktu','selesai'))
      <div>
        <dt class="text-gray-500">Selesai</dt>
        <dd class="mt-1">{{ $item->selesai }}</dd>
      </div>
    @endif

    <div>
      <dt class="text-gray-500">Kuota</dt>
      <dd class="mt-1">{{ $item->kuota }}</dd>
    </div>

    <div>
      <dt class="text-gray-500">Terpesan</dt>
      <dd class="mt-1">{{ $item->terpesan }}</dd>
    </div>

    <div>
      <dt class="text-gray-500">Sisa Kuota</dt>
      <dd class="mt-1">
        <span class="inline-flex items-center px-2 py-0.5 rounded-full ring-1 text-xs
          {{ $sisa > 0 ? 'ring-emerald-300 text-emerald-700 bg-emerald-50' : 'ring-rose-300 text-rose-700 bg-rose-50' }}">
          {{ $sisa }}
        </span>
      </dd>
    </div>

    <div>
      <dt class="text-gray-500">ID</dt>
      <dd class="mt-1 font-mono">{{ $item->id }}</dd>
    </div>

    <div>
      <dt class="text-gray-500">Dibuat</dt>
      <dd class="mt-1">{{ optional($item->created_at)->format('d M Y H:i') }}</dd>
    </div>
    <div>
      <dt class="text-gray-500">Diperbarui</dt>
      <dd class="mt-1">{{ optional($item->updated_at)->format('d M Y H:i') }}</dd>
    </div>
  </dl>
</div>
@endsection
