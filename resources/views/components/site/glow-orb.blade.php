{{-- Ambient accent glow. `drift` (1 or 2) picks the drift animation;
     `strength` sets the peak alpha of the radial gradient. --}}
@props([
    'drift' => 1,
    'strength' => 0.14,
])

<div {{ $attributes->merge(['class' => "glow-orb-{$drift} pointer-events-none absolute rounded-full"]) }}
     style="background: radial-gradient(ellipse, rgba(249,115,22,{{ $strength }}) 0%, transparent 65%);"
     aria-hidden="true"></div>
