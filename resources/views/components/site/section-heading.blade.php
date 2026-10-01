@props([
    'label',
])

<h2 {{ $attributes->merge(['class' => 'inline-flex items-center gap-3 eyebrow site-heading-space']) }} data-reveal>
    <span aria-hidden="true" class="section-accent-line h-px w-8 bg-accent/60 shrink-0"></span>
    <span>{{ $label }}</span>
</h2>
