@if($latestPost)
<section class="border-t border-neutral-800 bg-neutral-900/20 px-6 py-10" aria-label="Latest writing" data-reveal>
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <p class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-2">Latest writing</p>
            <a href="{{ $latestPost->url() }}" class="group block">
                <h2 class="font-display text-2xl md:text-3xl text-neutral-100 group-hover:text-orange-500 tracking-wide transition-colors leading-tight">
                    {{ $latestPost->title }} <span class="arrow-nudge inline-block text-orange-500" aria-hidden="true">→</span>
                </h2>
                <p class="text-neutral-500 text-sm mt-2 max-w-2xl leading-relaxed line-clamp-2 md:line-clamp-none">{{ $latestPost->excerpt }}</p>
            </a>
        </div>
        <a href="/blog" class="shrink-0 font-mono text-xs text-orange-500 uppercase tracking-widest border border-orange-500/40 hover:bg-orange-500/10 px-5 py-3 text-center transition-colors">
            All writing
        </a>
    </div>
</section>
@endif
