@extends('layouts.admin', ['title' => 'Edit Jenis Tes'])

@section('breadcrumb')
  <a href="{{ route('admin.dasbor_admin') }}" class="hover:underline">Dasbor</a> /
  <a href="{{ route('admin.jenis-tes.index') }}" class="hover:underline">Jenis Tes</a> / Edit
@endsection

@section('content')

<x-alert-success />

<div class="mb-6">
    <h2 class="text-xl font-semibold">Edit Jenis Tes</h2>
    <p class="text-sm text-gray-600">Perbarui informasi jenis tes.</p>
</div>

<form action="{{ route('admin.jenis-tes.update', $item) }}" method="POST"
      class="bg-white rounded-2xl ring-1 ring-gray-100 p-6 space-y-6">
    @csrf @method('PUT')

    <div>
        <label class="block text-sm text-gray-700">Kode</label>
        <input type="text" name="kode" value="{{ old('kode', $item->kode) }}"
            class="mt-1 w-full rounded-xl border-gray-300">
        <p class="text-xs text-gray-500 mt-1">
            Kosongkan untuk regenerasi otomatis dari nama.
        </p>
        @error('kode') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm text-gray-700">Nama</label>
        <input type="text" name="nama" value="{{ old('nama', $item->nama) }}"
            class="mt-1 w-full rounded-xl border-gray-300" required>
        @error('nama') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm text-gray-700">Deskripsi</label>
        <textarea name="deskripsi" rows="3" class="mt-1 w-full rounded-xl border-gray-300">{{ old('deskripsi', $item->deskripsi) }}</textarea>
    </div>

    <div>
        <label class="block text-sm text-gray-700">Biaya</label>
        <input type="number" name="biaya" value="{{ old('biaya', $item->biaya) }}"
            class="mt-1 w-full rounded-xl border-gray-300">
    </div>

    <div>
        <label class="block text-sm text-gray-700">Status</label>
        <select name="aktif" class="mt-1 w-full rounded-xl border-gray-300">
            <option value="1" @selected($item->aktif)>Aktif</option>
            <option value="0" @selected(!$item->aktif)>Nonaktif</option>
        </select>
    </div>

    <div class="flex items-center gap-2">
        <x-secondary-button as="a" href="{{ route('admin.jenis-tes.index') }}">Batal</x-secondary-button>
        <x-primary-button>Perbarui</x-primary-button>
    </div>
</form>

@endsection
