@props([
    'items' => [],
])

@if(count($items) > 0)
    @php
        $siteUrl = rtrim(config('app.url', 'https://karlhill.com'), '/');
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
    <nav aria-label="Breadcrumb" {{ $attributes->merge(['class' => 'mb-8']) }}>
        <ol class="flex flex-wrap items-center gap-2 font-mono text-[11px] text-neutral-500 uppercase tracking-widest">
            @foreach($items as $index => $item)
                @if($index > 0)
                    <li aria-hidden="true" class="text-neutral-700">/</li>
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
