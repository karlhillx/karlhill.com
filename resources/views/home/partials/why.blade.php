<x-site.section id="why" section-label="Why Me" :number="$sectionNumber ?? '01'" label="Why Me">
        <div class="site-pillars divide-y md:divide-y-0 md:divide-x divide-neutral-800">
            @foreach(config('site.pillars') as $pillar)
                <div data-reveal>
                    <h3 class="font-display text-5xl text-accent mb-6">{{ $pillar['title'] }}</h3>
                    <p class="opsz-scroll text-neutral-400 leading-relaxed text-sm">{{ $pillar['body'] }}</p>
                </div>
            @endforeach
        </div>
</x-site.section>
