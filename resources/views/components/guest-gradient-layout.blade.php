@props(['title' => 'VitaCheck'])

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $title }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>
    @keyframes gradientAnimation {
      0% { background-position: 0% 50% }
      50% { background-position: 100% 50% }
      100% { background-position: 0% 50% }
    }
  </style>
</head>
<body class="min-h-screen antialiased text-slate-800 selection:bg-indigo-200 selection:text-indigo-900">
  <div class="min-h-screen grid place-items-center"
       style="background: linear-gradient(135deg, #0ea5e9, #8b5cf6, #ef4444);
              background-size: 400% 400%; animation: gradientAnimation 9s ease infinite;">
    <main class="w-full max-w-md bg-white/15 backdrop-blur-xl shadow-2xl border border-white/20 rounded-2xl overflow-hidden">
      <header class="px-8 pt-8 pb-4 text-center">
        {{-- <img src="{{ asset('images/logo-vitacheck.svg') }}" alt="VitaCheck" class="h-10 mx-auto mb-3"> --}}
        <h1 class="text-3xl font-bold tracking-tight text-white">
          <span class="text-indigo-200">Vita</span>Check <span class="opacity-90">Unila</span>
        </h1>
        @if(trim($title))
          <p class="mt-1 text-sm text-white/80">{{ $title }}</p>
        @endif
      </header>

      <section class="px-8 pb-8">
        {{ $slot }}
      </section>

      <footer class="px-8 pb-6">
        <p class="text-center text-[11px] text-white/70">
          © {{ date('Y') }} VitaCheck Unila. Seluruh hak cipta.
        </p>
      </footer>
    </main>
  </div>
</body>
</html>
