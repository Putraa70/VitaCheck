<x-guest-gradient-layout :title="'Reset Password'">
  @if ($errors->any())
    <div class="mb-4 rounded-xl bg-rose-50/80 text-rose-800 ring-1 ring-rose-200 px-4 py-3 text-sm">
      <div class="font-medium">Periksa kembali isian Anda:</div>
      <ul class="list-disc ms-5 mt-1">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('password.store') }}" novalidate class="space-y-5">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <div>
      <label for="email" class="block text-sm font-medium text-white/90">Email</label>
      <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
             class="mt-1 w-full rounded-xl border border-white/30 bg-white/70 px-4 py-2.5 text-sm focus:ring-4 focus:ring-indigo-300/50 focus:border-indigo-600">
      @error('email') <p class="text-xs text-rose-100 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="password" class="block text-sm font-medium text-white/90">Password Baru</label>
      <input id="password" name="password" type="password" required autocomplete="new-password"
             class="mt-1 w-full rounded-xl border border-white/30 bg-white/70 px-4 py-2.5 text-sm focus:ring-4 focus:ring-indigo-300/50 focus:border-indigo-600">
      @error('password') <p class="text-xs text-rose-100 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="password_confirmation" class="block text-sm font-medium text-white/90">Konfirmasi Password Baru</label>
      <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
             class="mt-1 w-full rounded-xl border border-white/30 bg-white/70 px-4 py-2.5 text-sm focus:ring-4 focus:ring-indigo-300/50 focus:border-indigo-600">
      @error('password_confirmation') <p class="text-xs text-rose-100 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center justify-end">
      <button class="inline-flex justify-center items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-white text-sm
                     bg-indigo-600 hover:bg-indigo-700 shadow-sm">
        Reset Password
      </button>
    </div>
  </form>
</x-guest-gradient-layout>
