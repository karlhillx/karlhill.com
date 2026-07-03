@props([
    'number',
    'label',
])

<h2 {{ $attributes->merge(['class' => 'inline-flex items-center gap-3 font-mono text-accent text-xs tracking-widest uppercase mb-16']) }} data-reveal>
    <span aria-hidden="true" class="h-px w-8 bg-accent/60 shrink-0"></span>
    <span>{{ $number }} — {{ $label }}</span>
</h2>
