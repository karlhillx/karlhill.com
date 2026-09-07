{{-- Tag filter pill row shared by the blog and work indexes.
     `urlFor` is a closure resolving a tag to its filter URL.
     `counts` is an optional map of tag => post/project count.
     `showAll` toggles the leading "All" chip (off for secondary facets). --}}
@props([
    'allUrl',
    'tags' => [],
    'counts' => [],
    'activeTag' => null,
    'urlFor',
    'showAll' => true,
])

@php
    $counts = collect($counts);
    $chipClass = 'tag-filter__chip font-mono text-caption uppercase tracking-widest px-3 py-2.5 min-h-11 inline-flex items-center surface-chip shrink-0';
@endphp

<nav {{ $attributes->merge(['class' => 'tag-filter flex flex-wrap gap-2', 'aria-label' => 'Filter by tag']) }} data-reveal>
    @if($showAll)
        <a href="{{ $allUrl }}"
           @class([
               $chipClass,
               'is-active border-accent text-accent' => ! $activeTag,
               'border-neutral-800 text-neutral-400' => $activeTag,
           ])
           @if(! $activeTag) aria-current="page" @endif>
            All
        </a>
    @endif
    @foreach($tags as $tag)
        <a href="{{ $urlFor($tag) }}"
           @class([
               $chipClass,
               'is-active border-accent text-accent' => $activeTag === $tag,
               'border-neutral-800 text-neutral-400' => $activeTag !== $tag,
           ])
           @if($activeTag === $tag) aria-current="page" @endif>
            {{ $tag }}@if($counts->has($tag))<span class="tabular-nums text-neutral-500 hidden sm:inline">&nbsp;({{ $counts->get($tag) }})</span>@endif
        </a>
    @endforeach
</nav>
