<x-site.section id="notes" section-label="Writing" border="none" class="portfolio-writing-section">
    <div class="portfolio-section-heading">
        <div>
            <p class="eyebrow">Engineering notes</p>
            <h2>Writing from the work.</h2>
        </div>
        <p>Engineering systems, technical leadership, and teams — making integration visible, delivery repeatable, and engineers more effective.</p>
        <a href="/about" class="portfolio-text-link">About Karl <span aria-hidden="true">→</span></a>
    </div>
    <div class="portfolio-writing">
        @foreach($featuredPosts as $post)
            <a href="/blog/{{ $post->slug }}" class="portfolio-writing-link">
                <span>{{ $post->title }}</span>
                <span class="portfolio-writing-link__meta">
                    <time datetime="{{ $post->isoDate() }}">{{ $post->publishedAt->format('M Y') }}</time>
                    <span aria-hidden="true">→</span>
                </span>
            </a>
        @endforeach
        <a href="/blog" class="portfolio-text-link">All writing <span aria-hidden="true">→</span></a>
    </div>
</x-site.section>
