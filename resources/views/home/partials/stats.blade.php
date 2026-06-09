<section class="border-t border-b border-neutral-800 bg-neutral-900/40">
    <div class="max-w-6xl mx-auto px-6 py-10 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        @foreach(config('site.stats') as $stat)
            <div data-reveal>
                <p class="font-display text-5xl text-orange-500 leading-none"
                   data-counter
                   data-to="{{ $stat['to'] }}"
                   data-prefix="{{ $stat['prefix'] }}"
                   data-suffix="{{ $stat['suffix'] }}"
                   data-final="{{ $stat['display'] }}"
                   aria-label="{{ $stat['display'] }} {{ $stat['label'] }}">{{ $stat['display'] }}</p>
                <p class="font-mono text-xs text-neutral-500 mt-2 uppercase tracking-wide leading-snug">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>
</section>
