@extends('layouts.admin', ['title' => 'Ubah Slot Waktu'])

@section('breadcrumb')
  <a href="{{ route('admin.dasbor_admin') }}" class="hover:underline">Dasbor</a> /
  <a href="{{ route('admin.slot-waktu.index') }}" class="hover:underline">Slot Waktu</a> / Ubah
@endsection

@section('content')
<div class="mb-6">
  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
    <div>
      <h2 class="text-xl font-semibold">Ubah Slot Waktu</h2>
      <p class="text-sm text-gray-600">Perbarui jadwal slot pemeriksaan.</p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
      <x-secondary-button as="a" href="{{ route('admin.dasbor_admin') }}">← Dashboard</x-secondary-button>
      <x-secondary-button as="a" href="{{ route('admin.slot-waktu.index') }}">← Daftar Slot</x-secondary-button>
      <x-secondary-button as="a" href="{{ route('admin.slot-waktu.show', $item) }}">Lihat Detail</x-secondary-button>
    </div>
  </div>
</div>

<form action="{{ route('admin.slot-waktu.update', $item) }}" method="POST" class="bg-white rounded-2xl ring-1 ring-gray-100 p-6 space-y-5">
  @csrf @method('PUT')

  @if(\Illuminate\Support\Facades\Schema::hasColumn('slot_waktu','jenis_tes_id'))
    <div>
      <label class="block text-sm text-gray-700">Jenis Tes</label>
      <select name="jenis_tes_id" class="mt-1 w-full rounded-xl border-gray-300" required>
        @foreach($jenis as $j)
          <option value="{{ $j->id }}" @selected(old('jenis_tes_id', $item->jenis_tes_id)==$j->id)>{{ $j->nama }}</option>
        @endforeach
      </select>
      @error('jenis_tes_id') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
    </div>
  @endif

  <div class="grid sm:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm text-gray-700">Tanggal</label>
      <input type="date" name="tanggal" value="{{ old('tanggal', \Illuminate\Support\Carbon::parse($item->tanggal)->format('Y-m-d')) }}" class="mt-1 w-full rounded-xl border-gray-300" required>
      @error('tanggal') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
    </div>

    @if(\Illuminate\Support\Facades\Schema::hasColumn('slot_waktu','mulai'))
      <div>
        <label class="block text-sm text-gray-700">Mulai</label>
        <input type="time" name="mulai" value="{{ old('mulai', $item->mulai) }}" class="mt-1 w-full rounded-xl border-gray-300" required>
        @error('mulai') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
      </div>
    @endif

    @if(\Illuminate\Support\Facades\Schema::hasColumn('slot_waktu','selesai'))
      <div>
        <label class="block text-sm text-gray-700">Selesai</label>
        <input type="time" name="selesai" value="{{ old('selesai', $item->selesai) }}" class="mt-1 w-full rounded-xl border-gray-300" required>
        @error('selesai') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
      </div>
    @endif

    <div>
      <label class="block text-sm text-gray-700">Kuota</label>
      <input type="number" name="kuota" min="1" value="{{ old('kuota', $item->kuota) }}" class="mt-1 w-full rounded-xl border-gray-300" required>
      @error('kuota') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="block text-sm text-gray-700">Terpesan</label>
      <input type="number" name="terpesan" min="0" value="{{ old('terpesan', $item->terpesan) }}" class="mt-1 w-full rounded-xl border-gray-300">
      @error('terpesan') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
    </div>
  </div>

  <div class="flex flex-wrap items-center gap-2">
    <x-secondary-button as="a" href="{{ route('admin.slot-waktu.index') }}">Batal</x-secondary-button>
    <x-primary-button>Perbarui</x-primary-button>
  </div>
</form>
@endsection
