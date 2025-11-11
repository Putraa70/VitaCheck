@props(['class' => 'h-6 w-6'])
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" {{ $attributes->merge(['class'=>$class]) }}>
  <defs>
    <linearGradient id="g" x1="0" x2="1">
      <stop offset="0" stop-color="#38bdf8"/><stop offset="1" stop-color="#7c3aed"/>
    </linearGradient>
  </defs>
  <rect x="2" y="2" width="20" height="20" rx="6" fill="url(#g)"/>
  <path d="M8 12h8M12 8v8" stroke="white" stroke-width="2" stroke-linecap="round"/>
</svg>
