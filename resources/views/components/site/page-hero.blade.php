{{-- Shared inner-page hero scaffold: dot grid, glow orbs, breadcrumbs,
     eyebrow, display h1. Everything below the h1 (lede, actions) goes in
     the default slot; the title itself is the `title` slot so it can carry
     markup like <br>. --}}
@props([
    'eyebrow' => null,
    'breadcrumbs' => [],
])

<section {{ $attributes->merge(['class' => 'relative pt-40 pb-16 px-6 overflow-hidden']) }}>
    <div class="hero-dot-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <x-site.glow-orb :drift="1" class="-bottom-32 -left-32 w-[600px] h-[600px]" />
    <x-site.glow-orb :drift="2" :strength="0.09" class="-top-32 -right-32 w-[500px] h-[500px]" />

    <div class="relative z-10 max-w-6xl mx-auto">
        @if(count($breadcrumbs) > 0)
            <x-site.breadcrumbs :items="$breadcrumbs" class="mb-6" />
        @endif
        @if($eyebrow)
            <p class="font-mono text-accent text-xs tracking-widest uppercase mb-6">{{ $eyebrow }}</p>
        @endif
        <h1 class="font-display text-[clamp(3.5rem,12vw,9rem)] leading-none tracking-wide text-white mb-6">
            {{ $title }}
        </h1>
        {{ $slot }}
    </div>
</section>
