<x-site.section id="developer-tooling" class="tools-collection" section-label="Open source" border="none">
    <header class="studies__head">
        <div>
            <p class="eyebrow">Independent open source</p>
            <h2>Developer tools</h2>
        </div>
        <p>Three focused tools. Public source, tests, and documentation are the evidence. No adoption or download claim.</p>
        <a href="{{ \App\Support\ProjectCatalog::cardUrl($toolingProject) }}" class="portfolio-text-link">
            Tooling case study <span aria-hidden="true">→</span>
        </a>
    </header>

    <ul class="tools-collection__list" aria-label="Featured repositories">
        @foreach($toolingRepos as $repo)
            <li>
                <a href="{{ $repo['url'] }}" target="_blank" rel="noopener noreferrer" data-no-ext>
                    {{ $repo['name'] }}
                    <span class="sr-only"> (source, opens in a new tab)</span>
                </a>
                <p>{{ $repo['description'] }}</p>
            </li>
        @endforeach
    </ul>
    <p class="tools-collection__more">
        <a href="/open-source" class="portfolio-text-link">Open Source <span aria-hidden="true">→</span></a>
    </p>
</x-site.section>
