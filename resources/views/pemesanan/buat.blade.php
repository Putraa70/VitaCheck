<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg">Buat Pemesanan</h2>
    </x-slot>

    {{-- Alert error global --}}
    @if($errors->any())
        <div class="mb-4 rounded-xl bg-rose-50 text-rose-700 p-3 text-sm">
            <ul class="list-disc ml-5">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('pemesanan.simpan') }}" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 p-6 space-y-5">
        @csrf

        <div class="grid sm:grid-cols-2 gap-4">
            {{-- Jenis Tes --}}
            <div>
                <x-input-label for="jenis_tes_id" value="Jenis Tes" />
                <select id="jenis_tes_id" name="jenis_tes_id"
                        class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="">Pilih jenis tes…</option>
                    @foreach($jenisTes as $jt)
                        <option value="{{ $jt->id }}" @selected(old('jenis_tes_id')==$jt->id)>{{ $jt->nama }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('jenis_tes_id')" />
            </div>

            {{-- Slot Waktu --}}
            <div>
                <x-input-label for="slot_waktu_id" value="Slot Waktu" />
                <select id="slot_waktu_id" name="slot_waktu_id"
                        class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="">Pilih tanggal…</option>
                    @foreach($slotWaktu as $s)
                        @php
                            $tgl  = optional($s->tanggal)->translatedFormat('l, d M Y');
                            // jika mulai/selesai bertipe time string, aman ditampilkan langsung
                            $rentang = trim(($s->mulai ? $s->mulai : '') . ($s->selesai ? ' - ' . $s->selesai : ''));
                            $sisa = method_exists($s,'sisaKuota') ? $s->sisaKuota() : max(((int)($s->kuota ?? 0)) - ((int)($s->terpesan ?? 0)), 0);
                            $infoKuota = ($s->kuota ?? 0) == 0 ? 'Tak terbatas' : "Sisa $sisa";
                        @endphp
                        <option value="{{ $s->id }}" @selected(old('slot_waktu_id')==$s->id)>
                            {{ $tgl }} {{ $rentang ? "($rentang)" : '' }} — Kuota: {{ $infoKuota }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('slot_waktu_id')" />
            </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            {{-- Upload KTM --}}
            <div>
                <x-input-label for="ktm" value="Upload KTM (jpg/png/pdf)" />
                <input id="ktm" name="ktm" type="file" accept=".jpg,.jpeg,.png,.pdf"
                       class="block w-full text-sm file:mr-3 file:px-3 file:py-2 file:rounded-lg file:border-0 file:bg-gray-100 file:text-gray-700">
                <x-input-error :messages="$errors->get('ktm')" />
            </div>

            {{-- Bukti Bayar --}}
            <div>
                <x-input-label for="bukti_bayar" value="Bukti Bayar (jpg/png/pdf)" />
                <input id="bukti_bayar" name="bukti_bayar" type="file" accept=".jpg,.jpeg,.png,.pdf"
                       class="block w-full text-sm file:mr-3 file:px-3 file:py-2 file:rounded-lg file:border-0 file:bg-gray-100 file:text-gray-700">
                <x-input-error :messages="$errors->get('bukti_bayar')" />
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <x-secondary-button as="a" href="{{ route('pemesanan.indeks') }}">Batal</x-secondary-button>
            <x-primary-button>Simpan Pemesanan</x-primary-button>
        </div>
    </form>
</x-app-layout>
