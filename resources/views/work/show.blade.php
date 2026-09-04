@php
    $study = $caseStudy;
    $liveUrl = ($project['url'] ?? null) && str_starts_with($project['url'], 'http') ? $project['url'] : null;
    $canonical = \App\Support\PageMeta::siteUrl().'/work/'.$project['slug'];
    $ogImage = $meta->ogImage;
    $headlineOutcome = $study['outcome'][0] ?? null;
    $decisions = $study['decisions'] ?? $study['approach'] ?? [];
    $imageAlt = $project['image_alt'] ?? ('Screenshot of '.$project['title']);
    $toc = array_values(array_filter([
        ['id' => 'overview', 'text' => 'Overview', 'level' => 2],
        ['id' => 'snapshot', 'text' => 'Snapshot', 'level' => 2],
        ['id' => 'problem', 'text' => 'Problem', 'level' => 2],
        ! empty($decisions) ? ['id' => 'decisions', 'text' => 'Decisions', 'level' => 2] : null,
        ['id' => 'outcome', 'text' => 'Outcome', 'level' => 2],
        ! empty($study['leadership']) ? ['id' => 'leadership', 'text' => 'Leadership', 'level' => 2] : null,
        $relatedProjects->isNotEmpty() ? ['id' => 'related', 'text' => 'Related', 'level' => 2] : null,
    ]));
@endphp

@extends('layouts.site', ['meta' => $meta])

@push('head')
<script type="application/ld+json" nonce="{{ Vite::cspNonce() }}">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'CreativeWork',
    'name' => $project['title'],
    'description' => $study['lede'] ?? $project['description'],
    'image' => $ogImage,
    'url' => $canonical,
    'author' => [
        '@type' => 'Person',
        'name' => config('site.person.name'),
        'url' => \App\Support\PageMeta::siteUrl(),
    ],
    'keywords' => implode(', ', $project['tags'] ?? []),
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
<x-site.speculation-rules :rules="\App\Support\SpeculationRules::forCaseStudy($project, $previousProject, $nextProject)" />
@endpush

