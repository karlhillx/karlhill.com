{{-- Tag filter pill row shared by the blog and work indexes.
     `urlFor` is a closure resolving a tag to its filter URL.
     `counts` is an optional map of tag => post/project count. --}}
@props([
    'allUrl',
    'tags' => [],
    'counts' => [],
    'activeTag' => null,
    'urlFor',
])

@php
    $counts = collect($counts);
@endphp

<div {{ $attributes->merge(['class' => 'tag-filter flex flex-wrap gap-2']) }} data-reveal>
    <a href="{{ $allUrl }}"
       @class([
           'tag-filter__chip font-mono text-[10px] uppercase tracking-widest px-3 py-1.5 surface-chip',
           'is-active border-accent text-accent' => ! $activeTag,
           'border-neutral-800 text-neutral-500' => $activeTag,
       ])>
        All
    </a>
    @foreach($tags as $tag)
        <a href="{{ $urlFor($tag) }}"
           @class([
               'tag-filter__chip font-mono text-[10px] uppercase tracking-widest px-3 py-1.5 surface-chip',
               'is-active border-accent text-accent' => $activeTag === $tag,
               'border-neutral-800 text-neutral-500' => $activeTag !== $tag,
           ])>
            {{ $tag }}@if($counts->has($tag))&nbsp;<span class="tabular-nums opacity-60">({{ $counts->get($tag) }})</span>@endif
        </a>
    @endforeach
</div>
