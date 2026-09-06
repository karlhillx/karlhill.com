@php
    $study = $caseStudy;
    $liveUrl = ($project['url'] ?? null) && str_starts_with($project['url'], 'http') ? $project['url'] : null;
    $canonical = \App\Support\PageMeta::siteUrl().'/work/'.$project['slug'];
    $ogImage = $meta->ogImage;
    $headlineOutcome = $study['outcome'][0] ?? null;
    $decisions = $study['decisions'] ?? $study['approach'] ?? [];
    $imageAlt = $project['image_alt'] ?? ('Screenshot of '.$project['title']);

    $hasBody = ! empty($study['body_html']);
    $bodyH2s = array_values(array_filter($study['body_toc'] ?? [], fn ($item) => ($item['level'] ?? 2) === 2));
    $isJacobs = ($project['slug'] ?? '') === 'jacobs-mission-software';
    $frameTitle = $isJacobs
        ? 'Unclassified // Delivery Architecture'
        : (($liveUrl ? parse_url($liveUrl, PHP_URL_HOST) : null) ?: $project['title']);

    if ($hasBody) {
        $tocGroups = array_values(array_filter([
            [
                'label' => 'Executive Summary',
                'items' => array_values(array_filter([
                    ['id' => 'snapshot', 'text' => 'Snapshot'],
                    ['id' => 'overview', 'text' => 'Overview & Stack'],
                    ! empty($study['problem']) ? ['id' => 'problem', 'text' => 'Problem & Context'] : null,
                    ! empty($decisions) ? ['id' => 'decisions', 'text' => 'Decisions & Approach'] : null,
                    ! empty($study['leadership']) ? ['id' => 'leadership', 'text' => 'Team & Leadership'] : null,
                ])),
            ],
            ! empty($bodyH2s) ? [
                'label' => 'Technical Deep Dive',
                'items' => $bodyH2s,
            ] : null,
            $relatedProjects->isNotEmpty() ? [
                'label' => 'Explore',
                'items' => [
                    ['id' => 'related', 'text' => 'Related Projects'],
                ],
            ] : null,
        ]));

        $toc = collect($tocGroups)->pluck('items')->flatten(1)->all();
    } else {
        $tocGroups = array_values(array_filter([
            [
                'label' => 'Executive Summary',
                'items' => array_values(array_filter([
                    ['id' => 'snapshot', 'text' => 'Snapshot'],
                    ['id' => 'overview', 'text' => 'Overview & Stack'],
                    ['id' => 'problem', 'text' => 'Problem'],
                    ! empty($decisions) ? ['id' => 'decisions', 'text' => 'Decisions'] : null,
                    ['id' => 'outcome', 'text' => 'Outcome'],
                    ! empty($study['leadership']) ? ['id' => 'leadership', 'text' => 'Team & Leadership'] : null,
                ])),
            ],
            $relatedProjects->isNotEmpty() ? [
                'label' => 'Explore',
                'items' => [
                    ['id' => 'related', 'text' => 'Related Projects'],
                ],
            ] : null,
        ]));

        $toc = collect($tocGroups)->pluck('items')->flatten(1)->all();
    }
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
    <article class="relative site-article case-study-page" data-article>
        <div class="article-sticky-title" data-article-sticky-title hidden>
            <div class="site-shell site-gutter flex items-center gap-3 min-h-10">
                <p class="font-mono text-caption text-accent uppercase tracking-widest shrink-0">Work</p>
                <p class="font-sans font-semibold text-sm sm:text-base tracking-tight text-neutral-200 truncate">{{ $project['title'] }}</p>
            </div>
        </div>

        <div class="relative z-10 max-w-6xl mx-auto">
            <div class="lg:grid lg:grid-cols-[11.5rem_minmax(0,1fr)] xl:grid-cols-[12.5rem_minmax(0,1fr)] lg:gap-x-8 xl:gap-x-10 lg:items-start">
                <aside class="hidden lg:block sticky top-24">
                    <x-site.article-toc :items="$toc" :groups="$tocGroups" />
                </aside>

                <div class="min-w-0 max-w-3xl">
                    <x-site.breadcrumbs :items="[
                        ['label' => 'Home', 'url' => '/'],
                        ['label' => 'Work', 'url' => '/work'],
                        ['label' => $project['title']],
                    ]" />

                    <p class="font-mono text-accent text-xs tracking-widest uppercase mb-2.5">{{ $project['meta'] }}</p>
                    <h1 class="font-sans font-semibold text-[clamp(1.6rem,3.4vw,2.35rem)] leading-[1.12] tracking-tight text-neutral-100 text-balance mb-3.5"
                        data-article-title
                        style="view-transition-name: work-title-{{ $project['slug'] }}">
                        {{ $project['title'] }}
                    </h1>
                    <p class="case-study-lede text-neutral-400 mb-6">{{ $study['lede'] }}</p>

                    @if(count($toc) >= 2)
                        <details class="article-toc-mobile lg:hidden mb-6 surface-card-static p-4">
                            <summary class="font-mono text-xs text-accent uppercase tracking-widest cursor-pointer select-none">
                                On this page
                            </summary>
                            <div class="article-toc__groups mt-3 space-y-4">
                                @foreach($tocGroups as $group)
                                    <div>
                                        <p class="font-mono text-[10px] uppercase tracking-widest text-neutral-500 font-semibold mb-1.5">{{ $group['label'] }}</p>
                                        <ol class="article-toc-list">
                                            @foreach($group['items'] as $item)
                                                <li class="article-toc-item">
                                                    <a href="#{{ $item['id'] }}"
                                                       data-toc-link
                                                       class="article-toc-link font-mono text-caption text-neutral-400 hover:text-accent transition-colors">
                                                        {{ $item['text'] }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ol>
                                    </div>
                                @endforeach
                            </div>
                        </details>
                    @endif

                    <section id="snapshot" class="scroll-mt-24 mb-10" aria-label="Project snapshot">
                        <figure class="case-study-media" data-reveal>
                            <div class="case-study-frame__chrome" aria-hidden="true">
                                <span class="case-study-frame__dots">
                                    <span></span><span></span><span></span>
                                </span>
                                <span class="case-study-frame__title">{{ $frameTitle }}</span>
                                @if(! empty($project['logo']['path']))
                                    <img src="{{ $project['logo']['path'] }}" alt=""
                                         loading="lazy" decoding="async"
                                         @if(! empty($project['logo']['filter'])) style="filter: {{ $project['logo']['filter'] }};" @endif
                                         class="case-study-frame__logo">
                                @endif
                            </div>

                            @if($isJacobs)
                                <div class="case-study-confidential p-4 sm:p-5">
                                    <div class="flex flex-wrap items-center justify-between gap-2 pb-3.5 border-b border-neutral-800/80">
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse"></span>
                                            <span class="font-mono text-caption text-neutral-200 uppercase tracking-widest font-semibold">Operating model</span>
                                        </div>
                                        <span class="font-mono text-caption text-neutral-400 uppercase tracking-widest">Jacobs National Security</span>
                                    </div>

                                    <div class="grid sm:grid-cols-3 gap-3 my-4">
                                        <div class="surface-card p-3.5 flex flex-col gap-3">
                                            <div>
                                                <p class="font-mono text-caption text-accent uppercase tracking-widest mb-1">01 · Ingest</p>
                                                <p class="font-sans font-semibold text-sm text-neutral-100 mb-1.5">Simulation &amp; Telemetry</p>
                                                <p class="text-neutral-400 text-xs leading-snug">Cloud-native streaming pipelines ingesting synthetic flight data and operational sensor feeds.</p>
                                            </div>
                                            <div class="flex flex-wrap gap-1.5 mt-auto pt-2.5 border-t border-neutral-800/60 font-mono text-caption text-neutral-400">
                                                <span>Python</span> &middot; <span>AWS</span>
                                            </div>
                                        </div>

                                        <div class="surface-card p-3.5 flex flex-col gap-3">
                                            <div>
                                                <p class="font-mono text-caption text-accent uppercase tracking-widest mb-1">02 · Pipeline</p>
                                                <p class="font-sans font-semibold text-sm text-neutral-100 mb-1.5">DevSecOps &amp; Gates</p>
                                                <p class="text-neutral-400 text-xs leading-snug">Deterministic CI/CD, PR coaching, multi-repo governance, and immutable artifact verification.</p>
                                            </div>
                                            <div class="flex flex-wrap gap-1.5 mt-auto pt-2.5 border-t border-neutral-800/60 font-mono text-caption text-neutral-400">
                                                <span>Kubernetes</span> &middot; <span>CI/CD</span>
                                            </div>
                                        </div>

                                        <div class="surface-card p-3.5 flex flex-col gap-3">
                                            <div>
                                                <p class="font-mono text-caption text-accent uppercase tracking-widest mb-1">03 · Release</p>
                                                <p class="font-sans font-semibold text-sm text-neutral-100 mb-1.5">Multi-Environment Ship</p>
                                                <p class="text-neutral-400 text-xs leading-snug">Continuous readiness across isolated and connected baselines without late-stage heroics.</p>
                                            </div>
                                            <div class="flex flex-wrap gap-1.5 mt-auto pt-2.5 border-t border-neutral-800/60 font-mono text-caption text-neutral-400">
                                                <span>High-Assurance</span>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="font-mono text-caption text-neutral-400 text-center border-t border-neutral-800/80 pt-3">
                                        Schematic &middot; Program names, customers, and mission data are unpublished.
                                    </p>
                                </div>
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
                                        img-class="case-study-media__img w-full aspect-[16/9] object-cover {{ $project['imagePosition'] ?? 'object-center' }} transition-[opacity,filter] duration-300 group-hover:opacity-90"
                                    />
                                    <span class="case-study-media__zoom font-mono text-caption uppercase tracking-widest">
                                        Expand <span aria-hidden="true">↗</span>
                                    </span>
                                </button>
                            @endif

                            <figcaption class="case-study-media__footer">
                                <div class="case-study-media__caption">
                                    <span class="case-study-media__label">Case study</span>
                                    @if(! empty($project['meta']))
                                        <span class="case-study-media__detail">{{ $project['meta'] }}</span>
                                    @endif
                                </div>

                                @if(! empty($study['metrics']))
                                    <dl class="case-study-facts" aria-label="Key facts">
                                        @foreach($study['metrics'] as $metric)
                                            @php
                                                $metricValue = (string) ($metric['value'] ?? '');
                                                $isNumericMetric = preg_match('/\d/', $metricValue) === 1;
                                            @endphp
                                            <div class="case-study-facts__row">
                                                <dt class="case-study-facts__label">{{ $metric['label'] }}</dt>
                                                <dd @class(['case-study-facts__value', 'case-study-facts__value--stat' => $isNumericMetric])@if($isNumericMetric) data-counter data-final="{{ $metricValue }}"@endif>{{ $metricValue }}</dd>
                                            </div>
                                        @endforeach
                                    </dl>
                                @endif
                            </figcaption>
                        </figure>

                        @if(($project['slug'] ?? '') === 'flood-mapping-system')
                            {{-- Ships hidden; webgpu-flood.js reveals it only after a GPU device
                                 is acquired, so unsupported browsers and reduced-motion users never
                                 see an empty frame. The photograph above stays canonical. --}}
                            <figure class="webgpu-flood case-study-media mt-6" data-webgpu-flood-root hidden>
                                <div class="case-study-frame__chrome" aria-hidden="true">
                                    <span class="case-study-frame__dots">
                                        <span></span><span></span><span></span>
                                    </span>
                                    <span class="case-study-frame__title">Live WebGPU field</span>
                                </div>
                                <canvas data-webgpu-flood
                                        class="webgpu-flood__canvas w-full aspect-[16/9]"
                                        aria-label="Generative flood-extent field, animated"></canvas>
                                <figcaption class="case-study-media__footer">
                                    <p class="case-study-media__detail">Generative illustration, not mission data.</p>
                                </figcaption>
                            </figure>
                        @endif
                    </section>

                    {{-- Outcome → Stack → Role: the hiring skim path --}}
                    <section id="overview" class="case-study-glance scroll-mt-24 mb-8" data-reveal aria-label="Case study overview">
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

                    <div class="case-study-brief">
                        <section id="problem" class="case-study-brief__block scroll-mt-24" data-reveal>
                            <h2 class="case-study-brief__heading">Problem</h2>
                            <x-site.arrow-list class="case-study-list" :items="$study['problem']" />
                        </section>

                        @if(! empty($decisions))
                            <section id="decisions" class="case-study-brief__block scroll-mt-24" data-reveal>
                                <h2 class="case-study-brief__heading">Decisions</h2>
                                <x-site.arrow-list class="case-study-list" :items="$decisions" />
                            </section>
                        @endif

                        <section id="outcome" class="case-study-brief__block scroll-mt-24" data-reveal>
                            <h2 class="case-study-brief__heading">Outcome</h2>
                            <x-site.arrow-list class="case-study-list" :items="$study['outcome']" />
                        </section>

                        @if(! empty($study['leadership']))
                            <section id="leadership" class="case-study-brief__block scroll-mt-24" data-reveal>
                                <h2 class="case-study-brief__heading">Team &amp; leadership</h2>
                                <dl class="case-study-leadership">
                                    @foreach([
                                        'mode' => 'Leadership mode',
                                        'team' => 'Team & partners',
                                        'unblocked' => 'What I unblocked',
                                        'decision' => 'Hard decision',
                                    ] as $key => $label)
                                        @if(! empty($study['leadership'][$key]))
                                            <div @class(['case-study-leadership__cell', 'case-study-leadership__cell--wide' => in_array($key, ['unblocked', 'decision'], true)])>
                                                <dt class="font-mono text-caption text-neutral-400 uppercase tracking-widest mb-1">{{ $label }}</dt>
                                                <dd class="text-neutral-300 text-sm leading-snug">{{ $study['leadership'][$key] }}</dd>
                                            </div>
                                        @endif
                                    @endforeach
                                </dl>
                            </section>
                        @endif

                        @if(! empty($study['body_html']))
                            <div class="case-study-narrative" data-reveal>
                                <div class="prose-karl min-w-0">
                                    {!! $study['body_html'] !!}
                                </div>
                            </div>
                        @endif
                    </div>

                    <div id="related" class="scroll-mt-24">
                        <x-site.related-list
                            class="mt-10 pt-6 border-t border-neutral-800"
                            label="Related projects"
                            :items="$relatedProjects->map(fn ($related) => [
                                'url' => '/work/'.$related['slug'],
                                'title' => $related['title'],
                                'excerpt' => $related['description'],
                            ])->all()"
                        />
                    </div>

                    <x-site.adjacent-nav
                        class="mt-10 pt-6 border-t border-neutral-800"
                        aria-label="Case study navigation"
                        :previous="$previousProject ? ['url' => '/work/'.$previousProject['slug'], 'title' => $previousProject['title']] : null"
                        :next="$nextProject ? ['url' => '/work/'.$nextProject['slug'], 'title' => $nextProject['title']] : null"
                    />

                    <div class="mt-10 pt-6 border-t border-neutral-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4" data-reveal>
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
