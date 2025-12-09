<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Masuk | VitaCheck Unila</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    @keyframes gradientAnimation {
      0% { background-position: 0% 50% }
      50% { background-position: 100% 50% }
      100% { background-position: 0% 50% }
    }
  </style>
</head>
<body class="min-h-screen antialiased text-slate-800 selection:bg-indigo-200 selection:text-indigo-900">
  <!-- Background -->
  <div class="min-h-screen grid place-items-center"
       style="background: linear-gradient(135deg, #0ea5e9, #8b5cf6, #ef4444);
              background-size: 400% 400%;
              animation: gradientAnimation 9s ease infinite;">
    <!-- Glass Card -->
    <main class="w-full max-w-md bg-white/15 backdrop-blur-xl shadow-2xl border border-white/20 rounded-2xl">
      <!-- Header -->
      <div class="px-8 pt-8 pb-4 text-center">
        {{-- <img src="{{ asset('images/logo-vitacheck.svg') }}" alt="VitaCheck Logo" class="h-10 mx-auto mb-3"> --}}
        <h1 class="text-3xl font-bold tracking-tight text-white">
          <span class="text-indigo-200">Vita</span>Check <span class="opacity-90">Unila</span>
        </h1>
        <p class="mt-1 text-sm text-white/80">Silakan masuk untuk melanjutkan</p>
      </div>

      <div class="px-8">
        @if (session('status'))
          <div class="mb-4 rounded-xl bg-emerald-50/80 text-emerald-800 ring-1 ring-emerald-200 px-4 py-3 text-sm">
            {{ session('status') }}
          </div>
        @endif

        {{-- Pesan error khusus Google --}}
        @if (session('error_google'))
          <div class="mb-4 rounded-xl bg-rose-50/80 text-rose-800 ring-1 ring-rose-200 px-4 py-3 text-sm">
            {{ session('error_google') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="mb-4 rounded-xl bg-rose-50/80 text-rose-800 ring-1 ring-rose-200 px-4 py-3 text-sm">
            <div class="font-medium">Ada kesalahan pada isian Anda:</div>
            <ul class="list-disc ms-5 mt-1">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>


      <!-- Form -->
      <form method="POST" action="{{ route('login') }}" novalidate
            class="px-8 pb-8 space-y-5">
        @csrf

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-white/90">Email</label>
          <div class="mt-1 relative">
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="peer w-full rounded-xl border border-white/30 bg-white/70 backdrop-blur placeholder-slate-500/70
                          px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-4 focus:ring-indigo-300/50
                          focus:border-indigo-600 transition"
                   placeholder="nama@kampus.ac.id"
                   required autofocus autocomplete="email" inputmode="email" />
            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
              <!-- mail icon -->
              <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-slate-500/70"
                   viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                <path d="M4 6h16v12H4z"/><path d="m22 6-10 7L2 6"/>
              </svg>
            </div>
          </div>
          @error('email')
            <p class="mt-1 text-xs text-rose-100/90">{{ $message }}</p>
          @enderror
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-sm font-medium text-white/90">Kata Sandi</label>
          <div class="mt-1 relative">
            <input id="password" type="password" name="password"
                   class="peer w-full rounded-xl border border-white/30 bg-white/70 backdrop-blur placeholder-slate-500/70
                          px-4 py-2.5 pr-11 text-sm shadow-sm focus:outline-none focus:ring-4 focus:ring-indigo-300/50
                          focus:border-indigo-600 transition"
                   placeholder="••••••••" required autocomplete="current-password" />
            <button type="button" aria-label="Tampilkan / sembunyikan sandi"
                    class="absolute inset-y-0 right-2 grid place-items-center px-2 rounded-lg hover:bg-black/5"
                    onclick="const i=this.previousElementSibling; i.type=i.type==='password'?'text':'password'; this.querySelector('[data-eye]').classList.toggle('hidden'); this.querySelector('[data-eye-off]').classList.toggle('hidden');">
              <!-- eye -->
              <svg data-eye xmlns="http://www.w3.org/2000/svg" class="size-5 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
              <!-- eye-off -->
              <svg data-eye-off xmlns="http://www.w3.org/2000/svg" class="size-5 text-slate-600 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                <path d="M3 3l18 18M10.6 10.6A3 3 0 0 0 13.4 13.4M9.9 4.24C10.58 4.08 11.28 4 12 4c6.5 0 10 8 10 8a18.73 18.73 0 0 1-3.06 4.49M6.56 6.56A18.72 18.72 0 0 0 2 12s3.5 7 10 7a10.9 10.9 0 0 0 5.44-1.39"/>
              </svg>
            </button>
          </div>
          @error('password')
            <p class="mt-1 text-xs text-rose-100/90">{{ $message }}</p>
          @enderror
        </div>

        <!-- Remember + Forgot -->
        <div class="flex items-center justify-between text-sm">
          <label class="inline-flex items-center gap-2 text-white/90">
            <input type="checkbox" name="remember"
                   class="rounded border-white/40 bg-white/70 text-indigo-600 focus:ring-indigo-500" />
            Ingat saya
          </label>

          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}"
               class="font-medium text-indigo-100 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white/50 rounded">
              Lupa sandi?
            </a>
          @endif
        </div>

        <!-- Submit -->
        <button type="submit"
                class="w-full inline-flex justify-center items-center gap-2 py-3 rounded-xl font-semibold text-white text-sm
                       bg-gradient-to-r from-indigo-500 to-blue-500 hover:opacity-95
                       shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition">
          Masuk
        </button>

        <!-- Divider -->
        <div class="flex items-center gap-3 text-white/70 mt-1">
          <div class="h-px flex-1 bg-white/30"></div>
          <span class="text-xs">atau</span>
          <div class="h-px flex-1 bg-white/30"></div>
        </div>

        <!-- Tombol Login Google -->
        <a href="{{ route('auth.google.redirect') }}"
           class="w-full mt-2 inline-flex justify-center items-center gap-3 py-2.5 rounded-xl font-medium text-sm
                  bg-white/80 backdrop-blur-sm text-slate-700 border border-white/40 shadow-md hover:bg-white
                  hover:shadow-lg hover:-translate-y-0.5 transition">
          <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
               alt="Google" class="w-5 h-5">
          <span>Masuk dengan Google</span>
        </a>

        <!-- Register -->
        <p class="text-center text-white/85 text-sm mt-4">
          Belum punya akun?
          <a href="{{ route('register') }}" class="font-semibold text-indigo-100 hover:text-white underline-offset-4 hover:underline">
            Daftar
          </a>
        </p>
      </form>

      <!-- Footer kecil -->
      <div class="px-8 pb-6">
        <p class="text-center text-[11px] text-white/70">
          © {{ date('Y') }} VitaCheck Unila. Seluruh hak cipta.
        </p>
      </div>
    </main>
  </div>
</body>
</html>
