{{-- Shared inner-page hero scaffold: dot grid, breadcrumbs, optional eyebrow,
     display h1. Everything below the h1 (lede, actions) goes in the default
     slot; the title itself is the `title` slot so it can carry markup like <br>.

     Keep the chrome lean: the breadcrumb already names the page, so only pass
     an `eyebrow` when it adds information the title doesn't ("For recruiters &
     hiring managers"), not a synonym for it ("Portfolio" over "Selected Work").
     On phones the breadcrumb is hidden whenever the section rail is present —
     nav + rail + h1 is enough wayfinding for one screen (see layout.css). --}}
@props([
    'eyebrow' => null,
    'breadcrumbs' => [],
])

<section {{ $attributes->merge(['class' => 'relative site-page-hero overflow-hidden']) }}>
    <div class="hero-dot-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>

    <div class="relative z-10 site-shell">
        @if(count($breadcrumbs) > 0)
            <x-site.breadcrumbs :items="$breadcrumbs" class="site-page-hero__crumbs mb-5 hero-enter" style="animation-delay:80ms" />
        @endif
        @if($eyebrow)
            <p class="font-mono text-accent text-xs tracking-widest uppercase mb-3 hero-enter" style="animation-delay:160ms">{{ $eyebrow }}</p>
        @endif
        <h1 class="font-display text-[clamp(2.75rem,8.5vw,5.5rem)] leading-none tracking-wide text-white mb-4 hero-enter" style="animation-delay:240ms">
            {{ $title }}
        </h1>
        <div class="hero-enter" style="animation-delay:320ms">
            {{ $slot }}
        </div>
    </div>
</section>
