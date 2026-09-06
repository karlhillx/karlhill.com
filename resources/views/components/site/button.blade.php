{{-- The site's one CTA system. Three weights, and the rule is: one `primary`
     per region (a page hero, a form, the footer), at most one `secondary`
     beside it, everything else a `link`. Primary is the hire action wherever
     one exists (Book a conversation); on /resume it is the PDF because that is
     the page's purpose. Renders <a> when `href` is set, otherwise <button>. --}}
@props([
    'variant' => 'secondary',
    'href' => null,
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center min-h-11 gap-2 font-mono text-xs uppercase tracking-widest transition-colors cursor-pointer';
    $variants = [
        'primary' => 'btn-accent-fill magnetic-btn px-5 py-3',
        'secondary' => 'btn-sweep text-accent border border-accent/40 px-5 py-3',
        'link' => 'text-neutral-400 hover:text-accent',
    ];
    $classes = $base.' '.($variants[$variant] ?? $variants['secondary']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
