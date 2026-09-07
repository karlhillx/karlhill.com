{{-- Impact stats stay here; certifications & education live on /resume. --}}
<x-site.section id="impact" section-label="Impact" :number="$sectionNumber ?? '05'" label="Impact">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-px bg-neutral-800" data-reveal>
        @foreach(config('site.stats') as $stat)
            <x-site.stat
                padding="px-6 py-10"
                :value="$stat['display']"
                :label="$stat['label']"
                :to="$stat['to']"
                :prefix="$stat['prefix']"
                :suffix="$stat['suffix']"
            />
        @endforeach
    </div>

    <p class="mt-10 text-neutral-400 text-sm leading-relaxed max-w-2xl" data-reveal>
        Certifications, education, and the full stack list live on the
        <a href="/resume#credentials" class="text-accent hover:underline underline-offset-4">resume</a>
        — kept there so this page stays about how I lead and how I run delivery.
    </p>
</x-site.section>
