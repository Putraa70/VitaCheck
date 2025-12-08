@props([
    'as' => 'button',
    'href' => null,
])

@php
    $classes = 'inline-flex items-center px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition';
@endphp

@if($as === 'a')
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
