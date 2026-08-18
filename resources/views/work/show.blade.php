@php
    $study = $caseStudy;
    $liveUrl = ($project['url'] ?? null) && str_starts_with($project['url'], 'http') ? $project['url'] : null;
    $canonical = \App\Support\PageMeta::siteUrl().'/work/'.$project['slug'];
    $ogImage = $meta->ogImage;
    $headlineOutcome = $study['outcome'][0] ?? null;
    $decisions = $study['decisions'] ?? $study['approach'] ?? [];
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
                <p class="font-mono text-[10px] text-accent uppercase tracking-widest shrink-0">Work</p>
                <p class="font-display text-sm sm:text-base tracking-wide text-neutral-200 truncate">{{ $project['title'] }}</p>
            </div>
        </div>

        <div class="relative z-10 site-prose">
            <x-site.breadcrumbs :items="[
                ['label' => 'Home', 'url' => '/'],
                ['label' => 'Work', 'url' => '/work'],
                ['label' => $project['title']],
            ]" />

            <p class="font-mono text-accent text-xs tracking-widest uppercase mb-4">{{ $project['meta'] }}</p>
            <h1 class="font-display text-[clamp(2rem,5vw,3.5rem)] leading-[1.05] tracking-wide text-white mb-5"
                data-article-title
                style="view-transition-name: work-title-{{ $project['slug'] }}">
                {{ $project['title'] }}
            </h1>
            <p class="text-neutral-400 text-base leading-relaxed mb-10 max-w-2xl">{{ $study['lede'] }}</p>

            <details class="article-toc-mobile lg:hidden mb-6 surface-card-static p-4">
                <summary class="font-mono text-xs text-accent uppercase tracking-widest cursor-pointer select-none">
                    On this page
                </summary>
                <ol class="article-toc-list mt-3">
                    @foreach($toc as $item)
                        <li class="article-toc-item">
                            <a href="#{{ $item['id'] }}"
                               data-toc-link
                               class="article-toc-link font-mono text-[11px] text-neutral-500 hover:text-accent transition-colors">
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
                                           class="surface-chip font-mono text-[10px] text-neutral-400 uppercase tracking-widest px-2 py-1 hover:border-accent hover:text-accent transition-colors">
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
                            <button type="button"
                                    class="case-study-media__trigger group"
                                    data-lightbox-open
                                    data-lightbox-src="{{ $project['image'] }}"
                                    data-lightbox-alt="Screenshot of {{ $project['title'] }}">
                                <x-site.responsive-image
                                    :src="$project['image']"
                                    :alt="'Screenshot of '.$project['title']"
                                    sizes="(min-width: 832px) 48rem, 100vw"
                                    loading="eager"
                                    fetchpriority="high"
                                    :img-style="'view-transition-name: work-img-'.$project['slug'].'; view-transition-class: card-media'"
                                    img-class="case-study-media__img w-full aspect-[16/9] object-cover {{ $project['imagePosition'] ?? 'object-center' }} sm:rounded-sm border-y sm:border border-neutral-800/70 transition-[opacity,filter] duration-300 group-hover:opacity-90"
                                />
                                <span class="case-study-media__zoom font-mono text-[10px] uppercase tracking-widest">
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
                        </figure>

                        @if(! empty($study['metrics']))
                            @php($metricCols = count($study['metrics']) >= 3 ? 'sm:grid-cols-3' : 'sm:grid-cols-2')
                            <div class="grid grid-cols-2 {{ $metricCols }} gap-px bg-neutral-800 mt-8" data-reveal>
                                @foreach($study['metrics'] as $metric)
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

                        @if(($project['slug'] ?? '') === 'flood-mapping-system')
                            <div class="webgpu-flood mt-8" data-reveal>
                                <canvas data-webgpu-flood
                                        class="webgpu-flood__canvas w-full aspect-[16/9] sm:rounded-sm border-y sm:border border-neutral-800/70 bg-neutral-950"
                                        aria-label="Animated flood-extent field"></canvas>
                                <p data-webgpu-fallback hidden class="font-mono text-[11px] text-neutral-500 uppercase tracking-widest mt-3">
                                    Interactive field requires WebGPU. The still above is the canonical visual.
                                </p>
                                <p class="font-mono text-[10px] text-neutral-500 uppercase tracking-widest mt-3">
                                    WebGPU flood field — reduced-motion and unsupported browsers keep the photograph.
                                </p>
                            </div>
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
                                                <dt class="font-mono text-[10px] text-neutral-400 uppercase tracking-widest mb-2">{{ $label }}</dt>
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
            <button type="submit" class="media-lightbox__close font-mono text-[10px] uppercase tracking-widest" aria-label="Close">
                Close <span aria-hidden="true">✕</span>
            </button>
        </form>
        <img class="media-lightbox__img" data-lightbox-img alt="" width="1600" height="900">
    </dialog>
@endsection
