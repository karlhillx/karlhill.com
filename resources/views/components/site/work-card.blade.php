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
])

@php
    $cardClass = 'surface-card surface-card-media pointer-lit bg-bg group relative h-[22rem] sm:h-80 lg:h-96 block';
@endphp

@if($href)
    <a href="{{ $href }}"
       @if($external) target="_blank" rel="noopener noreferrer" @endif
       @if($slug) id="{{ $slug }}" @endif
       {{ $attributes->merge(['class' => $cardClass]) }}
       data-reveal>
@else
    <article @if($slug) id="{{ $slug }}" @endif
             {{ $attributes->merge(['class' => $cardClass]) }}
             data-reveal>
@endif
    <x-site.responsive-image
        :src="$image"
        :alt="'Screenshot of '.$title"
        sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw"
        width="960"
        height="720"
        loading="lazy"
        :lqip="false"
        :img-style="$slug ? 'view-transition-name: work-img-'.$slug.'; view-transition-class: card-media' : null"
        img-class="work-parallax absolute inset-0 w-full h-full object-cover {{ $imagePosition }} opacity-50 group-hover:opacity-70 group-hover:scale-[1.03] transition-[opacity,transform] duration-700 ease-out"
        class="contents"
    />

    <div class="absolute inset-x-0 top-0 h-20 bg-gradient-to-b from-black/60 to-transparent" aria-hidden="true"></div>

    @if($logo)
        <div class="absolute top-4 right-4">
            <img src="{{ $logo['path'] }}" alt="" loading="lazy" decoding="async" aria-hidden="true"
                 @if($logo['filter']) style="filter: {{ $logo['filter'] }};" @endif
                 class="{{ $logo['class'] }} w-auto object-contain opacity-70 group-hover:opacity-100 transition-opacity duration-300">
        </div>
    @endif

    <div class="absolute top-4 left-4 flex flex-wrap gap-1.5">
        @foreach($tags as $tag)
            <span class="surface-chip-overlay font-mono text-[10px] px-2 py-0.5 text-neutral-400">{{ $tag }}</span>
        @endforeach
    </div>

    <div class="absolute inset-x-0 bottom-0 bg-bg/90 backdrop-blur-md border-t border-white/[0.06] px-5 pt-5 pb-6 rounded-b-2xl">
        <p class="font-mono text-[10px] text-accent uppercase tracking-widest mb-2">{{ $meta }}</p>
        <h3 class="font-display text-lg tracking-wide text-white leading-tight">{{ $title }}</h3>
        {{-- Collapse/expand only on hover-capable (fine pointer) devices; touch
             devices always see the description since they can't hover. --}}
        <div class="work-card-details pointer-fine:max-h-0 pointer-fine:group-hover:max-h-52 pointer-fine:group-focus-within:max-h-52 overflow-hidden transition-[max-height] duration-500 ease-out">
            <p class="text-neutral-400 text-xs leading-relaxed mt-3 line-clamp-4 pointer-fine:line-clamp-none">{{ $description }}</p>
            @if($href)
                <p class="font-mono text-[10px] text-accent uppercase tracking-widest mt-4">
                    @if($external)
                        Visit project
                    @elseif(str_contains($href, '/work/'))
                        Read case study
                    @else
                        View details
                    @endif
                    <span class="arrow-nudge inline-block" aria-hidden="true">→</span>
                </p>
            @endif
        </div>
    </div>
@if($href)
    </a>
@else
    </article>
@endif
