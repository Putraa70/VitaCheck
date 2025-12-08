<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Masuk | VitaCheck Unila</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    @keyframes fadeZoom {
      0% { opacity: .2; transform: scale(1.05); }
      100% { opacity: .5; transform: scale(1.0); }
    }
  </style>
</head>

<body class="min-h-screen bg-slate-100 antialiased">

  <!-- WRAPPER FLEX 2 KOLOM -->
  <div class="grid lg:grid-cols-2 min-h-screen">

    <!-- ========================= -->
    <!-- KOLOM KIRI — FORM LOGIN -->
    <!-- ========================= -->
    <div class="flex items-center justify-center p-8 lg:p-16">

      <!-- Card Form -->
      <main class="w-full max-w-md bg-white/70 backdrop-blur-xl border border-white/40 shadow-2xl rounded-3xl p-8 lg:p-10">

        <!-- Header -->
        <div class="text-center mb-6">
          <h1 class="text-4xl font-extrabold bg-gradient-to-r from-indigo-600 to-blue-600 text-transparent bg-clip-text">
            VitaCheck Unila
          </h1>
          <p class="text-sm text-slate-600 mt-1">Silakan masuk untuk melanjutkan</p>
        </div>

        <!-- Alerts -->
        @if (session('status'))
          <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 p-3 text-sm">
            {{ session('status') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="mb-4 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 p-3 text-sm">
            <ul class="list-disc ml-4">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <!-- FORM -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
          @csrf

          <!-- Email -->
          <div>
            <label class="text-sm font-semibold text-slate-700">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
              class="mt-1 w-full px-4 py-3 rounded-xl border border-slate-300 bg-white shadow-sm focus:ring-4 focus:ring-indigo-200 focus:border-indigo-600 outline-none"
              placeholder="nama@kampus.ac.id">
          </div>

          <!-- Password -->
          <div>
            <label class="text-sm font-semibold text-slate-700">Kata Sandi</label>
            <input type="password" name="password" required
              class="mt-1 w-full px-4 py-3 rounded-xl border border-slate-300 bg-white shadow-sm focus:ring-4 focus:ring-indigo-200 focus:border-indigo-600 outline-none"
              placeholder="••••••••">
          </div>

          <!-- Remember + Lupa -->
          <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 text-slate-700">
              <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600">
              Ingat saya
            </label>

            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="font-medium text-indigo-600 hover:underline">
              Lupa sandi?
            </a>
            @endif
          </div>

          <!-- Submit -->
          <button type="submit"
            class="w-full py-3 rounded-xl text-white font-semibold bg-gradient-to-r from-indigo-500 to-blue-600 hover:opacity-95 shadow-lg hover:-translate-y-0.5 transition-all duration-200">
            Masuk
          </button>

          <!-- Divider -->
          <div class="flex items-center gap-3 text-slate-500">
            <div class="flex-1 h-px bg-slate-300"></div>
            <span class="text-xs">atau</span>
            <div class="flex-1 h-px bg-slate-300"></div>
          </div>

          <!-- Register -->
          <p class="text-center text-sm text-slate-700">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:underline">
              Daftar
            </a>
          </p>
        </form>

        <!-- Footer -->
        <p class="mt-10 text-center text-xs text-slate-500">
          © {{ date('Y') }} VitaCheck Unila. Seluruh hak cipta.
        </p>

      </main>
    </div>

    <!-- ========================= -->
<!-- KOLOM KANAN — GAMBAR -->
<!-- ========================= -->
<div class="relative hidden lg:block">

  <!-- Gambar Background -->
  <div class="absolute inset-0 bg-cover bg-center"
    style="background-image: url('{{ asset('image/klinik.jpg') }}'); 
           filter: brightness(0.92); /* lebih terang */
           ">
  </div>

  <!-- Overlay TIPIS (agar gambar tetap kelihatan) -->
  <div class="absolute inset-0 bg-black/10"></div>

  <!-- Tulisan di atas gambar -->
  <div class="absolute inset-0 flex flex-col items-center justify-center text-white px-16 text-center drop-shadow-lg">
    <h2 class="text-4xl font-extrabold">Selamat Datang di VitaCheck</h2>
    <p class="mt-3 text-lg opacity-95">Sistem pemeriksaan kesehatan yang cepat, akurat, dan modern.</p>
  </div>

    </div>

  </div>

</body>
</html>
