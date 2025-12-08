@php
    // Definisikan kembali warna jika form ini di-include tanpa variabel
    // Jika Anda memastikan $primaryColor dan $secondaryColor tersedia di context,
    // baris ini bisa dihilangkan. Saya masukkan untuk keamanan.
    $primaryColor = '#0f52ba';
    $secondaryColor = '#3f67ba';
@endphp

<section class="bg-white rounded-xl p-0"> {{-- Hapus shadow dan border di sini karena sudah ada di parent div --}}

    <header class="mb-6">
        <h2 class="text-xl font-bold flex items-center" style="color: {{ $primaryColor }}">
            <svg class="w-6 h-6 mr-2" viewBox="0 0 24 24" fill="none" style="color: {{ $primaryColor }}"><path d="M16 7L19 10M17 19H5C4.44772 19 4 18.5523 4 18V6C4 5.44772 4.44772 5 5 5H12M17 19L14 16M17 19V16M14 16L20 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Edit Informasi Profil & Akademik
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Perbarui data pribadi dan akademik Anda sesuai data kampus.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        {{-- =================== NAMA =================== --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input id="name" name="name" type="text"
                class="w-full rounded-lg border-gray-300 focus:border-{{ $primaryColor }}-500 focus:ring-{{ $primaryColor }}-500 shadow-sm"
                style="--tw-ring-color: {{ $primaryColor }}; --tw-border-color: {{ $primaryColor }};"
                value="{{ old('name', $user->name) }}" required />
            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- =================== EMAIL =================== --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input id="email" name="email" type="email"
                class="w-full rounded-lg border-gray-300 focus:border-{{ $primaryColor }}-500 focus:ring-{{ $primaryColor }}-500 shadow-sm"
                style="--tw-ring-color: {{ $primaryColor }}; --tw-border-color: {{ $primaryColor }};"
                value="{{ old('email', $user->email) }}" required />
            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror

            {{-- Status verifikasi --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <p class="text-sm text-gray-800">
                        Email Anda belum terverifikasi.
                        <button form="send-verification" class="underline ml-1" style="color: {{ $secondaryColor }}">
                            Kirim ulang verifikasi
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-green-600">Tautan verifikasi telah dikirim.</p>
                    @endif
                </div>
            @else
                <p class="mt-2 text-sm text-green-700 flex items-center">
                    <svg class="w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none"><path d="M5 12l4 4L19 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Email terverifikasi
                </p>
            @endif
        </div>
        
        <hr class="border-gray-200" />
        
        <h3 class="text-lg font-semibold text-gray-800 pt-2">Data Akademik Mahasiswa</h3>

        {{-- =================== NIM =================== --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
            <input name="nim" type="text"
                class="w-full rounded-lg border-gray-300 focus:border-{{ $primaryColor }}-500 focus:ring-{{ $primaryColor }}-500 shadow-sm"
                style="--tw-ring-color: {{ $primaryColor }}; --tw-border-color: {{ $primaryColor }};"
                value="{{ old('nim', $profil->nim ?? '') }}" required />
            @error('nim') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- =================== NOMOR HP =================== --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
            <input name="no_hp" type="text"
                class="w-full rounded-lg border-gray-300 focus:border-{{ $primaryColor }}-500 focus:ring-{{ $primaryColor }}-500 shadow-sm"
                style="--tw-ring-color: {{ $primaryColor }}; --tw-border-color: {{ $primaryColor }};"
                value="{{ old('no_hp', $profil->no_hp ?? '') }}" required />
            @error('no_hp') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- =================== PROGRAM STUDI =================== --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
            <select name="program_studi_id"
                class="w-full rounded-lg border-gray-300 focus:border-{{ $primaryColor }}-500 focus:ring-{{ $primaryColor }}-500 shadow-sm"
                style="--tw-ring-color: {{ $primaryColor }}; --tw-border-color: {{ $primaryColor }};">
                
                @foreach ($program_studi as $prodi)
                    <option value="{{ $prodi->id }}"
                        {{ old('program_studi_id', $profil->program_studi_id ?? '') == $prodi->id ? 'selected' : '' }}>
                        {{ $prodi->nama }}
                    </option>
                @endforeach
            </select>
            @error('program_studi_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- =================== FAKULTAS (AUTO) =================== --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Fakultas</label>
            <input type="text" disabled
                class="w-full rounded-lg bg-gray-100 border-gray-200 shadow-sm"
                value="{{ $profil && $profil->programStudi ? $profil->programStudi->fakultas->nama : 'Belum dipilih' }}" />
        </div>

        {{-- =================== BUTTON SIMPAN =================== --}}
        <div class="flex items-center gap-4 pt-4">
            <button
                class="px-5 py-2 text-white rounded-lg shadow hover:opacity-90 transition font-semibold"
                style="background-color: {{ $primaryColor }}">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-green-600">
                    <svg class="w-4 h-4 inline-block mr-1" viewBox="0 0 24 24" fill="none"><path d="M5 12l4 4L19 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Data berhasil diperbarui.
                </p>
            @endif
        </div>

    </form>
</section>