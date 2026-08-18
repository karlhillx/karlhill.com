<section id="hero" data-section-label="Top" class="hero relative min-h-[100svh] flex flex-col justify-end overflow-hidden site-gutter">
    <div class="hero-dot-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <div class="hero-mesh pointer-events-none absolute inset-0" aria-hidden="true">
        <span class="hero-mesh__blob hero-mesh__blob--a"></span>
        <span class="hero-mesh__blob hero-mesh__blob--b"></span>
    </div>

    <div class="relative z-10 site-shell w-full">
        <div class="hero-copy">
            @php($person = config('site.person'))
            @php($hero = config('site.hero'))
            @php($bookingUrl = config('site.booking.url'))
            @php($bookingLabel = config('site.booking.label'))
            @php($resumePdf = config('site.footer.resume'))
            <div class="hero-eyebrow hero-enter" style="animation-delay:100ms">
                <x-site.responsive-image
                    src="/img/webp/profile.webp"
                    :alt="$person['name']"
                    width="48"
                    height="48"
                    loading="eager"
                    fetchpriority="high"
                    :lqip="false"
                    img-style="view-transition-name: portrait"
                    img-class="hero-portrait rounded-full object-cover ring-2 ring-accent/30 shrink-0"
                />
                <p class="hero-eyebrow-label font-mono text-accent uppercase">
                    <span>{{ $person['job_title'] }}</span>
                    <span class="hero-eyebrow-sep" aria-hidden="true">·</span>
                    <span>{{ $person['employer'] }}</span>
                    <span class="hero-eyebrow-sep" aria-hidden="true">·</span>
                    <span>20+ Years</span>
                </p>
            </div>
            <h1 class="hero-title font-display tracking-wide text-white hero-enter" style="animation-delay:220ms">
                <span class="hero-mask"><span class="hero-shine">{{ $hero['headline'] }}</span></span>
            </h1>
            @if(! empty($hero['subtitle']))
                <p class="hero-subtitle font-mono text-[11px] sm:text-xs text-neutral-400 uppercase tracking-[0.18em] hero-enter" style="animation-delay:280ms">
                    {{ $hero['subtitle'] }}
                </p>
            @endif
            @if(! empty($hero['positioning']))
                <p class="hero-lede opsz-scroll text-neutral-200 leading-relaxed hero-enter" style="animation-delay:320ms">
                    {{ $hero['positioning'] }}
                </p>
            @endif
            {{-- Hire hierarchy: Book → Work → Resume → Contact --}}
            <div class="hero-cta flex flex-wrap items-center hero-enter" style="animation-delay:420ms">
                @if(filled($bookingUrl))
                    <a href="/now#book"
                       data-idle-cta
                       class="hero-cta-btn btn-accent-fill magnetic-btn inline-flex items-center justify-center font-semibold uppercase tracking-widest transition-colors duration-200">
                        {{ $bookingLabel }}
                    </a>
                @else
                    <a href="/now"
                       data-idle-cta
                       class="hero-cta-btn btn-accent-fill magnetic-btn inline-flex items-center justify-center font-semibold uppercase tracking-widest transition-colors duration-200">
                        Now
                    </a>
                @endif
                <a href="/work"
                   class="hero-cta-btn inline-flex items-center justify-center font-semibold uppercase tracking-widest transition-colors duration-200 btn-sweep border border-neutral-700 text-neutral-300">
                    Work
                </a>
                @if(filled($resumePdf))
                    <a href="{{ $resumePdf }}"
                       download="Karl-Hill-Resume.pdf"
                       class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors px-1">
                        Resume PDF
                    </a>
                @endif
                <a href="/#contact"
                   class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors px-1">
                    Contact
                </a>
            </div>
            <a href="{{ filled($bookingUrl) ? '/now#book' : '/now' }}"
               class="hero-availability group flex items-start w-fit max-w-full hero-enter" style="animation-delay:520ms">
                <span class="hero-availability-dot rounded-full bg-green-500 availability-pulse shrink-0" aria-hidden="true"></span>
                <span class="availability-label font-mono text-neutral-400 group-hover:text-accent uppercase transition-colors">
                    {{ $person['availability'] }}
                    <span class="arrow-nudge inline-block" aria-hidden="true">→</span>
                </span>
            </a>
        </div>
    </div>
</section>
