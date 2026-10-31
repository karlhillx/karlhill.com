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
                <div class="study__copy">
                    <p class="study__meta">{{ $home['role'] ?? $project['meta'] }}</p>
                    <h3 id="work-card-title-{{ $project['slug'] }}">
                        <a href="{{ $href }}"
                           data-analytics-event="case_study_opened"
                           data-analytics-project="{{ $project['slug'] }}">
                            <span class="sr-only">Read case study: </span>{{ $title }}
                        </a>
                    </h3>
                    <div class="study__prose">
                        <p class="study__body">{{ trim(($home['problem'] ?? $project['description']).' '.($home['contribution'] ?? '')) }}</p>
                        @if(filled($home['evidence'] ?? $home['impact'] ?? null) || filled($home['note'] ?? null))
                            <p class="study__impact">{{ trim(($home['evidence'] ?? $home['impact'] ?? '').' '.($home['note'] ?? '')) }}</p>
                        @endif
                    </div>
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
                </div>
                @if($showImage)
                    @php
                        $shotHost = parse_url($project['artifact']['href'] ?? '', PHP_URL_HOST);
                    @endphp
                    <figure class="study__figure">
                        <div class="study__browser">
                            <div class="study__browser-bar" aria-hidden="true">
                                <span class="study__browser-dots" aria-hidden="true"><span></span><span></span><span></span></span>
                                @if(filled($shotHost))
                                    <span class="study__browser-url">{{ $shotHost }}</span>
                                @endif
                            </div>
                            <x-site.responsive-image :src="$project['image']" :alt="$project['image_alt'] ?? $title"
                                width="1200" height="675" loading="lazy" :lqip="false"
                                sizes="(min-width: 800px) 28rem, 92vw" />
                        </div>
                        @if(filled($project['image_caption'] ?? null))
                            <figcaption>{{ $project['image_caption'] }}</figcaption>
                        @endif
                    </figure>
                @endif
            </article>
        @endforeach
    </div>
</x-site.section>
