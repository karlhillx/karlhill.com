<li @class(['kit-link-email' => $link['email'], 'py-1'])>
    <a href="{{ $link['href'] }}"
       @if($link['external']) target="_blank" rel="me noopener noreferrer" @endif
       @if($link['download']) download @endif
       data-analytics-event="{{ $link['external'] ? 'recruiter_link_opened' : ($link['download'] ? 'resume_downloaded' : 'recruiter_link_opened') }}"
       data-analytics-location="kit-links"
       data-analytics-target="{{ \Illuminate\Support\Str::slug($link['label']) }}"
       class="group flex flex-wrap items-center justify-between gap-2 min-h-11 py-3">
        <span class="kit-link-label text-neutral-200 group-hover:text-accent transition-colors">
            {{ $link['label'] }}
            @if($link['external'])
                <span class="sr-only"> (opens in a new tab)</span>
            @endif
        </span>
        @if($link['meta'] !== '')
            <span class="kit-link-meta font-mono text-caption text-neutral-500 uppercase tracking-widest">{{ $link['meta'] }}</span>
        @endif
    </a>
</li>
