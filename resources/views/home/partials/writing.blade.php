<x-site.section id="notes" section-label="Writing" border="soft">
    <div class="portfolio-section-heading">
        <div>
            <p class="eyebrow">Engineering notes</p>
            <h2>Writing from the work.</h2>
        </div>
        <p>Thirty years across enterprise, NASA Goddard, and aerospace mission software — the notes cover what held up in practice.</p>
        <a href="/about" class="portfolio-text-link">About Karl <span aria-hidden="true">→</span></a>
    </div>
    <div class="portfolio-writing">
        @foreach($latestPosts as $post)
            <a href="/blog/{{ $post->slug }}" class="portfolio-writing-link">
                <span>{{ $post->title }}</span>
                <span class="portfolio-writing-link__meta">
                    <time datetime="{{ $post->isoDate() }}">{{ $post->publishedAt->format('M Y') }}</time>
                    <span aria-hidden="true">→</span>
                </span>
            </a>
        @endforeach
        <a href="/blog" class="portfolio-text-link mt-6">All writing <span aria-hidden="true">→</span></a>
    </div>
</x-site.section>
