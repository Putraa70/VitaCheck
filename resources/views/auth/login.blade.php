<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | VitaCheck</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes gradientAnimation {
            0% { background-position: 0% 50% }
            50% { background-position: 100% 50% }
            100% { background-position: 0% 50% }
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center"
         style="background: linear-gradient(135deg, rgb(139,47,237) 0%, #2575FC 50%, #FF6B6B 100%);
                background-size: 400% 400%;
                animation: gradientAnimation 6s ease infinite;">

        <div class="w-full max-w-md bg-white/10 backdrop-blur-md border border-white/20 shadow-2xl rounded-xl p-8">
            <h2 class="text-center text-2xl font-bold text-white">Login ke VitaCheck</h2>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="mt-4 text-sm text-green-200 bg-green-800/40 border border-green-400 rounded-lg p-3">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-6">
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-white">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           autocomplete="email"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 outline-none
                                  focus:border-indigo-500 focus:ring-4 focus:ring-indigo-200 transition">
                    @error('email') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div class="mb-2">
                    <label for="password" class="block text-sm font-medium text-white">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 outline-none
                                  focus:border-indigo-500 focus:ring-4 focus:ring-indigo-200 transition">
                    @error('password') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Remember me + Forgot --}}
                <div class="flex items-center justify-between mt-2">
                    <label class="inline-flex items-center gap-2 text-sm text-white">
                        <input type="checkbox" name="remember"
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-sm font-semibold text-indigo-200 hover:text-indigo-100"
                           href="{{ route('password.request') }}">
                           Lupa password?
                        </a>
                    @endif
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="mt-6 w-full rounded-lg py-3 font-semibold text-white
                               transition transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl"
                        style="background: linear-gradient(to right, #6a11cb, #2575fc);">
                    Login
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-white">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-indigo-200 hover:text-indigo-100">
                    Daftar di sini
                </a>
            </p>
        </div>
    </div>
</body>
</html>
