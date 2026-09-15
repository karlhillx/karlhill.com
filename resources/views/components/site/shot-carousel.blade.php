@props([
    'slides' => [],
    'sizes' => '(min-width: 832px) 48rem, 100vw',
    'transitionName' => null,
])

@php
    $slides = collect($slides)
        ->map(function ($slide) {
            if (is_string($slide)) {
                return ['src' => $slide, 'alt' => '', 'label' => null];
            }

            return $slide;
        })
        ->filter(fn ($slide) => filled($slide['src'] ?? null))
        ->values();
    $count = $slides->count();
@endphp

@if($count > 0)
    <div @class(['shot-carousel', 'shot-carousel--multi' => $count > 1])>
        <div class="shot-carousel__scroller"
             @if($count > 1)
                 tabindex="0"
                 aria-label="Case study screenshots, {{ $count }} slides"
             @endif>
            @foreach($slides as $index => $slide)
                <figure class="shot-carousel__slide">
                    <button type="button"
                            class="case-study-media__trigger group"
                            data-lightbox-open
                            data-lightbox-src="{{ $slide['src'] }}"
                            data-lightbox-alt="{{ $slide['alt'] ?? '' }}">
                        <x-site.responsive-image
                            :src="$slide['src']"
                            :alt="$slide['alt'] ?? ''"
                            :sizes="$sizes"
                            :loading="$index === 0 ? 'eager' : 'lazy'"
                            :fetchpriority="$index === 0 ? 'high' : null"
                            :img-style="$index === 0 && $transitionName ? $transitionName : null"
                            img-class="case-study-media__img w-full aspect-[16/9] object-cover {{ $slide['position'] ?? 'object-center' }} transition-[opacity,filter] duration-300 group-hover:opacity-90"
                        />
                        <span class="case-study-media__zoom font-mono text-caption uppercase tracking-widest">
                            Expand <span aria-hidden="true">↗</span>
                        </span>
                    </button>
                    @if($count > 1 && filled($slide['label'] ?? null))
                        <figcaption class="shot-carousel__caption font-mono text-caption uppercase tracking-widest">
                            {{ $slide['label'] }}
                        </figcaption>
                    @endif
                </figure>
            @endforeach
        </div>
    </div>
@endif
