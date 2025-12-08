<x-guest-gradient-layout :title="'Lupa Password'">
  @if (session('status'))
    <div class="mb-4 rounded-xl bg-emerald-50/80 text-emerald-800 ring-1 ring-emerald-200 px-4 py-3 text-sm">
      {{ session('status') }}
    </div>
  @endif

  <p class="mb-4 text-sm text-white/90">
    Masukkan email Anda, kami akan mengirim <b>tautan reset password</b> untuk membuat sandi baru.
  </p>

  <form method="POST" action="{{ route('password.email') }}" class="space-y-5" novalidate>
    @csrf
    <div>
      <label for="email" class="block text-sm font-medium text-white/90">Alamat Email</label>
      <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email"
             class="mt-1 w-full rounded-xl border border-white/30 bg-white/70 px-4 py-2.5 text-sm focus:ring-4 focus:ring-indigo-300/50 focus:border-indigo-600" placeholder="nama@kampus.ac.id">
      @error('email') <p class="text-xs text-rose-100 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center justify-end">
      <button class="inline-flex justify-center items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-white text-sm
                     bg-indigo-600 hover:bg-indigo-700 shadow-sm">
        Kirim Link Reset Password
      </button>
    </div>
  </form>
</x-guest-gradient-layout>
