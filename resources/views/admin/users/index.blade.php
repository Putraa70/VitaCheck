@extends('layouts.admin', ['title' => 'Kelola User'])

@section('breadcrumb')
  <a href="{{ route('admin.dasbor_admin') }}" class="hover:underline">Dasbor</a> / Kelola User
@endsection

@section('content')
  {{-- Notif sukses / error --}}
  @if(session('sukses'))
    <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 px-4 py-3 text-sm">
      {{ session('sukses') }}
    </div>
  @endif

  @if(session('error'))
    <div class="mb-4 rounded-xl bg-rose-50 text-rose-700 ring-1 ring-rose-200 px-4 py-3 text-sm">
      {{ session('error') }}
    </div>
  @endif

  {{-- Header --}}
  <div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
      <div>
        <h2 class="text-xl font-semibold">Kelola User</h2>
        <p class="text-sm text-gray-600">Daftar akun yang terdaftar di VitaCheck Unila.</p>
      </div>
      <div class="flex flex-wrap items-center gap-2">
        <x-secondary-button as="a" href="{{ route('admin.dasbor_admin') }}">← Dashboard</x-secondary-button>
        <x-primary-button as="a" href="{{ route('admin.users.create') }}">+ Tambah User</x-primary-button>
      </div>
    </div>
  </div>

  {{-- Filter & Search --}}
  <div class="mb-4">
    <form method="GET" class="flex flex-wrap items-end gap-3">
      <div class="w-full md:w-64">
        <label class="block text-xs font-medium text-gray-600">Cari (nama / email)</label>
        <input
          type="text"
          name="q"
          value="{{ $q }}"
          placeholder="Ketik kata kunci..."
          class="mt-1 w-full px-3 py-2 rounded-xl border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500"
        />
      </div>

      <div class="w-full md:w-48">
        <label class="block text-xs font-medium text-gray-600">Peran</label>
        <select
          name="peran"
          class="mt-1 w-full px-3 py-2 rounded-xl border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500"
        >
          <option value="">— Semua —</option>
          @foreach($daftarPeran as $role)
            <option value="{{ $role }}" @selected($peran === $role)>{{ ucfirst($role) }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <x-secondary-button class="mt-4">
          Terapkan
        </x-secondary-button>
      </div>

      @if(!empty($q) || !empty($peran))
        <div>
          <a href="{{ route('admin.users.index') }}" class="mt-4 inline-block text-sm text-gray-500 hover:underline">
            Reset
          </a>
        </div>
      @endif
    </form>
  </div>

  {{-- Tabel --}}
  <div class="bg-white rounded-2xl ring-1 ring-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
          <tr>
            <th class="px-4 py-3 text-left font-medium">#</th>
            <th class="px-4 py-3 text-left font-medium">Nama</th>
            <th class="px-4 py-3 text-left font-medium">Email</th>
            <th class="px-4 py-3 text-left font-medium">Peran</th>
            <th class="px-4 py-3 text-left font-medium">Verifikasi</th>
            <th class="px-4 py-3 text-right font-medium">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          @forelse($users as $i => $u)
            <tr>
              <td class="px-4 py-3">
                {{ method_exists($users, 'firstItem') ? $users->firstItem() + $i : $i + 1 }}
              </td>

              <td class="px-4 py-3">
                <div class="font-medium text-gray-900">{{ $u->name }}</div>
              </td>

              <td class="px-4 py-3">
                <div class="text-gray-700">{{ $u->email }}</div>
              </td>

              <td class="px-4 py-3">
                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold
                  {{ $u->peran === 'admin'
                      ? 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200'
                      : 'bg-gray-50 text-gray-700 ring-1 ring-gray-200' }}">
                  {{ ucfirst($u->peran ?? 'user') }}
                </span>
              </td>

              <td class="px-4 py-3">
                @if($u->email_verified_at)
                  <span class="inline-flex items-center text-xs text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full ring-1 ring-emerald-200">
                    Terverifikasi
                  </span>
                @else
                  <span class="inline-flex items-center text-xs text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full ring-1 ring-amber-200">
                    Belum
                  </span>
                @endif
              </td>

              <td class="px-4 py-3">
                <div class="flex items-center justify-end gap-2">
                  {{-- Detail --}}
                  <x-secondary-button
                    as="a"
                    href="{{ route('admin.users.show', $u) }}"
                    class="!px-3 !py-1.5 text-xs"
                  >
                    Detail
                  </x-secondary-button>

                  {{-- Edit --}}
                  <x-primary-button
                    as="a"
                    href="{{ route('admin.users.edit', $u) }}"
                    class="!px-3 !py-1.5 text-xs"
                  >
                    Edit
                  </x-primary-button>

                  {{-- Hapus (tidak bisa menghapus diri sendiri) --}}
                  @if(auth()->id() !== $u->id)
                    <form
                      action="{{ route('admin.users.destroy', $u) }}"
                      method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus user ini?');"
                      class="inline"
                    >
                      @csrf
                      @method('DELETE')
                      <x-secondary-button class="!px-3 !py-1.5 text-xs !text-rose-600 !ring-rose-300 hover:!bg-rose-50">
                        Hapus
                      </x-secondary-button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="py-8 px-4 text-center text-gray-500">
                Belum ada user yang cocok dengan filter.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if(method_exists($users, 'links'))
      <div class="px-4 py-3 bg-gray-50">
        {{ $users->links() }}
      </div>
    @endif
  </div>
@endsection
