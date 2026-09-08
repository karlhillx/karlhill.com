@php($person = config('site.person'))
@php($hero = config('site.hero'))
@php($bookingUrl = config('site.booking.url'))
@php($bookingLabel = config('site.booking.label'))
@php($proof = $hero['proof'] ?? [])

<section id="hero" data-section-label="Top" class="hero relative min-h-[100svh] flex flex-col justify-end overflow-hidden site-gutter">
    {{-- Atmosphere + portrait. No product/screenshot photography in the hero. --}}
    <div class="hero-dot-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <div class="hero-mesh pointer-events-none absolute inset-0" aria-hidden="true">
        <span class="hero-mesh__blob hero-mesh__blob--a"></span>
        <span class="hero-mesh__blob hero-mesh__blob--b"></span>
    </div>

    <div class="relative z-10 site-shell w-full">
        <div class="hero-copy">
            <div class="hero-eyebrow hero-enter" style="animation-delay:80ms">
                {{-- The <picture> is the flex item, so it carries the fixed size and
                     shrink-0; otherwise it collapses when the label wraps and
                     preflight's img { max-width: 100% } squeezes the portrait thin. --}}
                <x-site.responsive-image
                    class="hero-portrait-frame shrink-0"
                    src="/img/webp/profile.webp"
                    :alt="$person['name']"
                    sizes="48px"
                    width="48"
                    height="48"
                    loading="eager"
                    fetchpriority="high"
                    :lqip="false"
                    img-style="view-transition-name: portrait"
                    img-class="hero-portrait rounded-full object-cover ring-2 ring-accent/30"
                />
                <p class="hero-kicker font-mono text-accent uppercase tracking-widest">
                    <span>{{ $person['job_title'] }}</span>
                    <span class="hero-kicker__sep" aria-hidden="true">·</span>
                    <span>{{ $person['employer_display'] ?? $person['employer'] }}</span>
                </p>
            </div>
            <h1 class="hero-title font-display tracking-wide text-white hero-enter" style="animation-delay:160ms">
                <span class="hero-mask"><span class="hero-shine">{{ $hero['headline'] }}</span></span>
            </h1>
            @if(! empty($hero['lede']))
                <p class="hero-lede opsz-scroll text-neutral-200 leading-relaxed hero-enter" style="animation-delay:240ms">
                    {{ $hero['lede'] }}
                </p>
            @endif
            @if($proof !== [])
                <ul class="hero-proof hero-enter" aria-label="At a glance" style="animation-delay:280ms">
                    @foreach($proof as $chip)
                        <li>{{ $chip }}</li>
                    @endforeach
                </ul>
            @endif
            <div class="hero-cta flex flex-wrap items-center gap-x-5 gap-y-3 hero-enter" style="animation-delay:320ms">
                @if(filled($bookingUrl))
                    <a href="/now#book"
                       data-idle-cta
                       data-analytics-event="booking_cta_clicked"
                       data-analytics-location="hero"
                       class="hero-cta-btn btn-accent-fill inline-flex items-center justify-center font-semibold uppercase tracking-widest transition-colors duration-200">
                        {{ $bookingLabel }}
                    </a>
                @else
                    <a href="/#contact"
                       data-idle-cta
                       class="hero-cta-btn btn-accent-fill inline-flex items-center justify-center font-semibold uppercase tracking-widest transition-colors duration-200">
                        Contact
                    </a>
                @endif
                <a href="/kit"
                   data-analytics-event="recruiter_link_opened"
                   data-analytics-location="hero"
                   data-analytics-target="kit"
                   class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-300 hover:text-accent uppercase tracking-widest transition-colors underline-offset-4 hover:underline">
                    Recruiter kit →
                </a>
            </div>
        </div>
    </div>
</section>
