<x-site.section id="why" section-label="Why Me" :number="$sectionNumber ?? '02'" label="Why Me">
        <div class="site-pillars divide-y md:divide-y-0 md:divide-x divide-neutral-800">
            @foreach(config('site.pillars') as $pillar)
                <div data-reveal>
                    <h3 class="font-display text-5xl text-accent mb-6">{{ $pillar['title'] }}</h3>
                    <p class="opsz-scroll text-neutral-400 leading-relaxed text-base">{{ $pillar['body'] }}</p>
                </div>
            @endforeach
        </div>
        <div class="flex flex-wrap items-center gap-x-5 gap-y-2 mt-10 sm:mt-12" data-reveal>
            <a href="/about#how-i-lead"
               class="inline-flex items-center min-h-11 font-mono text-xs text-accent uppercase tracking-widest hover:underline underline-offset-4">
                How I lead →
            </a>
            <a href="/lead"
               class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                How I run delivery
            </a>
            <a href="/work"
               class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                Selected work
            </a>
        </div>
</x-site.section>
