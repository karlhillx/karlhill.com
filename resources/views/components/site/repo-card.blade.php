@props(['repo'])

<a href="{{ $repo->url }}" target="_blank" rel="noopener noreferrer"
   class="surface-card bg-bg group block p-6"
   data-reveal>
    <div class="flex items-start justify-between gap-4 mb-3">
        <h3 class="font-mono text-sm font-normal text-neutral-200 group-hover:text-accent/80 transition-colors leading-snug break-all">{{ $repo->name }}</h3>
        @if($repo->stars > 0)
            <span class="font-mono text-xs text-neutral-600 whitespace-nowrap shrink-0">★ {{ number_format($repo->stars) }}</span>
        @endif
    </div>
    @if($repo->description)
        <p class="text-neutral-500 text-xs leading-relaxed mb-4 line-clamp-2">{{ $repo->description }}</p>
    @endif
    <div class="flex flex-wrap items-center gap-3">
        @if($repo->language)
            <span class="flex items-center gap-1.5 font-mono text-xs text-neutral-500">
                <span class="w-2 h-2 rounded-full shrink-0" style="background: {{ $repo->languageColor() }}"></span>
                {{ $repo->language }}
            </span>
        @endif
        @foreach(array_slice($repo->topics, 0, 2) as $topic)
            <span class="surface-chip font-mono text-xs px-2 py-0.5 text-neutral-600">{{ $topic }}</span>
        @endforeach
    </div>
</a>
