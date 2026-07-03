<section aria-label="Career impact at a glance" class="px-6 border-t border-neutral-800">
    <div class="max-w-6xl mx-auto grid grid-cols-2 lg:grid-cols-4 gap-px bg-neutral-800">
        @foreach(config('site.stats') as $stat)
            <div class="bg-bg px-6 py-10 text-center" data-reveal>
                <p class="font-display text-5xl sm:text-6xl text-accent mb-2"
                   data-counter
                   data-final="{{ $stat['display'] }}"
                   data-to="{{ $stat['to'] }}"
                   data-prefix="{{ $stat['prefix'] }}"
                   data-suffix="{{ $stat['suffix'] }}"
                   aria-label="{{ $stat['display'] }} — {{ $stat['label'] }}">{{ $stat['display'] }}</p>
                <p class="font-mono text-[10px] text-neutral-400 uppercase tracking-widest leading-relaxed">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>
</section>
