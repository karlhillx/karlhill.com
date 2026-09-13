@php($delivery = config('site.about.delivery', []))

@if(! empty($delivery))
    <x-site.section id="delivery" section-label="Engineering delivery" :number="$sectionNumber ?? '02'" :label="$delivery['title'] ?? 'Engineering delivery'">
        <div class="about-lede max-w-3xl" data-reveal>
            @foreach($delivery['intro'] ?? [] as $paragraph)
                <p class="opsz-scroll text-neutral-400 text-base leading-relaxed">
                    {{ $paragraph }}
                </p>
            @endforeach
        </div>

        @if(! empty($delivery['principles']))
            <div class="max-w-3xl mt-8" data-reveal>
                @if(! empty($delivery['principles_lede']))
                    <p class="text-neutral-300 text-base leading-relaxed mb-5">
                        {{ $delivery['principles_lede'] }}
                    </p>
                @endif
                <ul class="space-y-3 text-neutral-400 text-base leading-relaxed">
                    @foreach($delivery['principles'] as $item)
                        <li class="flex gap-3">
                            <span class="text-accent shrink-0" aria-hidden="true">→</span>
                            <span>{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(! empty($delivery['close']))
            <p class="mt-8 text-neutral-300 text-base leading-relaxed max-w-3xl" data-reveal>
                {{ $delivery['close'] }}
            </p>
        @endif

        @if(! empty($delivery['cta_href']))
            <a href="{{ $delivery['cta_href'] }}"
               class="inline-flex items-center min-h-11 mt-6 font-mono text-xs text-accent uppercase tracking-widest hover:underline underline-offset-4"
               data-reveal>
                {{ $delivery['cta_label'] ?? 'Engineering delivery' }} →
            </a>
        @endif
    </x-site.section>
@endif
