@php($impact = config('site.about.impact', []))
<x-site.section id="impact" section-label="Experience in numbers" :number="$sectionNumber ?? '04'" :label="$impact['heading'] ?? 'Experience in numbers'">
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

    @if(! empty($impact['context']))
        <p class="mt-10 text-neutral-400 text-sm leading-relaxed max-w-2xl" data-reveal>
            {{ $impact['context'] }}
        </p>
    @endif

    <p class="mt-6 text-neutral-400 text-sm leading-relaxed max-w-2xl" data-reveal>
        Education, certifications, and technical skills are on the
        <a href="/resume#credentials" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">resume</a>.
    </p>
</x-site.section>
