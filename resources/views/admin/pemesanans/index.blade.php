@extends('layouts.admin', ['title' => 'Pemesanan'])

@section('breadcrumb')
  <a href="{{ route('admin.dasbor_admin') }}" class="hover:underline">Dasbor</a> / Pemesanan
@endsection

@section('content')
<div class="mb-6">
  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
    <div>
      <h2 class="text-xl font-semibold">Pemesanan</h2>
      <p class="text-sm text-gray-600">Daftar pemesanan tes oleh mahasiswa.</p>
    </div>
    <div class="flex items-center gap-2">
      <x-secondary-button as="a" href="{{ route('admin.dasbor_admin') }}">← Dashboard</x-secondary-button>
    </div>
  </div>
</div>

{{-- Filter --}}
<form method="GET" class="mb-4 grid lg:grid-cols-6 md:grid-cols-3 sm:grid-cols-2 gap-2">
  <input type="text" name="q" value="{{ $q }}" placeholder="Cari: kode / nama"
         class="px-3 py-2 rounded-xl border-gray-300 focus:ring-indigo-500">

  <select name="status" class="px-3 py-2 rounded-xl border-gray-300">
    <option value="">Status (semua)</option>
    @foreach($daftarStatus as $st)
      <option value="{{ $st }}" @selected($status===$st)>{{ ucfirst(str_replace('_',' ', $st)) }}</option>
    @endforeach
  </select>

  <select name="jenis_tes_id" class="px-3 py-2 rounded-xl border-gray-300">
    <option value="">Jenis Tes (semua)</option>
    @foreach($daftarJenis as $j)
      <option value="{{ $j->id }}" @selected($jenisId==$j->id)>{{ $j->nama }}</option>
    @endforeach
  </select>

  <input type="date" name="from" value="{{ $from }}" class="px-3 py-2 rounded-xl border-gray-300" placeholder="Dari">
  <input type="date" name="to"   value="{{ $to }}"   class="px-3 py-2 rounded-xl border-gray-300" placeholder="Sampai">

  <div class="flex items-center gap-2">
    <x-secondary-button>Cari</x-secondary-button>
    @if(request()->query())
      <a href="{{ route('admin.pemesanans.index') }}" class="text-sm text-gray-500 hover:underline">Reset</a>
    @endif
  </div>
</form>

@php
  $statusStyles = [
    'menunggu'      => 'bg-amber-50 text-amber-700 ring-amber-200',
    'terkonfirmasi' => 'bg-sky-50 text-sky-700 ring-sky-200',
    'check_in'      => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
    'selesai'       => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    'dibatalkan'    => 'bg-rose-50 text-rose-700 ring-rose-200',
  ];
@endphp

<div class="bg-white rounded-2xl ring-1 ring-gray-100 overflow-hidden">
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50 text-gray-600">
        <tr>
          <th class="px-4 py-3 text-left font-medium">Kode</th>
          <th class="px-4 py-3 text-left font-medium">Mahasiswa</th>
          <th class="px-4 py-3 text-left font-medium">Jenis Tes</th>
          <th class="px-4 py-3 text-left font-medium">Tanggal</th>
          <th class="px-4 py-3 text-left font-medium">Status</th>
          <th class="px-4 py-3 text-left font-medium">Dibayar</th>
          <th class="px-4 py-3 text-left font-medium">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        @forelse($items as $p)
        @php $cls = $statusStyles[$p->status] ?? 'bg-gray-50 text-gray-700 ring-gray-200'; @endphp
        <tr>
          <td class="px-4 py-3 font-medium">{{ $p->kode }}</td>
          <td class="px-4 py-3">{{ $p->user->name ?? '-' }}</td>
          <td class="px-4 py-3">{{ $p->jenisTes->nama ?? '-' }}</td>
          <td class="px-4 py-3">
            {{ optional($p->slotWaktu?->tanggal)->format('d M Y') ?? '-' }}
          </td>
          <td class="px-4 py-3">
            <span class="inline-flex items-center rounded-full px-2.5 py-1 ring-1 text-xs {{ $cls }}">
              {{ ucfirst(str_replace('_',' ',$p->status)) }}
            </span>
          </td>
          <td class="px-4 py-3">
            {!! $p->dibayar_pada
                ? '<span class="text-emerald-700">Ya</span>'
                : '<span class="text-gray-500">Belum</span>' !!}
          </td>
          <td class="px-4 py-3">
            <x-secondary-button as="a" href="{{ route('admin.pemesanans.show', $p) }}">Detail</x-secondary-button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="py-8 px-4 text-center text-gray-500">Belum ada data pemesanan.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if(method_exists($items,'links'))
    <div class="px-4 py-3 bg-gray-50">
      {{ $items->links() }}
    </div>
  @endif
</div>
@endsection
