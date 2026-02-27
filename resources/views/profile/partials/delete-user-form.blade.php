@php
    // Definisikan kembali warna (pastikan ini di-pass atau diakses di partial Anda)
    $primaryColor = '#0f52ba';
    $secondaryColor = '#3f67ba';
    $dangerColor = '#dc3545'; // Merah standar untuk aksi berbahaya
@endphp

<section class="bg-white rounded-xl p-0 space-y-6">

    <header>
        <h2 class="text-xl font-bold flex items-center" style="color: {{ $primaryColor }}">
            <svg class="w-6 h-6 mr-2" viewBox="0 0 24 24" fill="none" style="color: {{ $primaryColor }}"><path d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z" stroke="currentColor" stroke-width="1.5"/><path d="M15 9.00001L9 15M9 9L15 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Hapus Permanen Akun
        </h2>

        <p class="mt-1 text-sm text-gray-600">
    Setelah akun Anda dihapus, semua sumber daya dan data akan terhapus <strong>permanen</strong>. Harap unduh data atau informasi yang ingin Anda simpan sebelum menghapus akun.
</p>
    </header>

    {{-- Tombol Pemicu Modal --}}
    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-5 py-2 font-semibold text-white rounded-lg shadow transition hover:opacity-90"
        style="background-color: {{ $dangerColor }}"
    >
        Hapus Akun
    </button>

    {{-- =================== MODAL KONFIRMASI (X-MODAL) =================== --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-xl font-bold text-gray-900">
                Apakah Anda yakin ingin menghapus akun Anda?
            </h2>

            <p class="mt-2 text-sm text-gray-600">
                Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus **permanen**. Mohon masukkan kata sandi Anda untuk mengonfirmasi penghapusan permanen akun Anda.
            </p>

            <div class="mt-6">
                {{-- Input Label Dihilangkan (sr-only) --}}
                <label for="password" class="sr-only">Password</label>

                {{-- Input Password --}}
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                    style="focus:border-color: {{ $primaryColor }}; focus:ring-color: {{ $primaryColor }};"
                    placeholder="Masukkan Kata Sandi Anda"
                />

                {{-- Input Error --}}
                @error('password', 'userDeletion') 
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p> 
                @enderror
            </div>

            <div class="mt-6 flex justify-end gap-3">
                
                {{-- Tombol Batal --}}
                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition font-medium"
                >
                    Batal
                </button>

                {{-- Tombol Hapus Akun --}}
                <button 
                    type="submit"
                    class="px-4 py-2 font-semibold text-white rounded-lg shadow transition hover:opacity-90"
                    style="background-color: {{ $dangerColor }}"
                >
                    Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>