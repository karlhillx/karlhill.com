<x-site.section id="work" class="featured-work" section-label="Featured Work">
    <div class="portfolio-section-heading">
        <div>
            <p class="eyebrow">Selected engineering / 01–03</p>
            <h2>Featured Work</h2>
        </div>
        <p>What I built, the decisions I made, and the evidence.</p>
        <a href="/work" class="portfolio-text-link">All work <span aria-hidden="true">→</span></a>
    </div>
    <div class="portfolio-grid portfolio-grid--featured">
        @foreach($featuredProjects as $project)
            <x-site.work-card :project="$project" :compact="true" />
        @endforeach
    </div>
</x-site.section>
