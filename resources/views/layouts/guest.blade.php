<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'VitaCheck Unila' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-full bg-gray-50 text-gray-900 antialiased">
    {{-- Top Navigation --}}
    {{-- @include('layouts.navigation') --}}

    {{-- Page Heading (optional) --}}
    @isset($header)
        <header class="bg-white/80 backdrop-blur border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                {{ $header }}
            </div>
        </header>
    @endisset

    {{-- Main --}}
    <main class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
        {{-- Flash alerts --}}
        @if(session('status'))
            <div class="mb-6">
                <x-auth-session-status :status="session('status')" />
            </div>
        @endif

        {{ $slot ?? '' }}
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="mt-12 border-t">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-sm text-gray-500 flex items-center justify-between">
            <p>&copy; {{ date('Y') }} VitaCheck Unila — Klinik Unila</p>
            <p class="hidden sm:block">Built with Laravel {{ app()->version() }}</p>
        </div>
    </footer>
</body>
</html>
