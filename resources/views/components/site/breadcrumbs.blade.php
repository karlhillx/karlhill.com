@props([
    'items' => [],
])

@if(count($items) > 0)
    @php
        $siteUrl = \App\Support\PageMeta::siteUrl();
        $listItems = collect($items)->values()->map(fn ($item, $index) => array_filter([
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $item['label'],
            'item' => isset($item['url']) ? $siteUrl.$item['url'] : null,
        ]))->all();
    @endphp
    <script type="application/ld+json" nonce="{{ Vite::cspNonce() }}">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems,
        ], JSON_UNESCAPED_SLASHES) !!}
    </script>
    {{-- Default bottom margin only when the caller hasn't set one, so the two
         utilities never both land on the element and fight in cascade order. --}}
    <nav aria-label="Breadcrumb" {{ $attributes->class(['mb-8' => ! str_contains($attributes->get('class', ''), 'mb-')]) }}>
        <ol class="flex flex-wrap items-center gap-2 eyebrow eyebrow--faint">
            @foreach($items as $index => $item)
                @if($index > 0)
                    <li aria-hidden="true" class="text-neutral-500">/</li>
                @endif
                <li @if($loop->last) aria-current="page" class="min-w-0" @endif>
                    @if(! $loop->last && ($item['url'] ?? null))
                        <a href="{{ $item['url'] }}" class="hover:text-accent transition-colors">{{ $item['label'] }}</a>
                    @else
                        {{-- The current page's own heading follows, so a long title is clipped
                             here rather than wrapping the trail onto a second line. --}}
                        <span @class(['text-neutral-400 block max-w-[28ch] truncate' => $loop->last])
                              @if($loop->last) title="{{ $item['label'] }}" @endif>{{ $item['label'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
