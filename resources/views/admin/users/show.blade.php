@extends('layouts.admin', ['title' => 'Detail User'])

@section('breadcrumb')
  <a href="{{ route('admin.dasbor_admin') }}" class="hover:underline">Dasbor</a> /
  <a href="{{ route('admin.users.index') }}" class="hover:underline">Kelola User</a> /
  <span>Detail</span>
@endsection

@section('content')
  @php
    $profil = $user->profilMahasiswa;

    // Siapkan nama prodi & fakultas dengan beberapa kemungkinan sumber
    $prodiNama = null;
    $fakultasNama = null;

    // 1) Coba dari relasi profilMahasiswa -> programStudi (kalau ada di model ProfilMahasiswa)
    if ($profil && method_exists($profil, 'programStudi')) {
        $prodiNama = optional($profil->programStudi)->nama;
        if ($prodiNama && method_exists($profil->programStudi, 'fakultas')) {
            $fakultasNama = optional($profil->programStudi->fakultas)->nama;
        }
    }

    // 2) Kalau masih belum ada, coba dari User -> programStudi
    if (! $prodiNama && $user->relationLoaded('programStudi')) {
        $prodiNama = optional($user->programStudi)->nama;
        if (! $fakultasNama && $user->programStudi && method_exists($user->programStudi, 'fakultas')) {
            $fakultasNama = optional($user->programStudi->fakultas)->nama;
        }
    }

    // 3) Kalau masih belum ada fakultas, coba langsung dari User -> fakultas
    if (! $fakultasNama && $user->relationLoaded('fakultas')) {
        $fakultasNama = optional($user->fakultas)->nama;
    }

    $initial = mb_strtoupper(mb_substr($user->name ?? 'U', 0, 1, 'UTF-8'));
  @endphp

  {{-- Header + actions --}}
  <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
    <div>
      <h2 class="text-xl font-semibold tracking-tight text-slate-900 dark:text-slate-50">
        Detail User
      </h2>
      <p class="text-sm text-slate-600 dark:text-slate-400">
        Ringkasan akun & profil mahasiswa di VitaCheck Unila.
      </p>
    </div>
    <div class="flex items-center gap-2">
      <x-secondary-button as="a" href="{{ route('admin.users.index') }}">
        ← Kembali
      </x-secondary-button>
      <x-primary-button as="a" href="{{ route('admin.users.edit', $user) }}">
        Edit
      </x-primary-button>
    </div>
  </div>

  <div class="max-w-4xl space-y-4">
    {{-- Kartu utama: avatar + info singkat --}}
    <div class="bg-white/90 dark:bg-slate-900/80 rounded-2xl border border-slate-200/70 dark:border-slate-800 px-5 py-4 md:px-6 md:py-5 shadow-sm">
      <div class="flex gap-4">
        {{-- Avatar inisial --}}
        <div class="shrink-0 h-12 w-12 rounded-2xl bg-gradient-to-br from-indigo-500 via-violet-500 to-fuchsia-500 text-white flex items-center justify-center text-lg font-semibold shadow-sm">
          {{ $initial }}
        </div>

        <div class="flex-1 min-w-0">
          <div class="flex flex-wrap items-center gap-2 justify-between">
            <div>
              <div class="text-sm font-semibold text-slate-900 dark:text-slate-50 truncate">
                {{ $user->name }}
              </div>
              <div class="text-xs text-slate-500 dark:text-slate-400 break-all">
                {{ $user->email }}
              </div>
            </div>

            <div class="flex flex-col items-end gap-1 text-xs">
              {{-- Badge peran --}}
              <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-semibold
                {{ $user->peran === 'admin'
                    ? 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200 dark:bg-indigo-900/40 dark:text-indigo-200 dark:ring-indigo-800'
                    : 'bg-slate-50 text-slate-700 ring-1 ring-slate-200 dark:bg-slate-800/70 dark:text-slate-200 dark:ring-slate-700' }}">
                {{ ucfirst($user->peran ?? 'user') }}
              </span>

              {{-- Status verifikasi --}}
              @if($user->email_verified_at)
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-medium
                  bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-200 dark:ring-emerald-800">
                  <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                  Terverifikasi
                </span>
              @else
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-medium
                  bg-amber-50 text-amber-700 ring-1 ring-amber-200 dark:bg-amber-900/40 dark:text-amber-200 dark:ring-amber-800">
                  <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                  Belum verifikasi
                </span>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Kartu detail 2 kolom --}}
    <div class="bg-white/90 dark:bg-slate-900/80 rounded-2xl border border-slate-200/70 dark:border-slate-800 px-5 py-4 md:px-6 md:py-5 shadow-sm">
      <div class="grid gap-6 md:grid-cols-2">
        {{-- Kolom kiri: Info akun --}}
        <div class="space-y-3">
          <div>
            <p class="text-[0.7rem] font-semibold tracking-[0.16em] text-slate-500 dark:text-slate-400 uppercase">
              Info akun
            </p>
          </div>

          <dl class="space-y-2 text-sm">
            <div class="flex justify-between gap-3">
              <dt class="text-slate-500 dark:text-slate-400">Nama</dt>
              <dd class="text-right text-slate-900 dark:text-slate-100 font-medium">
                {{ $user->name }}
              </dd>
            </div>

            <div class="flex justify-between gap-3">
              <dt class="text-slate-500 dark:text-slate-400">Email</dt>
              <dd class="text-right text-slate-900 dark:text-slate-100 break-all">
                {{ $user->email }}
              </dd>
            </div>

            <div class="flex justify-between gap-3">
              <dt class="text-slate-500 dark:text-slate-400">Dibuat</dt>
              <dd class="text-right text-slate-900 dark:text-slate-100">
                {{ $user->created_at?->format('d-m-Y H:i') ?? '-' }}
              </dd>
            </div>

            <div class="flex justify-between gap-3">
              <dt class="text-slate-500 dark:text-slate-400">Terakhir diubah</dt>
              <dd class="text-right text-slate-900 dark:text-slate-100">
                {{ $user->updated_at?->format('d-m-Y H:i') ?? '-' }}
              </dd>
            </div>

            @if($user->email_verified_at)
              <div class="flex justify-between gap-3">
                <dt class="text-slate-500 dark:text-slate-400">Email diverifikasi</dt>
                <dd class="text-right text-slate-900 dark:text-slate-100">
                  {{ $user->email_verified_at->format('d-m-Y H:i') }}
                </dd>
              </div>
            @endif
          </dl>
        </div>

        {{-- Kolom kanan: Profil Mahasiswa --}}
        <div class="space-y-3">
          <div class="flex items-center justify-between gap-2">
            <p class="text-[0.7rem] font-semibold tracking-[0.16em] text-slate-500 dark:text-slate-400 uppercase">
              Profil mahasiswa
            </p>

            @if(! $profil)
              <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium
                bg-amber-50 text-amber-700 ring-1 ring-amber-200 dark:bg-amber-900/40 dark:text-amber-200 dark:ring-amber-800">
                Belum diisi
              </span>
            @endif
          </div>

          @if($profil)
            <dl class="space-y-2 text-sm">
              @if($profil->nim)
                <div class="flex justify-between gap-3">
                  <dt class="text-slate-500 dark:text-slate-400">NIM</dt>
                  <dd class="text-right text-slate-900 dark:text-slate-100">
                    {{ $profil->nim }}
                  </dd>
                </div>
              @endif

              <div class="flex justify-between gap-3">
                <dt class="text-slate-500 dark:text-slate-400">Program Studi</dt>
                <dd class="text-right text-slate-900 dark:text-slate-100">
                  {{ $prodiNama ?? '-' }}
                </dd>
              </div>

              <div class="flex justify-between gap-3">
                <dt class="text-slate-500 dark:text-slate-400">Fakultas</dt>
                <dd class="text-right text-slate-900 dark:text-slate-100">
                  {{ $fakultasNama ?? '-' }}
                </dd>
              </div>

              @if($profil->no_hp)
                <div class="flex justify-between gap-3">
                  <dt class="text-slate-500 dark:text-slate-400">No. HP</dt>
                  <dd class="text-right text-slate-900 dark:text-slate-100">
                    {{ $profil->no_hp }}
                  </dd>
                </div>
              @endif
            </dl>
          @else
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
              Belum ada data profil mahasiswa yang terhubung dengan akun ini. NIM, program studi,
              fakultas, dan nomor HP akan muncul di sini setelah profil mahasiswa dilengkapi.
            </p>
          @endif
        </div>
      </div>
    </div>

    {{-- Aksi hapus --}}
    @if(auth()->id() !== $user->id)
      <div class="flex justify-end">
        <form
          action="{{ route('admin.users.destroy', $user) }}"
          method="POST"
          onsubmit="return confirm('Yakin ingin menghapus user ini?');">
          @csrf
          @method('DELETE')
          <x-danger-button>
            Hapus User
          </x-danger-button>
        </form>
      </div>
    @endif
  </div>
@endsection
