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
            One pattern: take operational work that depends on heroes and turn it into a platform. Jacobs is the current chapter, NASA is the public proof, and Finium is where it started.
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
