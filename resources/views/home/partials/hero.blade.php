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
                {{-- The <picture> is the flex item, so it carries the fixed size and
                     shrink-0; otherwise it collapses when the label wraps and
                     preflight's img { max-width: 100% } squeezes the portrait thin. --}}
                <x-site.responsive-image
                    class="hero-portrait-frame shrink-0"
                    src="/img/webp/profile.webp"
                    :alt="$person['name']"
                    width="48"
                    height="48"
                    loading="eager"
                    fetchpriority="high"
                    :lqip="false"
                    img-style="view-transition-name: portrait"
                    img-class="hero-portrait rounded-full object-cover ring-2 ring-accent/30"
                />
                <p class="hero-eyebrow-label font-mono text-accent uppercase">
                    <span>{{ $person['job_title'] }}</span>
                    <span class="hero-eyebrow-sep" aria-hidden="true">·</span>
                    <span>{{ $person['employer_display'] ?? $person['employer'] }}</span>
                    <span class="hero-eyebrow-sep" aria-hidden="true">·</span>
                    <span>20+ Years</span>
                </p>
            </div>
            <h1 class="hero-title font-display tracking-wide text-white hero-enter" style="animation-delay:220ms">
                <span class="hero-mask"><span class="hero-shine">{{ $hero['headline'] }}</span></span>
            </h1>
            @if(! empty($hero['arc']))
                <nav class="hero-arc hero-enter" aria-label="Career arc" style="animation-delay:280ms">
                    @foreach($hero['arc'] as $i => $stop)
                        @if($i > 0)
                            <span class="hero-arc__sep" aria-hidden="true">→</span>
                        @endif
                        <a href="{{ $stop['href'] }}" class="hero-arc__stop">
                            <span class="hero-arc__label">{{ $stop['label'] }}</span>
                            @if(! empty($stop['meta']))
                                <span class="hero-arc__meta">{{ $stop['meta'] }}</span>
                            @endif
                        </a>
                    @endforeach
                </nav>
            @endif
            @if(! empty($hero['positioning']))
                <p class="hero-lede opsz-scroll text-neutral-200 leading-relaxed hero-enter" style="animation-delay:320ms">
                    {{ $hero['positioning'] }}
                </p>
            @endif
            {{-- Hire hierarchy: Book → Work; resume / contact stay secondary. --}}
            <div class="hero-cta flex flex-wrap items-center gap-x-4 gap-y-3 hero-enter" style="animation-delay:420ms">
                @if(filled($bookingUrl))
                    <a href="/now#book"
                       data-idle-cta
                       data-analytics-event="booking_cta_clicked"
                       data-analytics-location="hero"
                       class="hero-cta-btn btn-accent-fill magnetic-btn inline-flex items-center justify-center font-semibold uppercase tracking-widest transition-colors duration-200">
                        {{ $bookingLabel }}
                    </a>
                @else
                    <a href="/now"
                       data-idle-cta
                       data-analytics-event="booking_cta_clicked"
                       data-analytics-location="hero"
                       class="hero-cta-btn btn-accent-fill magnetic-btn inline-flex items-center justify-center font-semibold uppercase tracking-widest transition-colors duration-200">
                        Now
                    </a>
                @endif
                <a href="/work"
                   class="hero-cta-btn inline-flex items-center justify-center font-semibold uppercase tracking-widest transition-colors duration-200 btn-sweep border border-neutral-700 text-neutral-300">
                    Work
                </a>
                <div class="inline-flex items-center gap-3 font-mono text-xs text-neutral-400 pl-1">
                    @if(filled($resumePdf))
                        <a href="{{ $resumePdf }}"
                           download="Karl-Hill-Resume.pdf"
                           data-analytics-event="resume_downloaded"
                           data-analytics-location="hero"
                           class="inline-flex items-center min-h-11 hover:text-accent uppercase tracking-widest transition-colors">
                            Resume PDF
                        </a>
                    @endif
                    <span class="text-neutral-500" aria-hidden="true">·</span>
                    <a href="/#contact"
                       class="inline-flex items-center min-h-11 hover:text-accent uppercase tracking-widest transition-colors">
                        Contact
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
