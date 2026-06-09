<section id="stack" class="py-28 px-6 border-t border-neutral-800">
    <div class="max-w-6xl mx-auto">
        <x-site.section-heading number="05" label="Technical Stack" />
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach(config('site.stack') as $group)
                <div data-reveal>
                    <p class="font-display text-lg text-neutral-500 tracking-widest mb-4">{{ $group['category'] }}</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($group['skills'] as $skill)
                            <span class="font-mono text-xs px-3 py-1.5 border border-neutral-800 text-neutral-400 hover:border-orange-500/60 hover:text-orange-400 transition-colors cursor-default">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
