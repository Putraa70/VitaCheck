@extends('layouts.admin', ['title' => 'Tambah Jenis Tes'])

@section('breadcrumb')
  <a href="{{ route('admin.dasbor_admin') }}" class="hover:underline">Dasbor</a> /
  <a href="{{ route('admin.jenis-tes.index') }}" class="hover:underline">Jenis Tes</a> / Tambah
@endsection

@section('content')

<x-alert-success />

<div class="mb-6">
    <h2 class="text-xl font-semibold">Tambah Jenis Tes</h2>
    <p class="text-sm text-gray-600">Buat jenis tes baru untuk pemesanan.</p>
</div>

<form action="{{ route('admin.jenis-tes.store') }}" method="POST"
      class="bg-white rounded-2xl ring-1 ring-gray-100 p-6 space-y-6">
    @csrf

    <div>
        <label class="block text-sm text-gray-700">Kode (opsional)</label>
        <input type="text" name="kode" value="{{ old('kode') }}"
            placeholder="Kosongkan untuk otomatis"
            class="mt-1 w-full rounded-xl border-gray-300">
        @error('kode') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm text-gray-700">Nama</label>
        <input type="text" name="nama" value="{{ old('nama') }}"
            class="mt-1 w-full rounded-xl border-gray-300" required>
        @error('nama') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm text-gray-700">Deskripsi (opsional)</label>
        <textarea name="deskripsi" rows="3"
            class="mt-1 w-full rounded-xl border-gray-300">{{ old('deskripsi') }}</textarea>
    </div>

    <div>
        <label class="block text-sm text-gray-700">Biaya (opsional)</label>
        <input type="number" name="biaya" value="{{ old('biaya') }}"
            class="mt-1 w-full rounded-xl border-gray-300">
    </div>

    <div>
        <label class="block text-sm text-gray-700">Status</label>
        <select name="aktif" class="mt-1 w-full rounded-xl border-gray-300">
            <option value="1" selected>Aktif</option>
            <option value="0">Nonaktif</option>
        </select>
    </div>

    <div class="flex items-center gap-2">
        <x-secondary-button as="a" href="{{ route('admin.jenis-tes.index') }}">Batal</x-secondary-button>
        <x-primary-button>Simpan</x-primary-button>
    </div>
</form>

@endsection
