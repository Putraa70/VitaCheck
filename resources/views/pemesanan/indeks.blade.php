@extends('layouts.app', ['title' => 'Pemesanan Saya'])

@section('content')
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h2 class="text-xl font-semibold">Pemesanan Saya</h2>
      <p class="text-sm text-gray-600">Riwayat dan status pemesanan tes.</p>
    </div>
    <x-primary-button as="a" href="{{ route('pemesanan.buat') }}">+ Pesan Tes</x-primary-button>
  </div>

  <div class="bg-white rounded-2xl ring-1 ring-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
          <tr>
            <th class="px-4 py-3 text-left">Kode</th>
            <th class="px-4 py-3 text-left">Jenis Tes</th>
            <th class="px-4 py-3 text-left">Tanggal</th>
            <th class="px-4 py-3 text-left">Status</th>
            <th class="px-4 py-3 text-left">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          @forelse($pemesanan as $p)
            <tr>
              <td class="px-4 py-3 font-medium">{{ $p->kode }}</td>
              <td class="px-4 py-3">{{ $p->jenisTes->nama ?? '-' }}</td>
              <td class="px-4 py-3">{{ optional($p->slotWaktu?->tanggal)->format('d M Y') ?? '-' }}</td>
              <td class="px-4 py-3">
                <span class="inline-flex items-center rounded-full px-2.5 py-1 ring-1 text-xs
                  @switch($p->status)
                    @case('menunggu') bg-amber-50 text-amber-700 ring-amber-200 @break
                    @case('terkonfirmasi') bg-sky-50 text-sky-700 ring-sky-200 @break
                    @case('check_in') bg-indigo-50 text-indigo-700 ring-indigo-200 @break
                    @case('selesai') bg-emerald-50 text-emerald-700 ring-emerald-200 @break
                    @default bg-gray-50 text-gray-700 ring-gray-200
                  @endswitch
                ">
                  {{ ucfirst(str_replace('_',' ',$p->status)) }}
                </span>
              </td>
              <td class="px-4 py-3">
                <x-secondary-button as="a" href="{{ route('pemesanan.lihat', $p->kode) }}">Detail</x-secondary-button>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="py-8 text-center text-gray-500">Belum ada pemesanan.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 bg-gray-50">
      {{ $pemesanan->links() }}
    </div>
  </div>
@endsection
