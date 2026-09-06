@props([
    'items' => [],
    'groups' => null,
])

@php
    $hasGroups = ! empty($groups);
    $totalCount = $hasGroups
        ? collect($groups)->sum(fn ($g) => count($g['items'] ?? []))
        : count($items);
@endphp

@if($totalCount >= 2)
    <nav id="article-toc" {{ $attributes->merge(['class' => 'article-toc']) }} aria-label="On this page">
        <div class="article-toc__header pb-2 mb-3.5 border-b border-neutral-800/80 flex items-center justify-between">
            <p class="font-mono text-caption text-accent uppercase tracking-widest font-semibold">On this page</p>
            <span class="font-mono text-[10px] text-neutral-500 uppercase tracking-wider" aria-hidden="true">Index</span>
        </div>

        @if($hasGroups)
            <div class="article-toc__groups space-y-5">
                @foreach($groups as $group)
                    @if(! empty($group['items']))
                        <div class="article-toc__group">
                            @if(! empty($group['label']))
                                <p class="font-mono text-[10px] uppercase tracking-widest text-neutral-500 font-semibold mb-1.5">
                                    {{ $group['label'] }}
                                </p>
                            @endif
                            <ol class="article-toc-list">
                                @foreach($group['items'] as $item)
                                    <li class="article-toc-item">
                                        <a href="#{{ $item['id'] }}"
                                           data-toc-link
                                           class="article-toc-link">
                                            {{ $item['text'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <ol class="article-toc-list">
                @foreach($items as $item)
                    <li @class([
                        'article-toc-item',
                        'article-toc-item--child' => ($item['level'] ?? 2) === 3,
                    ])>
                        <a href="#{{ $item['id'] }}"
                           data-toc-link
                           class="article-toc-link">
                            {{ $item['text'] }}
                        </a>
                    </li>
                @endforeach
            </ol>
        @endif
    </nav>
@endif
