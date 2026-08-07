{{-- Series context + chapter strip + prev/next within an ordered essay series. --}}
@props([
    'series',
])

@if($series)
    <aside {{ $attributes->merge(['class' => 'surface-card-static p-5 sm:p-6 mb-10']) }} data-reveal aria-label="Series">
        <div class="flex flex-wrap items-baseline justify-between gap-3 mb-2">
            <p class="font-mono text-accent text-xs tracking-widest uppercase">Series</p>
            <p class="font-mono text-[10px] text-neutral-500 uppercase tracking-widest">
                Part {{ $series['index'] + 1 }} of {{ $series['posts']->count() }}
            </p>
        </div>
        <p class="font-display text-xl tracking-wide text-white mb-2">{{ $series['title'] }}</p>
        @if(! empty($series['description']))
            <p class="text-neutral-400 text-sm leading-relaxed mb-6 max-w-2xl">{{ $series['description'] }}</p>
        @endif

        <x-site.series-chapters :series="$series" :current-index="$series['index']" class="mb-6" />

        <div class="flex flex-wrap gap-4 font-mono text-[11px] uppercase tracking-widest">
            @if($series['previous'])
                <a href="{{ $series['previous']->url() }}" class="text-neutral-400 hover:text-accent transition-colors">← Previous</a>
            @endif
            @if($series['next'])
                <a href="{{ $series['next']->url() }}" class="text-neutral-400 hover:text-accent transition-colors">Next →</a>
            @endif
            <a href="/blog#{{ $series['id'] }}" class="text-neutral-500 hover:text-accent transition-colors">All in series</a>
        </div>
    </aside>
@endif
