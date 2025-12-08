{{-- resources/views/layouts/guest-gradient.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes gradientAnimation {
            0% { background-position: 0% 50% }
            50% { background-position: 100% 50% }
            100% { background-position: 0% 50% }
        }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen flex items-center justify-center"
        style="background: linear-gradient(135deg, rgb(139,47,237) 0%, #2575FC 50%, #FF6B6B 100%);
                background-size: 400% 400%; animation: gradientAnimation 5s ease infinite;">
        {{ $slot }}
    </div>
</body>
</html>
