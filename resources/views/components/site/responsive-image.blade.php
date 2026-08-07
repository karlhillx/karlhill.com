{{-- AVIF → WebP → raster fallback with width variants + optional LQIP blur-up. --}}
@props([
    'src',
    'alt' => '',
    'sizes' => '100vw',
    'width' => null,
    'height' => null,
    'loading' => 'lazy',
    'fetchpriority' => null,
    'decoding' => 'async',
    'imgClass' => '',
    'imgStyle' => null,
    'lqip' => true,
])

@php
    $webp = \App\Support\Images::webp($src);
    $avif = \App\Support\Images::avif($src);
    $webpSrcset = \App\Support\Images::srcset($webp);
    $hasAvifFile = is_file(public_path(ltrim($avif, '/')));
    $avifSrcset = $hasAvifFile || \App\Support\Images::hasAvif($src)
        ? \App\Support\Images::srcset($avif)
        : null;
    $showAvif = $hasAvifFile || $avifSrcset !== null;
    $fallbackSrc = str_ends_with(strtolower($src), '.webp') || str_ends_with(strtolower($src), '.avif')
        ? $webp
        : $src;
    $lqipPath = $lqip ? \App\Support\Images::lqip($src) : null;
@endphp

@if($lqipPath)
    <span {{ $attributes->class('img-lqip')->merge(['style' => 'background-image: url('.$lqipPath.')']) }}>
@endif
<picture @unless($lqipPath) {{ $attributes }} @endunless>
    @if($showAvif)
        <source srcset="{{ $avifSrcset ?? $avif }}"
                @if($avifSrcset || $webpSrcset) sizes="{{ $sizes }}" @endif
                type="image/avif">
    @endif
    @if($webp !== $fallbackSrc || $webpSrcset)
        <source srcset="{{ $webpSrcset ?? $webp }}"
                @if($webpSrcset) sizes="{{ $sizes }}" @endif
                type="image/webp">
    @endif
    <img src="{{ $fallbackSrc }}"
         alt="{{ $alt }}"
         @if($width) width="{{ $width }}" @endif
         @if($height) height="{{ $height }}" @endif
         loading="{{ $loading }}"
         decoding="{{ $decoding }}"
         @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
         @if($webpSrcset && $fallbackSrc === $webp) srcset="{{ $webpSrcset }}" sizes="{{ $sizes }}" @endif
         @if($imgStyle) style="{{ $imgStyle }}" @endif
         @if($lqipPath) data-lqip-img @endif
         @class([$imgClass, 'img-lqip__media' => (bool) $lqipPath])>
</picture>
@if($lqipPath)
    </span>
@endif
