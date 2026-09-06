@php($arc = config('site.about.arc'))
@php($current = config('site.experience.current'))
@php($nasa = config('site.experience.roles.0'))

<x-site.section id="experience" section-label="Career arc">
        <div class="site-heading-space max-w-3xl" data-reveal>
            <x-site.section-heading :number="$sectionNumber ?? '03'" label="Career arc" class="!mb-5" />
            @if(! empty($arc['intro']))
                <p class="opsz-scroll text-neutral-400 text-base leading-relaxed">
                    {{ $arc['intro'] }}
                </p>
            @endif
        </div>

        <div class="space-y-10 max-w-3xl">
            <div data-reveal>
                <p class="font-mono text-caption text-accent uppercase tracking-widest mb-2">{{ $current['period'] }}</p>
                <h3 class="font-sans font-semibold text-xl sm:text-2xl tracking-tight text-neutral-100 leading-snug">{{ $current['title'] }}</h3>
                <p class="text-neutral-400 text-sm mt-1.5">{{ $current['company'] }} · {{ $current['location'] }}</p>
                <p class="text-neutral-300 text-base leading-relaxed mt-4">{{ $current['summary'] }}</p>
            </div>
            <div data-reveal>
                <p class="font-mono text-caption text-accent uppercase tracking-widest mb-2">{{ $nasa['period'] }}</p>
                <h3 class="font-sans font-semibold text-xl sm:text-2xl tracking-tight text-neutral-100 leading-snug">{{ $nasa['title'] }}</h3>
                <p class="text-neutral-400 text-sm mt-1.5">{{ $nasa['company'] }} · {{ $nasa['location'] }}</p>
                <p class="text-neutral-300 text-base leading-relaxed mt-4">{{ $nasa['summary'] }}</p>
            </div>
        </div>

        @if(! empty($arc['cta_href']))
            <x-site.button variant="secondary" :href="$arc['cta_href']" class="mt-10" data-reveal>
                {{ $arc['cta_label'] ?? 'Full resume' }} →
            </x-site.button>
        @endif
</x-site.section>
