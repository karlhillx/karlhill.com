<div {{ $attributes->merge(['class' => 'social-links flex flex-wrap items-center gap-2 pt-2']) }}>
    @foreach(config('site.social') as $link)
        <a href="{{ $link['url'] }}" target="_blank" rel="me noopener noreferrer" data-no-ext
           aria-label="{{ $link['label'] }} (opens in a new tab)" title="{{ $link['label'] }}"
           class="inline-flex items-center justify-center min-h-11 min-w-11 text-neutral-500 hover:text-accent hover:-translate-y-0.5 transition-[color,transform] duration-200">
            @include('components.site.icons.'.$link['icon'])
        </a>
    @endforeach
    <a href="/feed.xml" aria-label="RSS feed" title="RSS feed" data-no-ext
       class="inline-flex items-center justify-center min-h-11 min-w-11 text-neutral-500 hover:text-accent hover:-translate-y-0.5 transition-[color,transform] duration-200">
        @include('components.site.icons.rss')
    </a>
</div>
