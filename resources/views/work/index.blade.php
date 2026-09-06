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

    <x-site.page-hero eyebrow="Portfolio" :breadcrumbs="$breadcrumbs">
        <x-slot:title>Selected Work</x-slot:title>

        <p class="text-neutral-400 text-base leading-relaxed max-w-2xl">
            One pattern: take operational work that depends on heroes and turn it into a platform. Jacobs is the current chapter. NASA is the public proof — flood products under disaster timelines, LAADS Find Data for the MODIS/VIIRS archive, and the Goddard chapters below the grid. Finium is where it started: a multi-tenant security platform that scaled 10× and enabled a $105M acquisition.
        </p>
    </x-site.page-hero>

    @if($allTags->isNotEmpty() || $sectors->isNotEmpty())
        @php($urlFor = fn ($tag) => route('work.tag', \App\Support\ProjectCatalog::tagSlug($tag)))
        <section class="site-toolbar site-toolbar--sticky border-t border-neutral-800/80" aria-label="Filter projects">
            <div class="site-shell flex flex-col gap-4">
                <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2">
                    <p class="font-mono text-caption text-neutral-400 uppercase tracking-widest" aria-live="polite">
                        <span class="text-neutral-300 tabular-nums">{{ $projectCount }}</span>
                        {{ \Illuminate\Support\Str::plural('project', $projectCount) }}
                        @if($activeTag)
                            <span class="text-neutral-600" aria-hidden="true">·</span>
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

                {{-- Primary facet: the domain a hiring manager cares about. One line on
                     desktop; a single scrollable row on phones so the sticky toolbar stays short. --}}
                @if($sectors->isNotEmpty())
                    <div class="tag-filter-scroller">
                        <span class="tag-filter-scroller__label font-mono text-caption text-neutral-500 uppercase tracking-widest">Domain</span>
                        <x-site.tag-filter
                            class="tag-filter--scroll-mobile"
                            :all-url="route('work')"
                            :tags="$sectors"
                            :counts="$sectorCounts"
                            :active-tag="$activeTag"
                            :url-for="$urlFor"
                            aria-label="Filter by domain"
                        />
                    </div>
                @endif

                {{-- Secondary facet: stack, kept to one scrollable row so it never dominates the toolbar. --}}
                @if($allTags->isNotEmpty())
                    <div class="tag-filter-scroller">
                        <span class="tag-filter-scroller__label font-mono text-caption text-neutral-500 uppercase tracking-widest">Stack</span>
                        <x-site.tag-filter
                            class="tag-filter--scroll"
                            :all-url="route('work')"
                            :tags="$allTags"
                            :counts="$tagCounts"
                            :active-tag="$activeTag"
                            :url-for="$urlFor"
                            :show-all="false"
                            aria-label="Filter by stack"
                        />
                    </div>
                @endif
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
            <p class="text-neutral-400 text-sm leading-relaxed max-w-2xl mb-8" data-reveal>
                Flagship cards stay on the trajectory. These Goddard chapters are still the public record — resume links, not a second grid of screenshots.
            </p>
            <x-site.related-list
                :label="null"
                :items="$supporting->map(fn ($project) => [
                    'url' => '/work/'.$project['slug'],
                    'title' => $project['title'],
                    'excerpt' => $project['case_study']['lede'] ?? $project['description'],
                ])->all()"
            />
        </x-site.section>
    @endif

    @include('partials.open-source', ['sectionNumber' => ($supporting ?? collect())->isNotEmpty() ? '03' : '02'])
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
