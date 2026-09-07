@extends('layouts.site', ['meta' => $meta])

@push('head')
<x-site.speculation-rules :rules="\App\Support\SpeculationRules::forWorkIndex()" />
@endpush

@section('content')
    @php
        $projectCount = $projects->count();
        $breadcrumbs = [
            ['label' => 'Home', 'url' => '/'],
        ];
        if ($activeTag) {
            $breadcrumbs[] = ['label' => 'Work', 'url' => '/work'];
            $breadcrumbs[] = ['label' => $activeTag];
        } else {
            $breadcrumbs[] = ['label' => 'Work'];
        }
    @endphp

    <x-site.page-hero :breadcrumbs="$breadcrumbs">
        <x-slot:title>Selected Work</x-slot:title>

        {{-- Two sentences: the hero is a doorway, the cards carry the detail. --}}
        <p class="text-neutral-400 text-base leading-relaxed max-w-2xl">
            Jacobs is the leadership chapter — hard calls under constraint, engineer development, team execution. NASA is the public platform proof. Finium is where the pattern started.
        </p>
    </x-site.page-hero>

    {{-- One facet, the one a hiring manager filters by: domain. Four flagship
         cards don't need a second stack facet (that row clipped mid-word on
         phones behind two arrow buttons); each card already lists its stack,
         and /work/tag/{stack} stays routable for deep links. Not sticky: a
         four-card grid never scrolls far enough to lose the filter. --}}
    @if($sectors->isNotEmpty())
        @php($urlFor = fn ($tag) => route('work.tag', \App\Support\ProjectCatalog::tagSlug($tag)))
        <section class="site-toolbar border-t border-neutral-800/80" aria-label="Filter projects">
            <div class="site-shell flex flex-col gap-3">
                <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2">
                    <p class="font-mono text-caption text-neutral-400 uppercase tracking-widest" aria-live="polite">
                        <span class="text-neutral-300 tabular-nums">{{ $projectCount }}</span>
                        {{ \Illuminate\Support\Str::plural('project', $projectCount) }}
                        @if($activeTag)
                            <span class="text-neutral-500" aria-hidden="true">·</span>
                            <span class="text-accent">{{ $activeTag }}</span>
                        @endif
                    </p>
                    @if($activeTag)
                        <a href="{{ route('work') }}"
                           class="font-mono text-caption text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                            Clear filter
                        </a>
                    @endif
                </div>

                <x-site.tag-filter
                    :all-url="route('work')"
                    :tags="$sectors"
                    :counts="$sectorCounts"
                    :active-tag="$activeTag"
                    :url-for="$urlFor"
                    aria-label="Filter by domain"
                />
            </div>
        </section>
    @endif

    @include('partials.work', [
        'projects' => $projects,
        'sectionNumber' => '01',
        'heading' => $activeTag ? "Projects · {$activeTag}" : 'Projects',
    ])

    @if(($supporting ?? collect())->isNotEmpty())
        <x-site.section id="chapters" class="scroll-mt-32" section-label="NASA Goddard" number="02" label="Also shipped at NASA Goddard">
            <p class="text-neutral-400 text-sm leading-relaxed max-w-2xl mb-6" data-reveal>
                Flagship cards stay on the trajectory. These Goddard chapters are the public record — short hops for resume readers, not a second screenshot grid.
            </p>
            <ul class="work-chapters border-y border-neutral-800 divide-y divide-neutral-800" data-reveal>
                @foreach($supporting as $project)
                    <li>
                        <a href="/work/{{ $project['slug'] }}"
                           class="work-chapters__link group flex flex-col gap-1 py-4 sm:flex-row sm:items-baseline sm:gap-x-5 sm:gap-y-1">
                            <span class="font-mono text-caption text-neutral-500 uppercase tracking-widest shrink-0 sm:w-40">
                                {{ $project['meta'] }}
                            </span>
                            <span class="font-sans font-semibold text-neutral-100 tracking-tight group-hover:text-accent transition-colors">
                                {{ $project['title'] }}
                            </span>
                            <span class="text-neutral-400 text-sm leading-snug line-clamp-1 sm:min-w-0 sm:flex-1">
                                {{ $project['case_study']['lede'] ?? $project['description'] }}
                            </span>
                            <span class="font-mono text-caption text-accent uppercase tracking-widest sm:shrink-0" aria-hidden="true">
                                View <span class="arrow-nudge inline-block">→</span>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </x-site.section>
    @endif

    @include('partials.open-source', ['sectionNumber' => ($supporting ?? collect())->isNotEmpty() ? '03' : '02'])
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