@section('content')
    <article class="relative site-article" data-article>
        <div class="article-sticky-title" data-article-sticky-title hidden>
            <div class="site-shell site-gutter flex items-center gap-3 min-h-10">
                <p class="font-mono text-caption text-accent uppercase tracking-widest shrink-0">Work</p>
                <p class="font-sans font-semibold text-sm sm:text-base tracking-tight text-neutral-200 truncate">{{ $project['title'] }}</p>
            </div>
        </div>

        <div class="relative z-10 site-prose">
            <x-site.breadcrumbs :items="[
                ['label' => 'Home', 'url' => '/'],
                ['label' => 'Work', 'url' => '/work'],
                ['label' => $project['title']],
            ]" />

            <p class="font-mono text-accent text-xs tracking-widest uppercase mb-4">{{ $project['meta'] }}</p>
            <h1 class="font-sans font-semibold text-[clamp(1.85rem,4.5vw,3rem)] leading-[1.15] tracking-tight text-neutral-100 text-balance mb-5"
                data-article-title
                style="view-transition-name: work-title-{{ $project['slug'] }}">
                {{ $project['title'] }}
            </h1>
            <p class="case-study-lede text-neutral-400 text-base leading-relaxed mb-10 max-w-2xl">{{ $study['lede'] }}</p>

            <details class="article-toc-mobile lg:hidden mb-6 surface-card-static p-4">
                <summary class="font-mono text-xs text-accent uppercase tracking-widest cursor-pointer select-none">
                    On this page
                </summary>
                <ol class="article-toc-list mt-3">
                    @foreach($toc as $item)
                        <li class="article-toc-item">
                            <a href="#{{ $item['id'] }}"
                               data-toc-link
                               class="article-toc-link font-mono text-caption text-neutral-500 hover:text-accent transition-colors">
                                {{ $item['text'] }}
                            </a>
                        </li>
                    @endforeach
                </ol>
            </details>

            <div class="lg:grid lg:grid-cols-[9.5rem_minmax(0,1fr)] lg:gap-x-12 lg:items-start">
                <x-site.article-toc :items="$toc" class="hidden lg:block sticky top-28" />

                <div class="min-w-0">
                    {{-- Outcome → Stack → Role: the hiring skim path --}}
                    <section id="overview" class="case-study-glance scroll-mt-28 mb-12" data-reveal aria-label="Case study overview">
                        <div class="case-study-glance__cell">
                            <h2 class="case-study-glance__label">Outcome</h2>
                            <p class="case-study-glance__body">
                                {{ $headlineOutcome ? strip_tags($headlineOutcome) : $study['lede'] }}
                            </p>
                        </div>
                        <div class="case-study-glance__cell">
                            <h2 class="case-study-glance__label">Stack</h2>
                            <ul class="case-study-glance__stack">
                                @foreach($project['tags'] as $tag)
                                    <li>
                                        <a href="{{ route('work.tag', \App\Support\ProjectCatalog::tagSlug($tag)) }}"
                                           class="surface-chip font-mono text-caption text-neutral-400 uppercase tracking-widest px-2 py-1 hover:border-accent hover:text-accent transition-colors">
                                            {{ $tag }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @if(! empty($study['role']))
                            <div class="case-study-glance__cell case-study-glance__cell--role">
                                <h2 class="case-study-glance__label">Role</h2>
                                <p class="case-study-glance__body">{{ $study['role'] }}</p>
                            </div>
                        @endif
                    </section>

                    <section id="snapshot" class="scroll-mt-28 mb-12" aria-label="Project snapshot">
                        <figure class="case-study-media" data-reveal>
                            @if(($project['slug'] ?? '') === 'jacobs-mission-software')
                                <div class="case-study-confidential border border-neutral-800 bg-neutral-900/60 rounded-xl p-6 sm:p-8 backdrop-blur-sm">
                                    <div class="flex flex-wrap items-center justify-between gap-3 pb-5 border-b border-neutral-800/80">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-2 h-2 rounded-full bg-accent animate-pulse"></span>
                                            <span class="font-mono text-caption text-neutral-200 uppercase tracking-widest font-semibold">Unclassified // Delivery Architecture</span>
                                        </div>
                                        <span class="font-mono text-caption text-neutral-400 uppercase tracking-widest">Jacobs National Security</span>
                                    </div>

                                    <div class="grid sm:grid-cols-3 gap-4 my-6">
                                        <div class="surface-card p-4 sm:p-5 flex flex-col justify-between">
                                            <div>
                                                <p class="font-mono text-caption text-accent uppercase tracking-widest mb-1.5">01 · Ingest</p>
                                                <p class="font-sans font-semibold text-sm sm:text-base text-neutral-100 mb-2">Simulation &amp; Telemetry</p>
                                                <p class="text-neutral-400 text-xs leading-relaxed">Cloud-native streaming pipelines ingesting synthetic flight data and operational sensor feeds.</p>
                                            </div>
                                            <div class="flex flex-wrap gap-1.5 mt-4 pt-3 border-t border-neutral-800/60 font-mono text-caption text-neutral-400">
                                                <span>Python</span> &middot; <span>AWS</span>
                                            </div>
                                        </div>

                                        <div class="surface-card p-4 sm:p-5 flex flex-col justify-between">
                                            <div>
                                                <p class="font-mono text-caption text-accent uppercase tracking-widest mb-1.5">02 · Pipeline</p>
                                                <p class="font-sans font-semibold text-sm sm:text-base text-neutral-100 mb-2">DevSecOps &amp; Gates</p>
                                                <p class="text-neutral-400 text-xs leading-relaxed">Deterministic CI/CD, PR coaching, multi-repo governance, and immutable artifact verification.</p>
                                            </div>
                                            <div class="flex flex-wrap gap-1.5 mt-4 pt-3 border-t border-neutral-800/60 font-mono text-caption text-neutral-400">
                                                <span>Kubernetes</span> &middot; <span>CI/CD</span>
                                            </div>
                                        </div>

                                        <div class="surface-card p-4 sm:p-5 flex flex-col justify-between">
                                            <div>
                                                <p class="font-mono text-caption text-accent uppercase tracking-widest mb-1.5">03 · Release</p>
                                                <p class="font-sans font-semibold text-sm sm:text-base text-neutral-100 mb-2">Multi-Environment Ship</p>
                                                <p class="text-neutral-400 text-xs leading-relaxed">Continuous readiness across isolated and connected baselines without late-stage heroics.</p>
                                            </div>
                                            <div class="flex flex-wrap gap-1.5 mt-4 pt-3 border-t border-neutral-800/60 font-mono text-caption text-neutral-400">
                                                <span>High-Assurance</span>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="font-mono text-caption text-neutral-400 text-center border-t border-neutral-800/80 pt-4">
                                        Operating model schematic &middot; Program names, customers, and mission data are unpublished.
                                    </p>
                                </div>
                                <figcaption class="case-study-media__caption site-gutter sm:!px-0 mt-3">
                                    <span class="case-study-media__label">Case study</span>
                                    <span class="case-study-media__detail">
                                        {{ $project['title'] }}
                                        @if(! empty($project['meta']))
                                            <span class="text-neutral-600" aria-hidden="true">·</span>
                                            {{ $project['meta'] }}
                                        @endif
                                    </span>
                                </figcaption>
                            @else
                                <button type="button"
                                        class="case-study-media__trigger group"
                                        data-lightbox-open
                                        data-lightbox-src="{{ $project['image'] }}"
                                        data-lightbox-alt="{{ $imageAlt }}">
                                    <x-site.responsive-image
                                        :src="$project['image']"
                                        :alt="$imageAlt"
                                        sizes="(min-width: 832px) 48rem, 100vw"
                                        loading="eager"
                                        fetchpriority="high"
                                        :img-style="'view-transition-name: work-img-'.$project['slug'].'; view-transition-class: card-media'"
                                        img-class="case-study-media__img w-full aspect-[16/9] object-cover {{ $project['imagePosition'] ?? 'object-center' }} sm:rounded-sm border-y sm:border border-neutral-800/70 transition-[opacity,filter] duration-300 group-hover:opacity-90"
                                    />
                                    @if(! empty($project['logo']['path']))
                                        <img src="{{ $project['logo']['path'] }}" alt="" aria-hidden="true"
                                             loading="lazy" decoding="async"
                                             @if(! empty($project['logo']['filter'])) style="filter: {{ $project['logo']['filter'] }};" @endif
                                             class="{{ $project['logo']['class'] ?? 'h-8' }} pointer-events-none absolute top-4 right-4 z-[1] w-auto object-contain opacity-80">
                                    @endif
                                    <span class="case-study-media__zoom font-mono text-caption uppercase tracking-widest">
                                        Expand <span aria-hidden="true">↗</span>
                                    </span>
                                </button>
                                <figcaption class="case-study-media__caption site-gutter sm:!px-0">
                                    <span class="case-study-media__label">Case study</span>
                                    <span class="case-study-media__detail">
                                        {{ $project['title'] }}
                                        @if(! empty($project['meta']))
                                            <span class="text-neutral-600" aria-hidden="true">·</span>
                                            {{ $project['meta'] }}
                                        @endif
                                    </span>
                                </figcaption>
                            @endif
                        </figure>

                        @if(! empty($study['metrics']))
                            @php
                                // Big-number treatment only earns its weight when the value *is* a
                                // number ("1.5M+", "$105M", "24/7"). Qualitative facts ("Self-serve",
                                // "Unpublished") read as a compact key/value strip instead.
                                [$stats, $facts] = collect($study['metrics'])
                                    ->partition(fn (array $m) => preg_match('/\d/', (string) ($m['value'] ?? '')) === 1);
                                $statCount = $stats->count();
                                $metricCols = match (true) {
                                    $statCount <= 1 => 'grid-cols-1',
                                    $statCount === 2 => 'grid-cols-2',
                                    default => 'grid-cols-2 sm:grid-cols-3',
                                };
                            @endphp
                            @if($statCount > 0)
                                <div class="grid {{ $metricCols }} gap-px bg-neutral-800 mt-8" data-reveal>
                                    @foreach($stats as $metric)
                                        <x-site.stat
                                            padding="p-6"
                                            :value="$metric['value']"
                                            :label="$metric['label']"
                                            value-class="text-3xl sm:text-4xl mb-1"
                                            label-class="text-neutral-400"
                                        />
                                    @endforeach
                                </div>
                            @endif
                            @if($facts->isNotEmpty())
                                <dl class="case-study-facts mt-8" data-reveal aria-label="Key facts">
                                    @foreach($facts as $metric)
                                        <div class="case-study-facts__row">
                                            <dt class="case-study-facts__label">{{ $metric['label'] }}</dt>
                                            <dd class="case-study-facts__value">{{ $metric['value'] }}</dd>
                                        </div>
                                    @endforeach
                                </dl>
                            @endif
                        @endif

                        @if(($project['slug'] ?? '') === 'flood-mapping-system')
                            {{-- Ships hidden; webgpu-flood.js reveals it only after a GPU device
                                 is acquired, so unsupported browsers and reduced-motion users never
                                 see an empty frame. The photograph above stays canonical. --}}
                            <figure class="webgpu-flood mt-8" data-webgpu-flood-root hidden>
                                <canvas data-webgpu-flood
                                        class="webgpu-flood__canvas w-full aspect-[16/9] sm:rounded-sm border-y sm:border border-neutral-800/70"
                                        aria-label="Generative flood-extent field, animated"></canvas>
                                <figcaption class="font-mono text-caption text-neutral-500 uppercase tracking-widest mt-3">
                                    Live WebGPU flood field — generative illustration, not mission data.
                                </figcaption>
                            </figure>
                        @endif
                    </section>

                    <div class="space-y-12">
                        <section id="problem" class="scroll-mt-28" data-reveal>
                            <h2 class="font-mono text-accent text-xs tracking-widest uppercase mb-4">Problem</h2>
                            <x-site.arrow-list :items="$study['problem']" />
                        </section>

                        @if(! empty($decisions))
                            <section id="decisions" class="scroll-mt-28" data-reveal>
                                <h2 class="font-mono text-accent text-xs tracking-widest uppercase mb-4">Decisions</h2>
                                <x-site.arrow-list :items="$decisions" />
                            </section>
                        @endif

                        <section id="outcome" class="scroll-mt-28" data-reveal>
                            <h2 class="font-mono text-accent text-xs tracking-widest uppercase mb-4">Outcome</h2>
                            <x-site.arrow-list :items="$study['outcome']" />
                        </section>

                        @if(! empty($study['leadership']))
                            <section id="leadership" class="scroll-mt-28" data-reveal>
                                <h2 class="font-mono text-accent text-xs tracking-widest uppercase mb-4">Team &amp; leadership</h2>
                                <dl class="grid sm:grid-cols-2 gap-6 max-w-3xl">
                                    @foreach([
                                        'mode' => 'Leadership mode',
                                        'team' => 'Team & partners',
                                        'unblocked' => 'What I unblocked',
                                        'decision' => 'Hard decision',
                                    ] as $key => $label)
                                        @if(! empty($study['leadership'][$key]))
                                            <div @class(['sm:col-span-2' => in_array($key, ['unblocked', 'decision'], true)])>
                                                <dt class="font-mono text-caption text-neutral-400 uppercase tracking-widest mb-2">{{ $label }}</dt>
                                                <dd class="text-neutral-300 text-sm leading-relaxed">{{ $study['leadership'][$key] }}</dd>
                                            </div>
                                        @endif
                                    @endforeach
                                </dl>
                            </section>
                        @endif
                    </div>

                    <div id="related" class="scroll-mt-28">
                        <x-site.related-list
                            class="mt-14 pt-8 border-t border-neutral-800"
                            label="Related projects"
                            :items="$relatedProjects->map(fn ($related) => [
                                'url' => '/work/'.$related['slug'],
                                'title' => $related['title'],
                                'excerpt' => $related['description'],
                            ])->all()"
                        />
                    </div>

                    <x-site.adjacent-nav
                        class="mt-14 pt-8 border-t border-neutral-800"
                        aria-label="Case study navigation"
                        :previous="$previousProject ? ['url' => '/work/'.$previousProject['slug'], 'title' => $previousProject['title']] : null"
                        :next="$nextProject ? ['url' => '/work/'.$nextProject['slug'], 'title' => $nextProject['title']] : null"
                    />

                    <div class="mt-14 pt-8 border-t border-neutral-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4" data-reveal>
                        <a href="/work" class="font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                            ← All work
                        </a>
                        @if($liveUrl)
                            <a href="{{ $liveUrl }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 font-mono text-xs text-accent border border-accent/40 hover:bg-accent/10 px-5 py-3 uppercase tracking-widest transition-colors">
                                Visit live project <span aria-hidden="true">↗</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </article>

    <dialog id="media-lightbox" class="media-lightbox" data-media-lightbox aria-label="Expanded project screenshot">
        <form method="dialog" class="media-lightbox__chrome">
            <button type="submit" class="media-lightbox__close font-mono text-caption uppercase tracking-widest" aria-label="Close">
                Close <span aria-hidden="true">✕</span>
            </button>
        </form>
        <img class="media-lightbox__img" data-lightbox-img alt="" width="1600" height="900">
    </dialog>
@endsection
