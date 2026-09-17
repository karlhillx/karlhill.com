@extends('layouts.site', ['meta' => $meta])

@push('head')
    <x-site.json-ld :data="\App\Support\ScholarlyArticleJsonLd::pageGraph()" />
    @foreach(\App\Support\ScholarlyArticleJsonLd::citationMetas() as $tag)
        <meta name="{{ $tag['name'] }}" content="{{ $tag['content'] }}">
    @endforeach
@endpush

@section('content')
    @php
        $authors = collect($research['authors'] ?? []);
        $authorLine = $authors->pluck('name')->filter()->join(', ', ', and ');
        $figures = collect($research['figures'] ?? [])->filter(fn ($figure) => filled($figure['src'] ?? null))->values();
        $karlOrcid = data_get($authors->firstWhere('self', true), 'url', 'https://orcid.org/0009-0002-6847-3368');
        $links = array_values(array_filter([
            ['label' => 'Paper', 'href' => $research['doi'] ?? null, 'detail' => $research['doi_id'] ?? null],
            ['label' => 'GWFMS', 'href' => $research['gwfms'] ?? null, 'detail' => 'Live map'],
            ['label' => 'ADS', 'href' => $research['ads'] ?? null, 'detail' => 'NASA ADS'],
            ['label' => 'ORCID', 'href' => $karlOrcid, 'detail' => 'Karl M. Hill'],
            ['label' => 'Zenodo', 'href' => $research['zenodo'] ?? null, 'detail' => 'Figure datasets'],
            ['label' => 'Work', 'href' => $research['work_path'] ?? '/work/flood-mapping-system', 'detail' => 'Case study', 'external' => false],
        ], fn ($link) => filled($link['href'])));
    @endphp

    <x-site.page-hero
        :eyebrow="$research['identity_label'] ?? $research['label']"
        title-class="site-page-hero__title site-page-hero__title--article font-sans font-semibold text-white tracking-tight"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => '/'],
            ['label' => 'Research'],
        ]">
        <x-slot:title>{{ $research['title'] }}</x-slot:title>

        @if(! empty($research['intro']))
            <p class="text-neutral-300 text-base sm:text-lg leading-relaxed max-w-3xl">
                {{ $research['intro'] }}
            </p>
        @endif

        @if(! empty($research['credit']))
            <p class="mt-4 font-mono text-xs text-neutral-500 uppercase tracking-widest max-w-3xl">
                {{ $research['credit_label'] ?? 'CRediT' }}:
                <span class="text-neutral-300 normal-case tracking-normal">{{ $research['credit'] }}</span>
            </p>
        @endif

        <p class="mt-4 text-neutral-500 text-sm leading-relaxed max-w-3xl">
            {{ $authorLine }}.
            {{ $research['journal'] }}
            {{ $research['published'] }}.
        </p>

        <div class="flex flex-wrap items-center gap-x-4 gap-y-3 mt-6 sm:mt-8">
            <x-site.button variant="primary" :href="$research['doi']" target="_blank" rel="noopener noreferrer" data-no-ext>
                {{ $research['doi_label'] }}
                <span aria-hidden="true">↗</span>
            </x-site.button>
            <x-site.button variant="secondary" :href="$research['gwfms']" target="_blank" rel="noopener noreferrer" data-no-ext>
                Open GWFMS
                <span aria-hidden="true">↗</span>
            </x-site.button>
            <x-site.button variant="link" :href="$research['work_path']">
                Flood mapping case study
            </x-site.button>
        </div>
    </x-site.page-hero>

    @if(! empty($research['results']))
        <x-site.section id="results" border="soft" label="Paper results">
            <dl class="grid grid-cols-2 sm:grid-cols-3 gap-8 max-w-3xl" data-reveal>
                @foreach($research['results'] as $result)
                    <div>
                        <dt class="font-mono text-xs text-neutral-500 uppercase tracking-widest">{{ $result['label'] }}</dt>
                        <dd class="mt-2 font-sans font-semibold text-[clamp(2rem,5vw,2.75rem)] leading-none tracking-tight text-neutral-100">{{ $result['value'] }}</dd>
                    </div>
                @endforeach
            </dl>
            @if(! empty($research['results_note']))
                <p class="mt-6 max-w-3xl text-neutral-500 text-sm leading-relaxed">{{ $research['results_note'] }}</p>
            @endif
        </x-site.section>
    @endif

    <x-site.section id="contribution" label="Software">
        <p class="max-w-3xl text-neutral-300 text-base leading-relaxed" data-reveal>
            {{ $research['contribution'] }}
        </p>
    </x-site.section>

    @if(! empty($research['writing']))
        <x-site.section id="writing" border="soft" label="Writing">
            <p class="max-w-3xl text-neutral-300 text-base leading-relaxed" data-reveal>
                {{ $research['writing'] }}
            </p>
        </x-site.section>
    @endif

    <x-site.section id="summary" label="What the paper reports">
        <p class="max-w-3xl text-neutral-300 text-base leading-relaxed" data-reveal>
            {{ $research['plain_english'] }}
        </p>
    </x-site.section>

    <x-site.section id="citation" label="Citation">
        <blockquote class="max-w-3xl text-neutral-300 text-base leading-relaxed" data-reveal>
            <p>{{ $research['citation_full'] }}</p>
            <p class="mt-4 font-mono text-xs text-neutral-500 uppercase tracking-widest">
                {{ $research['license_name'] }}
                ·
                <a href="{{ $research['license'] }}" target="_blank" rel="noopener noreferrer" data-no-ext class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">License</a>
            </p>
        </blockquote>
    </x-site.section>

    @if($figures->isNotEmpty())
        <x-site.section id="figures" border="soft" label="Figures">
            <div class="grid md:grid-cols-2 gap-6 lg:gap-8" data-reveal>
                @foreach($figures as $figure)
                    <figure class="overflow-hidden border border-neutral-800 bg-neutral-900/30">
                        <div @class(['bg-[#fff]' => str_contains((string) $figure['src'], 'geohorizons')])>
                            <x-site.responsive-image
                                :src="$figure['src']"
                                :alt="$figure['alt'] ?? $research['title']"
                                sizes="(min-width: 768px) 50vw, 100vw"
                                loading="lazy"
                                img-class="w-full h-auto"
                            />
                        </div>
                        <figcaption class="p-4 sm:p-5">
                            @if(! empty($figure['label']))
                                <p class="font-mono text-xs text-accent uppercase tracking-widest mb-2">{{ $figure['label'] }}</p>
                            @endif
                            <p class="text-neutral-400 text-sm leading-relaxed">{{ $figure['caption'] ?? '' }}</p>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
            @if(! empty($research['zenodo']))
                <p class="mt-6 text-neutral-500 text-sm leading-relaxed max-w-3xl">
                    Paper figure datasets:
                    <a href="{{ $research['zenodo'] }}" target="_blank" rel="noopener noreferrer" data-no-ext class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">{{ $research['zenodo_doi'] }}</a>.
                    The publisher already links this supplementary material from the article.
                </p>
            @endif
        </x-site.section>
    @endif

    <x-site.section id="links" label="Links">
        <ul class="grid sm:grid-cols-2 gap-px bg-neutral-800 border border-neutral-800" data-reveal>
            @foreach($links as $link)
                @php
                    $external = (bool) ($link['external'] ?? str_starts_with((string) $link['href'], 'http'));
                @endphp
                <li class="bg-bg">
                    <a href="{{ $link['href'] }}"
                       @if($external) target="_blank" rel="noopener noreferrer" data-no-ext @endif
                       class="flex items-baseline justify-between gap-4 p-5 min-h-11 hover:bg-neutral-900/40 transition-colors">
                        <span class="font-mono text-xs uppercase tracking-widest text-accent">{{ $link['label'] }}</span>
                        <span class="text-neutral-400 text-sm text-right">{{ $link['detail'] }}@if($external) <span aria-hidden="true">↗</span>@endif</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </x-site.section>

    @if(! empty($research['abstract']))
        <x-site.section id="abstract" border="soft" label="Publisher abstract">
            <details class="max-w-3xl border border-neutral-800 bg-neutral-900/30 p-5 sm:p-6" data-reveal>
                <summary class="font-mono text-xs text-accent uppercase tracking-widest cursor-pointer">
                    Reproduced under {{ $research['license_name'] }}
                </summary>
                <div class="mt-5 space-y-5 text-neutral-400 text-sm leading-relaxed">
                    @foreach($research['abstract'] as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>
            </details>
        </x-site.section>
    @endif
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
