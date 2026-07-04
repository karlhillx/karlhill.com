{{-- Tag filter pill row shared by the blog and work indexes.
     `urlFor` is a closure resolving a tag to its filter URL. --}}
@props([
    'allUrl',
    'tags' => [],
    'activeTag' => null,
    'urlFor',
])

<div {{ $attributes->merge(['class' => 'flex flex-wrap gap-2']) }} data-reveal>
    <a href="{{ $allUrl }}"
       @class([
           'font-mono text-[10px] uppercase tracking-widest px-3 py-1.5 surface-chip transition-colors',
           'border-accent text-accent' => ! $activeTag,
           'border-neutral-800 text-neutral-500 hover:border-accent hover:text-accent' => $activeTag,
       ])>
        All
    </a>
    @foreach($tags as $tag)
        <a href="{{ $urlFor($tag) }}"
           @class([
               'font-mono text-[10px] uppercase tracking-widest px-3 py-1.5 surface-chip transition-colors',
               'border-accent text-accent' => $activeTag === $tag,
               'border-neutral-800 text-neutral-500 hover:border-accent hover:text-accent' => $activeTag !== $tag,
           ])>
            {{ $tag }}
        </a>
    @endforeach
</div>
