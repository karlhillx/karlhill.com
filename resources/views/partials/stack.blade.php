<x-site.section id="stack" :number="$sectionNumber ?? '03'" label="Technical Stack">
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach(config('site.stack') as $group)
                <div data-reveal>
                    <h3 class="font-display text-lg text-neutral-500 tracking-widest mb-4">{{ $group['category'] }}</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($group['skills'] as $skill)
                            <span class="font-mono text-xs px-3 py-1.5 border border-neutral-800 text-neutral-400 hover:border-accent/60 hover:text-accent/80 transition-colors cursor-default">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
</x-site.section>
