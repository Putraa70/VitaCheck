@extends('layouts.admin', ['title' => 'Slot Waktu'])

@section('breadcrumb')
  <a href="{{ route('admin.dasbor_admin') }}" class="hover:underline">Dasbor</a> / Slot Waktu
@endsection

@section('content')
<div class="mb-6">
  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
    <div>
      <h2 class="text-xl font-semibold">Slot Waktu</h2>
      <p class="text-sm text-gray-600">Kelola jadwal slot pemeriksaan.</p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
      <x-secondary-button as="a" href="{{ route('admin.dasbor_admin') }}">← Dashboard</x-secondary-button>
      <x-primary-button as="a" href="{{ route('admin.slot-waktu.create') }}">+ Tambah Slot</x-primary-button>
    </div>
  </div>
</div>

<div class="mb-4">
  <form method="GET" class="flex flex-wrap items-center gap-2">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari tgl (YYYY-MM-DD) / jenis tes"
           class="w-full md:w-72 px-3 py-2 rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" />
    <x-secondary-button>Cari</x-secondary-button>
    @if(request()->filled('q'))
      <a href="{{ route('admin.slot-waktu.index') }}" class="text-sm text-gray-500 hover:underline">Reset</a>
    @endif
  </form>
</div>

<div class="bg-white rounded-2xl ring-1 ring-gray-100 overflow-hidden">
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50 text-gray-600">
        <tr>
          <th class="px-4 py-3 text-left font-medium">#</th>
          @if(\Illuminate\Support\Facades\Schema::hasColumn('slot_waktu','jenis_tes_id'))
            <th class="px-4 py-3 text-left font-medium">Jenis Tes</th>
          @endif
          <th class="px-4 py-3 text-left font-medium">Tanggal</th>
          @if(\Illuminate\Support\Facades\Schema::hasColumn('slot_waktu','mulai'))
            <th class="px-4 py-3 text-left font-medium">Mulai</th>
          @endif
          @if(\Illuminate\Support\Facades\Schema::hasColumn('slot_waktu','selesai'))
            <th class="px-4 py-3 text-left font-medium">Selesai</th>
          @endif
          <th class="px-4 py-3 text-left font-medium">Kuota</th>
          <th class="px-4 py-3 text-left font-medium">Terpesan</th>
          <th class="px-4 py-3 text-left font-medium">Sisa</th>
          <th class="px-4 py-3 text-left font-medium">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        @forelse($items as $i => $item)
        @php $sisa = max(($item->kuota - $item->terpesan), 0); @endphp
        <tr>
          <td class="px-4 py-3">{{ method_exists($items,'firstItem') ? $items->firstItem() + $i : $i+1 }}</td>
          @if(\Illuminate\Support\Facades\Schema::hasColumn('slot_waktu','jenis_tes_id'))
            <td class="px-4 py-3">{{ $item->jenisTes->nama ?? '-' }}</td>
          @endif
          <td class="px-4 py-3 font-medium">{{ \Illuminate\Support\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</td>
          @if(\Illuminate\Support\Facades\Schema::hasColumn('slot_waktu','mulai'))
            <td class="px-4 py-3">{{ $item->mulai }}</td>
          @endif
          @if(\Illuminate\Support\Facades\Schema::hasColumn('slot_waktu','selesai'))
            <td class="px-4 py-3">{{ $item->selesai }}</td>
          @endif
          <td class="px-4 py-3">{{ $item->kuota }}</td>
          <td class="px-4 py-3">{{ $item->terpesan }}</td>
          <td class="px-4 py-3">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full ring-1 text-xs
                {{ $sisa > 0 ? 'ring-emerald-300 text-emerald-700 bg-emerald-50' : 'ring-rose-300 text-rose-700 bg-rose-50' }}">
              {{ $sisa }}
            </span>
          </td>
          <td class="px-4 py-3">
            <div class="flex items-center gap-2">
              <x-secondary-button as="a" href="{{ route('admin.slot-waktu.show', $item) }}">Detail</x-secondary-button>
              <x-primary-button as="a" href="{{ route('admin.slot-waktu.edit', $item) }}">Edit</x-primary-button>
              <form action="{{ route('admin.slot-waktu.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus slot ini?')">
                @csrf @method('DELETE')
                <x-secondary-button class="!text-rose-600 !ring-rose-300 hover:!bg-rose-50">Hapus</x-secondary-button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="py-8 px-4 text-center text-gray-500">Belum ada slot.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if(method_exists($items, 'links'))
    <div class="px-4 py-3 border-top bg-gray-50">
      {{ $items->links() }}
    </div>
  @endif
</div>
@endsection
