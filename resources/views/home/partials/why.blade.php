<x-site.section id="why" section-label="Why Me" :number="$sectionNumber ?? '01'" label="Why Me">
        <div class="grid md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-neutral-800">
            @foreach(config('site.pillars') as $index => $pillar)
                <div @class([
                    'py-10 md:py-0',
                    'md:pr-12' => $index === 0,
                    'md:px-12' => $index === 1,
                    'md:pl-12' => $index === 2,
                ]) data-reveal>
                    <h3 class="font-display text-5xl text-accent mb-5">{{ $pillar['title'] }}</h3>
                    <p class="text-neutral-400 leading-relaxed text-sm">{{ $pillar['body'] }}</p>
                </div>
            @endforeach
        </div>
</x-site.section>
