@php($numbers = config('site.about.numbers', []))

<x-site.section id="impact" section-label="Experience in numbers" :number="$sectionNumber ?? '04'" :label="$numbers['heading'] ?? 'Experience in numbers'">
    @if(! empty($numbers['items']))
        <div class="grid grid-cols-2 md:grid-cols-3 gap-px bg-neutral-800/50" data-reveal>
            @foreach($numbers['items'] as $stat)
                <x-site.stat
                    padding="px-5 py-6 sm:py-8"
                    :value="$stat['display']"
                    :label="$stat['label']"
                    :to="$stat['to'] ?? null"
                    :prefix="$stat['prefix'] ?? ''"
                    :suffix="$stat['suffix'] ?? ''"
                    value-class="text-4xl sm:text-5xl mb-3"
                    label-class="text-neutral-400"
                />
            @endforeach
        </div>
    @endif
</x-site.section>
