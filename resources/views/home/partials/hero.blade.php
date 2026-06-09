<section id="hero" class="relative min-h-screen flex flex-col justify-end pt-24 pb-16 px-6 overflow-hidden">
    <div class="hero-dot-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <div class="glow-orb-1 pointer-events-none absolute -bottom-32 -left-32 w-[600px] h-[600px] rounded-full"
         style="background: radial-gradient(ellipse, rgba(249,115,22,0.14) 0%, transparent 65%);"
         aria-hidden="true"></div>
    <div class="glow-orb-2 pointer-events-none absolute -top-48 -right-48 w-[500px] h-[500px] rounded-full"
         style="background: radial-gradient(ellipse, rgba(249,115,22,0.09) 0%, transparent 65%);"
         aria-hidden="true"></div>

    <div class="relative z-10 max-w-6xl mx-auto w-full">
        <div class="pt-12">
            @php($person = config('site.person'))
            @php($hero = config('site.hero'))
            <div class="flex items-center gap-4 mb-8 hero-enter" style="animation-delay:100ms">
                <img src="/img/webp/profile.webp" alt="{{ $person['name'] }}"
                     width="48" height="48"
                     loading="eager" fetchpriority="high" decoding="async"
                     style="view-transition-name: portrait"
                     class="w-12 h-12 rounded-full object-cover ring-2 ring-orange-500/30 shrink-0">
                <p class="font-mono text-orange-500 text-xs tracking-widest uppercase">
                    {{ $person['job_title'] }} &nbsp;·&nbsp; 25+ Years
                </p>
            </div>
            <h1 class="font-display text-[clamp(5rem,20vw,15rem)] leading-none tracking-wide text-white mb-6 hero-enter" style="animation-delay:220ms">
                {{ $hero['headline'] }}
            </h1>
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8 mb-12">
                <p class="font-display text-[clamp(1.4rem,3.5vw,2.5rem)] text-neutral-500 tracking-widest uppercase hero-enter" style="animation-delay:360ms">
                    {{ $hero['subtitle'] }}
                </p>
                <p class="text-neutral-400 text-base leading-relaxed max-w-md lg:text-right hero-enter" style="animation-delay:440ms">
                    {{ $hero['bio'] }}
                </p>
            </div>
            <div class="flex flex-wrap gap-4 hero-enter" style="animation-delay:560ms">
                @foreach($hero['cta'] as $link)
                    <a href="{{ $link['url'] }}" @unless(str_starts_with($link['url'], 'mailto:')) target="_blank" rel="noopener noreferrer" @endunless
                       @class([
                           'font-semibold px-8 py-3.5 text-xs uppercase tracking-widest transition-colors duration-200',
                           'bg-orange-500 text-black font-bold hover:bg-orange-400' => $link['primary'] ?? false,
                           'border border-neutral-700 text-neutral-300 hover:border-orange-500 hover:text-orange-500' => ! ($link['primary'] ?? false),
                       ])>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>
            <div class="flex items-center gap-2.5 mt-6 hero-enter" style="animation-delay:640ms">
                <span class="w-2 h-2 rounded-full bg-green-500 availability-pulse" aria-hidden="true"></span>
                <span class="font-mono text-xs text-neutral-500 uppercase tracking-widest">{{ $person['availability'] }}</span>
            </div>
        </div>
    </div>
</section>
