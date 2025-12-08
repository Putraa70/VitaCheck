@extends('layouts.admin', ['title' => 'Ubah Fakultas'])

@section('content')
{{-- Header --}}
<div class="mb-6">
  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
    <div>
      <h2 class="text-xl font-semibold">Ubah Fakultas</h2>
      <p class="text-sm text-gray-600">Perbarui data fakultas di bawah ini.</p>
    </div>
    <div class="flex items-center gap-2">
      <x-secondary-button as="a" href="{{ route('admin.dasbor_admin') }}">← Dashboard</x-secondary-button>
      <x-secondary-button as="a" href="{{ route('admin.fakultas.index') }}">← Daftar Fakultas</x-secondary-button>
      <x-secondary-button as="a" href="{{ route('admin.fakultas.show', $item) }}">Lihat Detail</x-secondary-button>
    </div>
  </div>
</div>

{{-- Form --}}
<form action="{{ route('admin.fakultas.update', $item) }}" method="POST" class="bg-white rounded-2xl ring-1 ring-gray-100 p-6 space-y-5">
  @csrf
  @method('PUT')

  <div>
    <label class="block text-sm text-gray-700">Kode (opsional)</label>
    <input type="text" name="kode" value="{{ old('kode', $item->kode ?? '') }}" class="mt-1 w-full rounded-xl border-gray-300" placeholder="mis. FMIPA">
    @error('kode') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block text-sm text-gray-700">Nama Fakultas</label>
    <input type="text" name="nama" value="{{ old('nama', $item->nama) }}" class="mt-1 w-full rounded-xl border-gray-300" required>
    @error('nama') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="flex items-center gap-2">
    <x-secondary-button as="a" href="{{ route('admin.fakultas.index') }}">Batal</x-secondary-button>
    <x-primary-button>Perbarui</x-primary-button>
  </div>
</form>
@endsection
