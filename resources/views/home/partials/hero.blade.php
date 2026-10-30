@php($person = config('site.person'))
@php($hero = config('site.hero'))
@php($facts = config('site.facts'))

<section id="hero" class="opening" aria-labelledby="hero-title">
    <div class="site-shell opening__grid">
        <div class="opening__copy">
            <p class="opening__name">{{ $person['name'] }}</p>
            <p class="opening__role">{{ $person['job_title'] }}</p>
            <h1 id="hero-title" class="opening__statement">{{ $hero['statement'] }}</h1>
            <p class="opening__lede">{{ $hero['lede'] }}</p>
            <div data-home-actions class="opening__actions">
                <x-site.button variant="primary" href="/work">Explore Projects</x-site.button>
                <x-site.button variant="secondary" href="/resume">View Resume</x-site.button>
            </div>
        </div>
        <aside class="opening__aside" aria-label="Portrait and current context">
            <x-site.responsive-image src="/img/webp/profile.webp" :alt="$person['name']"
                width="800" height="800" sizes="(min-width: 900px) 16rem, 70vw" loading="eager" :lqip="false"
                img-class="opening__photo" />
            <dl class="opening__facts">
                <div>
                    <dt>Now</dt>
                    <dd>Jacobs</dd>
                </div>
                <div>
                    <dt>Before</dt>
                    <dd>NASA {{ $facts['nasa_period'] }}</dd>
                </div>
                <div>
                    <dt>Based in</dt>
                    <dd>{{ $person['location'] }}</dd>
                </div>
            </dl>
        </aside>
    </div>
</section>
