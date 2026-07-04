{{-- Previous/next navigation cards shared by blog posts and case studies.
     Each side is `['url' => ..., 'title' => ...]` or null. --}}
@props([
    'previous' => null,
    'next' => null,
    'ariaLabel' => 'Adjacent navigation',
])

@if($previous || $next)
    <nav {{ $attributes->merge(['class' => 'grid sm:grid-cols-2 gap-6']) }} aria-label="{{ $ariaLabel }}" data-reveal>
        @if($previous)
            <a href="{{ $previous['url'] }}" class="group border border-neutral-800 p-5 hover:border-accent/40 transition-colors">
                <p class="font-mono text-[10px] text-neutral-400 uppercase tracking-widest mb-2">← Previous</p>
                <p class="font-display text-lg text-neutral-200 group-hover:text-accent tracking-wide transition-colors">{{ $previous['title'] }}</p>
            </a>
        @else
            <div></div>
        @endif
        @if($next)
            <a href="{{ $next['url'] }}" class="group border border-neutral-800 p-5 hover:border-accent/40 transition-colors sm:text-right">
                <p class="font-mono text-[10px] text-neutral-400 uppercase tracking-widest mb-2">Next →</p>
                <p class="font-display text-lg text-neutral-200 group-hover:text-accent tracking-wide transition-colors">{{ $next['title'] }}</p>
            </a>
        @endif
    </nav>
@endif
