@props([
    'variant' => 'default',
    'section' => null,
])

@php
    $person = config('site.person');
    $footer = config('site.footer');
    $isHome = $variant === 'home';
@endphp

<footer id="contact" @if($isHome) data-section-label="Contact" @endif @class(['relative z-10 border-t border-neutral-800/50 site-footer', 'site-footer--home' => $isHome])>
    <div class="site-shell">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-14 lg:gap-16">
            <div class="max-w-xl" @if($isHome) data-reveal @endif>
                @if($isHome && $section)
                    <h2 class="font-mono text-accent text-xs tracking-widest uppercase mb-8">{{ $section }} — Contact</h2>
                @else
                    <h2 class="font-mono text-accent text-xs tracking-widest uppercase mb-5">Get in Touch</h2>
                @endif
                <p @class([
                    'font-display leading-none tracking-wide text-balance',
                    'text-[clamp(3rem,8vw,6rem)] mb-6 sm:mb-7' => $isHome,
                    'text-[clamp(2rem,5vw,3.5rem)] mb-4 sm:mb-5' => ! $isHome,
                ])>
                    {!! nl2br(e($footer['headline'])) !!}
                </p>
                <p class="text-neutral-400 text-sm leading-relaxed max-w-sm">
                    {{ $footer['body'] }}
                </p>

                <x-site.contact-form id-prefix="contact" :return-to="url()->current()" />
            </div>
            <div class="flex flex-col gap-4 {{ $isHome ? 'lg:pt-16' : 'lg:pt-10' }} shrink-0" @if($isHome) data-reveal @endif>
                <p class="font-mono text-[10px] text-neutral-400 uppercase tracking-widest">Prefer to reach me directly?</p>
                <div class="flex items-center gap-3">
                    <a href="mailto:{{ $person['email'] }}"
                       class="flex items-center gap-4 font-mono text-sm text-neutral-400 hover:text-accent transition-colors group">
                        <span class="text-accent text-base arrow-nudge" aria-hidden="true">→</span>
                        {{ $person['email'] }}
                    </a>
                    <button type="button" data-copy-text="{{ $person['email'] }}" aria-label="Copy email address"
                            class="relative inline-flex items-center justify-center min-h-11 min-w-11 text-neutral-500 hover:text-accent transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V5a2 2 0 012-2h9a2 2 0 012 2v9a2 2 0 01-2 2h-2M5 8h9a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2v-9a2 2 0 012-2z"/>
                        </svg>
                        <span data-copy-feedback role="status" aria-live="polite"
                              class="surface-chip-accent pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 inline-flex items-center gap-1.5 whitespace-nowrap px-2.5 py-1 font-mono text-[10px] text-accent uppercase tracking-widest opacity-0 transition-opacity duration-200 shadow-lg shadow-black/40">
                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Copied to clipboard
                        </span>
                    </button>
                </div>
                @if(filled(config('site.booking.url')))
                    <a href="{{ request()->routeIs('now') ? '#book' : '/now#book' }}"
                       class="btn-sweep inline-flex items-center gap-3 border border-accent/40 text-accent font-semibold px-6 py-3 text-xs uppercase tracking-widest w-fit">
                        {{ config('site.booking.label') }}
                        <span aria-hidden="true">→</span>
                    </a>
                @endif

                @unless(request()->routeIs('resume'))
                    <a href="/resume"
                       class="inline-flex items-center min-h-11 font-mono text-sm text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors w-fit">
                        Resume
                    </a>
                @endunless

                @unless(request()->routeIs('kit'))
                    <a href="/kit"
                       class="inline-flex items-center min-h-11 font-mono text-sm text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors w-fit">
                        Recruiter kit
                    </a>
                @endunless

                <x-site.social-links />
            </div>
            <nav class="shrink-0" aria-label="Site">
                <h2 class="font-mono text-accent text-xs tracking-widest uppercase mb-4">Explore</h2>
                <ul class="space-y-1 font-mono text-sm">
                    <li><a href="/work" class="inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors">Work</a></li>
                    <li><a href="/about" class="inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors">About</a></li>
                    <li><a href="/blog" class="inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors">Writing</a></li>
                    <li><a href="/now" class="inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors">Now</a></li>
                    <li><a href="/resume" class="inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors">Resume</a></li>
                    <li><a href="/kit" class="inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors">Recruiter kit</a></li>
                </ul>
            </nav>
        </div>
        <div @class([
            'pt-10 border-t border-neutral-800/50 flex flex-col sm:flex-row sm:items-center justify-between gap-5',
            'mt-24' => $isHome,
            'mt-16' => ! $isHome,
        ])>
            <p class="font-display {{ $isHome ? 'text-3xl' : 'text-2xl' }} tracking-widest text-neutral-500">{{ $person['name'] }}</p>
            <p class="font-mono text-xs text-neutral-400">{{ $person['location'] }} &nbsp;·&nbsp; {{ $person['job_title'] }} &nbsp;·&nbsp; 20+ Years</p>
        </div>
        <div class="mt-8 flex sm:justify-end">
            <p class="surface-chip inline-flex items-center bg-neutral-900/40 px-2.5 py-1 font-mono text-[10px] uppercase tracking-widest text-neutral-700 hover:text-neutral-500 hover:border-neutral-700/80 transition-colors duration-300">
                Built with Laravel {{ \App\Support\Stack::laravelVersion() }} &middot; Tailwind CSS {{ \App\Support\Stack::tailwindVersion() ?? '4' }}
            </p>
        </div>
    </div>
</footer>
