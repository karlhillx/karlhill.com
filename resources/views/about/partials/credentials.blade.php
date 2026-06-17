<section id="credentials" class="py-28 px-6 border-t border-neutral-800">
    <div class="max-w-6xl mx-auto">
        <x-site.section-heading :number="$sectionNumber ?? '04'" label="Credentials" />

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-px bg-neutral-800 mb-px" data-reveal>
            @foreach(config('site.stats') as $stat)
                <div class="bg-bg p-8 text-center">
                    <p class="font-display text-5xl text-accent mb-2"
                       data-counter
                       data-final="{{ $stat['display'] }}"
                       data-to="{{ $stat['to'] }}"
                       data-prefix="{{ $stat['prefix'] }}"
                       data-suffix="{{ $stat['suffix'] }}"
                       aria-label="{{ $stat['display'] }} {{ $stat['label'] }}">{{ $stat['display'] }}</p>
                    <p class="font-mono text-[10px] text-neutral-500 uppercase tracking-widest leading-relaxed">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-px bg-neutral-800">
            @foreach(config('site.certifications') as $cert)
                <a href="{{ $cert['url'] }}" target="_blank" rel="noopener noreferrer"
                   class="bg-bg p-8 hover:bg-neutral-900/60 transition-colors group" data-reveal>
                    <p class="font-display text-5xl text-accent mb-3 group-hover:opacity-90 transition-opacity">{{ $cert['abbr'] }}</p>
                    <p class="text-sm text-neutral-300 font-medium leading-snug">{{ $cert['name'] }}</p>
                    <p class="font-mono text-xs text-neutral-600 mt-3">{{ $cert['issuer'] }}</p>
                    <p class="font-mono text-xs text-neutral-700 mt-2 group-hover:text-accent transition-colors">Verify ↗</p>
                </a>
            @endforeach
        </div>

        <div class="mt-px bg-neutral-800">
            <div class="bg-bg p-8" data-reveal>
                <p class="font-display text-lg text-neutral-500 tracking-widest mb-4">Education</p>
                <div class="flex flex-col sm:flex-row gap-8">
                    @foreach(config('site.education') as $school)
                        <div>
                            <p class="text-sm text-neutral-300 font-medium">{{ $school['degree'] }}</p>
                            <p class="font-mono text-xs text-neutral-600 mt-1">{{ $school['school'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
