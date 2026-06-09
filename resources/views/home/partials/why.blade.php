<section id="why" class="py-28 px-6 border-t border-neutral-800">
    <div class="max-w-6xl mx-auto">
        <x-site.section-heading number="01" label="Why Me" />
        <div class="grid md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-neutral-800">
            @foreach(config('site.pillars') as $index => $pillar)
                <div @class([
                    'py-10 md:py-0',
                    'md:pr-12' => $index === 0,
                    'md:px-12' => $index === 1,
                    'md:pl-12' => $index === 2,
                ]) data-reveal>
                    <p class="font-display text-6xl text-orange-500 mb-5">{{ $pillar['title'] }}</p>
                    <p class="text-neutral-400 leading-relaxed text-sm">{{ $pillar['body'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
