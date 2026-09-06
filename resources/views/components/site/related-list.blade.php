{{-- Related-content card list shared by blog posts and case studies.
     Each item is `['url' => ..., 'title' => ..., 'excerpt' => ...]`. --}}
@props([
    'label' => 'Related',
    'items' => [],
])

@if(count($items) > 0)
    <div {{ $attributes }} data-reveal>
        @if(filled($label))
            <p class="font-mono text-accent text-xs tracking-widest uppercase mb-4">{{ $label }}</p>
        @endif
        <ul class="space-y-4">
            @foreach($items as $item)
                <li>
                    <a href="{{ $item['url'] }}" class="surface-card group block p-5">
                        <p class="font-sans font-semibold text-lg text-neutral-100 group-hover:text-accent tracking-tight transition-colors">{{ $item['title'] }}</p>
                        <p class="text-neutral-400 text-sm mt-2 line-clamp-2">{{ $item['excerpt'] }}</p>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
