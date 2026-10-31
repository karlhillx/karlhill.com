<x-site.section id="system" section-label="How I work" border="none" class="portfolio-delivery">
    <div class="portfolio-section-heading">
        <div>
            <p class="eyebrow eyebrow--muted">Working approach</p>
            <h2>How I work</h2>
        </div>
        <p>Direction stays close to the code: architecture, the path a change takes, and the people shipping it.</p>
        <a href="/work/jacobs-mission-software#delivery-system" class="portfolio-text-link">
            See the delivery system <span aria-hidden="true">→</span>
        </a>
    </div>
    <ul class="portfolio-delivery__pillars" aria-label="How I work">
        @foreach(config('site.home.how_i_work') as $index => $theme)
            <li>
                <span class="portfolio-delivery__num" aria-hidden="true">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                <div class="portfolio-delivery__body">
                    <strong>{{ $theme['title'] }}</strong>
                    <p>{{ $theme['body'] }}</p>
                    <a href="{{ $theme['href'] }}" class="portfolio-text-link">{{ $theme['label'] }} <span aria-hidden="true">→</span></a>
                </div>
            </li>
        @endforeach
    </ul>
</x-site.section>
