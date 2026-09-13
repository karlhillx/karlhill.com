@php($how = config('site.about.leadership'))

@if(! empty($how['items']))
    <x-site.section id="how-i-lead" section-label="Technical leadership" :number="$sectionNumber ?? '01'" :label="$how['title'] ?? 'Technical leadership'">
        <div class="about-lede max-w-3xl mb-8 sm:mb-10" data-reveal>
            @foreach($how['intro'] ?? [] as $paragraph)
                <p class="opsz-scroll text-neutral-400 text-base leading-relaxed">
                    {{ $paragraph }}
                </p>
            @endforeach
        </div>

        <ol class="lead-principles" data-reveal aria-label="Technical leadership">
            @foreach($how['items'] as $index => $item)
                <li class="lead-principles__item">
                    <span class="lead-principles__num" aria-hidden="true">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    <div class="lead-principles__body">
                        @if(is_array($item) && ! empty($item['title']))
                            <h3 class="lead-principles__title">{{ $item['title'] }}</h3>
                            <p class="lead-principles__text">{{ $item['body'] ?? '' }}</p>
                        @else
                            <p class="lead-principles__text">{{ is_array($item) ? ($item['body'] ?? '') : $item }}</p>
                        @endif
                    </div>
                </li>
            @endforeach
        </ol>

        @if(! empty($how['note']))
            <p class="mt-8 sm:mt-10 text-neutral-400 text-base leading-relaxed max-w-3xl" data-reveal>
                {{ $how['note'] }}
            </p>
        @endif
    </x-site.section>
@endif
