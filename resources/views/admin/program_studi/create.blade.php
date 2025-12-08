@extends('layouts.admin', ['title' => 'Tambah Program Studi'])

@section('breadcrumb')
  <a href="{{ route('admin.dasbor_admin') }}" class="hover:underline">Dasbor</a> /
  <a href="{{ route('admin.program-studi.index') }}" class="hover:underline">Program Studi</a> / Tambah
@endsection

@section('content')
{{-- Header --}}
<div class="mb-6">
  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
    <div>
      <h2 class="text-xl font-semibold">Tambah Program Studi</h2>
      <p class="text-sm text-gray-600">Isi data di bawah untuk menambahkan prodi baru.</p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
      <x-secondary-button as="a" href="{{ route('admin.dasbor_admin') }}">← Dashboard</x-secondary-button>
      <x-secondary-button as="a" href="{{ route('admin.program-studi.index') }}">← Daftar Prodi</x-secondary-button>
    </div>
  </div>
</div>

{{-- Form --}}
<form action="{{ route('admin.program-studi.store') }}" method="POST"
      class="bg-white rounded-2xl ring-1 ring-gray-100 p-6 space-y-5">
  @csrf

  {{-- Nama Prodi --}}
  <div>
    <label class="block text-sm text-gray-700">Nama Program Studi</label>
    <input type="text"
           name="nama"
           value="{{ old('nama') }}"
           class="mt-1 w-full rounded-xl border-gray-300"
           placeholder="mis. Ilmu Komputer"
           required>
    @error('nama')
      <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
    @enderror
  </div>

  {{-- Kode Prodi --}}
  <div>
    <label class="block text-sm text-gray-700">Kode Program Studi</label>
    <input type="text"
           name="kode"
           value="{{ old('kode') }}"
           class="mt-1 w-full rounded-xl border-gray-300"
           placeholder="mis. IF, SI, HK"
           required>
    @error('kode')
      <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
    @enderror
  </div>

  {{-- Fakultas --}}
  <div>
    <label class="block text-sm text-gray-700">Fakultas</label>
    <select name="fakultas_id"
            class="mt-1 w-full rounded-xl border-gray-300"
            required>
      <option value="">— pilih fakultas —</option>
      @foreach($fakultas as $f)
        <option value="{{ $f->id }}" @selected(old('fakultas_id') == $f->id)>
          {{ $f->nama }}
        </option>
      @endforeach
    </select>
    @error('fakultas_id')
      <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
    @enderror
  </div>

  <div class="flex flex-wrap items-center gap-2">
    <x-secondary-button as="a" href="{{ route('admin.program-studi.index') }}">Batal</x-secondary-button>
    <x-primary-button>Simpan</x-primary-button>
  </div>
</form>
@endsection
