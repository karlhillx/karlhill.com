@php
    $study = $caseStudy;
    $liveUrl = \App\Support\ProjectCatalog::liveUrl($project);
    $liveLabel = \App\Support\ProjectCatalog::artifactLabel($project);
    $alsoLinks = \App\Support\ProjectCatalog::alsoLinks($project);
    $canonical = \App\Support\PageMeta::siteUrl().'/work/'.$project['slug'];
    $ogImage = $meta->ogImage;
    $decisions = $study['decisions'] ?? $study['approach'] ?? [];
    $imageAlt = $project['image_alt'] ?? ('Screenshot of '.$project['title']);

    $bodyH2s = array_values(array_filter($study['body_toc'] ?? [], fn ($item) => ($item['level'] ?? 2) === 2));
    $isJacobs = ($project['slug'] ?? '') === 'jacobs-mission-software';
    $jobScope = $isJacobs ? (config('site.experience.current.scope') ?? []) : [];
    $hasScope = filled($jobScope['owned'] ?? null)
        && filled($jobScope['influence'] ?? null)
        && filled($jobScope['reserved'] ?? null);
    $frameTitle = $isJacobs
        ? 'Technical delivery'
        : (($liveUrl ? parse_url($liveUrl, PHP_URL_HOST) : null) ?: $project['title']);

    // Flat TOC — leave-behind skim, not academic grouping.
    $toc = array_values(array_filter([
        ['id' => 'snapshot', 'text' => 'Snapshot'],
        ! empty($study['problem']) ? ['id' => 'problem', 'text' => 'Problem'] : null,
        ! empty($decisions) ? ['id' => 'decisions', 'text' => 'Decisions'] : null,
        ! empty($study['outcome']) ? ['id' => 'outcome', 'text' => 'Outcome'] : null,
        $hasScope ? ['id' => 'scope', 'text' => 'Scope'] : null,
        ! empty($study['leadership']) ? ['id' => 'leadership', 'text' => 'Team & contribution'] : null,
        ...$bodyH2s,
        $relatedProjects->isNotEmpty() ? ['id' => 'related', 'text' => 'Related'] : null,
    ]));
    $tocGroups = null;

    $gallery = collect($project['gallery'] ?? [])
        ->map(function ($shot) use ($imageAlt, $project) {
            if (is_string($shot)) {
                return [
                    'src' => $shot,
                    'alt' => $imageAlt,
                    'label' => $project['title'],
                    'position' => $project['imagePosition'] ?? 'object-center',
                ];
            }

            return array_merge([
                'alt' => $imageAlt,
                'position' => $project['imagePosition'] ?? 'object-center',
            ], $shot);
        })
        ->filter(fn ($shot) => filled($shot['src'] ?? null))
        ->values();

    if ($gallery->isEmpty() && ! $isJacobs && filled($project['image'] ?? null)) {
        $gallery = collect([[
            'src' => $project['image'],
            'alt' => $imageAlt,
            'label' => $project['title'],
            'position' => $project['imagePosition'] ?? 'object-center',
        ]]);
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
                    <header class="case-study-masthead">
                        <p class="case-study-masthead__meta font-mono text-accent text-xs tracking-widest uppercase">{{ $project['meta'] }}</p>
                        <h1 class="case-study-masthead__title font-sans font-semibold text-[clamp(1.75rem,3.8vw,2.55rem)] leading-[1.15] tracking-tight text-neutral-100 text-balance"
                            data-article-title
                            style="view-transition-name: work-title-{{ $project['slug'] }}">
                            {{ $project['title'] }}
                        </h1>
                        <p class="case-study-lede text-neutral-400">{{ $study['lede'] }}</p>
                        @if(! empty($study['role']) || ! empty($project['tags']))
                            <div class="case-study-masthead__meta-row">
                                @if(! empty($study['role']))
                                    <p class="case-study-masthead__role">{{ $study['role'] }}</p>
                                @endif
                                @if(! empty($project['tags']))
                                    <ul class="case-study-masthead__stack">
                                        @foreach($project['tags'] as $tag)
                                            <li>
                                                <a href="{{ route('work.tag', \App\Support\ProjectCatalog::tagSlug($tag)) }}"
                                                   class="surface-chip font-mono text-caption text-neutral-400 uppercase tracking-widest px-2 py-1 hover:border-accent hover:text-accent transition-colors">
                                                    {{ $tag }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endif
                        @if($liveUrl || $alsoLinks !== [])
                            <div class="case-study-masthead__actions flex flex-wrap items-center gap-3">
                                @if($liveUrl)
                                    <x-site.button variant="secondary" :href="$liveUrl" target="_blank" rel="noopener noreferrer" data-no-ext>
                                        {{ $liveLabel }} <span aria-hidden="true">↗</span>
                                    </x-site.button>
                                @endif
                                @foreach($alsoLinks as $link)
                                    <a href="{{ $link['href'] }}"
                                       class="font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors"
                                       target="_blank" rel="noopener noreferrer" data-no-ext>
                                        {{ $link['label'] }} <span aria-hidden="true">↗</span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </header>

                    @if(count($toc) >= 2)
                        <details class="article-toc-mobile lg:hidden mb-6 surface-card-static p-4">
                            <summary class="font-mono text-xs text-accent uppercase tracking-widest cursor-pointer select-none">
                                On this page
                            </summary>
                            <ol class="article-toc-list mt-3" hidden="until-found">
                                @foreach($toc as $item)
                                    <li @class([
                                        'article-toc-item',
                                        'article-toc-item--child' => ($item['level'] ?? 2) === 3,
                                    ])>
                                        <a href="#{{ $item['id'] }}"
                                           data-toc-link
                                           class="article-toc-link font-mono text-caption text-neutral-400 hover:text-accent transition-colors">
                                            {{ $item['text'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ol>
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
                                <div class="case-study-logo-plate" aria-hidden="true">
                                    <div class="case-study-logo-plate__grid"></div>
                                    <div class="case-study-logo-plate__glow"></div>
                                    <img src="{{ $project['card_image'] ?? $project['logo']['path'] }}"
                                         alt=""
                                         class="case-study-logo-plate__mark">
                                </div>
                            @else
                                <x-site.shot-carousel
                                    :slides="$gallery"
                                    :transition-name="'view-transition-name: work-img-'.$project['slug'].'; view-transition-class: card-media'"
                                />
                            @endif

                            <figcaption class="case-study-media__footer">
                                <div class="case-study-media__caption">
                                    <span class="case-study-media__label">Case study</span>
                                    @if(! empty($project['meta']))
                                        <span class="case-study-media__sep" aria-hidden="true">·</span>
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

                                @if(! empty($study['status']))
                                    <dl class="case-study-status" aria-label="Delivery status">
                                        @foreach($study['status'] as $row)
                                            <div class="case-study-status__row">
                                                <dt>
                                                    <span class="case-study-status__state">{{ $row['state'] }}</span>
                                                    {{ $row['label'] }}
                                                </dt>
                                                <dd>{{ $row['detail'] }}</dd>
                                            </div>
                                        @endforeach
                                    </dl>
                                @endif
                            </figcaption>
                        </figure>

                        @if(($project['slug'] ?? '') === 'flood-mapping-system' && \App\Support\SiteFeatures::webgpu())
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

                    <div class="case-study-brief">
                        <div class="case-study-brief__arc">
                            <section id="problem" class="case-study-brief__block scroll-mt-24" data-reveal>
                                <h2 class="case-study-brief__heading">
                                    <span class="case-study-brief__step" aria-hidden="true">01</span>
                                    Problem
                                </h2>
                                <x-site.arrow-list class="case-study-list" :items="$study['problem']" />
                            </section>

                            @if(! empty($decisions))
                                <section id="decisions" class="case-study-brief__block scroll-mt-24" data-reveal>
                                    <h2 class="case-study-brief__heading">
                                        <span class="case-study-brief__step" aria-hidden="true">02</span>
                                        Decisions
                                    </h2>
                                    <x-site.arrow-list class="case-study-list" :items="$decisions" />
                                </section>
                            @endif

                            <section id="outcome" class="case-study-brief__block scroll-mt-24" data-reveal>
                                <h2 class="case-study-brief__heading">
                                    <span class="case-study-brief__step" aria-hidden="true">03</span>
                                    Outcome
                                </h2>
                                <x-site.arrow-list class="case-study-list" :items="$study['outcome']" />
                            </section>
                        </div>

                        @if($hasScope)
                            <section id="scope" class="case-study-brief__block case-study-brief__block--solo scroll-mt-24" data-reveal>
                                <h2 class="case-study-brief__heading">Scope</h2>
                                <x-site.job-scope :heading="false" :scope="$jobScope" />
                            </section>
                        @endif

                        @if(! empty($study['leadership']))
                            <section id="leadership" class="case-study-brief__block case-study-brief__block--solo scroll-mt-24" data-reveal>
                                <h2 class="case-study-brief__heading">Team &amp; contribution</h2>
                                <dl class="case-study-leadership">
                                    @foreach([
                                        'mode' => 'Contribution',
                                        'team' => 'Team & partners',
                                        'unblocked' => 'What I improved',
                                        'decision' => 'Key decision',
                                    ] as $key => $label)
                                        @if(! empty($study['leadership'][$key]))
                                            <div class="case-study-leadership__cell">
                                                <dt class="case-study-leadership__label">{{ $label }}</dt>
                                                <dd class="case-study-leadership__body">{{ $study['leadership'][$key] }}</dd>
                                            </div>
                                        @endif
                                    @endforeach
                                </dl>
                                @if(! empty($study['leadership']['note']))
                                    <p class="case-study-leadership__note">{{ $study['leadership']['note'] }}</p>
                                @endif
                            </section>
                        @endif

                        @if(! empty($study['body_html']))
                            @php
                                $narrativeHtml = (string) $study['body_html'];
                                $narrativeDiagram = (! empty($study['diagram']['zones']) || ! empty($study['diagram']['stages']))
                                    ? $study['diagram']
                                    : [];
                                $narrativeCaption = $study['diagram']['caption'] ?? null;
                                $narrativeLead = $narrativeHtml;
                                $narrativeRest = '';
                                if ($narrativeDiagram !== []) {
                                    $parts = preg_split('/(?=<h2\b)/i', $narrativeHtml, 2);
                                    $narrativeLead = $parts[0] ?? $narrativeHtml;
                                    $narrativeRest = $parts[1] ?? '';
                                }
                            @endphp
                            <div class="case-study-narrative" data-reveal>
                                <div class="prose-karl min-w-0">
                                    {!! $narrativeLead !!}
                                </div>
                                @if($narrativeDiagram !== [])
                                    <figure class="case-study-flow-figure">
                                        <x-site.case-study-flow :diagram="$narrativeDiagram" />
                                        @if(filled($narrativeCaption))
                                            <figcaption class="case-study-flow-figure__caption">{{ $narrativeCaption }}</figcaption>
                                        @endif
                                    </figure>
                                @endif
                                @if($narrativeRest !== '')
                                    <div class="prose-karl min-w-0">
                                        {!! $narrativeRest !!}
                                    </div>
                                @endif
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
                        @if($liveUrl || $alsoLinks !== [])
                            <div class="flex flex-wrap items-center gap-3">
                                @if($liveUrl)
                                    <x-site.button variant="secondary" :href="$liveUrl" target="_blank" rel="noopener noreferrer" data-no-ext>
                                        {{ $liveLabel }} <span aria-hidden="true">↗</span>
                                    </x-site.button>
                                @endif
                                @foreach($alsoLinks as $link)
                                    <a href="{{ $link['href'] }}"
                                       class="font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors"
                                       target="_blank" rel="noopener noreferrer" data-no-ext>
                                        {{ $link['label'] }} <span aria-hidden="true">↗</span>
                                    </a>
                                @endforeach
                            </div>
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
