@php($experience = config('site.experience'))
@php($current = $experience['current'])

<x-site.section id="experience" :number="$sectionNumber ?? '01'" label="Experience">
        <div class="role-active rounded-sm mb-16 p-8 md:p-10 border border-accent/25 bg-accent/[0.03]" data-reveal>
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3 mb-8">
                <div>
                    <p class="font-mono text-accent text-xs tracking-widest uppercase mb-2">{{ $current['label'] }}</p>
                    <h3 class="font-display text-4xl tracking-wide">{{ $current['title'] }}</h3>
                    <p class="text-accent/80 font-medium mt-1.5">{{ $current['company'] }} &nbsp;·&nbsp; {{ $current['location'] }}</p>
                </div>
                <span class="font-mono text-xs text-neutral-600 uppercase tracking-widest whitespace-nowrap mt-1">{{ $current['period'] }}</span>
            </div>
            <x-site.arrow-list :items="$current['highlights']" class="text-neutral-300" />
        </div>

        <div class="space-y-0 divide-y divide-neutral-800">
            @foreach($experience['roles'] as $role)
                <div class="grid md:grid-cols-[220px_1fr] gap-6 md:gap-12 py-14" data-reveal>
                    <div>
                        <h3 class="font-display text-2xl tracking-wide leading-tight">{{ $role['title'] }}</h3>
                        <p class="text-accent text-sm font-medium mt-2">{{ $role['company'] }}</p>
                        <p class="text-neutral-600 text-sm">{{ $role['location'] }}</p>
                        <span class="font-mono text-xs text-neutral-600 mt-3 block">{{ $role['period'] }}</span>
                    </div>
                    <x-site.arrow-list :items="$role['highlights']" />
                </div>
            @endforeach

            @php($earlier = $experience['earlier'])
            <div class="grid md:grid-cols-[220px_1fr] gap-6 md:gap-12 py-14" data-reveal>
                <div>
                    <h3 class="font-display text-2xl tracking-wide leading-tight">{{ $earlier['title'] }}</h3>
                    <span class="font-mono text-xs text-neutral-600 mt-3 block">{{ $earlier['period'] }}</span>
                </div>
                <details class="group/earlier">
                    <summary class="cursor-pointer list-none [&::-webkit-details-marker]:hidden font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors w-fit">
                        <span class="group-open/earlier:hidden">Show {{ count($earlier['entries']) }} earlier roles <span aria-hidden="true">↓</span></span>
                        <span class="hidden group-open/earlier:inline">Hide earlier roles <span aria-hidden="true">↑</span></span>
                    </summary>
                    <div class="space-y-6 mt-8">
                        @foreach($earlier['entries'] as $entry)
                            <div class="flex gap-5">
                                <div class="pt-1.5 shrink-0">
                                    <div class="w-1.5 h-1.5 rounded-full bg-accent"></div>
                                </div>
                                <div>
                                    <p class="font-semibold text-neutral-200 text-sm">{{ $entry['company'] }}</p>
                                    <p class="font-mono text-xs text-neutral-600 mt-0.5">{{ $entry['meta'] }}</p>
                                    <p class="text-neutral-500 text-sm mt-2 leading-relaxed">{{ $entry['detail'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </details>
            </div>
        </div>
</x-site.section>
