<x-site.section id="work" class="featured-work" section-label="Featured Work">
    <div class="portfolio-section-heading">
        <div>
            <p class="eyebrow">Selected engineering / 01–03</p>
            <h2>Featured Work</h2>
        </div>
        <p>Three studies: what I built, what I led, and where to inspect the evidence. The full catalog is on the work page.</p>
        <a href="/work" class="portfolio-text-link">All work <span aria-hidden="true">→</span></a>
    </div>
    <div class="portfolio-grid">
        @foreach($featuredProjects as $project)
            <x-site.work-card :project="$project" :featured="true" />
        @endforeach
    </div>
</x-site.section>
