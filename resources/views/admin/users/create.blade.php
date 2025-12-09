@extends('layouts.admin', ['title' => 'Tambah User'])

@section('breadcrumb')
  <a href="{{ route('admin.dasbor_admin') }}" class="hover:underline">Dasbor</a> /
  <a href="{{ route('admin.users.index') }}" class="hover:underline">Kelola User</a> /
  <span>Tambah</span>
@endsection

@section('content')
  <div class="mb-6">
    <h2 class="text-xl font-semibold">Tambah User</h2>
  </div>

  <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-6 max-w-xl">
    <form method="POST"
          action="{{ route('admin.users.store') }}"
          class="space-y-4"
          autocomplete="off">
      @csrf

      <div>
        <label class="block text-sm font-medium text-gray-700">Nama</label>
        <input
          type="text"
          name="name"
          value="{{ old('name') }}"
          class="mt-1 w-full rounded-xl border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500"
          autofocus
          required
        >
        @error('name')
          <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input
          type="email"
          name="email"
          value="{{ old('email') }}"
          class="mt-1 w-full rounded-xl border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500"
          autocomplete="email"
          required
        >
        @error('email')
          <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Peran</label>
        <select
          name="peran"
          class="mt-1 w-full rounded-xl border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500"
          required
        >
          @foreach($daftarPeran as $pr)
            <option value="{{ $pr }}" @selected(old('peran') === $pr)>{{ ucfirst($pr) }}</option>
          @endforeach
        </select>
        @error('peran')
          <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
  <label class="block text-sm font-medium text-gray-700">Password</label>
  <input
    type="password"
    name="password"
    class="mt-1 w-full rounded-xl border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500"
    autocomplete="new-password"
    required
  >
  <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter.</p>

  @error('password')
    <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
  @enderror
</div>

<div>
  <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
  <input
    type="password"
    name="password_confirmation"
    class="mt-1 w-full rounded-xl border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500"
    autocomplete="new-password"
    required
  >
  @error('password_confirmation')
    <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
  @enderror
</div>


      <div class="flex justify-end gap-2">
        <x-secondary-button as="a" href="{{ route('admin.users.index') }}">
          Batal
        </x-secondary-button>
        <x-primary-button>
          Simpan
        </x-primary-button>
      </div>
    </form>
  </div>
@endsection
