<section id="open-source" class="py-28 px-6 border-t border-neutral-800">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-16" data-reveal>
            <h2 class="font-mono text-orange-500 text-xs tracking-widest uppercase">06 — Open Source</h2>
            <a href="https://github.com/karlhillx" target="_blank" rel="noopener noreferrer"
               class="font-mono text-xs text-neutral-600 hover:text-orange-500 transition-colors">
                github.com/karlhillx ↗
            </a>
        </div>

        @if($githubRepos->isEmpty())
            <div class="bg-[#080808] rounded-2xl border border-neutral-800/80 p-6" data-reveal>
                <p class="font-mono text-xs text-neutral-500 uppercase tracking-widest mb-2">Open Source</p>
                <p class="text-neutral-400 text-sm">No public repositories were returned right now. Please check back shortly.</p>
            </div>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($githubRepos as $repo)
                    <x-site.repo-card :repo="$repo" />
                @endforeach
            </div>
        @endif
    </div>
</section>
