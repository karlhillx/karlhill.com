@props([
    'listClass' => 'flex flex-col gap-0.5 font-mono text-sm',
    'itemClass' => 'inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors',
])

@php
    $groups = [
        'Work' => [
            ['href' => '/work', 'label' => 'Portfolio'],
            ['href' => '/work#open-source', 'label' => 'Open Source'],
            ['href' => '/research/global-flood-mapping', 'label' => 'Research'],
            ['href' => '/delivery', 'label' => 'How I Deliver'],
        ],
        'Background' => [
            ['href' => '/about', 'label' => 'About'],
            ['href' => '/blog', 'label' => 'Writing'],
            ['href' => '/resume', 'label' => 'Resume'],
            ['href' => '/resume#credentials', 'label' => 'Certifications'],
            ['href' => '/kit', 'label' => 'Recruiter Kit'],
        ],
        'Connect' => [
            ['href' => '/now', 'label' => 'Now'],
            ['href' => '/#contact', 'label' => 'Contact'],
        ],
    ];
@endphp

<nav {{ $attributes->merge(['aria-label' => 'Site']) }}>
    <h2 class="font-mono text-accent text-xs tracking-widest uppercase mb-3">Explore</h2>
    <div class="flex flex-wrap gap-x-8 gap-y-6">
        @foreach($groups as $label => $items)
            <div class="min-w-0">
                <h3 class="font-mono text-caption text-neutral-300 uppercase mb-2">{{ $label }}</h3>
                <ul class="{{ $listClass }}">
                    @foreach($items as $item)
                        <li>
                            <a href="{{ $item['href'] }}" class="{{ $itemClass }}" @if(request()->url() === url($item['href'])) aria-current="page" @endif>{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</nav>
