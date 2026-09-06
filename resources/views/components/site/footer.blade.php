@props([
    'variant' => 'compact',
    'section' => null,
])

@php
    $person = config('site.person');
    $footer = config('site.footer');
    $isHome = $variant === 'home';
    $bookingUrl = config('site.booking.url');
    $bookingLabel = config('site.booking.label');
    $bookingHref = request()->routeIs('now') ? '#book' : '/now#book';
@endphp

<footer id="contact" @if($isHome) data-section-label="Contact" @endif @class([
    'relative z-10 border-t border-neutral-800/50 site-footer',
    'site-footer--home' => $isHome,
    'site-footer--compact' => ! $isHome,
])>
    <div class="site-shell">
        @if($isHome)
            <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-14 xl:gap-16">
                <div class="w-full max-w-2xl xl:flex-1 xl:min-w-0" data-reveal>
                    @if($section)
                        <h2 class="font-mono text-accent text-xs tracking-widest uppercase mb-8">{{ $section }} — Contact</h2>
                    @else
                        <h2 class="font-mono text-accent text-xs tracking-widest uppercase mb-5">Contact</h2>
                    @endif
                    <p class="font-display leading-none tracking-wide text-balance text-[clamp(3rem,8vw,6rem)] mb-6 sm:mb-7">
                        {!! nl2br(e($footer['headline'])) !!}
                    </p>
                    <p class="text-neutral-400 text-sm leading-relaxed max-w-xl">
                        {{ $footer['body'] }}
                    </p>

                    <x-site.contact-form id-prefix="contact" :return-to="url()->current()" />
                </div>
                <div class="flex flex-col xl:pt-16 shrink-0" data-reveal>
                    <p class="font-mono text-caption text-neutral-400 uppercase tracking-widest mb-12">Prefer to reach me directly?</p>
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-3">
                            <a href="mailto:{{ $person['email'] }}"
                               data-analytics-event="email_clicked"
                               data-analytics-location="footer-home"
                               class="flex items-center gap-4 font-mono text-sm text-neutral-400 hover:text-accent transition-colors group">
                                <span class="text-accent text-base arrow-nudge" aria-hidden="true">→</span>
                                {{ $person['email'] }}
                            </a>
                            <button type="button" data-copy-text="{{ $person['email'] }}" aria-label="Copy email address"
                                    class="relative isolate inline-flex items-center justify-center min-h-11 min-w-11 text-neutral-500 hover:text-accent transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V5a2 2 0 012-2h9a2 2 0 012 2v9a2 2 0 01-2 2h-2M5 8h9a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2v-9a2 2 0 012-2z"/>
                                </svg>
                                <span data-copy-feedback role="status" aria-live="polite"
                                      class="copy-feedback pointer-events-none absolute inline-flex items-center gap-1.5 whitespace-nowrap px-2.5 py-1 font-mono text-caption uppercase tracking-widest opacity-0 transition-opacity duration-200">
                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Copied to clipboard
                                </span>
                            </button>
                        </div>
                        @if(filled($bookingUrl))
                            <a href="{{ $bookingHref }}"
                               data-analytics-event="booking_cta_clicked"
                               data-analytics-location="footer-home"
                               class="btn-accent-fill inline-flex items-center gap-3 font-semibold px-6 py-3 text-xs uppercase tracking-widest w-fit">
                                {{ $bookingLabel }}
                                <span aria-hidden="true">→</span>
                            </a>
                        @endif

                        <x-site.social-links />
                    </div>
                </div>
                <nav class="shrink-0" aria-label="Site">
                    <h2 class="font-mono text-accent text-xs tracking-widest uppercase mb-4">Explore</h2>
                    <ul class="space-y-1 font-mono text-sm">
                        <li><a href="/work" class="inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors">Work</a></li>
                        <li><a href="/about" class="inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors">About</a></li>
                        <li><a href="/blog" class="inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors">Writing</a></li>
                        <li><a href="/now" class="inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors">Now</a></li>
                        @unless(request()->routeIs('resume'))
                            <li><a href="/resume" class="inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors">Resume</a></li>
                        @endunless
                        @unless(request()->routeIs('kit'))
                            <li><a href="/kit" class="inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors">Recruiter kit</a></li>
                        @endunless
                        @unless(request()->routeIs('lead'))
                            <li><a href="/lead" class="inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors">How I run delivery</a></li>
                        @endunless
                    </ul>
                </nav>
            </div>
        @else
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-10 lg:gap-16">
                <div class="max-w-xl">
                    <h2 class="font-mono text-accent text-xs tracking-widest uppercase mb-4">Contact</h2>
                    <p class="text-neutral-300 text-base leading-relaxed max-w-md">
                        Book a time, or email me — I reply personally.
                    </p>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-3 mt-12">
                        @if(filled($bookingUrl))
                            <a href="{{ $bookingHref }}"
                               data-analytics-event="booking_cta_clicked"
                               data-analytics-location="footer"
                               class="btn-accent-fill inline-flex items-center justify-center min-h-11 gap-2 font-semibold px-5 py-3 text-xs uppercase tracking-widest">
                                {{ $bookingLabel }}
                            </a>
                        @endif
                        <div class="flex items-center gap-2">
                            <a href="mailto:{{ $person['email'] }}"
                               data-analytics-event="email_clicked"
                               data-analytics-location="footer"
                               class="inline-flex items-center min-h-11 font-mono text-sm text-neutral-400 hover:text-accent transition-colors">
                                {{ $person['email'] }}
                            </a>
                            <button type="button" data-copy-text="{{ $person['email'] }}" aria-label="Copy email address"
                                    class="relative isolate inline-flex items-center justify-center min-h-11 min-w-11 text-neutral-500 hover:text-accent transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V5a2 2 0 012-2h9a2 2 0 012 2v9a2 2 0 01-2 2h-2M5 8h9a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2v-9a2 2 0 012-2z"/>
                                </svg>
                                <span data-copy-feedback role="status" aria-live="polite"
                                      class="copy-feedback pointer-events-none absolute inline-flex items-center gap-1.5 whitespace-nowrap px-2.5 py-1 font-mono text-caption uppercase tracking-widest opacity-0 transition-opacity duration-200">
                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Copied to clipboard
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <nav class="shrink-0" aria-label="Site">
                    <h2 class="font-mono text-accent text-xs tracking-widest uppercase mb-3">Explore</h2>
                    <ul class="flex flex-wrap gap-x-5 gap-y-1 font-mono text-sm">
                        <li><a href="/work" class="inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors">Work</a></li>
                        <li><a href="/about" class="inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors">About</a></li>
                        <li><a href="/blog" class="inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors">Writing</a></li>
                        <li><a href="/now" class="inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors">Now</a></li>
                        @unless(request()->routeIs('resume'))
                            <li><a href="/resume" class="inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors">Resume</a></li>
                        @endunless
                        @unless(request()->routeIs('kit'))
                            <li><a href="/kit" class="inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors">Recruiter kit</a></li>
                        @endunless
                        @unless(request()->routeIs('lead'))
                            <li><a href="/lead" class="inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors">How I run delivery</a></li>
                        @endunless
                    </ul>
                </nav>
            </div>
        @endif
        <div @class([
            'pt-10 border-t border-neutral-800/50 flex flex-col sm:flex-row sm:items-center justify-between gap-5',
            'mt-24' => $isHome,
            'mt-12' => ! $isHome,
        ])>
            <p class="font-display {{ $isHome ? 'text-3xl' : 'text-2xl' }} tracking-widest text-neutral-500">{{ $person['name'] }}</p>
            <p class="font-mono text-xs text-neutral-400">{{ $person['location'] }} &nbsp;·&nbsp; {{ $person['job_title'] }} &nbsp;·&nbsp; 20+ Years</p>
        </div>
        <div class="mt-8 flex sm:justify-end">
            <p class="surface-chip inline-flex flex-wrap items-center gap-x-1.5 bg-neutral-900/40 px-2.5 py-1 font-mono text-caption uppercase tracking-widest text-neutral-500">
                Built with Laravel {{ \App\Support\Stack::laravelVersion() }} &middot; Tailwind CSS {{ \App\Support\Stack::tailwindVersion() ?? '4' }}
                &middot;
                <a href="https://github.com/karlhillx/karlhill.com/blob/main/scripts/generate-og-images.py"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="text-neutral-500 hover:text-neutral-300 transition-colors">
                    OG cards in Python
                    <span class="sr-only"> (opens in a new tab)</span>
                </a>
            </p>
        </div>
    </div>
</footer>
