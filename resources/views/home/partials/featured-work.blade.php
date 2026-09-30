@php
    $missionProjects = \App\Support\ProjectCatalog::mission()->take(3);
    $productProjects = \App\Support\ProjectCatalog::products();
    $dryStandard = $productProjects->firstWhere('slug', 'the-dry-standard');
    $tools = app(\App\Support\GitHubRepository::class)->topRepos(3);
@endphp

<x-site.section
    id="work"
    section-label="What I Build"
    number="01"
    label="What I Build"
>
    <x-slot:actions>
        <a href="/work"
           class="font-mono text-xs text-neutral-500 hover:text-accent uppercase tracking-widest transition-colors shrink-0">
            All work <span class="arrow-nudge inline-block" aria-hidden="true">→</span>
        </a>
    </x-slot:actions>

    {{-- Connective narrative & proof links --}}
    <div class="mb-12 -mt-2 max-w-3xl" data-reveal>
        <p class="text-neutral-300 text-base sm:text-lg leading-relaxed mb-4">
            Software systems, engineering tools, and digital products — connected by a focus on structured data, automation, and operational reliability.
        </p>
        <p class="text-neutral-400 text-sm leading-relaxed">
            Jacobs is current. Public NASA systems:
            <a href="https://floodmapping.gsfc.nasa.gov/" target="_blank" rel="noopener noreferrer" data-no-ext
               class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">
                Flood map <span aria-hidden="true">↗</span>
            </a>
            <span aria-hidden="true"> · </span>
            <a href="https://ladsweb.modaps.eosdis.nasa.gov/search/" target="_blank" rel="noopener noreferrer" data-no-ext
               class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">
                Find Data <span aria-hidden="true">↗</span>
            </a>
            <span aria-hidden="true"> · </span>
            <a href="/research/global-flood-mapping"
               class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">
                Paper
            </a>
        </p>
    </div>

    {{-- Pillar 1: Mission & Professional Software --}}
    <div class="mb-14 sm:mb-16">
        <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-2 mb-6" data-reveal>
            <div>
                <p class="font-mono text-caption text-accent uppercase tracking-widest font-semibold">01 · Mission &amp; Professional Software</p>
                <h3 class="font-sans font-semibold text-xl sm:text-2xl tracking-tight text-neutral-100 mt-1">Aerospace &amp; Defense Systems</h3>
            </div>
            <a href="/work#work" class="font-mono text-caption text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                View mission case studies →
            </a>
        </div>

        <div class="site-card-grid" style="view-transition-name: work-grid">
            @foreach($missionProjects as $project)
                @php($cardUrl = \App\Support\ProjectCatalog::cardUrl($project))
                <x-site.work-card
                    :title="$project['card_title'] ?? $project['title']"
                    :meta="$project['meta']"
                    :description="$project['description']"
                    :image="$project['image']"
                    :imagePosition="$project['imagePosition'] ?? 'object-top'"
                    :image-alt="$project['image_alt'] ?? null"
                    :tags="$project['card_tags'] ?? $project['tags']"
                    :logo="$project['logo']"
                    :href="$cardUrl"
                    :slug="$project['slug'] ?? null"
                    :external="\App\Support\ProjectCatalog::isExternalUrl($project)"
                    :variant="$project['card_variant'] ?? 'media'"
                    :parallax="$project['card_parallax'] ?? true"
                />
            @endforeach
        </div>
    </div>

    {{-- Pillar 2: Independent Products (The Dry Standard) --}}
    @if($dryStandard)
        <div class="mb-14 sm:mb-16 pt-10 border-t border-neutral-800/60">
            <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-2 mb-6" data-reveal>
                <div>
                    <p class="font-mono text-caption text-accent uppercase tracking-widest font-semibold">02 · Independent Products</p>
                    <h3 class="font-sans font-semibold text-xl sm:text-2xl tracking-tight text-neutral-100 mt-1">Production Web Products</h3>
                </div>
                <a href="/work#products" class="font-mono text-caption text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                    Explore products →
                </a>
            </div>

            <article class="surface-card bg-bg group relative overflow-hidden p-6 sm:p-8 lg:p-10 transition-all duration-300" data-reveal>
                <div class="grid lg:grid-cols-[1.1fr_1fr] gap-8 lg:gap-12 items-center">
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <img src="{{ $dryStandard['logo']['path'] }}" alt="" class="h-6 w-auto object-contain" aria-hidden="true">
                            <span class="font-mono text-caption text-accent uppercase tracking-widest">{{ $dryStandard['meta'] }}</span>
                        </div>
                        <h4 class="font-sans font-semibold text-2xl sm:text-3xl text-neutral-100 group-hover:text-accent transition-colors leading-tight mb-3">
                            {{ $dryStandard['title'] }}
                        </h4>
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
                             class="w-full h-auto object-cover transform group-hover:scale-[1.02] transition-transform duration-500 ease-out"
                             width="1200" height="630" loading="lazy">
                    </div>
                </div>
            </article>
        </div>
    @endif

    {{-- Pillar 3: Open Source & Engineering Tools --}}
    <div class="pt-10 border-t border-neutral-800/60">
        <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-2 mb-6" data-reveal>
            <div>
                <p class="font-mono text-caption text-accent uppercase tracking-widest font-semibold">03 · Open Source &amp; Engineering Tools</p>
                <h3 class="font-sans font-semibold text-xl sm:text-2xl tracking-tight text-neutral-100 mt-1">Developer Tooling &amp; Simulation</h3>
            </div>
            <a href="/work#open-source" class="font-mono text-caption text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                View all tools →
            </a>
        </div>

        <div class="site-card-grid">
            @foreach($tools as $repo)
                <x-site.repo-card :repo="$repo" />
            @endforeach
        </div>
    </div>
</x-site.section>
