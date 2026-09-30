@php
    $profiles = collect(config('site.social'))->keyBy('icon');
    $links = [
        ['href' => '/blog', 'label' => 'Writing'],
        ['href' => '/resume', 'label' => 'Resume'],
    ];
    foreach (['github', 'linkedin'] as $icon) {
        if (isset($profiles[$icon])) {
            $links[] = ['href' => $profiles[$icon]['url'], 'label' => $profiles[$icon]['label'], 'external' => true];
        }
    }
    $links[] = ['href' => '/privacy', 'label' => 'Privacy'];
@endphp

<nav {{ $attributes->merge(['aria-label' => 'Site']) }}>
    <ul class="flex flex-wrap gap-x-6 gap-y-1 font-mono text-xs">
        @foreach($links as $link)
            <li>
                <a href="{{ $link['href'] }}" class="inline-flex items-center min-h-11 text-neutral-400 hover:text-accent transition-colors"
                   @if($link['external'] ?? false) target="_blank" rel="me noopener noreferrer"
                   @elseif(request()->url() === url($link['href'])) aria-current="page" @endif>
                    {{ $link['label'] }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
