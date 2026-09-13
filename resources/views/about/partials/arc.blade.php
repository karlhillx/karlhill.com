@php($career = config('site.about.career', []))

<x-site.section id="experience" section-label="Career" :number="$sectionNumber ?? '03'" :label="$career['title'] ?? 'Career'">
        @if(! empty($career['intro']))
            <div class="site-heading-space max-w-3xl" data-reveal>
                <p class="opsz-scroll text-neutral-400 text-base leading-relaxed">
                    {{ $career['intro'] }}
                </p>
            </div>
        @endif

        <div class="space-y-10 max-w-3xl">
            @foreach($career['roles'] ?? [] as $role)
                <div data-reveal>
                    <h3 class="font-sans font-semibold text-xl sm:text-2xl tracking-tight text-neutral-100 leading-snug">{{ $role['title'] }}</h3>
                    @if(! empty($role['org']))
                        <p class="text-neutral-400 text-sm mt-1.5">{{ $role['org'] }}</p>
                    @endif
                    @if(! empty($role['summary']))
                        <p class="text-neutral-300 text-base leading-relaxed mt-4">{{ $role['summary'] }}</p>
                    @endif
                    @if(! empty($role['highlights']))
                        <ul class="mt-5 space-y-2.5 text-neutral-400 text-sm leading-relaxed">
                            @foreach($role['highlights'] as $item)
                                <li class="flex gap-3">
                                    <span class="text-accent shrink-0" aria-hidden="true">→</span>
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach

            @if(! empty($career['earlier']))
                <div data-reveal>
                    <h3 class="font-sans font-semibold text-xl sm:text-2xl tracking-tight text-neutral-100 leading-snug">{{ $career['earlier']['title'] }}</h3>
                    <p class="text-neutral-300 text-base leading-relaxed mt-4">{{ $career['earlier']['body'] }}</p>
                </div>
            @endif
        </div>

        @if(! empty($career['cta_note']))
            <p class="mt-8 text-neutral-400 text-sm leading-relaxed max-w-2xl" data-reveal>
                {{ $career['cta_note'] }}
            </p>
        @endif

        @if(! empty($career['cta_href']))
            <x-site.button variant="secondary" :href="$career['cta_href']" class="mt-6" data-reveal>
                {{ $career['cta_label'] ?? 'Full resume' }} →
            </x-site.button>
        @endif
</x-site.section>
