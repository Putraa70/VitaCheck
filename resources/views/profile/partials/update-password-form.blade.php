@php
    // Definisikan kembali warna (pastikan ini di-pass atau diakses di partial Anda)
    $primaryColor = '#0f52ba';
    $secondaryColor = '#3f67ba';
@endphp

<section class="bg-white rounded-xl p-0">

    <header class="mb-6">
        <h2 class="text-xl font-bold flex items-center" style="color: {{ $primaryColor }}">
            <svg class="w-6 h-6 mr-2" viewBox="0 0 24 24" fill="none" style="color: {{ $primaryColor }}"><path d="M12 11V15M12 18H12.01M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Ubah Kata Sandi (Password)
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk menjaga keamanan.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        {{-- =================== PASSWORD SAAT INI =================== --}}
        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-1">
                Kata Sandi Saat Ini
            </label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                style="focus:border-color: {{ $primaryColor }}; focus:ring-color: {{ $primaryColor }};"
                autocomplete="current-password" />
            
            {{-- Mengganti x-input-error dengan markup Tailwind biasa --}}
            @error('current_password', 'updatePassword') 
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p> 
            @enderror
        </div>

        {{-- =================== PASSWORD BARU =================== --}}
        <div>
            <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-1">
                Kata Sandi Baru
            </label>
            <input id="update_password_password" name="password" type="password" 
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                style="focus:border-color: {{ $primaryColor }}; focus:ring-color: {{ $primaryColor }};"
                autocomplete="new-password" />
            
            @error('password', 'updatePassword') 
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p> 
            @enderror
        </div>

        {{-- =================== KONFIRMASI PASSWORD =================== --}}
        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                Konfirmasi Kata Sandi Baru
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                style="focus:border-color: {{ $primaryColor }}; focus:ring-color: {{ $primaryColor }};"
                autocomplete="new-password" />
            
            @error('password_confirmation', 'updatePassword') 
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p> 
            @enderror
        </div>

        {{-- =================== BUTTON SIMPAN & STATUS =================== --}}
        <div class="flex items-center gap-4 pt-4">
            
            {{-- Mengganti x-primary-button --}}
            <button
                class="px-5 py-2 text-white rounded-lg shadow hover:opacity-90 transition font-semibold"
                style="background-color: {{ $primaryColor }}">
                Simpan Password
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 flex items-center"
                >
                    <svg class="w-4 h-4 inline-block mr-1" viewBox="0 0 24 24" fill="none"><path d="M5 12l4 4L19 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Password berhasil diperbarui.
                </p>
            @endif
        </div>
    </form>
</section>