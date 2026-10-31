@php
    $profiles = collect(config('site.social'))->keyBy('icon');
    $links = [
        ['href' => '/experience', 'label' => 'Experience'],
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

<nav {{ $attributes->merge(['aria-label' => 'Site', 'class' => 'site-footer__nav']) }}>
    <ul class="site-footer__links">
        @foreach($links as $link)
            <li>
                <a href="{{ $link['href'] }}" class="site-footer__link"
                   @if($link['external'] ?? false) target="_blank" rel="me noopener noreferrer"
                   @elseif(request()->url() === url($link['href'])) aria-current="page" @endif>
                    {{ $link['label'] }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
