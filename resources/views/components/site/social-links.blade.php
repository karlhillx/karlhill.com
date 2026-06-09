@props(['extended' => false])

@php
    $defaultIcons = ['linkedin', 'github', 'twitter'];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-5 pt-2']) }}>
    @foreach(config('site.social') as $link)
        @if(! $extended && ! in_array($link['icon'], $defaultIcons, true))
            @continue
        @endif
        <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer"
           aria-label="{{ $link['label'] }}" @if($extended) title="{{ $link['label'] }}" @endif
           class="text-neutral-500 hover:text-orange-500 transition-colors">
            @include('components.site.icons.'.$link['icon'])
        </a>
    @endforeach
    @unless($extended)
        <a href="/feed.xml" aria-label="RSS feed"
           class="text-neutral-500 hover:text-orange-500 transition-colors">
            @include('components.site.icons.rss')
        </a>
    @endunless
</div>
