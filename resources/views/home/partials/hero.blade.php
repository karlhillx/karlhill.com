@php($person = config('site.person'))
@php($hero = config('site.hero'))

<section id="hero" class="portfolio-hero site-gutter" aria-labelledby="hero-title">
    <div class="portfolio-hero__atmosphere" aria-hidden="true"></div>
    <div class="site-shell portfolio-hero__layout">
        <div class="portfolio-hero__primary">
            <div class="portfolio-hero__identity">
                <x-site.responsive-image src="/img/webp/profile.webp" :alt="$person['name']"
                    width="64" height="64" sizes="64px" loading="eager" :lqip="false"
                    img-class="portfolio-portrait object-cover" />
                <div class="portfolio-hero__identity-text">
                    <p class="portfolio-hero__role">{{ $person['job_title'] }}</p>
                </div>
            </div>
            <h1 id="hero-title" class="portfolio-hero__statement">{{ $hero['statement'] }}</h1>
            <p class="portfolio-hero__lede">{{ $hero['lede'] }}</p>
            <p class="portfolio-caption mt-3 max-w-xl">{{ $hero['proof'] }}</p>
            <div data-home-actions class="portfolio-hero__actions flex flex-wrap items-center">
                <x-site.button variant="primary" href="/work">Explore the work <span aria-hidden="true">→</span></x-site.button>
                <a href="/#contact" class="portfolio-text-link">Contact Karl</a>
            </div>
        </div>
        <nav class="portfolio-hero__index" aria-label="Explore the portfolio">
            <p class="eyebrow eyebrow--muted">Portfolio index / 1997–present</p>
            @foreach($collections as $collection)
                <a href="/work#{{ $collection['id'] }}">
                    <span class="portfolio-hero__index-number" aria-hidden="true">0{{ $loop->iteration }}</span>
                    <span class="portfolio-hero__index-title">{{ $collection['title'] }}</span>
                    <span class="portfolio-hero__index-arrow" aria-hidden="true">↗</span>
                </a>
            @endforeach
            <div class="portfolio-hero__index-footer">
                <p class="eyebrow eyebrow--muted">Based in</p>
                <p class="portfolio-hero__location">{{ $person['location'] }}</p>
            </div>
        </nav>
    </div>
</section>
