<section id="work" class="py-28 px-6 border-t border-neutral-800">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-16" data-reveal>
            <x-site.section-heading number="{{ $sectionNumber ?? '03' }}" label="{{ $heading ?? 'Selected Work' }}" class="mb-0" />
            @if($showViewAll ?? false)
                <a href="/work"
                   class="font-mono text-xs text-neutral-500 hover:text-accent uppercase tracking-widest transition-colors shrink-0">
                    View all work <span class="arrow-nudge inline-block" aria-hidden="true">→</span>
                </a>
            @endif
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($projects as $project)
                <x-site.work-card
                    :title="$project['title']"
                    :meta="$project['meta']"
                    :description="$project['description']"
                    :image="$project['image']"
                    :imagePosition="$project['imagePosition'] ?? 'object-top'"
                    :tags="$project['tags']"
                    :logo="$project['logo']"
                    :url="$project['url'] ?? null"
                    :slug="$project['slug'] ?? null"
                />
            @endforeach
        </div>
    </div>
</section>
