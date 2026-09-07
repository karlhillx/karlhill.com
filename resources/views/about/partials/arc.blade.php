@php($arc = config('site.about.arc'))
@php($current = config('site.experience.current'))
@php($roles = config('site.experience.roles', []))

{{-- Career arc: narrative chapters from experience — summaries, not a second resume. --}}
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
                @if(! empty($current['summary']))
                    <p class="text-neutral-300 text-base leading-relaxed mt-4">{{ $current['summary'] }}</p>
                @endif
                @if(! empty($current['highlights']))
                    <ul class="mt-5 space-y-2.5 text-neutral-400 text-sm leading-relaxed">
                        @foreach(array_slice($current['highlights'], 0, 3) as $item)
                            <li class="flex gap-3">
                                <span class="text-accent shrink-0" aria-hidden="true">→</span>
                                <span>{!! $item !!}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            @foreach($roles as $role)
                <div data-reveal>
                    <p class="font-mono text-caption text-accent uppercase tracking-widest mb-2">{{ $role['period'] }}</p>
                    <h3 class="font-sans font-semibold text-xl sm:text-2xl tracking-tight text-neutral-100 leading-snug">{{ $role['title'] }}</h3>
                    <p class="text-neutral-400 text-sm mt-1.5">{{ $role['company'] }} · {{ $role['location'] }}</p>
                    @if(! empty($role['summary']))
                        <p class="text-neutral-300 text-base leading-relaxed mt-4">{{ $role['summary'] }}</p>
                    @endif
                    @if(! empty($role['highlights']))
                        <ul class="mt-5 space-y-2.5 text-neutral-400 text-sm leading-relaxed">
                            @foreach(array_slice($role['highlights'], 0, 3) as $item)
                                <li class="flex gap-3">
                                    <span class="text-accent shrink-0" aria-hidden="true">→</span>
                                    <span>{!! $item !!}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>

        @if(! empty($arc['cta_href']))
            <x-site.button variant="secondary" :href="$arc['cta_href']" class="mt-10" data-reveal>
                {{ $arc['cta_label'] ?? 'Full resume' }} →
            </x-site.button>
        @endif
</x-site.section>
