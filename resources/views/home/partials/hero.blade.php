<section id="hero" data-section-label="Top" class="relative min-h-[100svh] flex flex-col justify-end pt-24 pb-16 sm:pt-28 sm:pb-24 overflow-hidden site-gutter">
    <div class="hero-mesh" aria-hidden="true"></div>
    <div class="hero-dot-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <x-site.glow-orb :drift="1" class="-bottom-32 -left-32 w-[600px] h-[600px]" />
    <x-site.glow-orb :drift="2" :strength="0.09" class="-top-48 -right-48 w-[500px] h-[500px]" />

    <div class="relative z-10 site-shell w-full">
        <div class="pt-8 sm:pt-16">
            @php($person = config('site.person'))
            @php($hero = config('site.hero'))
            <div class="flex items-center gap-3 sm:gap-4 mb-7 sm:mb-10 hero-enter" style="animation-delay:100ms">
                <x-site.responsive-image
                    src="/img/webp/profile.webp"
                    :alt="$person['name']"
                    width="48"
                    height="48"
                    loading="eager"
                    fetchpriority="high"
                    :lqip="false"
                    img-style="view-transition-name: portrait"
                    img-class="portrait-glow w-11 h-11 sm:w-12 sm:h-12 rounded-full object-cover ring-2 ring-accent/30 shrink-0"
                />
                <p class="font-mono text-accent text-[10px] sm:text-xs tracking-widest uppercase leading-snug">
                    {{ $person['job_title'] }} &nbsp;·&nbsp; 25+ Years
                </p>
            </div>
            <h1 class="hero-title font-display tracking-wide text-white hero-enter" style="animation-delay:220ms">
                <span class="hero-mask"><span class="hero-shine">{{ $hero['headline'] }}</span></span>
            </h1>
            @if(! empty($hero['positioning']))
                <p class="opsz-scroll text-neutral-200 text-base sm:text-lg md:text-xl leading-relaxed max-w-3xl mb-10 sm:mb-14 hero-enter" style="animation-delay:320ms">
                    {{ $hero['positioning'] }}
                </p>
            @endif
            <div class="flex flex-wrap gap-3 sm:gap-4 hero-enter" style="animation-delay:420ms">
                @foreach($hero['cta'] as $link)
                    @php($isExternal = str_starts_with($link['url'], 'http'))
                    <a href="{{ $link['url'] }}" @if($isExternal) target="_blank" rel="noopener noreferrer" @endif
                       @class([
                           'inline-flex items-center justify-center min-h-11 font-semibold px-6 sm:px-8 py-3 sm:py-3.5 text-xs uppercase tracking-widest transition-colors duration-200',
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
               class="group flex items-start gap-2.5 mt-8 sm:mt-10 w-fit max-w-full hero-enter" style="animation-delay:520ms">
                <span class="w-2 h-2 mt-1.5 rounded-full bg-green-500 availability-pulse shrink-0" aria-hidden="true"></span>
                <span class="availability-label font-mono text-[10px] sm:text-xs text-neutral-500 group-hover:text-accent uppercase tracking-widest transition-colors">{{ $person['availability'] }} <span class="arrow-nudge inline-block" aria-hidden="true">→</span></span>
            </a>
        </div>
    </div>
</section>
