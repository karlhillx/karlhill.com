@extends('layouts.site', ['meta' => $meta])

@push('head')
<script type="application/ld+json" nonce="{{ Vite::cspNonce() }}">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'Blog',
    'name'     => 'Karl Hill — Writing',
    'url'      => \App\Support\PageMeta::siteUrl() . '/blog',
    'author'   => [
        '@type' => 'Person',
        'name'  => config('site.person.name'),
        'url'   => \App\Support\PageMeta::siteUrl(),
    ],
    'blogPost' => $posts->map(fn($p) => [
        '@type'         => 'BlogPosting',
        'headline'      => $p->title,
        'datePublished' => $p->publishedAt->toIso8601String(),
        'url'           => $p->canonicalUrl(),
        'description'   => $p->excerpt,
    ])->all(),
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
<x-site.speculation-rules :rules="\App\Support\SpeculationRules::forBlogIndex($posts)" />
@endpush

@section('content')

@php
    $breadcrumbs = [
        ['label' => 'Home', 'url' => '/'],
    ];
    if ($activeTag) {
        $breadcrumbs[] = ['label' => 'Writing', 'url' => '/blog'];
        $breadcrumbs[] = ['label' => ucfirst($activeTag)];
    } else {
        $breadcrumbs[] = ['label' => 'Writing'];
    }
@endphp

<x-site.page-hero eyebrow="Writing" :breadcrumbs="$breadcrumbs" class="pb-28">
    <x-slot:title>Notes from<br>the field</x-slot:title>

    <p class="text-neutral-300 text-base leading-relaxed max-w-2xl">
        Reflections on engineering leadership, mission software, and the overlooked work that turns code into something people can depend on.
    </p>
    <div class="flex flex-wrap items-center gap-4 mt-10">
        <a href="{{ route('feed') }}"
           class="inline-flex items-center gap-2 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
            @include('components.site.icons.rss', ['class' => 'w-3.5 h-3.5'])
            Subscribe via RSS
        </a>
        <a href="{{ route('feed.json') }}"
           class="inline-flex items-center gap-2 font-mono text-xs text-neutral-500 hover:text-accent uppercase tracking-widest transition-colors">
            JSON Feed
        </a>
        <x-site.push-subscribe />
        <span class="font-mono text-[11px] text-neutral-500 uppercase tracking-widest">No newsletter, no spam — just the feed.</span>
    </div>
</x-site.page-hero>

@if(isset($seriesList) && $seriesList->isNotEmpty())
    <section class="site-section site-section--soft border-t border-neutral-800/50" aria-label="Series">
        <div class="site-shell space-y-12">
            @foreach($seriesList as $series)
                <div id="{{ $series['id'] }}" class="scroll-mt-28" data-reveal>
                    <div class="grid md:grid-cols-[220px_1fr] gap-6 md:gap-12 mb-8">
                        <div>
                            <p class="font-mono text-accent text-xs tracking-widest uppercase mb-3">Series</p>
                            <h2 class="font-display text-2xl tracking-wide text-white leading-tight">{{ $series['title'] }}</h2>
                        </div>
                        <p class="text-neutral-400 text-base leading-relaxed max-w-2xl md:pt-8">{{ $series['description'] }}</p>
                    </div>
                    <x-site.series-chapters :series="$series" />
                </div>
            @endforeach
        </div>
    </section>
@endif

<section class="site-section border-t border-neutral-800" style="padding-block: var(--space-section-soft) var(--space-section)">
    <div class="site-shell">
        @if($allTags->isNotEmpty())
            <x-site.tag-filter
                class="mb-14"
                :all-url="route('blog.index')"
                :tags="$allTags"
                :counts="$tagCounts"
                :active-tag="$activeTag"
                :url-for="fn ($tag) => route('blog.tag', $tag)"
            />
        @endif

        @if($posts->isEmpty())
            <p class="font-mono text-sm text-neutral-400">
                @if($activeTag)
                    No posts tagged “{{ $activeTag }}” yet.
                @else
                    No posts yet — check back soon.
                @endif
            </p>
        @else
            <ul class="divide-y divide-neutral-800/70 site-bleed" data-soft-nav-target style="view-transition-name: writing-list">
                @foreach($posts as $post)
                    <li class="group" data-reveal>
                        <div class="site-list-row grid md:grid-cols-[200px_1fr] gap-6 md:gap-12 hover:bg-neutral-900/30 transition-colors relative">
                            <div class="relative z-10 flex flex-col gap-2">
                                <time datetime="{{ $post->isoDate() }}"
                                      class="font-mono text-xs text-neutral-400 uppercase tracking-widest">
                                    {{ $post->publishedAt->format('M j, Y') }}
                                </time>
                                <span class="font-mono text-[10px] text-neutral-400 uppercase tracking-widest">
                                    {{ $post->readMinutes }} min read
                                </span>
                            </div>
                            <div class="relative z-10 min-w-0">
                                <h2 class="font-display text-2xl sm:text-3xl md:text-4xl tracking-wide text-neutral-100 group-hover:text-accent transition-colors mb-4 leading-tight text-balance"
                                    style="view-transition-name: post-{{ $post->slug }}; view-transition-class: post-title">
                                    <a href="{{ $post->url() }}"
                                       interestfor="post-preview-{{ $post->slug }}"
                                       class="inline-block after:absolute after:inset-0 after:content-['']">
                                        {{ $post->title }}
                                    </a>
                                </h2>
                                <p class="text-neutral-400 leading-relaxed mb-6 max-w-2xl">
                                    {{ $post->excerpt }}
                                </p>
                                <div class="relative z-20 flex flex-wrap items-center gap-4">
                                    @foreach($post->tags as $tag)
                                        <a href="{{ route('blog.tag', $tag) }}"
                                           class="surface-chip font-mono text-[10px] text-neutral-400 uppercase tracking-widest px-2 py-1 hover:border-accent hover:text-accent transition-colors">
                                            {{ $tag }}
                                        </a>
                                    @endforeach
                                    <span class="blog-read-cta font-mono text-xs text-neutral-400 ml-auto group-hover:text-accent transition-colors">
                                        Read <span class="arrow-nudge inline-block" aria-hidden="true">→</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div id="post-preview-{{ $post->slug }}" popover="hint" class="interest-preview">
                            <p class="font-mono text-[10px] text-accent uppercase tracking-widest mb-1">{{ $post->publishedAt->format('M j, Y') }} · {{ $post->readMinutes }} min</p>
                            <p class="font-display text-base tracking-wide text-white leading-tight mb-2">{{ $post->title }}</p>
                            <p class="text-neutral-400 text-xs leading-relaxed">{{ $post->excerpt }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="mt-20 pt-12 border-t border-neutral-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-8" data-reveal>
                <div>
                    <p class="font-display text-2xl text-neutral-100 tracking-wide mb-2">Follow along</p>
                    <p class="text-neutral-400 text-sm max-w-md leading-relaxed">New essays land here first. Subscribe with your reader of choice — the feed is open and always will be.</p>
                </div>
                <a href="{{ route('feed') }}"
                   class="inline-flex items-center gap-2 shrink-0 font-mono text-xs text-accent border border-accent/40 hover:bg-accent/10 px-5 py-3 uppercase tracking-widest transition-colors">
                    @include('components.site.icons.rss', ['class' => 'w-3.5 h-3.5'])
                    Subscribe via RSS
                </a>
            </div>
        @endif
    </div>
</section>

@endsection
