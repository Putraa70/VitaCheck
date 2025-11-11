{{-- Komponen pembungkus untuk halaman tamu dengan background gradient --}}
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'VitaCheck Unila') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center p-6">
    {{-- Reuse struktur internal Breeze (card putih di tengah) --}}
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
        {{ $slot }}
    </div>
</body>
</html>
