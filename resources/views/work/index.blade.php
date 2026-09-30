@extends('layouts.site', ['meta' => $meta])

@push('head')
<x-site.speculation-rules :rules="\App\Support\SpeculationRules::forWorkIndex()" />
@endpush

@section('content')
    <x-site.page-hero :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Work'],
    ]">
        <x-slot:title>Work</x-slot:title>

        <p class="site-page-hero__lede text-neutral-300">
            {{ config('site.work.lede') }}
        </p>

    </x-site.page-hero>

    <nav class="portfolio-nav site-gutter" aria-label="Portfolio sections">
        <div class="portfolio-nav__links site-shell">
            <a href="#work" data-toc-link data-toc-sections="work chapters" aria-current="location">
                Mission Software
            </a>
            <a href="#products" data-toc-link>
                Independent Products
            </a>
            <a href="#open-source" data-toc-link>
                Open Source &amp; Tools
            </a>
            @if(($earlierProjects ?? collect())->isNotEmpty())
                <a href="#earlier" data-toc-link>
                    Earlier Work
                </a>
            @endif
        </div>
    </nav>

    {{-- Section 01: Mission & Professional Software --}}
    <div data-soft-nav-target>
        @include('partials.work', [
            'projects' => $missionProjects ?? $projects->take(3),
            'hideHeading' => false,
            'heading' => 'Mission Software',
            'sectionNumber' => '01',
            'proof' => config('site.work.mission_intro'),
        ])
    </div>

    @if(($supporting ?? collect())->isNotEmpty())
        <x-site.section id="chapters" class="scroll-mt-32" section-label="Also at Goddard" number="01" label="Also at Goddard" border="soft">
            <p class="text-neutral-400 text-sm leading-relaxed max-w-2xl mb-6" data-reveal>
                {{ config('site.work.chapters_intro') }}
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
                            <span class="text-neutral-400 text-sm leading-snug sm:min-w-0 sm:flex-1">
                                {{ $project['description'] }}
                            </span>
                            <span class="work-chapters__cta font-mono text-caption text-accent uppercase tracking-widest sm:shrink-0" aria-hidden="true">
                                View <span class="arrow-nudge inline-block">→</span>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </x-site.section>
    @endif

    {{-- Section 02: Independent Products (The Dry Standard) --}}
    @php
        $dryStandard = ($productProjects ?? collect())->firstWhere('slug', 'the-dry-standard');
    @endphp
    @if($dryStandard)
        <x-site.section id="products" section-label="Independent Products" number="02" label="Independent Products" border="soft">
            <div class="mb-8 -mt-2 max-w-2xl" data-reveal>
                <p class="text-neutral-400 text-sm leading-relaxed">
                    {{ config('site.work.products_intro') }}
                </p>
            </div>

            <article class="surface-card-static bg-bg relative overflow-hidden p-6 sm:p-8 lg:p-10" data-reveal>
                <div class="grid lg:grid-cols-[1.1fr_1fr] gap-8 lg:gap-12 items-center">
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <img src="{{ $dryStandard['logo']['path'] }}" alt="" class="h-6 w-auto object-contain" aria-hidden="true">
                            <span class="font-mono text-caption text-accent uppercase tracking-widest">{{ $dryStandard['meta'] }}</span>
                        </div>
                        <h3 class="font-sans font-semibold text-2xl sm:text-3xl text-neutral-100 leading-tight mb-3">
                            <a href="{{ route('work.show', ['slug' => 'the-dry-standard']) }}" class="inline-flex items-center min-h-11 hover:text-accent focus-visible:text-accent hover:underline focus-visible:underline underline-offset-4 transition-colors">
                                {{ $dryStandard['title'] }}
                            </a>
                        </h3>
                        <p class="text-neutral-300 text-base leading-relaxed mb-4">
                            {{ $dryStandard['description'] }}
                        </p>
                        <p class="text-neutral-400 text-sm leading-relaxed mb-6 border-l-2 border-accent/40 pl-3">
                            Designed and built the product architecture, structured data model, editorial workflow, search/discovery experience, evidence model, validation tooling, and publishing system.
                        </p>
                        <div class="flex flex-wrap items-center gap-2 mb-8">
                            @foreach($dryStandard['tags'] as $tag)
                                <span class="surface-chip font-mono text-caption px-2.5 py-1 text-neutral-300">{{ $tag }}</span>
                            @endforeach
                        </div>
                        <div class="flex flex-wrap items-center gap-4">
                            <x-site.button variant="primary" :href="$dryStandard['artifact']['href']" target="_blank" rel="noopener noreferrer" data-no-ext>
                                Visit The Dry Standard <span aria-hidden="true">↗</span>
                            </x-site.button>
                            <x-site.button variant="secondary" :href="route('work.show', ['slug' => 'the-dry-standard'])">
                                Read case study →
                            </x-site.button>
                            <a href="https://github.com/karlhillx/drinkdrystandard.com" target="_blank" rel="noopener noreferrer" data-no-ext
                               class="font-mono text-caption text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors inline-flex items-center gap-1">
                                GitHub repo <span aria-hidden="true">↗</span>
                            </a>
                        </div>
                    </div>
                    <div class="relative rounded-lg overflow-hidden border border-neutral-800 shadow-2xl bg-neutral-950">
                        <img src="{{ $dryStandard['image'] }}" alt="{{ $dryStandard['image_alt'] }}"
                             class="w-full h-auto object-cover"
                             width="1200" height="630" loading="lazy">
                    </div>
                </div>
            </article>
        </x-site.section>
    @endif

    {{-- Section 03: Open Source & Engineering Tools --}}
    <x-site.section id="open-source" :section-label="'Open Source & Tools'" border="soft" number="03" :label="'Open Source & Tools'">
        <x-slot:actions>
            <a href="https://github.com/karlhillx" target="_blank" rel="noopener noreferrer" data-no-ext
               class="font-mono text-xs text-neutral-500 hover:text-accent transition-colors">
                github.com/karlhillx
            </a>
        </x-slot:actions>

        <p class="text-neutral-400 text-sm leading-relaxed max-w-2xl mb-8 -mt-2" data-reveal>
            {{ config('site.work.open_source_intro') }}
        </p>

        @if($githubRepos->isEmpty())
            <div class="surface-card-static bg-bg p-8" data-reveal>
                <p class="font-mono text-xs text-neutral-500 uppercase tracking-widest mb-2">Open Source</p>
                <p class="text-neutral-400 text-sm">Repositories are unavailable here right now. View the projects on GitHub.</p>
            </div>
        @else
            <div class="site-card-grid">
                @foreach($githubRepos as $repo)
                    <x-site.repo-card :repo="$repo" />
                @endforeach
            </div>
        @endif
    </x-site.section>

    {{-- Section 04: Earlier Work --}}
    @if(($earlierProjects ?? collect())->isNotEmpty())
        <x-site.section id="earlier" class="scroll-mt-32" section-label="Earlier Work" border="soft" number="04" label="Earlier Work">
            <p class="text-neutral-400 text-sm leading-relaxed max-w-2xl mb-6" data-reveal>
                {{ config('site.work.earlier_intro') }}
            </p>
            <ul class="work-chapters border-y border-neutral-800 divide-y divide-neutral-800" data-reveal>
                @foreach($earlierProjects as $project)
                    <li>
                        <a href="/work/{{ $project['slug'] }}"
                           class="work-chapters__link group flex flex-col gap-1 py-4 sm:flex-row sm:items-baseline sm:gap-x-5 sm:gap-y-1">
                            <span class="font-mono text-caption text-neutral-500 uppercase tracking-widest shrink-0 sm:w-40">
                                {{ $project['meta'] }}
                            </span>
                            <span class="font-sans font-semibold text-neutral-100 tracking-tight group-hover:text-accent transition-colors">
                                {{ $project['title'] }}
                            </span>
                            <span class="text-neutral-400 text-sm leading-snug sm:min-w-0 sm:flex-1">
                                {{ $project['description'] }}
                            </span>
                            <span class="work-chapters__cta font-mono text-caption text-accent uppercase tracking-widest sm:shrink-0" aria-hidden="true">
                                View <span class="arrow-nudge inline-block">→</span>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </x-site.section>
    @endif
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
