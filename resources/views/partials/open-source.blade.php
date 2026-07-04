<x-site.section id="open-source">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-16" data-reveal>
            <x-site.section-heading :number="$sectionNumber ?? '02'" label="Open Source" class="mb-0" />
            <a href="https://github.com/karlhillx" target="_blank" rel="noopener noreferrer"
               class="font-mono text-xs text-neutral-600 hover:text-accent transition-colors">
                github.com/karlhillx ↗
            </a>
        </div>

        @if($githubRepos->isEmpty())
            <div class="bg-bg rounded-2xl border border-neutral-800/80 p-6" data-reveal>
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
</x-site.section>
