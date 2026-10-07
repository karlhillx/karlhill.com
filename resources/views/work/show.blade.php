@php
    $study = $caseStudy;
    $liveUrl = $page->liveUrl;
    $liveLabel = $page->liveLabel;
    $alsoLinks = $page->alsoLinks;
@endphp

@extends('layouts.site', ['meta' => $meta])

@push('head')
<script type="application/ld+json" nonce="{{ Vite::cspNonce() }}">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'CreativeWork',
    'name' => $project['title'],
    'description' => $study['lede'] ?? $project['description'],
    'image' => $meta->ogImage,
    'url' => $page->canonical,
    'author' => [
        '@type' => 'Person',
        'name' => config('site.person.name'),
        'url' => \App\Support\PageMeta::siteUrl(),
    ],
    'keywords' => implode(', ', $project['tags'] ?? []),
    'dateModified' => $study['updated'] ?? null,
    'isPartOf' => [
        '@type' => 'CollectionPage',
        'name' => 'Engineering portfolio',
        'url' => \App\Support\PageMeta::siteUrl().'/work',
    ],
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
                    <x-site.article-toc :items="$page->toc" />
                </aside>

                <div class="min-w-0 max-w-3xl">
                    <header class="case-study-masthead">
                        <x-site.breadcrumbs class="mb-4" :items="[
                            ['label' => 'Home', 'url' => '/'],
                            ['label' => 'Work', 'url' => '/work'],
                            $collection
                                ? ['label' => $collection['title'], 'url' => '/work#'.$collection['id']]
                                : ['label' => 'Earlier Work', 'url' => '/work#earlier'],
                            ['label' => $project['title']],
                        ]" />
                        <p class="case-study-masthead__meta eyebrow">{{ $project['meta'] }}</p>
                        <h1 class="case-study-masthead__title font-sans font-semibold text-[clamp(1.75rem,3.8vw,2.55rem)] leading-[1.15] tracking-tight text-neutral-100 text-balance"
                            data-article-title
                            style="view-transition-name: work-title-{{ $project['slug'] }}">
                            {{ $project['title'] }}
                        </h1>
                        <p class="case-study-lede text-neutral-400">{{ $study['lede'] }}</p>
                        @if(! empty($project['summary']))
                            <div class="case-study-evidence">
                                <p class="eyebrow">Impact &amp; evidence</p>
                                <p>{{ $project['summary']['impact'] }}</p>
                                <p class="portfolio-caption">{{ $project['summary']['note'] }}</p>
                            </div>
                        @endif
                        @if(! empty($study['role']) || ! empty($project['tags']))
                            <div class="case-study-masthead__meta-row">
                                @if(! empty($study['role']))
                                    <p class="case-study-masthead__role">{{ $study['role'] }}</p>
                                @endif
                                @if(! empty($project['tags']))
                                    <ul class="case-study-masthead__stack">
                                        @foreach($project['tags'] as $tag)
                                            <li>
                                                <span class="surface-chip eyebrow eyebrow--muted px-2 py-1">
                                                    {{ $tag }}
                                                </span>
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

                    @if(! empty($study['attribution']))
                        <aside class="surface-card-static p-4 mb-6" aria-label="Scope and attribution">
                            <p class="eyebrow mb-2">Scope &amp; attribution</p>
                            <p class="portfolio-caption">{{ $study['attribution'] }}</p>
                        </aside>
                    @endif

                    @if($project['portfolio_group'] === 'tooling')
                        <x-site.tooling-list />
                    @endif

                    @if(count($page->toc) >= 2)
                        <details class="article-toc-mobile lg:hidden mb-6 surface-card-static p-4">
                            <summary class="font-mono text-xs text-accent uppercase tracking-widest cursor-pointer select-none">
                                On this page
                            </summary>
                            <ol class="article-toc-list mt-3" hidden="until-found">
                                @foreach($page->toc as $item)
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

                    <div class="case-study-brief">
                        <div class="case-study-brief__arc">
                            <section id="problem" class="case-study-brief__block scroll-mt-24" data-reveal>
                                <h2 class="case-study-brief__heading">
                                    <span class="case-study-brief__step" aria-hidden="true">01</span>
                                    Problem
                                </h2>
                                <x-site.arrow-list class="case-study-list" :items="$study['problem']" />
                            </section>

                            @if($page->decisions !== [])
                                <section id="decisions" class="case-study-brief__block scroll-mt-24" data-reveal>
                                    <h2 class="case-study-brief__heading">
                                        <span class="case-study-brief__step" aria-hidden="true">02</span>
                                        Decisions
                                    </h2>
                                    <x-site.arrow-list class="case-study-list" :items="$page->decisions" />
                                </section>
                            @endif

                            @if($page->hasDiagram)
                                <figure id="delivery-system" class="case-study-flow-figure scroll-mt-24" data-reveal>
                                    <x-site.case-study-flow :diagram="$study['diagram']" />
                                    @if(filled($study['diagram']['caption'] ?? null))
                                        <figcaption class="case-study-flow-figure__caption">{{ $study['diagram']['caption'] }}</figcaption>
                                    @endif
                                </figure>
                            @endif

                            @include('work.partials.evidence')

                            <section id="outcome" class="case-study-brief__block scroll-mt-24" data-reveal>
                                <h2 class="case-study-brief__heading">
                                    <span class="case-study-brief__step" aria-hidden="true">03</span>
                                    Outcome
                                </h2>
                                <x-site.arrow-list class="case-study-list" :items="$study['outcome']" />
                            </section>
                        </div>

                        @if($page->hasScope)
                            <section id="scope" class="case-study-brief__block case-study-brief__block--solo scroll-mt-24" data-reveal>
                                <h2 class="case-study-brief__heading">Scope</h2>
                                <x-site.job-scope :heading="false" :scope="$page->jobScope" />
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
