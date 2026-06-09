<section id="certs" class="py-28 px-6 border-t border-neutral-800">
    <div class="max-w-6xl mx-auto">
        <x-site.section-heading number="07" label="Certifications & Education" />
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-px bg-neutral-800">
            @foreach(config('site.certifications') as $cert)
                <a href="{{ $cert['url'] }}" target="_blank" rel="noopener noreferrer"
                   class="bg-[#080808] p-8 hover:bg-neutral-900/60 transition-colors group" data-reveal>
                    <p class="font-display text-5xl text-orange-500 mb-3 group-hover:text-orange-400 transition-colors">{{ $cert['abbr'] }}</p>
                    <p class="text-sm text-neutral-300 font-medium leading-snug">{{ $cert['name'] }}</p>
                    <p class="font-mono text-xs text-neutral-600 mt-3">{{ $cert['issuer'] }}</p>
                    <p class="font-mono text-xs text-neutral-700 mt-2 group-hover:text-orange-600 transition-colors">Verify ↗</p>
                </a>
            @endforeach
        </div>
        <div class="mt-px bg-neutral-800">
            <div class="bg-[#080808] p-8" data-reveal>
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
