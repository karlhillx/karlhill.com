<div class="tooling-directory" aria-label="Open-source repository directory">
    @foreach(config('site.github.fallback_repos') as $repo)
        <article class="tooling-directory__item">
            <p class="portfolio-eyebrow">{{ $repo['category'] }}</p>
            <h3><a href="{{ $repo['url'] }}" target="_blank" rel="noopener noreferrer" data-no-ext>{{ $repo['name'] }} <span aria-hidden="true">↗</span><span class="sr-only"> (source, opens in a new tab)</span></a></h3>
            <p>{{ $repo['description'] }}</p>
            <p class="portfolio-caption">{{ $repo['language'] }} / Source, tests &amp; documentation</p>
        </article>
    @endforeach
</div>
