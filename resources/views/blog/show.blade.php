@php
    $heroPath  = $post->heroImagePath();
    $ogImage   = $post->ogImageUrl();
    $shareUrl  = $post->canonicalUrl();
    $shareText = $post->title . ' — by Karl Hill';
@endphp

@extends('layouts.site', ['meta' => $meta])

@push('head')
<script type="application/ld+json" nonce="{{ Vite::cspNonce() }}">
{!! json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'BlogPosting',
    'headline'        => $post->title,
    'description'     => $post->excerpt,
    'image'           => $ogImage,
    'datePublished'   => $post->publishedAt->toIso8601String(),
    'dateModified'    => $post->modifiedAt()->toIso8601String(),
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id'   => $post->canonicalUrl(),
    ],
    'author' => [
        '@type' => 'Person',
        'name'  => config('site.person.name'),
        'url'   => rtrim(config('app.url', 'https://karlhill.com'), '/'),
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name'  => 'Karl Hill',
        'url'   => rtrim(config('app.url', 'https://karlhill.com'), '/'),
    ],
    'keywords' => implode(', ', $post->tags),
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')

<article class="relative pt-28 pb-20 px-6">
    <div class="glow-orb-1 pointer-events-none absolute top-24 -left-48 w-[500px] h-[500px] rounded-full"
         style="background: radial-gradient(ellipse, rgba(249,115,22,0.10) 0%, transparent 65%);"
         aria-hidden="true"></div>

    <div class="relative z-10 max-w-3xl mx-auto">
        <x-site.breadcrumbs :items="[
            ['label' => 'Home', 'url' => '/'],
            ['label' => 'Writing', 'url' => '/blog'],
            ['label' => $post->title],
        ]" />

        <p class="font-mono text-accent text-xs tracking-widest uppercase mb-4">
            <time datetime="{{ $post->isoDate() }}">{{ $post->publishedAt->format('M j, Y') }}</time>
            &nbsp;·&nbsp; {{ $post->readMinutes }} min read
        </p>

        <h1 class="font-display text-[clamp(1.85rem,4vw,3rem)] leading-[1.1] tracking-wide text-white mb-5"
            style="view-transition-name: post-{{ $post->slug }}; view-transition-class: post-title">
            {{ $post->title }}
        </h1>

        <p class="text-neutral-400 text-[0.95rem] leading-relaxed mb-7 max-w-2xl">
            {{ $post->excerpt }}
        </p>

        <div class="flex flex-wrap items-center gap-2 mb-10">
            @foreach($post->tags as $tag)
                <span class="font-mono text-[10px] text-neutral-400 uppercase tracking-widest border border-neutral-800 px-2 py-1">
                    {{ $tag }}
                </span>
            @endforeach
        </div>

        @if($heroPath)
            @php
                $heroWebpPath = preg_replace('#^/img/#', '/img/webp/', preg_replace('/\.(jpe?g|png)$/i', '.webp', $heroPath));
            @endphp
            <figure class="mb-8 -mx-6 sm:mx-0">
                <picture>
                    @if($heroWebpPath && $heroWebpPath !== $heroPath)
                        <source srcset="{{ $heroWebpPath }}" type="image/webp">
                    @endif
                    <img src="{{ $heroPath }}"
                         alt="{{ $post->title }}"
                         loading="eager" decoding="async" fetchpriority="high"
                         class="w-full aspect-[16/7] sm:aspect-[5/2] object-cover object-center sm:rounded-sm border-y sm:border border-neutral-800/70">
                </picture>
            </figure>
        @endif

        <div class="prose-karl">
            {!! $post->bodyHtml !!}
        </div>

        <hr class="border-neutral-800 my-12">

        @if($adjacentPosts['previous'] || $adjacentPosts['next'])
            <nav class="grid sm:grid-cols-2 gap-6 mb-12" aria-label="Post navigation" data-reveal>
                @if($adjacentPosts['previous'])
                    <a href="{{ $adjacentPosts['previous']->url() }}" class="group border border-neutral-800 p-5 hover:border-accent/40 transition-colors">
                        <p class="font-mono text-[10px] text-neutral-400 uppercase tracking-widest mb-2">← Previous</p>
                        <p class="font-display text-lg text-neutral-200 group-hover:text-accent tracking-wide transition-colors">{{ $adjacentPosts['previous']->title }}</p>
                    </a>
                @else
                    <div></div>
                @endif
                @if($adjacentPosts['next'])
                    <a href="{{ $adjacentPosts['next']->url() }}" class="group border border-neutral-800 p-5 hover:border-accent/40 transition-colors sm:text-right">
                        <p class="font-mono text-[10px] text-neutral-400 uppercase tracking-widest mb-2">Next →</p>
                        <p class="font-display text-lg text-neutral-200 group-hover:text-accent tracking-wide transition-colors">{{ $adjacentPosts['next']->title }}</p>
                    </a>
                @endif
            </nav>
        @endif

        @if($relatedPosts->isNotEmpty())
            <div class="mb-12" data-reveal>
                <p class="font-mono text-accent text-xs tracking-widest uppercase mb-4">Related reading</p>
                <ul class="space-y-4">
                    @foreach($relatedPosts as $related)
                        <li>
                            <a href="{{ $related->url() }}" class="group block border border-neutral-800 p-5 hover:border-accent/40 transition-colors">
                                <p class="font-display text-xl text-neutral-100 group-hover:text-accent tracking-wide transition-colors">{{ $related->title }}</p>
                                <p class="text-neutral-400 text-sm mt-2 line-clamp-2">{{ $related->excerpt }}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="border border-neutral-800 p-5 mb-12" data-reveal>
            <p class="font-mono text-accent text-xs tracking-widest uppercase mb-3">On this site</p>
            <div class="flex flex-wrap gap-4 font-mono text-[11px] uppercase tracking-widest">
                <a href="/work" class="text-neutral-400 hover:text-accent transition-colors">Selected work →</a>
                <a href="/about#experience" class="text-neutral-400 hover:text-accent transition-colors">Experience →</a>
                <a href="/about#research" class="text-neutral-400 hover:text-accent transition-colors">Research →</a>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div>
                <p class="font-mono text-accent text-xs tracking-widest uppercase mb-2">Share</p>
                <div class="flex items-center gap-4">
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($shareUrl) }}"
                       target="_blank" rel="noopener noreferrer"
                       aria-label="Share on LinkedIn"
                       class="text-neutral-400 hover:text-accent transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($shareText) }}&url={{ urlencode($shareUrl) }}"
                       target="_blank" rel="noopener noreferrer"
                       aria-label="Share on X / Twitter"
                       class="text-neutral-400 hover:text-accent transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    <a href="https://bsky.app/intent/compose?text={{ urlencode($shareText . ' ' . $shareUrl) }}"
                       target="_blank" rel="noopener noreferrer"
                       aria-label="Share on Bluesky"
                       class="text-neutral-400 hover:text-accent transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 64 57" aria-hidden="true">
                            <path d="M13.873 3.805C21.21 9.332 29.103 20.537 32 26.55v15.882c0-.338-.13.044-.41.867-1.512 4.456-7.418 21.847-20.923 7.944-7.111-7.32-3.819-14.64 9.125-16.85-7.405 1.264-15.73-.825-18.014-9.015C1.12 23.022 0 8.51 0 6.55 0-3.268 8.579-.182 13.873 3.805ZM50.127 3.805C42.79 9.332 34.897 20.537 32 26.55v15.882c0-.338.13.044.41.867 1.512 4.456 7.418 21.847 20.923 7.944 7.111-7.32 3.819-14.64-9.125-16.85 7.405 1.264 15.73-.825 18.014-9.015C62.88 23.022 64 8.51 64 6.55 64-3.268 55.421-.182 50.127 3.805Z"/>
                        </svg>
                    </a>
                    <button type="button"
                            data-copy-link="{{ $shareUrl }}"
                            aria-label="Copy link"
                            class="text-neutral-400 hover:text-accent transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 010 5.656l-3 3a4 4 0 11-5.656-5.656l1.5-1.5M10.172 13.828a4 4 0 010-5.656l3-3a4 4 0 115.656 5.656l-1.5 1.5"/>
                        </svg>
                    </button>
                    <span data-copy-feedback class="font-mono text-[10px] text-accent uppercase tracking-widest opacity-0 transition-opacity">Copied</span>
                </div>
            </div>
            <div class="text-right">
                <p class="font-mono text-[10px] text-neutral-400 uppercase tracking-widest mb-2">Written by</p>
                <a href="/" class="font-display text-2xl text-neutral-300 hover:text-accent tracking-widest transition-colors">
                    Karl Hill
                </a>
            </div>
        </div>
    </div>
</article>

@endsection
