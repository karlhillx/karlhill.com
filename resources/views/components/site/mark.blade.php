{{-- Site mark — same rocket-and-box SVG as the favicon. Decorative next to
     the wordmark; pass alt when it is the only identity on the page. --}}
@props([
    'size' => 28,
    'alt' => '',
])

<img src="/img/favicon.svg"
     width="{{ $size }}"
     height="{{ $size }}"
     alt="{{ $alt }}"
     decoding="async"
     fetchpriority="low"
     @if($alt === '') aria-hidden="true" @endif
     {{ $attributes->class('site-mark') }}>
