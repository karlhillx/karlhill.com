@props([
    'idPrefix' => 'contact',
    'eyebrow' => 'Contact',
    'headline' => null,
    'body' => null,
])

@php
    $person = config('site.person');
    $footer = config('site.footer');
    $bookingUrl = config('site.booking.url');
    $bookingLabel = config('site.booking.label');
    $linkedin = collect(config('site.social'))->first(fn ($link) => ($link['icon'] ?? '') === 'linkedin');
    $headline = $headline ?? $footer['headline'];
    $body = $body ?? $footer['body'];
@endphp

<section id="contact" class="site-section border-t border-neutral-800/50" aria-label="Contact">
    <div class="site-shell grid lg:grid-cols-[minmax(0,1fr)_minmax(16rem,20rem)] gap-12 lg:gap-16">
        <div class="max-w-xl" data-reveal>
            <p class="font-mono text-accent text-xs tracking-widest uppercase mb-5">{{ $eyebrow }}</p>
            <p class="font-display text-[clamp(2rem,5vw,3.5rem)] leading-none tracking-wide text-balance mb-4 sm:mb-5">
                {!! nl2br(e($headline)) !!}
            </p>
            <p class="text-neutral-400 text-sm leading-relaxed max-w-sm">
                {{ $body }}
            </p>

            <x-site.contact-form :id-prefix="$idPrefix" :return-to="url()->current()" />
        </div>

        <div class="flex flex-col gap-4 lg:pt-10" data-reveal>
            <p class="font-mono text-[10px] text-neutral-400 uppercase tracking-widest">Prefer a faster path?</p>

            @if(filled($bookingUrl))
                <a href="{{ $bookingUrl }}" target="_blank" rel="noopener noreferrer"
                   class="btn-sweep inline-flex items-center justify-center min-h-11 gap-3 border border-accent/40 text-accent font-semibold px-6 py-3 text-xs uppercase tracking-widest w-fit">
                    {{ $bookingLabel }}
                    <span aria-hidden="true">→</span>
                </a>
            @endif

            @if($linkedin)
                <a href="{{ $linkedin['url'] }}" target="_blank" rel="me noopener noreferrer"
                   class="btn-sweep inline-flex items-center justify-center min-h-11 gap-3 border border-neutral-700 text-neutral-300 font-semibold px-6 py-3 text-xs uppercase tracking-widest w-fit">
                    LinkedIn
                    <span aria-hidden="true">→</span>
                </a>
            @endif

            <div class="flex items-center gap-3">
                <a href="mailto:{{ $person['email'] }}"
                   class="flex items-center gap-4 font-mono text-sm text-neutral-400 hover:text-accent transition-colors group">
                    <span class="text-accent text-base arrow-nudge" aria-hidden="true">→</span>
                    {{ $person['email'] }}
                </a>
                <button type="button" data-copy-text="{{ $person['email'] }}" aria-label="Copy email address"
                        class="relative text-neutral-500 hover:text-accent transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V5a2 2 0 012-2h9a2 2 0 012 2v9a2 2 0 01-2 2h-2M5 8h9a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2v-9a2 2 0 012-2z"/>
                    </svg>
                    <span data-copy-feedback role="status" aria-live="polite"
                          class="surface-chip-accent pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 inline-flex items-center gap-1.5 whitespace-nowrap px-2.5 py-1 font-mono text-[10px] text-accent uppercase tracking-widest opacity-0 transition-opacity duration-200 shadow-lg shadow-black/40">
                        Copied
                    </span>
                </button>
            </div>

            <a href="/resume"
               class="btn-sweep inline-flex items-center gap-3 border border-neutral-700 text-neutral-300 font-semibold px-6 py-3 text-xs uppercase tracking-widest w-fit">
                View resume
                <span aria-hidden="true">→</span>
            </a>
        </div>
    </div>
</section>
