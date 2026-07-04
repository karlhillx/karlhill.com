{{-- Animated stat counter cell (see the counter observer in app.js). --}}
@props([
    'value',
    'label',
    'to' => null,
    'prefix' => null,
    'suffix' => null,
    'padding' => 'p-8',
    'valueClass' => 'text-5xl mb-2',
    'labelClass' => 'text-neutral-500',
])

<div {{ $attributes->merge(['class' => "bg-bg {$padding} text-center"]) }}>
    <p class="font-display text-accent {{ $valueClass }}"
       data-counter
       data-final="{{ $value }}"
       @if($to !== null) data-to="{{ $to }}" data-prefix="{{ $prefix }}" data-suffix="{{ $suffix }}" @endif
       aria-label="{{ $value }} — {{ $label }}">{{ $value }}</p>
    <p class="font-mono text-[10px] {{ $labelClass }} uppercase tracking-widest leading-relaxed">{{ $label }}</p>
</div>
