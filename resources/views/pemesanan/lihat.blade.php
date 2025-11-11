<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg">Detail Pemesanan — {{ $pemesanan->kode }}</h2>
    </x-slot>

    <div class="grid lg:grid-cols-3 gap-6">
        <section class="lg:col-span-2 bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 p-6 space-y-4">
            <dl class="grid sm:grid-cols-2 gap-4 text-sm">
                <div><dt class="text-gray-500">Jenis Tes</dt><dd class="font-medium">{{ $pemesanan->jenisTes->nama }}</dd></div>
                <div><dt class="text-gray-500">Tanggal</dt><dd class="font-medium">{{ $pemesanan->slotWaktu->tanggal->format('d M Y') }}</dd></div>
                <div><dt class="text-gray-500">Status</dt><dd>
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 ring-1 bg-indigo-50 text-indigo-700 ring-indigo-200">
                        {{ ucfirst(str_replace('_',' ',$pemesanan->status)) }}
                    </span>
                </dd></div>
                <div><dt class="text-gray-500">Dibayar Pada</dt><dd class="font-medium">{{ optional($pemesanan->dibayar_pada)?->format('d M Y H:i') ?? '-' }}</dd></div>
            </dl>
            <div class="border-t pt-4">
                <h3 class="font-semibold mb-2">Berkas</h3>
                <ul class="list-disc pl-5 text-sm space-y-1">
                    @foreach($pemesanan->berkas as $b)
                        <li>
                            {{ strtoupper($b->jenis) }} —
                            <a class="text-indigo-600 hover:underline" href="{{ Storage::url($b->lokasi_berkas) }}" target="_blank">Lihat</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        <aside class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 p-6 space-y-4">
            <h3 class="font-semibold">Hasil Tes</h3>
            @if($pemesanan->hasilTes)
                <p class="text-sm">Status:
                    <span class="font-medium">{{ strtoupper($pemesanan->hasilTes->status_hasil) }}</span>
                </p>
                @if($pemesanan->hasilTes->catatan)
                    <p class="text-sm text-gray-600">{{ $pemesanan->hasilTes->catatan }}</p>
                @endif
                @if($pemesanan->hasilTes->berkas_hasil)
                    <a class="text-sm text-indigo-600 hover:underline" href="{{ Storage::url($pemesanan->hasilTes->berkas_hasil) }}" target="_blank">Unduh Berkas Hasil</a>
                @endif
            @else
                <p class="text-sm text-gray-600">Hasil belum tersedia.</p>
            @endif
        </aside>
    </div>
</x-app-layout>
