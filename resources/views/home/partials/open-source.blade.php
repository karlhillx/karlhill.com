<section id="open-source" class="py-28 px-6 border-t border-neutral-800">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-16" data-reveal>
            <h2 class="font-mono text-orange-500 text-xs tracking-widest uppercase">06 — Open Source</h2>
            <a href="https://github.com/karlhillx" target="_blank" rel="noopener noreferrer"
               class="font-mono text-xs text-neutral-600 hover:text-orange-500 transition-colors">
                github.com/karlhillx ↗
            </a>
        </div>
        <div id="github-repos" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-px bg-neutral-800" aria-busy="true" aria-label="Loading repositories">
            @for($i = 0; $i < 6; $i++)
                <div class="bg-[#080808] p-6 animate-pulse">
                    <div class="h-3 bg-neutral-800 rounded mb-3 w-3/4"></div>
                    <div class="h-2 bg-neutral-900 rounded mb-1.5 w-full"></div>
                    <div class="h-2 bg-neutral-900 rounded mb-4 w-2/3"></div>
                    <div class="h-2 bg-neutral-800 rounded w-1/4"></div>
                </div>
            @endfor
        </div>
    </div>
</section>
