@php
    $flagship = $featuredProjects->first();
    $supporting = $featuredProjects->slice(1)->values();
    $dry = \App\Support\ProjectCatalog::find('the-dry-standard');
@endphp

<x-site.section id="work" class="featured-work" section-label="Featured Work" border="none">
    <div class="portfolio-section-heading portfolio-section-heading--featured">
        <div>
            <p class="eyebrow">Selected engineering</p>
            <h2>Featured Work</h2>
        </div>
        <p>Mission delivery, engineering systems, and externally verifiable NASA work.</p>
        <a href="/work" class="portfolio-text-link">All work <span aria-hidden="true">→</span></a>
    </div>

    <div class="portfolio-grid portfolio-grid--featured">
        @if($flagship)
            <x-site.work-card :project="$flagship" :compact="true" :flagship="true" />
        @endif
        @foreach($supporting as $project)
            <x-site.work-card :project="$project" :compact="true" />
        @endforeach
    </div>

    @if($dry)
        <aside class="portfolio-product-callout" aria-labelledby="dry-callout-title">
            <div class="portfolio-product-callout__visual">
                <x-site.responsive-image :src="$dry['image']" :alt="$dry['image_alt']"
                    width="1200" height="675" loading="lazy" :lqip="false"
                    sizes="(min-width: 1024px) 460px, (min-width: 640px) 560px, 90vw"
                    img-class="portfolio-product-callout__image" />
            </div>
            <div class="portfolio-product-callout__copy">
                <p class="eyebrow eyebrow--muted">Independent product</p>
                <h3 id="dry-callout-title">{{ $dry['card_title'] ?? $dry['title'] }}</h3>
                <p>{{ $dry['summary']['contribution'] ?? $dry['description'] }}</p>
            </div>
            <div class="portfolio-product-callout__meta">
                @if(! empty($dry['summary']['impact']))
                    <p class="portfolio-product-callout__impact">{{ $dry['summary']['impact'] }}</p>
                @endif
                <a href="{{ \App\Support\ProjectCatalog::cardUrl($dry) }}" class="portfolio-text-link"
                   data-analytics-event="case_study_opened" data-analytics-project="{{ $dry['slug'] }}">
                    Read the case study <span aria-hidden="true">→</span>
                </a>
            </div>
        </aside>
    @endif
</x-site.section>
