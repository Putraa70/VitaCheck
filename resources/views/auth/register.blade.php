<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Daftar Akun | VitaCheck Unila</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 antialiased">

  <!-- WRAPPER 2 KOLOM -->
  <div class="grid lg:grid-cols-2 min-h-screen">

    <!-- ========================= -->
    <!-- KOLOM KIRI — FORM REGISTER -->
    <!-- ========================= -->
    <div class="flex items-center justify-center p-8 lg:p-16">

      <!-- Card Form -->
      <main class="w-full max-w-md bg-white/70 backdrop-blur-xl border border-white/40 shadow-2xl rounded-3xl p-8 lg:p-10">

        <!-- Header -->
        <div class="text-center mb-6">
          <h1 class="text-4xl font-extrabold bg-gradient-to-r from-indigo-600 to-blue-600 text-transparent bg-clip-text">
            Buat Akun Baru
          </h1>
          <p class="text-sm text-slate-600 mt-1">Lengkapi data Anda untuk mendaftar</p>
        </div>

        {{-- Alerts --}}
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
        <form method="POST" action="{{ route('register') }}" class="space-y-5" novalidate>
          @csrf

          <!-- Nama -->
          <div>
            <label class="text-sm font-semibold text-slate-700">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required
              class="mt-1 w-full px-4 py-3 rounded-xl border border-slate-300 bg-white shadow-sm 
                     focus:ring-4 focus:ring-indigo-200 focus:border-indigo-600 outline-none"
              placeholder="Nama lengkap">
          </div>

          <!-- Email -->
          <div>
            <label class="text-sm font-semibold text-slate-700">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
              class="mt-1 w-full px-4 py-3 rounded-xl border border-slate-300 bg-white shadow-sm 
                     focus:ring-4 focus:ring-indigo-200 focus:border-indigo-600 outline-none"
              placeholder="nama@kampus.ac.id">
          </div>

          <!-- Password -->
          <div>
            <label class="text-sm font-semibold text-slate-700">Password</label>
            <input type="password" name="password" required
              class="mt-1 w-full px-4 py-3 rounded-xl border border-slate-300 bg-white shadow-sm 
                     focus:ring-4 focus:ring-indigo-200 focus:border-indigo-600 outline-none"
              placeholder="••••••••">
          </div>

          <!-- Konfirmasi Password -->
          <div>
            <label class="text-sm font-semibold text-slate-700">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required
              class="mt-1 w-full px-4 py-3 rounded-xl border border-slate-300 bg-white shadow-sm 
                     focus:ring-4 focus:ring-indigo-200 focus:border-indigo-600 outline-none"
              placeholder="Ulangi password">
          </div>

          <!-- Tombol -->
          <button type="submit"
            class="w-full py-3 rounded-xl text-white font-semibold 
                   bg-gradient-to-r from-indigo-500 to-blue-600 hover:opacity-95 
                   shadow-lg hover:-translate-y-0.5 transition-all duration-200">
            Daftar
          </button>

          <!-- Login Link -->
          <p class="text-center text-sm text-slate-700">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:underline">
              Masuk
            </a>
          </p>
        </form>

      </main>
    </div>

    <!-- ========================= -->
    <!-- KOLOM KANAN — GAMBAR BESAR -->
    <!-- ========================= -->
    <div class="relative hidden lg:block">

      <!-- Background Image -->
      <div class="absolute inset-0 bg-cover bg-center"
        style="background-image: url('{{ asset('image/klinik.jpg') }}');
               filter: brightness(0.92);">
      </div>

      <!-- Overlay Tipis -->
      <div class="absolute inset-0 bg-black/10"></div>

      <!-- Text di atas gambar -->
      <div class="absolute inset-0 flex flex-col items-center justify-center text-white px-16 text-center drop-shadow-lg">
        <h2 class="text-4xl font-extrabold">Bergabung dengan VitaCheck</h2>
        <p class="mt-3 text-lg opacity-95">
          Sistem kesehatan modern untuk pengalaman yang lebih baik.
        </p>
      </div>
    </div>

  </div>

</body>
</html>
