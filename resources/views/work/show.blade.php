@php
    $study = $caseStudy;
    $liveUrl = ($project['url'] ?? null) && str_starts_with($project['url'], 'http') ? $project['url'] : null;
    $canonical = rtrim(config('app.url', 'https://karlhill.com'), '/').'/work/'.$project['slug'];
    $ogImage = $meta->ogImage;
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
        'url' => rtrim(config('app.url', 'https://karlhill.com'), '/'),
    ],
    'keywords' => implode(', ', $project['tags'] ?? []),
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
<x-site.speculation-rules :rules="\App\Support\SpeculationRules::forCaseStudy($project, $previousProject, $nextProject)" />
@endpush

@section('content')
    <article class="relative site-article">
        <div class="relative z-10 site-prose">
            <x-site.breadcrumbs :items="[
                ['label' => 'Home', 'url' => '/'],
                ['label' => 'Work', 'url' => '/work'],
                ['label' => $project['title']],
            ]" />

            <p class="font-mono text-accent text-xs tracking-widest uppercase mb-4">{{ $project['meta'] }}</p>
            <h1 class="font-display text-[clamp(2rem,5vw,3.5rem)] leading-[1.05] tracking-wide text-white mb-5">
                {{ $project['title'] }}
            </h1>
            <p class="text-neutral-400 text-base leading-relaxed mb-6 max-w-2xl">{{ $study['lede'] }}</p>

            @if(! empty($study['role']))
                <p class="flex items-baseline gap-3 mb-8 max-w-2xl">
                    <span class="font-mono text-[10px] text-accent uppercase tracking-widest shrink-0 pt-0.5">My role</span>
                    <span class="text-neutral-300 text-sm leading-relaxed">{{ $study['role'] }}</span>
                </p>
            @endif

            <div class="flex flex-wrap items-center gap-2 mb-10">
                @foreach($project['tags'] as $tag)
                    <a href="{{ route('work.tag', \App\Support\ProjectCatalog::tagSlug($tag)) }}"
                       class="surface-chip font-mono text-[10px] text-neutral-400 uppercase tracking-widest px-2 py-1 hover:border-accent hover:text-accent transition-colors">
                        {{ $tag }}
                    </a>
                @endforeach
            </div>

            <figure class="case-study-media mb-12 site-bleed sm:!mx-0" data-reveal>
                <x-site.responsive-image
                    :src="$project['image']"
                    :alt="'Screenshot of '.$project['title']"
                    sizes="(min-width: 832px) 48rem, 100vw"
                    loading="eager"
                    fetchpriority="high"
                    :img-style="'view-transition-name: work-img-'.$project['slug'].'; view-transition-class: card-media'"
                    img-class="case-study-media__img w-full aspect-[16/9] object-cover {{ $project['imagePosition'] ?? 'object-center' }} sm:rounded-sm border-y sm:border border-neutral-800/70"
                />
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
                <div class="grid grid-cols-2 {{ $metricCols }} gap-px bg-neutral-800 mb-12" data-reveal>
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

            @if(! empty($study['outcome'][0]))
                <blockquote class="relative mb-12 pl-6 border-l-2 border-accent/70" data-reveal>
                    <p class="font-display text-2xl sm:text-3xl leading-snug tracking-wide text-neutral-100">
                        {!! $study['outcome'][0] !!}
                    </p>
                </blockquote>
            @endif

            <div class="space-y-12">
                @if(! empty($study['leadership']))
                    <section data-reveal>
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
                                        <dt class="font-mono text-[10px] text-neutral-500 uppercase tracking-widest mb-2">{{ $label }}</dt>
                                        <dd class="text-neutral-300 text-sm leading-relaxed">{{ $study['leadership'][$key] }}</dd>
                                    </div>
                                @endif
                            @endforeach
                        </dl>
                    </section>
                @endif

                <section data-reveal>
                    <h2 class="font-mono text-accent text-xs tracking-widest uppercase mb-4">Problem</h2>
                    <x-site.arrow-list :items="$study['problem']" />
                </section>

                <section data-reveal>
                    <h2 class="font-mono text-accent text-xs tracking-widest uppercase mb-4">Approach</h2>
                    <x-site.arrow-list :items="$study['approach']" />
                </section>

                <section data-reveal>
                    <h2 class="font-mono text-accent text-xs tracking-widest uppercase mb-4">Outcome</h2>
                    <x-site.arrow-list :items="$study['outcome']" />
                </section>
            </div>

            <x-site.related-list
                class="mt-14 pt-8 border-t border-neutral-800"
                label="Related projects"
                :items="$relatedProjects->map(fn ($related) => [
                    'url' => '/work/'.$related['slug'],
                    'title' => $related['title'],
                    'excerpt' => $related['description'],
                ])->all()"
            />

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
    </article>
@endsection
