@props([
    'number',
    'label',
])

<h2 {{ $attributes->merge(['class' => 'font-mono text-accent text-xs tracking-widest uppercase mb-16']) }} data-reveal>
    {{ $number }} — {{ $label }}
</h2>
