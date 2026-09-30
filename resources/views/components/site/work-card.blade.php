@props(['project', 'featured' => false])

@php
    $group = $project['portfolio_group'];
    $summary = $project['summary'] ?? null;
    $study = $project['case_study'];
    $href = \App\Support\ProjectCatalog::cardUrl($project);
    $isMission = $group === 'mission';
    $isTooling = $group === 'tooling';
    $wide = $isMission || $isTooling || (! $featured && $group === 'product');
@endphp

<article id="{{ $project['slug'] }}" @class([
    'portfolio-card',
    'portfolio-card--'.$group,
    'portfolio-card--wide' => $wide,
]) aria-labelledby="work-card-title-{{ $project['slug'] }}">
    <div class="portfolio-card__visual">
        @if($isMission)
            <div class="mission-proof">
                <p class="portfolio-eyebrow">Engineering delivery / Jacobs</p>
                <p class="mission-proof__headline">Many repositories.<br>One delivery standard.</p>
                <ol class="mission-proof__flow" aria-label="Simplified delivery workflow">
                    <li>Local checks</li><li>Review + CI</li><li>Integration</li><li>Release</li>
                </ol>
                <div class="mission-proof__facts">
                    <p><strong>~20</strong><span>repositories</span></p>
                    <p><strong>≥80%</strong><span>test coverage baseline</span></p>
                    <p><strong>2</strong><span>review approvals</span></p>
                </div>
                <p class="portfolio-caption">Simplified delivery view. Not program architecture.</p>
            </div>
        @elseif($isTooling)
            <div class="tooling-proof">
                <p class="portfolio-eyebrow">Source available / Independent tools</p>
                <p class="mission-proof__headline">Make the feedback<br>loop inspectable.</p>
                <ul class="tooling-proof__index" aria-label="Featured repositories">
                    @foreach(config('site.github.fallback_repos') as $repo)
                        <li><a href="{{ $repo['url'] }}" target="_blank" rel="noopener noreferrer" data-no-ext>{{ $repo['name'] }} <span aria-hidden="true">↗</span></a><span>{{ $repo['language'] }}</span></li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="portfolio-card__chrome" aria-hidden="true">
                <span>{{ $project['sector'] }}</span><span>Public system ↗</span>
            </div>
            <x-site.responsive-image
                :src="$project['image']"
                :alt="$project['image_alt'] ?? 'Screenshot of '.$project['title']"
                sizes="(min-width: 1280px) 590px, (min-width: 768px) 46vw, 92vw"
                width="1200" height="675" loading="lazy" :lqip="false"
                img-class="portfolio-card__image {{ $project['imagePosition'] ?? 'object-top' }}"
            />
        @endif
    </div>
    <div class="portfolio-card__body">
        <p class="portfolio-eyebrow">{{ $project['meta'] }}</p>
        <h3 id="work-card-title-{{ $project['slug'] }}">{{ $project['card_title'] ?? $project['title'] }}</h3>
        <p class="portfolio-card__role">{{ $study['role'] }}</p>
        @if($summary)
            <dl class="portfolio-card__brief">
                <div><dt>Problem</dt><dd>{{ $summary['problem'] }}</dd></div>
                <div><dt>Contribution</dt><dd>{{ $summary['contribution'] }}</dd></div>
            </dl>
            <div class="portfolio-card__impact">
                <p>{{ $summary['impact'] }}</p>
                <p class="portfolio-caption">{{ $summary['note'] }}</p>
            </div>
        @else
            <p class="portfolio-card__description">{{ $project['description'] }}</p>
        @endif
        <ul class="portfolio-card__stack" aria-label="Stack">
            @foreach($project['card_tags'] ?? $project['tags'] as $tag)
                <li>{{ $tag }}</li>
            @endforeach
        </ul>
        <div class="portfolio-card__actions">
            <a href="{{ $href }}" class="portfolio-text-link"
               data-analytics-event="case_study_opened" data-analytics-project="{{ $project['slug'] }}">
                Read case study <span class="sr-only">: {{ $project['title'] }}</span><span aria-hidden="true">→</span>
            </a>
        </div>
    </div>
</article>
