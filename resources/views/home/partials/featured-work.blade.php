@php
    $dry = \App\Support\ProjectCatalog::find('the-dry-standard');
@endphp

<x-site.section id="work" class="studies" section-label="Selected work" border="none">
    <header class="studies__head">
        <div>
            <p class="eyebrow">Selected engineering</p>
            <h2>Selected work</h2>
        </div>
        <p>Mission software, a public NASA system, an independent product, and open-source tools. Each one separates the problem, the responsibility, and the evidence.</p>
        <a href="/work#work" class="portfolio-text-link">All work <span aria-hidden="true">→</span></a>
    </header>

    <div class="studies__list">
        @foreach($featuredProjects as $project)
            @php
                $summary = $project['summary'] ?? null;
                $title = $project['card_title'] ?? $project['title'];
                $href = \App\Support\ProjectCatalog::cardUrl($project);
                $showImage = ($project['portfolio_group'] ?? '') === 'nasa' && ! empty($project['image']);
            @endphp
            <article id="{{ $project['slug'] }}" @class(['study', 'study--split' => $showImage])>
                <p class="study__meta">{{ $project['meta'] }}</p>
                <h3 id="work-card-title-{{ $project['slug'] }}">
                    <a href="{{ $href }}"
                       data-analytics-event="case_study_opened"
                       data-analytics-project="{{ $project['slug'] }}">
                        <span class="sr-only">Read case study: </span>{{ $title }}
                    </a>
                </h3>
                @if($summary)
                    <p class="study__body">{{ $summary['contribution'] }}</p>
                    <p class="study__impact">{{ $summary['impact'] }}</p>
                    @if(filled($summary['note'] ?? null))
                        <p class="study__note">{{ $summary['note'] }}</p>
                    @endif
                @else
                    <p class="study__body">{{ $project['description'] }}</p>
                @endif
                @if($showImage)
                    <figure class="study__figure">
                        <x-site.responsive-image :src="$project['image']" :alt="$project['image_alt'] ?? $title"
                            width="1200" height="675" loading="lazy" :lqip="false"
                            sizes="(min-width: 800px) 420px, 92vw" />
                    </figure>
                @endif
            </article>
            @if($dry && $project['slug'] === 'flood-mapping-system')
                <aside class="portfolio-product-callout" aria-labelledby="dry-callout-title">
                    <div class="portfolio-product-callout__visual">
                        <x-site.responsive-image :src="$dry['image']" :alt="$dry['image_alt']"
                            width="1200" height="675" loading="lazy" :lqip="false"
                            sizes="(min-width: 1024px) 640px, 92vw"
                            img-class="portfolio-product-callout__image" />
                    </div>
                    <div class="portfolio-product-callout__copy">
                        <p class="eyebrow">Independent product</p>
                        <h3 id="dry-callout-title">{{ $dry['card_title'] ?? $dry['title'] }}</h3>
                        <p>{{ $dry['summary']['problem'] }}</p>
                        <p>{{ $dry['summary']['contribution'] }}</p>
                        <a href="{{ \App\Support\ProjectCatalog::cardUrl($dry) }}" class="portfolio-text-link"
                           data-analytics-event="case_study_opened" data-analytics-project="{{ $dry['slug'] }}">
                            Read the case study <span aria-hidden="true">→</span>
                        </a>
                        <a href="{{ $dry['artifact']['href'] }}" class="portfolio-text-link" target="_blank" rel="noopener noreferrer">
                            {{ $dry['artifact']['label'] }} <span aria-hidden="true">↗</span>
                        </a>
                    </div>
                </aside>
            @endif
        @endforeach
    </div>
</x-site.section>
