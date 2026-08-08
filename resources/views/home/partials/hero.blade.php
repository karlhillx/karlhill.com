<section id="hero" data-section-label="Top" class="hero relative min-h-[100svh] flex flex-col justify-end overflow-hidden site-gutter">
    <div class="hero-dot-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>

    <div class="relative z-10 site-shell w-full">
        <div class="hero-copy">
            @php($person = config('site.person'))
            @php($hero = config('site.hero'))
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
                    <span>20+ Years</span>
                </p>
            </div>
            <h1 class="hero-title font-display tracking-wide text-white hero-enter" style="animation-delay:220ms">
                <span class="hero-mask"><span class="hero-shine">{{ $hero['headline'] }}</span></span>
            </h1>
            @if(! empty($hero['positioning']))
                <p class="hero-lede opsz-scroll text-neutral-200 leading-relaxed hero-enter" style="animation-delay:320ms">
                    {{ $hero['positioning'] }}
                </p>
            @endif
            <div class="hero-cta flex flex-wrap hero-enter" style="animation-delay:420ms">
                @foreach($hero['cta'] as $link)
                    @php($isExternal = str_starts_with($link['url'], 'http'))
                    <a href="{{ $link['url'] }}" @if($isExternal) target="_blank" rel="noopener noreferrer" @endif
                       @class([
                           'hero-cta-btn inline-flex items-center justify-center font-semibold uppercase tracking-widest transition-colors duration-200',
                           'bg-accent text-black font-bold hover:bg-accent/80' => $link['primary'] ?? false,
                           'btn-sweep border border-neutral-700 text-neutral-300' => ! ($link['primary'] ?? false),
                       ])>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>
            @php($bookingUrl = config('site.booking.url'))
            <a href="{{ filled($bookingUrl) ? $bookingUrl : '/now' }}"
               @if(filled($bookingUrl)) target="_blank" rel="noopener noreferrer" @endif
               class="hero-availability group flex items-start w-fit max-w-full hero-enter" style="animation-delay:520ms">
                <span class="hero-availability-dot rounded-full bg-green-500 availability-pulse shrink-0" aria-hidden="true"></span>
                <span class="availability-label font-mono text-neutral-500 group-hover:text-accent uppercase transition-colors">
                    {{ $person['availability'] }}
                    <span class="arrow-nudge inline-block" aria-hidden="true">→</span>
                </span>
            </a>
        </div>
    </div>
</section>
