@props(['project', 'compact' => false])

<div class="project-visual project-visual--repos {{ $compact ? 'project-visual--compact' : '' }}">
    <p class="eyebrow">Public source</p>
    <p class="record__note">{{ $project['description'] }}</p>
    @unless($compact)
        <ul class="tooling-proof__index" aria-label="Featured repositories">
            @foreach(config('site.github.fallback_repos') as $repo)
                <li>
                    <a href="{{ $repo['url'] }}" target="_blank" rel="noopener noreferrer" data-no-ext>
                        {{ $repo['name'] }}
                        <span class="sr-only"> (source, opens in a new tab)</span>
                    </a>
                    <span>{{ $repo['language'] }}</span>
                </li>
            @endforeach
        </ul>
    @endunless
</div>
