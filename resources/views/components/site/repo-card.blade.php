@props(['repo'])

<a href="{{ $repo->url }}" target="_blank" rel="noopener noreferrer" data-no-ext
   class="surface-card bg-bg group flex flex-col justify-between p-6 transition-all duration-300"
   data-reveal>
    <div>
        <div class="flex items-start justify-between gap-4 mb-2">
            <h3 class="font-mono text-base font-semibold text-neutral-100 group-hover:text-accent transition-colors leading-snug break-all">{{ $repo->name }}</h3>
            @if($repo->stars > 0)
                <span class="font-mono text-caption text-neutral-500 whitespace-nowrap shrink-0">★ {{ number_format($repo->stars) }}</span>
            @endif
        </div>
        @if(! empty($repo->category))
            <p class="font-mono text-caption text-accent uppercase tracking-widest mb-2.5">{{ $repo->category }}</p>
        @endif
        @if($repo->description)
            <p class="text-neutral-300 text-sm leading-relaxed mb-3">{{ $repo->description }}</p>
        @endif
        @if(! empty($repo->problem))
            <p class="text-neutral-400 text-sm leading-relaxed mb-4 border-l border-neutral-800 pl-2.5 italic">
                {{ $repo->problem }}
            </p>
        @endif
    </div>
    <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-neutral-800/80 mt-auto">
        <div class="flex flex-wrap items-center gap-2">
            @if($repo->language)
                <span class="flex items-center gap-1.5 font-mono text-caption text-neutral-400">
                    <span class="w-2 h-2 rounded-full shrink-0" style="background: {{ $repo->languageColor() }}"></span>
                    {{ $repo->language }}
                </span>
            @endif
            @foreach(array_slice($repo->topics, 0, 2) as $topic)
                <span class="surface-chip font-mono text-caption px-2 py-0.5 text-neutral-400">{{ $topic }}</span>
            @endforeach
        </div>
        <span class="font-mono text-caption text-accent uppercase tracking-widest group-hover:underline inline-flex items-center gap-1" aria-hidden="true">
            Code ↗
        </span>
    </div>
</a>
