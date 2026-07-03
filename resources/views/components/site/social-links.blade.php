<div {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-5 pt-2']) }}>
    @foreach(config('site.social') as $link)
        <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer"
           aria-label="{{ $link['label'] }}" title="{{ $link['label'] }}"
           class="text-neutral-500 hover:text-accent hover:-translate-y-0.5 transition-[color,transform] duration-200">
            @include('components.site.icons.'.$link['icon'])
        </a>
    @endforeach
    <a href="/feed.xml" aria-label="RSS feed" title="RSS feed"
       class="text-neutral-500 hover:text-accent hover:-translate-y-0.5 transition-[color,transform] duration-200">
        @include('components.site.icons.rss')
    </a>
</div>
