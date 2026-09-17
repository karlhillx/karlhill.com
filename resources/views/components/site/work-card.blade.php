@props([
    'title',
    'meta',
    'description',
    'image',
    'tags' => [],
    'logo' => null,
    'imagePosition' => 'object-top',
    'href' => null,
    'slug' => null,
    'external' => false,
    'imageAlt' => null,
    'variant' => 'media',
    'scope' => [],
])

@php
    $titleId = $slug ? 'work-card-title-'.$slug : null;
    $isScope = $variant === 'scope';
    $showCornerLogo = filled($logo['path'] ?? null);
    $cardClass = 'surface-card surface-card-media pointer-lit bg-bg group relative h-[22rem] sm:h-80 lg:h-96 block'
        .($isScope ? ' work-card--scope' : '');
    $cta = $external
        ? 'Visit project'
        : (is_string($href) && str_contains($href, '/work/') ? 'Read case study' : 'View details');
    $imageAlt = $imageAlt ?: 'Screenshot of '.$title;
@endphp

<article
    @if($slug) id="{{ $slug }}" @endif
    {{ $attributes->merge(['class' => $cardClass]) }}
    data-reveal
>
    @if($href)
        <a href="{{ $href }}"
           @if($external) target="_blank" rel="noopener noreferrer" @endif
           @if(! $external && is_string($href) && str_contains($href, '/work/')) data-analytics-event="case_study_opened" @if($slug) data-analytics-project="{{ $slug }}" @endif @endif
           class="absolute inset-0 z-10 rounded-[inherit] focus-visible:outline-none after:content-['']"
           @if($titleId) aria-labelledby="{{ $titleId }}" @else aria-label="{{ $title }}" @endif>
            <span class="sr-only">
                {{ $cta }}: {{ $title }}@if($external) (opens in a new tab)@endif
            </span>
        </a>
    @endif

    @if($isScope)
        <div class="work-card-scope">
            <div class="work-card-scope__grid" aria-hidden="true"></div>
            <div class="work-card-scope__glow" aria-hidden="true"></div>
            @if($scope !== [])
                <dl class="work-card-scope__stats">
                    @foreach($scope as $stat)
                        <div class="work-card-scope__stat">
                            <dt class="work-card-scope__label">{{ $stat['label'] }}</dt>
                            <dd class="work-card-scope__value">{{ $stat['value'] }}</dd>
                        </div>
                    @endforeach
                </dl>
            @endif
        </div>
    @else
        <x-site.responsive-image
            :src="$image"
            :alt="$imageAlt"
            sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw"
            width="960"
            height="720"
            loading="lazy"
            :lqip="false"
            :img-style="$slug ? 'view-transition-name: work-img-'.$slug.'; view-transition-class: card-media' : null"
            img-class="work-parallax work-card-media absolute inset-0 w-full h-full object-cover {{ $imagePosition }}"
            class="contents"
        />
    @endif

    @if($showCornerLogo)
        <div class="work-card-brand absolute top-4 right-4 z-[2]">
            <img src="{{ $logo['path'] }}" alt="" loading="lazy" decoding="async" aria-hidden="true"
                 @if($logo['filter']) style="filter: {{ $logo['filter'] }};" @endif
                 @class([
                     $logo['class'] ?? 'h-8',
                     'w-auto object-contain opacity-80 group-hover:opacity-100 transition-opacity duration-300',
                     'work-card-brand__ink' => ! empty($logo['ink']),
                 ])>
        </div>
    @endif

    <div @class([
        'work-card-panel border-t border-hairline px-5 pt-5 pb-6 rounded-b-2xl',
        'absolute inset-x-0 bottom-0' => ! $isScope,
    ])>
        <p class="font-mono text-caption text-accent uppercase tracking-widest mb-2">{{ $meta }}</p>
        <h3 @if($titleId) id="{{ $titleId }}" @endif class="font-sans font-semibold text-xl tracking-tight text-neutral-100 group-hover:text-accent transition-colors leading-snug">{{ $title }}</h3>
        @if($tags !== [])
            <div class="work-card-tags" aria-hidden="true">
                @foreach($tags as $tag)
                    <span class="surface-chip font-mono text-caption px-2 py-0.5 text-neutral-400">{{ $tag }}</span>
                @endforeach
            </div>
        @endif
        <div class="work-card-details overflow-hidden">
            <p class="text-neutral-400 text-sm leading-relaxed mt-2.5 line-clamp-3 pointer-fine:group-hover:line-clamp-none pointer-fine:group-focus-within:line-clamp-none">{{ $description }}</p>
            @if($href)
                <p class="font-mono text-caption text-accent uppercase tracking-widest mt-4" aria-hidden="true">
                    {{ $cta }}
                    <span class="arrow-nudge inline-block">→</span>
                </p>
            @endif
        </div>
    </div>
</article>
