@php($lead = config('site.lead'))

@if(! empty($lead['sections']))
    <x-site.section id="delivery" section-label="Engineering delivery" :number="$sectionNumber ?? '02'" label="Engineering delivery" class="scroll-mt-28">
        <div class="max-w-3xl" data-reveal>
            @if(! empty($lead['lede']))
                <p class="opsz-scroll text-neutral-400 text-base leading-relaxed mb-4">
                    {{ $lead['lede'] }}
                </p>
            @endif
            @if(! empty($lead['why']))
                <p class="text-neutral-300 text-base leading-relaxed">
                    {{ $lead['why'] }}
                </p>
            @endif
        </div>
    </x-site.section>

    @foreach($lead['sections'] as $section)
        <section id="{{ $section['id'] }}" class="site-section border-t border-neutral-800/50 scroll-mt-24" aria-labelledby="delivery-{{ $section['id'] }}-heading">
            <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
                <h3 id="delivery-{{ $section['id'] }}-heading" class="font-sans font-semibold text-xl sm:text-2xl tracking-tight leading-snug text-neutral-100">
                    {{ $section['title'] }}
                </h3>
                <div class="max-w-2xl">
                    @if(! empty($section['intro']))
                        <p class="text-neutral-400 text-base leading-relaxed mb-6">{{ $section['intro'] }}</p>
                    @endif
                    <ul class="lead-packet-list">
                        @foreach($section['items'] as $item)
                            <li class="lead-packet-list__item">
                                @if(is_array($item))
                                    <p class="lead-packet-list__title">{{ $item['title'] }}</p>
                                    <p class="lead-packet-list__body">{{ $item['body'] ?? '' }}</p>
                                @else
                                    <p class="lead-packet-list__body">{{ $item }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>
    @endforeach

    @if(! empty($lead['links']))
        <section class="site-section border-t border-neutral-800/50" aria-label="Related pages">
            <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
                <p class="font-mono text-accent text-xs tracking-widest uppercase pt-1">Continue</p>
                <div class="max-w-2xl">
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-3">
                        @foreach($lead['links'] as $link)
                            <a href="{{ $link['href'] }}"
                               @class([
                                   'inline-flex items-center min-h-11 font-mono text-xs uppercase tracking-widest',
                                   'text-accent hover:underline underline-offset-4' => ! empty($link['emphasis']),
                                   'text-neutral-400 hover:text-accent transition-colors' => empty($link['emphasis']),
                               ])>
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
@endif
