@extends('layouts.admin', ['title' => 'Detail Program Studi'])

@section('breadcrumb')
  <a href="{{ route('admin.dasbor_admin') }}" class="hover:underline">Dasbor</a> /
  <a href="{{ route('admin.program-studi.index') }}" class="hover:underline">Program Studi</a> / Detail
@endsection

@section('content')
{{-- Header --}}
<div class="mb-6">
  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
    <div>
      <h2 class="text-xl font-semibold">Detail Program Studi</h2>
      <p class="text-sm text-gray-600">Informasi lengkap program studi.</p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
      <x-secondary-button as="a" href="{{ route('admin.dasbor_admin') }}">← Dashboard</x-secondary-button>
      <x-secondary-button as="a" href="{{ route('admin.program-studi.index') }}">← Daftar Prodi</x-secondary-button>
      <x-primary-button   as="a" href="{{ route('admin.program-studi.edit', $item) }}">Ubah</x-primary-button>
      <form action="{{ route('admin.program-studi.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
        @csrf @method('DELETE')
        <x-secondary-button class="!text-rose-600 !ring-rose-300 hover:!bg-rose-50">Hapus</x-secondary-button>
      </form>
    </div>
  </div>
</div>

{{-- Card detail --}}
<div class="bg-white rounded-2xl ring-1 ring-gray-100 p-6">
  <dl class="grid sm:grid-cols-2 gap-6 text-sm">
    <div>
      <dt class="text-gray-500">Nama Program Studi</dt>
      <dd class="mt-1 font-medium">{{ $item->nama }}</dd>
    </div>
    <div>
      <dt class="text-gray-500">Fakultas</dt>
      <dd class="mt-1 font-medium">{{ $item->fakultas->nama ?? '-' }}</dd>
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
