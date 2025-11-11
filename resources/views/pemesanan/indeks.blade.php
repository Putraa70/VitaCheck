<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-lg">Pemesanan Tes</h2>
                <p class="text-sm text-gray-500">Kelola & pantau pemesananmu</p>
            </div>
            <x-primary-button as="a" href="{{ route('pemesanan.buat') }}">Pesan Tes</x-primary-button>
        </div>
    </x-slot>

    @php
        // $pemesanans = paginated collection from controller
        // fields: kode, jenis_tes->nama, slot_waktu->tanggal, status
        $badge = fn($s)=>[
            'menunggu'=>'bg-amber-50 text-amber-700 ring-amber-200',
            'terkonfirmasi'=>'bg-sky-50 text-sky-700 ring-sky-200',
            'check_in'=>'bg-indigo-50 text-indigo-700 ring-indigo-200',
            'selesai'=>'bg-emerald-50 text-emerald-700 ring-emerald-200',
            'dibatalkan'=>'bg-rose-50 text-rose-700 ring-rose-200',
        ][$s] ?? 'bg-gray-50 text-gray-700 ring-gray-200';
    @endphp

    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Kode</th>
                    <th class="px-4 py-3 text-left">Jenis Tes</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($pemesanan as $p)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $p->kode }}</td>
                        <td class="px-4 py-3">{{ $p->jenisTes->nama ?? '-' }}</td>
                        <td class="px-4 py-3">{{ optional($p->slotWaktu)->tanggal?->format('d M Y') ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 ring-1 {{ $badge($p->status) }}">{{ ucfirst(str_replace('_',' ',$p->status)) }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('pemesanan.show',$p) }}" class="text-indigo-600 hover:underline">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada pemesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $pemesanan->links() }}</div>
</x-app-layout>
