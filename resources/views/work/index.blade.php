@extends('layouts.site', ['meta' => $meta])

@push('head')
    <x-site.speculation-rules :rules="\App\Support\SpeculationRules::forWorkIndex()" />
    <x-site.json-ld :data="[
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => 'Engineering portfolio — Karl Hill',
        'url' => \App\Support\PageMeta::siteUrl().'/work',
        'mainEntity' => \App\Support\ProjectCatalog::itemList($collections->pluck('projects')->flatten(1)->concat($earlierProjects)),
    ]" />
@endpush

@section('content')
    <x-site.page-hero :breadcrumbs="[['label' => 'Home', 'url' => '/'], ['label' => 'Work']]">
        <x-slot:title>Work, in context.</x-slot:title>
        <p class="site-page-hero__lede text-neutral-300">{{ config('site.work.lede') }}</p>
        <p class="portfolio-caption mt-4">Start with a collection. Each case study separates the problem, my contribution, and the evidence.</p>
    </x-site.page-hero>

    <nav class="portfolio-nav site-gutter" aria-label="Portfolio sections">
        <div class="portfolio-nav__links site-shell">
            @foreach($collections as $group => $collection)
                <a href="#{{ $collection['id'] }}" data-toc-link
                   @if($group === 'nasa') data-toc-sections="nasa chapters" @endif
                   @if($loop->first) aria-current="location" @endif>{{ $collection['title'] }}</a>
            @endforeach
            <a href="#earlier" data-toc-link>Earlier Work</a>
        </div>
    </nav>

    @foreach($collections as $group => $collection)
        <x-site.section :id="$collection['id']" :section-label="$collection['title']" class="portfolio-collection">
            <div class="portfolio-section-heading">
                <div>
                    <p class="portfolio-eyebrow">{{ $collection['kicker'] }}</p>
                    <h2>{{ $collection['title'] }}</h2>
                </div>
                <p>{{ $collection['intro'] }}</p>
            </div>
            <div class="portfolio-grid">
                @foreach($collection['projects']->filter(fn ($project) => \App\Support\ProjectCatalog::isListed($project)) as $project)
                    <x-site.work-card :project="$project" />
                @endforeach
            </div>
            @if($group === 'nasa')
                <section id="chapters" class="portfolio-chapters" aria-labelledby="chapters-title">
                    <h3 id="chapters-title" class="portfolio-eyebrow">Also at Goddard</h3>
                    @foreach($collection['projects']->reject(fn ($project) => \App\Support\ProjectCatalog::isListed($project)) as $project)
                        <a href="/work/{{ $project['slug'] }}" class="portfolio-chapters__row">
                            <strong>{{ $project['title'] }}</strong>
                            <span>{{ $project['description'] }}</span>
                            <span class="portfolio-text-link">Case study <span aria-hidden="true">→</span></span>
                        </a>
                    @endforeach
                </section>
            @endif
            @if($group === 'tooling')
                <x-site.tooling-list />
            @endif
        </x-site.section>
    @endforeach

    <x-site.section id="earlier" section-label="Earlier Work">
        <div class="portfolio-section-heading">
            <div><p class="portfolio-eyebrow">The foundation / Enterprise &amp; healthcare</p><h2>Earlier Work</h2></div>
            <p>Reliability, operational visibility, and domain-heavy applications were the work long before aerospace.</p>
        </div>
        @foreach($earlierProjects as $project)
            <a href="/work/{{ $project['slug'] }}" class="portfolio-chapters__row">
                <strong>{{ $project['title'] }}<small>{{ $project['meta'] }}</small></strong>
                <span>{{ $project['description'] }}</span>
                <span class="portfolio-text-link">Case study <span aria-hidden="true">→</span></span>
            </a>
        @endforeach
        <p class="portfolio-caption mt-6">For the complete chronology, education, and certifications: <a href="/resume" class="portfolio-text-link">Resume</a>.</p>
    </x-site.section>
@endsection
