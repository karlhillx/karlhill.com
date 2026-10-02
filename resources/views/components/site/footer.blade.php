@props([
    'variant' => 'compact',
])

@php
    $person = config('site.person');
    $footer = config('site.footer');
    $isHome = $variant === 'home';
    $bookingUrl = config('site.booking.url');
    $bookingLabel = config('site.booking.label');
    $bookingEmbed = config('site.booking.embed_src');
@endphp

<footer id="contact" @if($isHome) data-section-label="Contact" @endif @class([
    'relative z-10 border-t border-neutral-800/50 site-footer',
    'site-footer--home' => $isHome,
    'site-footer--compact' => ! $isHome,
])>
    <div class="site-shell">
        @if($isHome)
            <div class="site-footer-home">
                <div class="min-w-0" data-reveal>
                    <h2 class="eyebrow mb-5 sm:mb-6">Contact</h2>
                    <p class="font-display leading-none tracking-wide text-balance text-[clamp(2.75rem,7vw,5.5rem)] mb-5 sm:mb-6">
                        {!! nl2br(e($footer['headline'])) !!}
                    </p>
                    <p class="text-neutral-400 text-sm leading-relaxed max-w-xl">
                        {{ $footer['body'] }}
                    </p>

                </div>

                <aside class="site-footer-aside flex flex-col gap-6" data-reveal aria-label="Direct contact and scheduling">
                    <div>
                        <p class="eyebrow eyebrow--muted mb-5">Email directly</p>
                        <x-site.email-copy location="footer-home" :arrow="true" />
                    </div>

                    @if(filled($bookingUrl))
                        <div>
                            <p class="eyebrow eyebrow--muted mb-3">Schedule</p>
                            {{-- The summary lives here; the wide scheduler panel renders below the
                                 grid and is revealed with :has() so the iframe gets full width. --}}
                            <details class="contact-booking">
                                <summary class="contact-booking__summary portfolio-text-link cursor-pointer"
                                         data-analytics-event="booking_cta_clicked"
                                         data-analytics-location="footer-home">
                                    <span>{{ $bookingLabel }}</span>
                                    <svg class="contact-booking__chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M6 9l6 6 6-6"/>
                                    </svg>
                                </summary>
                                <div id="book" class="contact-booking__body">
                                    @if(filled($bookingEmbed))
                                        <p class="text-neutral-400 text-sm leading-relaxed">Pick a time in the scheduler below.</p>
                                    @endif
                                    <a href="{{ $bookingUrl }}" target="_blank" rel="noopener noreferrer"
                                       data-analytics-event="scheduler_opened" data-analytics-location="contact"
                                       class="portfolio-text-link text-sm">
                                        Open scheduler in a new tab
                                    </a>
                                </div>
                            </details>
                        </div>
                    @else
                        <span id="book"></span>
                    @endif

                </aside>
                <div class="site-footer-form">
                    <x-site.contact-form id-prefix="contact" :return-to="url()->current()" />
                </div>
                <div>
                    <p class="eyebrow eyebrow--muted mb-1">Elsewhere</p>
                    <x-site.social-links class="-ml-3" />
                </div>
            </div>
            @if(filled($bookingUrl) && filled($bookingEmbed))
                <div class="booking-embed booking-embed--footer">
                    <iframe class="booking-embed__frame"
                            src="{{ $bookingEmbed }}"
                            title="{{ $bookingLabel }}"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            allow="payment *"></iframe>
                </div>
            @endif
        @else
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-10 lg:gap-16">
                <div class="max-w-xl">
                    <h2 class="eyebrow mb-4">Contact</h2>
                    <p class="text-neutral-300 text-base leading-relaxed">
                        {{ $footer['compact_body'] ?? 'Schedule a conversation or send email.' }}
                    </p>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-3 mt-12">
                        @if(filled($bookingUrl))
                            <x-site.button variant="primary" href="/#book"
                                data-analytics-event="booking_cta_clicked"
                                data-analytics-location="footer">
                                {{ $bookingLabel }}
                            </x-site.button>
                        @endif
                        <x-site.email-copy location="footer" />
                    </div>
                </div>
            </div>
        @endif
        <div @class([
            'pt-10 border-t border-neutral-800/50 flex flex-col sm:flex-row sm:items-center justify-between gap-5',
            'mt-20' => $isHome,
            'mt-12' => ! $isHome,
        ])>
            <p class="font-display {{ $isHome ? 'text-3xl' : 'text-2xl' }} tracking-widest text-neutral-500">{{ $person['name'] }}</p>
            <p class="font-mono text-xs text-neutral-400">
                {{ $person['location'] }} · {{ $person['job_title'] }}
            </p>
        </div>
        <div class="mt-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <x-site.footer-explore />
            <p class="site-build-credit font-mono uppercase text-neutral-500">
                <span>Built with Laravel {{ \App\Support\Stack::laravelVersion() }}</span>
                <span class="site-build-credit__sep" aria-hidden="true">&middot;</span>
                <span>Tailwind CSS {{ \App\Support\Stack::tailwindVersion() ?? '4' }}</span>
            </p>
        </div>
    </div>
</footer>
