@props([
    'as' => 'button',
    'href' => null,
])

@php
    $classes = 'inline-flex items-center px-4 py-2 rounded-xl ring-1 ring-gray-300 text-gray-700 hover:bg-gray-100 transition';
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
