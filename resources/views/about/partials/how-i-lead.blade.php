@php($how = config('site.about.how_i_lead'))

@if(! empty($how['items']))
    <x-site.section id="how-i-lead" section-label="How I lead">
        <div class="site-heading-space max-w-3xl" data-reveal>
            <x-site.section-heading :number="$sectionNumber ?? '01'" :label="$how['title']" class="!mb-5" />
            @if(! empty($how['intro']))
                <p class="opsz-scroll text-neutral-400 text-base leading-relaxed">
                    {{ $how['intro'] }}
                </p>
            @endif
        </div>

        <ol class="lead-principles" data-reveal aria-label="Leadership principles">
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
    </x-site.section>
@endif
