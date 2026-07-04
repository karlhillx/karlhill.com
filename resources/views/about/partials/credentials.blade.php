<x-site.section id="credentials" :number="$sectionNumber ?? '04'" label="Credentials">
        {{-- Impact stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-px bg-neutral-800 rounded-2xl overflow-hidden ring-1 ring-white/[0.06]" data-reveal>
            @foreach(config('site.stats') as $stat)
                <x-site.stat
                    padding="px-6 py-10"
                    :value="$stat['display']"
                    :label="$stat['label']"
                    :to="$stat['to']"
                    :prefix="$stat['prefix']"
                    :suffix="$stat['suffix']"
                />
            @endforeach
        </div>

        {{-- Certifications --}}
        @php
            $certifications = config('site.certifications');
            $verifiedCount = count(array_filter($certifications, fn ($cert) => ! isset($cert['status'])));
            $inProgressCount = count($certifications) - $verifiedCount;
        @endphp
        <div class="flex items-baseline justify-between gap-4 mt-20 mb-6" data-reveal>
            <h3 class="font-display text-lg text-neutral-500 tracking-widest">Certifications</h3>
            <p class="font-mono text-[10px] text-neutral-600 uppercase tracking-widest">
                {{ $verifiedCount }} verified{{ $inProgressCount ? " · {$inProgressCount} in progress" : '' }}
            </p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
            @foreach($certifications as $cert)
                <a href="{{ $cert['url'] }}" target="_blank" rel="noopener noreferrer"
                   class="group relative flex flex-col rounded-2xl ring-1 ring-white/[0.06] bg-neutral-900/20 p-6 transition-[box-shadow,background-color,transform] duration-300 hover:ring-accent/40 hover:bg-neutral-900/40 hover:-translate-y-1 focus-visible:ring-2 focus-visible:ring-accent/60"
                   data-reveal>
                    {{-- Accent hairline sweeps in along the top edge on hover --}}
                    <span class="pointer-events-none absolute inset-x-6 top-0 h-px bg-gradient-to-r from-accent/80 to-transparent scale-x-0 origin-left transition-transform duration-500 ease-out group-hover:scale-x-100" aria-hidden="true"></span>

                    <p class="font-display text-5xl leading-none text-accent mb-4 transition-transform duration-300 group-hover:scale-[1.04] origin-bottom-left">{{ $cert['abbr'] }}</p>
                    <p class="text-sm text-neutral-300 font-medium leading-snug">{{ $cert['name'] }}</p>
                    <p class="font-mono text-xs text-neutral-600 mt-2">{{ $cert['issuer'] }}</p>
                    <p class="mt-auto pt-6 font-mono text-[10px] uppercase tracking-widest text-neutral-600 group-hover:text-accent transition-colors">
                        @if($cert['status'] ?? null)
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-amber-400 availability-pulse align-middle mr-1" aria-hidden="true"></span>{{ $cert['status'] }}
                        @else
                            Verify credential
                        @endif
                        <span class="inline-block transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" aria-hidden="true">↗</span>
                    </p>
                </a>
            @endforeach
        </div>

        {{-- Education --}}
        <h3 class="font-display text-lg text-neutral-500 tracking-widest mt-20 mb-6" data-reveal>Education</h3>
        <div class="grid sm:grid-cols-3 gap-3">
            @foreach(config('site.education') as $school)
                <div class="group relative rounded-2xl ring-1 ring-white/[0.06] bg-neutral-900/20 p-7 transition-[box-shadow,background-color] duration-300 hover:ring-white/[0.12] hover:bg-neutral-900/40"
                     data-reveal>
                    <p class="font-mono text-[10px] text-accent/70 tracking-widest mb-4" aria-hidden="true">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</p>
                    <p class="text-sm text-neutral-200 font-medium leading-snug">{{ $school['degree'] }}</p>
                    <p class="font-mono text-xs text-neutral-500 mt-2">{{ $school['school'] }}</p>
                </div>
            @endforeach
        </div>
</x-site.section>
