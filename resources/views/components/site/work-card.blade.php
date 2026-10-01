@props(['project', 'compact' => false])

@php
    $group = $project['portfolio_group'];
    $summary = $project['summary'] ?? null;
    $study = $project['case_study'];
    $href = \App\Support\ProjectCatalog::cardUrl($project);
    $isMission = $group === 'mission';
    $isTooling = $group === 'tooling';
    $wide = ! $compact && ($isMission || $isTooling || $group === 'product');
@endphp

<article id="{{ $project['slug'] }}" @class([
    'portfolio-card',
    'portfolio-card--'.$group,
    'portfolio-card--wide' => $wide,
    'portfolio-card--compact' => $compact,
]) aria-labelledby="work-card-title-{{ $project['slug'] }}">
    @unless($compact)
    <div class="portfolio-card__visual">
        @if($isMission)
            <div class="mission-proof">
                <p class="eyebrow">Engineering delivery / Jacobs</p>
                <p class="mission-proof__headline">Many repositories.<br>One delivery standard.</p>
                <ol class="mission-proof__flow" aria-label="Simplified delivery workflow">
                    <li>Local checks</li><li>Review + CI</li><li>Integration</li><li>Release</li>
                </ol>
                <div class="mission-proof__facts">
                    <p><strong>{{ config('site.facts.repos_display') }}</strong><span>repositories</span></p>
                    <p><strong>{{ config('site.facts.coverage_display') }}</strong><span>test coverage baseline</span></p>
                    <p><strong>{{ config('site.facts.approvals_display') }}</strong><span>review approvals</span></p>
                </div>
                <p class="portfolio-caption">Simplified delivery view. Not program architecture.</p>
            </div>
        @elseif($isTooling)
            <div class="tooling-proof">
                <p class="eyebrow">Source available / Independent tools</p>
                <p class="mission-proof__headline">Make the feedback<br>loop inspectable.</p>
                <ul class="tooling-proof__index" aria-label="Featured repositories">
                    @foreach(config('site.github.fallback_repos') as $repo)
                        <li><a href="{{ $repo['url'] }}" target="_blank" rel="noopener noreferrer" data-no-ext>{{ $repo['name'] }} <span aria-hidden="true">↗</span></a><span>{{ $repo['language'] }}</span></li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="portfolio-card__chrome" aria-hidden="true">
                <span>{{ $project['sector'] }}</span><span>Project preview</span>
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
    @endunless
    <div class="portfolio-card__body">
        <p class="eyebrow eyebrow--muted">{{ $project['meta'] }}</p>
        <h3 id="work-card-title-{{ $project['slug'] }}">
            <a href="{{ $href }}" class="portfolio-card__title-link"
               data-analytics-event="case_study_opened" data-analytics-project="{{ $project['slug'] }}">
                <span class="sr-only">Read case study: </span>{{ $project['card_title'] ?? $project['title'] }}
            </a>
        </h3>
        @if(! empty($project['subtitle']))
            <p class="portfolio-caption">{{ $project['subtitle'] }}</p>
        @endif
        @unless($compact)
            <p class="portfolio-card__role">{{ $study['role'] }}</p>
        @endunless
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
        @unless($compact)
        <ul class="portfolio-card__stack" aria-label="Stack">
            @foreach($project['card_tags'] ?? $project['tags'] as $tag)
                <li>{{ $tag }}</li>
            @endforeach
        </ul>
        @endunless
        <div class="portfolio-card__actions">
            <span class="portfolio-text-link" aria-hidden="true">Read case study <span>→</span></span>
        </div>
    </div>
</article>
