@props([
    'mentions' => [],
    'target' => null,
])

<section class="mb-12" data-reveal aria-labelledby="webmentions-heading">
    <p id="webmentions-heading" class="font-mono text-accent text-xs tracking-widest uppercase mb-3">Mentions</p>
    @if(count($mentions) > 0)
        <ul class="space-y-3">
            @foreach($mentions as $mention)
                <li class="text-sm text-neutral-400 leading-relaxed">
                    <a href="{{ $mention['source'] }}"
                       rel="nofollow ugc"
                       class="text-neutral-200 hover:text-accent transition-colors">
                        {{ $mention['title'] ?? $mention['source'] }}
                    </a>
                    @if(! empty($mention['author']))
                        <span class="text-neutral-500"> · {{ $mention['author'] }}</span>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-neutral-500 text-sm leading-relaxed">
            No webmentions yet.
            @if($target)
                This post accepts mentions at
                <span class="font-mono text-[11px] text-neutral-400">{{ url('/webmention') }}</span>.
            @endif
        </p>
    @endif
</section>
