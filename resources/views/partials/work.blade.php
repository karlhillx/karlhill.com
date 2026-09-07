<x-site.section id="work" section-label="Selected Work" :number="$sectionNumber ?? '03'" :label="$heading ?? 'Selected Work'">
        @if($showViewAll ?? false)
            <x-slot:actions>
                <a href="/work"
                   class="font-mono text-xs text-neutral-500 hover:text-accent uppercase tracking-widest transition-colors shrink-0">
                    View all work <span class="arrow-nudge inline-block" aria-hidden="true">→</span>
                </a>
            </x-slot:actions>
        @endif
        @if(! empty($proof ?? null))
            <p class="text-neutral-400 text-sm leading-relaxed max-w-2xl mb-8 -mt-2" data-reveal>
                {{ $proof }}
            </p>
        @endif
        <div class="site-card-grid" style="view-transition-name: work-grid">
            @foreach($projects as $project)
                @php($cardUrl = \App\Support\ProjectCatalog::cardUrl($project))
                <x-site.work-card
                    :title="$project['title']"
                    :meta="$project['meta']"
                    :description="$project['description']"
                    :image="$project['image']"
                    :imagePosition="$project['imagePosition'] ?? 'object-top'"
                    :image-alt="$project['image_alt'] ?? null"
                    :tags="$project['tags']"
                    :logo="$project['logo']"
                    :href="$cardUrl"
                    :slug="$project['slug'] ?? null"
                    :external="\App\Support\ProjectCatalog::isExternalUrl($project)"
                    :variant="$project['card_variant'] ?? 'media'"
                    :constraints="$project['constraints'] ?? []"
                />
            @endforeach
        </div>
</x-site.section>
