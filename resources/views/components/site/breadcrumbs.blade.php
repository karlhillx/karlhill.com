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
        <ol class="flex flex-wrap items-center gap-2 font-mono text-caption text-neutral-500 uppercase tracking-widest">
            @foreach($items as $index => $item)
                @if($index > 0)
                    <li aria-hidden="true" class="text-neutral-500">/</li>
                @endif
                <li @if($loop->last) aria-current="page" @endif>
                    @if(! $loop->last && ($item['url'] ?? null))
                        <a href="{{ $item['url'] }}" class="hover:text-accent transition-colors">{{ $item['label'] }}</a>
                    @else
                        <span @class(['text-neutral-400' => $loop->last])>{{ $item['label'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
