{{-- Horizontal chapter strip for an essay series. --}}
@props([
    'series',
    'currentIndex' => null,
])

@if(! empty($series['posts']) && count($series['posts']) > 0)
    @php($total = count($series['posts']))
    <div {{ $attributes->merge(['class' => 'series-chapters']) }} data-reveal data-series-chapters>
        @if($currentIndex !== null)
            <p class="series-chapters__progress font-mono text-[10px] uppercase tracking-widest text-neutral-500 mb-3 md:hidden">
                Part <span class="text-accent tabular-nums">{{ $currentIndex + 1 }}</span>
                <span class="text-neutral-600">/</span>
                <span class="tabular-nums">{{ $total }}</span>
                <span class="text-neutral-600">·</span>
                {{ $series['title'] }}
            </p>
        @else
            <p class="series-chapters__progress font-mono text-[10px] uppercase tracking-widest text-neutral-500 mb-3 md:hidden">
                {{ $total }} chapters
                <span class="text-neutral-600">·</span>
                Swipe to browse
            </p>
        @endif

        <ol class="series-chapters__track" aria-label="{{ $series['title'] }} chapters">
            @foreach($series['posts'] as $index => $seriesPost)
                @php($isCurrent = $currentIndex !== null && $index === $currentIndex)
                <li @class([
                    'series-chapters__item',
                    'is-current' => $isCurrent,
                    'is-past' => $currentIndex !== null && $index < $currentIndex,
                ])>
                    @if($isCurrent)
                        <span class="series-chapters__card" aria-current="step">
                            <span class="series-chapters__num" aria-hidden="true">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="series-chapters__meta md:hidden">Part {{ $index + 1 }} of {{ $total }}</span>
                            <span class="series-chapters__title">{{ $seriesPost->title }}</span>
                        </span>
                    @else
                        <a href="{{ $seriesPost->url() }}" class="series-chapters__card">
                            <span class="series-chapters__num" aria-hidden="true">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="series-chapters__meta md:hidden">Part {{ $index + 1 }} of {{ $total }}</span>
                            <span class="series-chapters__title">{{ $seriesPost->title }}</span>
                        </a>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
@endif
