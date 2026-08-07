{{-- Previous/next navigation cards shared by blog posts and case studies.
     Each side is `['url' => ..., 'title' => ...]` or null. --}}
@props([
    'previous' => null,
    'next' => null,
    'ariaLabel' => 'Adjacent navigation',
])

@if($previous || $next)
    <nav {{ $attributes->merge(['class' => 'adjacent-nav grid sm:grid-cols-2 gap-6']) }} aria-label="{{ $ariaLabel }}" data-reveal>
        @if($previous)
            <a href="{{ $previous['url'] }}" class="adjacent-nav__card adjacent-nav__card--prev surface-card group p-5">
                <p class="font-mono text-[10px] text-neutral-400 uppercase tracking-widest mb-2">
                    <span class="adjacent-nav__arrow" aria-hidden="true">←</span> Previous
                </p>
                <p class="font-display text-lg text-neutral-200 group-hover:text-accent tracking-wide transition-colors">{{ $previous['title'] }}</p>
            </a>
        @else
            <div></div>
        @endif
        @if($next)
            <a href="{{ $next['url'] }}" class="adjacent-nav__card adjacent-nav__card--next surface-card group p-5 sm:text-right">
                <p class="font-mono text-[10px] text-neutral-400 uppercase tracking-widest mb-2">
                    Next <span class="adjacent-nav__arrow" aria-hidden="true">→</span>
                </p>
                <p class="font-display text-lg text-neutral-200 group-hover:text-accent tracking-wide transition-colors">{{ $next['title'] }}</p>
            </a>
        @endif
    </nav>
@endif
