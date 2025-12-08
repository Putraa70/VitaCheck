@extends('layouts.admin', ['title' => 'Kelola Fakultas'])

@section('content')
{{-- Header --}}
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold">Fakultas</h2>
            <p class="text-sm text-gray-600">Daftar fakultas Universitas Lampung</p>
        </div>

        <div class="flex items-center gap-2">
            {{-- Tombol kembali ke dashboard --}}
            <x-secondary-button as="a" href="{{ route('admin.dasbor_admin') }}">
                ← Kembali
            </x-secondary-button>

            {{-- Tombol tambah fakultas --}}
            <x-primary-button as="a" href="{{ route('admin.fakultas.create') }}">
                + Tambah Fakultas
            </x-primary-button>
        </div>
    </div>
</div>

{{-- Search --}}
<div class="mb-4">
    <form method="GET" class="flex items-center gap-2">
        <input 
            type="text" 
            name="q" 
            value="{{ request('q') }}"
            placeholder="Cari fakultas..."
            class="w-full md:w-72 px-3 py-2 rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
        />

        <x-secondary-button> Cari </x-secondary-button>

        @if(request('q'))
            <a href="{{ route('admin.fakultas.index') }}" class="text-sm text-gray-500 hover:underline">
                Reset
            </a>
        @endif
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl ring-1 ring-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">#</th>
                    <th class="px-4 py-3 text-left font-medium">Kode</th>
                    <th class="px-4 py-3 text-left font-medium">Nama Fakultas</th>
                    <th class="px-4 py-3 text-left font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($items as $i => $item)
                <tr>
                    <td class="px-4 py-3">{{ $items->firstItem() + $i }}</td>
                    <td class="px-4 py-3 font-medium">{{ $item->kode ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $item->nama }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <x-secondary-button as="a" href="{{ route('admin.fakultas.show', $item) }}">
                                Detail
                            </x-secondary-button>

                            <x-primary-button as="a" href="{{ route('admin.fakultas.edit', $item) }}">
                                Edit
                            </x-primary-button>

                            <form action="{{ route('admin.fakultas.destroy', $item) }}" method="POST" 
                                  onsubmit="return confirm('Yakin ingin menghapus fakultas ini?')">
                                @csrf @method('DELETE')
                                <x-secondary-button class="!text-rose-600 !ring-rose-300 hover:!bg-rose-50">
                                    Hapus
                                </x-secondary-button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-8 px-4 text-center text-gray-500">
                        Tidak ada data fakultas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="px-4 py-3 border-t bg-gray-50">
        {{ $items->links() }}
    </div>
</div>
@endsection
