<x-site.section id="work" class="studies" section-label="Selected work" border="none">
    <header class="studies__head">
        <div>
            <p class="eyebrow">Selected engineering</p>
            <h2>Selected work</h2>
        </div>
        <p>Current mission software, public NASA systems, and one product I own end to end.</p>
        <a href="/work" class="portfolio-text-link">All work <span aria-hidden="true">→</span></a>
    </header>

    <div class="studies__list">
        @foreach($featuredProjects as $project)
            @php
                $home = $project['home'] ?? $project['summary'] ?? [];
                $title = $project['card_title'] ?? $project['title'];
                $href = \App\Support\ProjectCatalog::cardUrl($project);
                $showImage = in_array($project['slug'], ['flood-mapping-system', 'the-dry-standard'], true)
                    && ! empty($project['image']);
            @endphp
            <article id="{{ $project['slug'] }}" @class(['study', 'study--split' => $showImage])>
                <p class="study__meta">{{ $home['role'] ?? $project['meta'] }}</p>
                <h3 id="work-card-title-{{ $project['slug'] }}">
                    <a href="{{ $href }}"
                       data-analytics-event="case_study_opened"
                       data-analytics-project="{{ $project['slug'] }}">
                        <span class="sr-only">Read case study: </span>{{ $title }}
                    </a>
                </h3>
                <p class="study__body">{{ $home['problem'] ?? $project['description'] }}</p>
                <p class="study__contribution">{{ $home['contribution'] ?? '' }}</p>
                @if(filled($home['evidence'] ?? $home['impact'] ?? null))
                    <p class="study__impact">{{ $home['evidence'] ?? $home['impact'] }}</p>
                @endif
                @if(filled($home['note'] ?? null))
                    <p class="study__note">{{ $home['note'] }}</p>
                @endif
                <p class="study__links">
                    <a href="{{ $href }}" class="portfolio-text-link">Read the case study <span aria-hidden="true">→</span></a>
                    @if($project['slug'] === 'the-dry-standard' && ! empty($project['artifact']['href']))
                        <a href="{{ $project['artifact']['href'] }}" class="portfolio-text-link" target="_blank" rel="noopener noreferrer">
                            {{ $project['artifact']['label'] }} <span aria-hidden="true">↗</span>
                        </a>
                    @endif
                </p>
                @if($project['slug'] === 'flood-mapping-system' && $nasaSupporting->isNotEmpty())
                    <p class="study__also">
                        Also public:
                        @foreach($nasaSupporting as $related)
                            <a href="{{ \App\Support\ProjectCatalog::cardUrl($related) }}">{{ $related['card_title'] ?? $related['title'] }}</a>@if(! $loop->last), @endif
                        @endforeach
                    </p>
                @endif
                @if($showImage)
                    <figure class="study__figure">
                        <x-site.responsive-image :src="$project['image']" :alt="$project['image_alt'] ?? $title"
                            width="1200" height="675" loading="lazy" :lqip="false"
                            sizes="(min-width: 800px) 520px, 92vw" />
                        @if(filled($project['image_caption'] ?? null))
                            <figcaption>{{ $project['image_caption'] }}</figcaption>
                        @endif
                    </figure>
                @endif
            </article>
        @endforeach
    </div>
</x-site.section>
