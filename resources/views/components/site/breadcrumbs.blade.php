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
        <ol class="flex min-w-0 flex-nowrap items-center gap-x-2 eyebrow eyebrow--faint">
            @foreach($items as $index => $item)
                @if($index > 0)
                    <li aria-hidden="true" class="shrink-0 text-neutral-500">/</li>
                @endif
                <li @class(['min-w-0' => $loop->last, 'shrink-0' => ! $loop->last]) @if($loop->last) aria-current="page" @endif>
                    @if(! $loop->last && ($item['url'] ?? null))
                        <a href="{{ $item['url'] }}" class="hover:text-accent transition-colors">{{ $item['label'] }}</a>
                    @else
                        {{-- The heading repeats this label, so the trail stays one line and clips. --}}
                        <span @class(['block truncate text-neutral-400' => $loop->last])
                              @if($loop->last) title="{{ $item['label'] }}" @endif>{{ $item['label'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
