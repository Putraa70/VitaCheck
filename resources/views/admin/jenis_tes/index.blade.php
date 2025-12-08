@extends('layouts.admin', ['title' => 'Jenis Tes'])

@section('breadcrumb')
  <a href="{{ route('admin.dasbor_admin') }}" class="hover:underline">Dasbor</a> / Jenis Tes
@endsection

@section('content')

<x-alert-success />

<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold">Jenis Tes</h2>
            <p class="text-sm text-gray-600">Daftar jenis pemeriksaan yang tersedia.</p>
        </div>
        <div class="flex items-center gap-2">
            <x-secondary-button as="a" href="{{ route('admin.dasbor_admin') }}">← Dashboard</x-secondary-button>
            <x-primary-button as="a" href="{{ route('admin.jenis-tes.create') }}">+ Tambah Jenis Tes</x-primary-button>
        </div>
    </div>
</div>

{{-- Search --}}
<form method="GET" class="mb-4 flex flex-wrap items-center gap-2">
    <input type="text" name="q" value="{{ request('q') }}"
           placeholder="Cari kode / nama..."
           class="w-full md:w-64 px-3 py-2 rounded-xl border-gray-300 focus:ring-indigo-500">
    <x-secondary-button>Cari</x-secondary-button>
    @if(request('q'))
      <a href="{{ route('admin.jenis-tes.index') }}" class="text-sm text-gray-500 hover:underline">Reset</a>
    @endif
</form>

{{-- Table --}}
<div class="bg-white rounded-2xl ring-1 ring-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">#</th>
                    <th class="px-4 py-3 text-left font-medium">Kode</th>
                    <th class="px-4 py-3 text-left font-medium">Nama</th>
                    <th class="px-4 py-3 text-left font-medium">Biaya</th>
                    <th class="px-4 py-3 text-left font-medium">Status</th>
                    <th class="px-4 py-3 text-left font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">

                @forelse($items as $i => $item)
                <tr>
                    <td class="px-4 py-3">
                        {{ method_exists($items,'firstItem') ? $items->firstItem() + $i : $i+1 }}
                    </td>

                    <td class="px-4 py-3 font-mono font-medium">{{ $item->kode }}</td>

                    <td class="px-4 py-3">{{ $item->nama }}</td>

                    <td class="px-4 py-3">
                        {{ $item->biaya ? 'Rp ' . number_format($item->biaya,0,',','.') : '-' }}
                    </td>

                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 text-xs rounded-full ring-1 
                          {{ $item->aktif 
                              ? 'text-emerald-700 bg-emerald-50 ring-emerald-300' 
                              : 'text-gray-600 bg-gray-100 ring-gray-300' }}">
                            {{ $item->aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>

                    <td class="px-4 py-3">
                        <div class="flex flex-wrap items-center gap-2">
                            <x-secondary-button as="a"
                                href="{{ route('admin.jenis-tes.show', $item) }}">Detail</x-secondary-button>

                            <x-primary-button as="a"
                                href="{{ route('admin.jenis-tes.edit', $item) }}">Edit</x-primary-button>

                            <form method="POST"
                                  action="{{ route('admin.jenis-tes.destroy', $item) }}"
                                  onsubmit="return confirm('Hapus jenis tes ini?')">
                                @csrf @method('DELETE')
                                <x-secondary-button
                                    class="!text-rose-600 !ring-rose-300 hover:!bg-rose-50">Hapus</x-secondary-button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty

                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500">
                        Belum ada data.
                    </td>
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
