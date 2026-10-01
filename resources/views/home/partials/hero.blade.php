@php($person = config('site.person'))
@php($hero = config('site.hero'))

<section id="hero" class="portfolio-hero site-gutter" aria-labelledby="hero-title">
    <div class="site-shell portfolio-hero__layout">
        <div>
            <div class="portfolio-hero__identity">
                <x-site.responsive-image src="/img/webp/profile.webp" :alt="$person['name']"
                    width="48" height="48" sizes="48px" loading="eager" :lqip="false"
                    img-class="portfolio-portrait rounded-full object-cover" />
                <p class="eyebrow">{{ $person['job_title'] }} <span>/ Jacobs</span></p>
            </div>
            <h1 id="hero-title" class="portfolio-hero__name">{{ $hero['headline'] }}</h1>
            <p class="portfolio-hero__statement">{{ $hero['statement'] }}</p>
            <p class="portfolio-hero__lede">{{ $hero['lede'] }}</p>
            <div data-home-actions class="portfolio-hero__actions flex flex-wrap items-center gap-5">
                <x-site.button variant="primary" href="/work">Explore the work <span aria-hidden="true">→</span></x-site.button>
                <a href="/#contact" class="portfolio-text-link">Contact Karl</a>
            </div>
        </div>
        <nav class="portfolio-hero__index" aria-label="Explore the portfolio">
            <p class="eyebrow">A body of work / 1996–today</p>
            @foreach($collections as $collection)
                <a href="/work#{{ $collection['id'] }}">
                    <span class="portfolio-hero__index-number" aria-hidden="true">0{{ $loop->iteration }}</span>
                    <span>{{ $collection['title'] }}</span>
                    <span aria-hidden="true">↗</span>
                </a>
            @endforeach
            <p class="portfolio-hero__location">{{ $person['location'] }} · Code, systems, and technical leadership.</p>
        </nav>
    </div>
</section>
