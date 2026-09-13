{{-- Site mark — same rocket-and-box art as the favicon. Decorative next to
     the wordmark; pass alt when it is the only identity on the page. --}}
@props([
    'size' => 28,
    'alt' => '',
])

<img src="{{ $size >= 48 ? '/img/android-chrome-192x192.png' : '/img/favicon-96x96.png' }}"
     srcset="/img/favicon-48x48.png 1x, /img/favicon-96x96.png 2x, /img/android-chrome-192x192.png 3x"
     width="{{ $size }}"
     height="{{ $size }}"
     alt="{{ $alt }}"
     decoding="async"
     fetchpriority="low"
     @if($alt === '') aria-hidden="true" @endif
     {{ $attributes->class('site-mark') }}>
