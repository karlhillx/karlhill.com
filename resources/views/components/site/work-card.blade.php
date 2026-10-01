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
    <div class="portfolio-card__visual">
        <x-site.project-visual :project="$project" :compact="$compact" />
    </div>
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
                <li class="inline-flex items-center gap-1.5">
                    @if($icon = \App\Support\TechIcons::name($tag))
                        <x-site.icon :name="$icon" class="w-3.5 h-3.5 text-accent shrink-0" />
                    @endif
                    <span>{{ $tag }}</span>
                </li>
            @endforeach
        </ul>
        @endunless
        <div class="portfolio-card__actions">
            <span class="portfolio-text-link" aria-hidden="true">Read case study <x-site.icon name="arrow-right" class="w-4 h-4 ml-1 inline-block" /></span>
        </div>
    </div>
</article>
