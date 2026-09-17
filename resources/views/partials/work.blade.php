<x-site.section
    id="work"
    section-label="Selected Work"
    :number="($hideHeading ?? false) ? null : ($sectionNumber ?? '03')"
    :label="($hideHeading ?? false) ? null : ($heading ?? 'Selected Work')"
>
        @if($showViewAll ?? false)
            <x-slot:actions>
                <a href="/work"
                   class="font-mono text-xs text-neutral-500 hover:text-accent uppercase tracking-widest transition-colors shrink-0">
                    All work <span class="arrow-nudge inline-block" aria-hidden="true">→</span>
                </a>
            </x-slot:actions>
        @endif
        @if(! empty($proof ?? null) || ! empty($proofLinks ?? []))
            <p class="text-neutral-400 text-sm leading-relaxed max-w-2xl mb-8 -mt-2" data-reveal>
                @if(! empty($proof ?? null))
                    {{ $proof }}
                @endif
                @foreach($proofLinks ?? [] as $index => $link)
                    @php
                        $href = (string) ($link['href'] ?? '');
                        $external = (bool) ($link['external'] ?? str_starts_with($href, 'http'));
                    @endphp
                    @if($index > 0)
                        <span aria-hidden="true"> · </span>
                    @elseif(! empty($proof ?? null))
                        {{ ' ' }}
                    @endif
                    <a href="{{ $href }}"
                       @if($external) target="_blank" rel="noopener noreferrer" data-no-ext @endif
                       class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">
                        {{ $link['label'] }}@if($external) <span aria-hidden="true">↗</span>@endif
                    </a>
                @endforeach
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
                    :scope="$project['scope'] ?? []"
                />
            @endforeach
        </div>
</x-site.section>
