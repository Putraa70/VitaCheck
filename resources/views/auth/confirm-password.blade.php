<x-guest-gradient-layout :title="'Konfirmasi Password'">
  <p class="mb-4 text-sm text-white/90">
    Ini area aman aplikasi. Mohon konfirmasi password Anda untuk melanjutkan.
  </p>

  <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5" novalidate>
    @csrf
    <div>
      <label for="password" class="block text-sm font-medium text-white/90">Password</label>
      <div class="mt-1 relative">
        <input id="password" name="password" type="password" required autocomplete="current-password"
               class="w-full rounded-xl border border-white/30 bg-white/70 px-4 py-2.5 pr-11 text-sm focus:ring-4 focus:ring-indigo-300/50 focus:border-indigo-600">
        <button type="button" aria-label="Tampilkan / sembunyikan" class="absolute inset-y-0 right-2 grid place-items-center px-2 rounded-lg hover:bg-black/5"
                onclick="const i=this.previousElementSibling; i.type=i.type==='password'?'text':'password'; this.querySelector('[data-e1]').classList.toggle('hidden'); this.querySelector('[data-e2]').classList.toggle('hidden');">
          <svg data-e1 xmlns="http://www.w3.org/2000/svg" class="size-5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
          <svg data-e2 xmlns="http://www.w3.org/2000/svg" class="size-5 text-slate-700 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 3l18 18M10.6 10.6A3 3 0 0 0 13.4 13.4M9.9 4.24C10.58 4.08 11.28 4 12 4c6.5 0 10 8 10 8a18.73 18.73 0 0 1-3.06 4.49M6.56 6.56A18.72 18.72 0 0 0 2 12s3.5 7 10 7a10.9 10.9 0 0 0 5.44-1.39"/></svg>
        </button>
      </div>
      @error('password') <p class="text-xs text-rose-100 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex justify-end">
      <button class="inline-flex justify-center items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-white text-sm
                     bg-indigo-600 hover:bg-indigo-700 shadow-sm">
        Konfirmasi
      </button>
    </div>
  </form>
</x-guest-gradient-layout>
