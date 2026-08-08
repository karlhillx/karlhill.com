<section id="impact" data-section-label="Impact" aria-label="Career impact at a glance" class="site-section site-section--soft">
    <div class="site-shell">
        <h2 class="inline-flex items-center gap-3 font-mono text-accent text-xs tracking-widest uppercase site-heading-space" data-reveal>
            <span aria-hidden="true" class="section-accent-line h-px w-8 bg-accent/60 shrink-0"></span>
            <span>04 — Impact</span>
        </h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-px bg-neutral-800/50">
            @foreach(config('site.stats') as $stat)
                <x-site.stat
                    padding="px-6 py-12 sm:py-14"
                    data-reveal
                    style="transition-delay: {{ $loop->index * 80 }}ms"
                    :value="$stat['display']"
                    :label="$stat['label']"
                    :to="$stat['to']"
                    :prefix="$stat['prefix']"
                    :suffix="$stat['suffix']"
                    value-class="text-5xl sm:text-6xl mb-3"
                    label-class="text-neutral-400"
                />
            @endforeach
        </div>
    </div>
</section>
