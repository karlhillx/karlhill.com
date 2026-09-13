@props([
    'listClass' => 'flex flex-col gap-0.5 font-mono text-sm',
    'itemClass' => 'inline-flex items-center min-h-10 text-neutral-400 hover:text-accent transition-colors',
])

@php
    $items = array_values(array_filter([
        ['href' => '/work', 'label' => 'Work'],
        ['href' => '/blog', 'label' => 'Writing'],
        request()->routeIs('about') ? null : ['href' => '/about', 'label' => 'About'],
        request()->routeIs('now') ? null : ['href' => '/now', 'label' => 'Now'],
    ]));
@endphp

<nav {{ $attributes->merge(['aria-label' => 'Site']) }}>
    <h2 class="font-mono text-accent text-xs tracking-widest uppercase mb-3">Explore</h2>
    <ul class="{{ $listClass }}">
        @foreach($items as $item)
            <li>
                <a href="{{ $item['href'] }}" class="{{ $itemClass }}">{{ $item['label'] }}</a>
            </li>
        @endforeach
    </ul>
</nav>
