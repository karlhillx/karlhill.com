@props([
    'variant' => 'default',
    'section' => null,
])

@php
    $person = config('site.person');
    $footer = config('site.footer');
    $isHome = $variant === 'home';
@endphp

<footer @if($isHome) id="contact" @endif class="relative z-10 border-t border-neutral-800 {{ $isHome ? 'py-24' : 'py-16' }} px-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-12">
            <div class="max-w-xl" @if($isHome) data-reveal @endif>
                @if($isHome && $section)
                    <h2 class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-6">{{ $section }} — Contact</h2>
                @else
                    <p class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-4">Get in Touch</p>
                @endif
                <p class="font-display {{ $isHome ? 'text-[clamp(3rem,8vw,6rem)] mb-6' : 'text-[clamp(2rem,5vw,3.5rem)] mb-4' }} leading-none tracking-wide">
                    {!! nl2br(e($footer['headline'])) !!}
                </p>
                <p class="text-neutral-500 text-sm leading-relaxed max-w-sm">
                    {{ $footer['body'] }}
                </p>
            </div>
            <div class="flex flex-col gap-4 {{ $isHome ? 'lg:pt-16' : '' }} shrink-0" @if($isHome) data-reveal @endif>
                <a href="mailto:{{ $person['email'] }}"
                   class="flex items-center gap-4 font-mono text-sm text-neutral-400 hover:text-orange-500 transition-colors group">
                    <span class="text-orange-500 text-base arrow-nudge" aria-hidden="true">→</span>
                    {{ $person['email'] }}
                </a>
                <a href="{{ $footer['resume'] }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-3 border border-neutral-700 text-neutral-300 font-semibold px-6 py-3 text-xs uppercase tracking-widest hover:border-orange-500 hover:text-orange-500 transition-colors duration-200 w-fit">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download Resume
                </a>

                <x-site.social-links :extended="$isHome" />
            </div>
        </div>
        <div @class([
            'pt-8 border-t border-neutral-800/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4',
            'mt-20' => $isHome,
            'mt-12' => ! $isHome,
        ])>
            <p class="font-display {{ $isHome ? 'text-3xl' : 'text-2xl' }} tracking-widest text-neutral-600">{{ $person['name'] }}</p>
            <p class="font-mono text-xs text-neutral-500">{{ $person['location'] }} &nbsp;·&nbsp; {{ $person['job_title'] }} &nbsp;·&nbsp; 25+ Years</p>
        </div>
    </div>
</footer>
