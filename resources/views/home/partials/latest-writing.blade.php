@if($latestPost)
<x-site.section id="writing" aria-label="Latest writing">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-16" data-reveal>
            <x-site.section-heading number="03" label="Latest Writing" class="mb-0" />
            <a href="/blog"
               class="font-mono text-xs text-neutral-500 hover:text-accent uppercase tracking-widest transition-colors shrink-0">
                All writing <span class="arrow-nudge inline-block" aria-hidden="true">→</span>
            </a>
        </div>

        <a href="{{ $latestPost->url() }}"
           class="surface-card group block px-7 py-8 md:px-10 md:py-10"
           data-reveal>
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mb-5 font-mono text-[10px] uppercase tracking-widest text-neutral-500">
                <time datetime="{{ $latestPost->isoDate() }}">{{ $latestPost->publishedAt->format('M j, Y') }}</time>
                <span class="text-neutral-700" aria-hidden="true">·</span>
                <span>{{ $latestPost->readMinutes }} min read</span>
                @foreach($latestPost->tags as $tag)
                    <span class="surface-chip px-2 py-0.5 text-neutral-600">{{ $tag }}</span>
                @endforeach
            </div>
            <h3 class="font-display text-3xl md:text-4xl tracking-wide text-neutral-100 group-hover:text-accent transition-colors leading-tight mb-4">
                {{ $latestPost->title }}
            </h3>
            <p class="text-neutral-400 leading-relaxed max-w-2xl mb-6">{{ $latestPost->excerpt }}</p>
            <span class="font-mono text-xs text-accent uppercase tracking-widest">
                Read the post <span class="arrow-nudge inline-block" aria-hidden="true">→</span>
            </span>
        </a>
</x-site.section>
@endif
