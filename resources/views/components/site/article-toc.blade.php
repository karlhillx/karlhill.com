@props(['items' => []])

@if(count($items) >= 2)
    <nav id="article-toc" {{ $attributes->merge(['class' => 'article-toc']) }} aria-label="On this page">
        <p class="font-mono text-[10px] text-accent uppercase tracking-widest mb-3">On this page</p>
        <ol class="article-toc-list">
            @foreach($items as $item)
                <li @class([
                    'article-toc-item',
                    'article-toc-item--child' => ($item['level'] ?? 2) === 3,
                ])>
                    <a href="#{{ $item['id'] }}"
                       data-toc-link
                       class="article-toc-link font-mono text-[11px] text-neutral-500 hover:text-accent transition-colors">
                        {{ $item['text'] }}
                    </a>
                </li>
            @endforeach
        </ol>
    </nav>
@endif
