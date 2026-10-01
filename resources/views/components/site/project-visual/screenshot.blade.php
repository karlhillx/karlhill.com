@props(['project', 'compact' => false])

@php
    $href = $project['artifact']['href'] ?? null;
    $domain = $href ? parse_url($href, PHP_URL_HOST) : null;
    $path = $href ? parse_url($href, PHP_URL_PATH) : '';
    $displayUrl = $domain ? ($domain . ($path && $path !== '/' ? $path : '')) : ($project['sector'] ?? 'System Preview');
    $fit = $project['imageFit'] ?? 'object-cover';
    $stageBg = $project['imageBg'] ?? null;
@endphp

<div class="project-visual project-visual--screenshot {{ $compact ? 'project-visual--compact' : '' }}" aria-hidden="true">
    <div class="project-visual__chrome">
        <div class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full inline-block" style="background: var(--border-strong);"></span>
            <span class="w-2 h-2 rounded-full inline-block" style="background: var(--border-strong);"></span>
            <span class="w-2 h-2 rounded-full inline-block" style="background: var(--border-strong);"></span>
            <div class="ml-2 flex items-center gap-1.5 px-2 py-0.5 rounded text-[11px] font-mono max-w-[240px] truncate" style="background: light-dark(rgba(20, 20, 20, 0.05), rgba(255, 255, 255, 0.05)); border: 1px solid var(--border); color: var(--text);">
                <x-site.icon name="shield-check" class="w-3 h-3 text-emerald-500 shrink-0" />
                <span class="truncate">{{ $displayUrl }}</span>
            </div>
        </div>
        <span class="text-xs font-mono hidden sm:inline" style="color: var(--muted-2);">{{ $project['sector'] }}</span>
    </div>
    <div class="project-visual__stage project-visual__stage--screenshot overflow-hidden" @if($stageBg) style="background: {{ $stageBg }};" @endif>
        <x-site.responsive-image
            :src="$project['image']"
            :alt="$project['image_alt'] ?? 'Screenshot of '.$project['title']"
            sizes="(min-width: 1280px) 590px, (min-width: 768px) 46vw, 92vw"
            width="1200" height="675" loading="lazy" :lqip="false"
            img-class="portfolio-card__image w-full h-full {{ $fit }} {{ $fit === 'object-contain' ? 'p-3 sm:p-5' : '' }} {{ $project['imagePosition'] ?? 'object-top' }}"
        />
    </div>
</div>
