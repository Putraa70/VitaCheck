<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Masuk • VitaCheck' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-full grid place-items-center bg-gray-50">
    <div class="w-full max-w-md p-8">
        <div class="flex flex-col items-center gap-4 mb-6">
            <a href="{{ url('/') }}"><x-application-logo class="h-12 w-12" /></a>
            <h1 class="text-xl font-semibold">{{ $heading ?? 'VitaCheck Unila' }}</h1>
            <p class="text-sm text-gray-500">{{ $subheading ?? 'Sistem pendaftaran cek kesehatan & tes narkoba' }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 p-6">
            {{ $slot }}
        </div>

        <p class="text-xs text-center text-gray-500 mt-6">&copy; {{ date('Y') }} VitaCheck Unila</p>
    </div>
</body>
</html>
