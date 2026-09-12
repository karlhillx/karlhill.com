@php($impact = config('site.about.impact', []))
<x-site.section id="impact" section-label="Selected impact" :number="$sectionNumber ?? '03'" :label="$impact['heading'] ?? 'Selected impact'">
    @if(! empty($impact['items']))
        <ul class="max-w-3xl space-y-4 text-neutral-300 text-base leading-relaxed" data-reveal>
            @foreach($impact['items'] as $item)
                <li class="flex gap-3">
                    <span class="text-accent shrink-0" aria-hidden="true">→</span>
                    <span>{{ $item }}</span>
                </li>
            @endforeach
        </ul>
    @endif

    <p class="mt-8 text-neutral-400 text-sm leading-relaxed max-w-2xl" data-reveal>
        Education, certifications, and technical skills are on the
        <a href="/resume#credentials" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">resume</a>.
    </p>
</x-site.section>
