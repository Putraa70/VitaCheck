<x-app-layout>
    @php
        $user = auth()->user();
        $profil = $user->profilMahasiswa;
        // Warna Spesialis Anda
        $primaryColor = '#0f52ba';
        $secondaryColor = '#3f67ba';
        $dangerColor = '#dc3545'; // Merah standar untuk aksi berbahaya
        
        // Tentukan tab aktif default untuk kolom KANAN
        $activeSection = request()->get('section', 'edit-profil'); 
    @endphp

    <x-slot name="header">
        <h2 class="font-bold text-2xl" style="color: {{ $primaryColor }}">
            Profil Mahasiswa & Pengaturan Akun
        </h2>
    </x-slot>

    <div class="py-10" x-data="{ activeTab: '{{ $activeSection }}' }">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- ======================================
                KOLOM KIRI (Profil User & Info Dasar)
            ======================================= --}}
            <div class="lg:col-span-1 space-y-8">
                <div class="bg-white shadow-xl rounded-2xl p-6 text-center border border-gray-100 ring-1 ring-gray-200">
                    
                    {{-- Avatar --}}
                    <div class="h-28 w-28 rounded-full flex items-center justify-center text-5xl font-bold mx-auto mb-4 border-4"
                        style="background-color: {{ $primaryColor }}; color: white; border-color: {{ $secondaryColor }}44;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    
                    {{-- Nama & Email --}}
                    <h2 class="text-2xl font-extrabold text-gray-900 mb-1">{{ $user->name }}</h2>
                    <p class="text-gray-600 text-sm font-medium">{{ $user->email }}</p>

                    {{-- Status Verifikasi & Peran --}}
                    <div class="mt-4 flex flex-wrap justify-center gap-2">
                        @if($user->email_verified_at)
                            <span class="inline-flex items-center px-3 py-1 text-xs font-bold bg-green-100 text-green-700 rounded-full">
                                <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="none"><path d="M5 12l4 4L19 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg> Email Terverifikasi
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 text-xs font-bold bg-yellow-100 text-yellow-700 rounded-full">
                                <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="none"><path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.5"/></svg> Belum Verifikasi
                            </span>
                        @endif
                        <span class="px-3 py-1 text-xs font-medium rounded-full" style="background-color: {{ $primaryColor }}1a; color: {{ $primaryColor }}">
                            {{ strtoupper($user->peran ?? 'mahasiswa') }}
                        </span>
                    </div>
                </div>

                {{-- Data Akademik Mahasiswa --}}
                <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100 ring-1 ring-gray-200">
                    <h3 class="text-xl font-bold mb-6 flex items-center" style="color: {{ $primaryColor }}">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        Data Akademik Mahasiswa
                    </h3>

                    @if(!$profil)
                        <div class="p-5 bg-yellow-50 rounded-xl border border-yellow-200">
                            <p class="text-yellow-800 mb-4 font-medium">Profil Akademik Anda belum lengkap. Silakan lengkapi data Anda untuk dapat memesan tes.</p>
                        </div>
                    @else
                        <div class="space-y-6 text-gray-700">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 font-medium uppercase">NIM (Nomor Induk Mahasiswa)</p>
                                    <p class="text-lg font-bold tracking-wide">{{ $profil->nim }}</p>
                                </div>
                                {{-- Nomor HP --}}
                                <div>
                                    <p class="text-xs text-gray-500 font-medium uppercase">Nomor HP</p>
                                    <p class="text-lg font-semibold tracking-wide">{{ $profil->no_hp }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-4 border-t pt-4 border-gray-100">
                                {{-- Program Studi --}}
                                <div>
                                    <p class="text-xs text-gray-500 font-medium uppercase">Program Studi</p>
                                    <p class="text-lg font-semibold" style="color: {{ $secondaryColor }}">{{ $profil->programStudi->nama ?? '-' }}</p>
                                </div>
                                {{-- Fakultas --}}
                                <div>
                                    <p class="text-xs text-gray-500 font-medium uppercase">Fakultas</p>
                                    <p class="text-lg font-semibold" style="color: {{ $secondaryColor }}">{{ $profil->programStudi->fakultas->nama ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            {{-- ======================================
                KOLOM KANAN (Navigasi Tab & Konten Pengaturan)
            ======================================= --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- Navigasi Tab Horizontal --}}
                <div class="flex border-b border-gray-200 bg-white rounded-t-2xl shadow-xl ring-1 ring-gray-200 overflow-x-auto">
                    
                    {{-- Tab Edit Profil --}}
                    <button @click="activeTab = 'edit-profil'"
                        :class="{'border-b-4 font-bold': activeTab === 'edit-profil', 'text-gray-600': activeTab !== 'edit-profil'}"
                        class="px-6 py-3 text-sm transition duration-150 ease-in-out whitespace-nowrap"
                        :style="activeTab === 'edit-profil' ? `border-color: ${primaryColor}; color: ${primaryColor};` : ''"
                    >
                        <svg class="w-4 h-4 mr-1 inline-block" viewBox="0 0 24 24" fill="none"><path d="M16 7L19 10M17 19H5C4.44772 19 4 18.5523 4 18V6C4 5.44772 4.44772 5 5 5H12M17 19L14 16M17 19V16M14 16L20 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Edit Profil
                    </button>

                    {{-- Tab Ubah Password --}}
                    <button @click="activeTab = 'ubah-password'"
                        :class="{'border-b-4 font-bold': activeTab === 'ubah-password', 'text-gray-600': activeTab !== 'ubah-password'}"
                        class="px-6 py-3 text-sm transition duration-150 ease-in-out whitespace-nowrap"
                        :style="activeTab === 'ubah-password' ? `border-color: ${primaryColor}; color: ${primaryColor};` : ''"
                    >
                        <svg class="w-4 h-4 mr-1 inline-block" viewBox="0 0 24 24" fill="none"><path d="M12 11V15M12 18H12.01M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Ubah Password
                    </button>

                    {{-- Tab Hapus Akun --}}
                    <button @click="activeTab = 'hapus-akun'"
                        :class="{'border-b-4 font-bold text-red-600': activeTab === 'hapus-akun', 'text-gray-600': activeTab !== 'hapus-akun'}"
                        class="px-6 py-3 text-sm transition duration-150 ease-in-out whitespace-nowrap"
                        :style="activeTab === 'hapus-akun' ? `border-color: ${dangerColor};` : ''"
                    >
                        <svg class="w-4 h-4 mr-1 inline-block" viewBox="0 0 24 24" fill="none"><path d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z" stroke="currentColor" stroke-width="1.5"/><path d="M15 9.00001L9 15M9 9L15 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Hapus Akun
                    </button>
                    
                </div>
                
                {{-- Konten Tab --}}
                <div class="bg-white shadow-xl rounded-b-2xl p-6 border border-t-0 border-gray-100 ring-1 ring-gray-200">
                    
                    {{-- Tab 1: Edit Profil --}}
                    <div x-show="activeTab === 'edit-profil'" x-transition:enter.duration.500ms>
                        @include('profile.partials.update-profile-information-form')
                    </div>
                    
                    {{-- Tab 2: Ubah Password --}}
                    <div x-show="activeTab === 'ubah-password'" x-transition:enter.duration.500ms>
                        @include('profile.partials.update-password-form')
                    </div>

                    {{-- Tab 3: Hapus Akun --}}
                    <div x-show="activeTab === 'hapus-akun'" x-transition:enter.duration.500ms>
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>