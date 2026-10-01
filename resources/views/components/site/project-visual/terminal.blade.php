@props(['project', 'compact' => false])

<div class="project-visual project-visual--terminal {{ $compact ? 'project-visual--compact' : '' }}">
    <div class="project-visual__chrome" aria-hidden="true">
        <div class="flex items-center gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full bg-rose-500/80 inline-block"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80 inline-block"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80 inline-block"></span>
            <span class="ml-2 text-xs font-mono font-medium" style="color: var(--text);">~/dev/tooling (main)</span>
        </div>
        <span class="text-xs font-mono hidden sm:inline" style="color: var(--muted-2);">zsh · local-ci</span>
    </div>
    <div class="project-visual__stage project-visual__stage--terminal p-4 font-mono text-xs leading-relaxed select-none overflow-hidden" aria-hidden="true">
        <div class="space-y-2">
            <div>
                <p class="terminal-cmd">
                    <span class="terminal-prompt">$</span> testrisk --changed
                </p>
                <p class="terminal-out">
                    ↳ 28 suites analyzed · 4 prioritized for immediate execution
                </p>
            </div>
            <div>
                <p class="terminal-cmd">
                    <span class="terminal-prompt">$</span> pipeguard verify --policy ./standards.yaml
                </p>
                <p class="terminal-success">
                    ✔ policy-as-code: 0 violations across 12 rule gates
                </p>
            </div>
            <div>
                <p class="terminal-cmd">
                    <span class="terminal-prompt">$</span> bb-run --pipeline local-ci
                </p>
                <p class="terminal-success">
                    ✔ pipeline passed in 3.4s (3 packages hermetic)
                </p>
            </div>
        </div>
    </div>
    @unless($compact)
        <ul class="tooling-proof__index p-4 border-t border-neutral-800" aria-label="Featured repositories">
            @foreach(config('site.github.fallback_repos') as $repo)
                <li>
                    <a href="{{ $repo['url'] }}" target="_blank" rel="noopener noreferrer" data-no-ext>
                        <x-site.icon name="git" class="w-3.5 h-3.5 text-accent" />
                        {{ $repo['name'] }}
                        <x-site.icon name="external-link" class="w-3 h-3 text-neutral-500" />
                    </a>
                    <span>{{ $repo['language'] }}</span>
                </li>
            @endforeach
        </ul>
    @endunless
</div>
