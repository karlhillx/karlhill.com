@extends('layouts.site', ['meta' => $meta])

@push('head')
<script type="application/ld+json" nonce="{{ Vite::cspNonce() }}">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'Blog',
    'name'     => 'Karl Hill — Writing',
    'url'      => rtrim(config('app.url', 'https://karlhill.com'), '/') . '/blog',
    'author'   => [
        '@type' => 'Person',
        'name'  => config('site.person.name'),
        'url'   => rtrim(config('app.url', 'https://karlhill.com'), '/'),
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

<section class="relative pt-40 pb-20 px-6 overflow-hidden">
    <div class="hero-dot-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <div class="glow-orb-1 pointer-events-none absolute -bottom-32 -left-32 w-[600px] h-[600px] rounded-full"
         style="background: radial-gradient(ellipse, rgba(249,115,22,0.14) 0%, transparent 65%);"
         aria-hidden="true"></div>
    <div class="glow-orb-2 pointer-events-none absolute -top-32 -right-32 w-[500px] h-[500px] rounded-full"
         style="background: radial-gradient(ellipse, rgba(249,115,22,0.09) 0%, transparent 65%);"
         aria-hidden="true"></div>

    <div class="relative z-10 max-w-6xl mx-auto">
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
        <x-site.breadcrumbs :items="$breadcrumbs" class="mb-6" />
        <p class="font-mono text-accent text-xs tracking-widest uppercase mb-6">Writing</p>
        <h1 class="font-display text-[clamp(3.5rem,12vw,9rem)] leading-none tracking-wide text-white mb-8">
            Notes from<br>the field
        </h1>
        <p class="text-neutral-300 text-base leading-relaxed max-w-2xl">
            Reflections on engineering leadership, mission software, and the overlooked work that turns code into something people can depend on.
        </p>
        <p class="text-neutral-400 text-base leading-relaxed max-w-2xl mt-4">
            I write to think in public — to work out hard-won lessons on architecture, delivery, and leading teams, and to leave a trail worth following.
        </p>
        <div class="flex flex-wrap items-center gap-4 mt-8">
            <a href="{{ route('feed') }}"
               class="inline-flex items-center gap-2 font-mono text-xs text-accent border border-accent/40 hover:bg-accent/10 px-4 py-2.5 uppercase tracking-widest transition-colors">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M6.18 15.64a2.18 2.18 0 1 1 0 4.36 2.18 2.18 0 0 1 0-4.36ZM4 4.44A15.56 15.56 0 0 1 19.56 20h-2.83A12.73 12.73 0 0 0 4 7.27V4.44Zm0 5.66A9.9 9.9 0 0 1 13.9 20h-2.83A7.07 7.07 0 0 0 4 12.93V10.1Z"/>
                </svg>
                Subscribe via RSS
            </a>
            <span class="font-mono text-[11px] text-neutral-500 uppercase tracking-widest">No newsletter, no spam — just the feed.</span>
        </div>
    </div>
</section>

<section class="px-6 pb-32 border-t border-neutral-800">
    <div class="max-w-6xl mx-auto pt-20">
        @if($allTags->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-12" data-reveal>
                <a href="{{ route('blog.index') }}"
                   @class([
                       'font-mono text-[10px] uppercase tracking-widest px-3 py-1.5 border transition-colors',
                       'border-accent text-accent' => ! $activeTag,
                       'border-neutral-800 text-neutral-500 hover:border-accent hover:text-accent' => $activeTag,
                   ])>
                    All
                </a>
                @foreach($allTags as $tag)
                    <a href="{{ route('blog.tag', $tag) }}"
                       @class([
                           'font-mono text-[10px] uppercase tracking-widest px-3 py-1.5 border transition-colors',
                           'border-accent text-accent' => $activeTag === $tag,
                           'border-neutral-800 text-neutral-500 hover:border-accent hover:text-accent' => $activeTag !== $tag,
                       ])>
                        {{ $tag }}
                    </a>
                @endforeach
            </div>
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
            <ul class="divide-y divide-neutral-800/70">
                @foreach($posts as $post)
                    <li class="group" data-reveal>
                        <a href="{{ $post->url() }}" class="grid md:grid-cols-[200px_1fr] gap-6 md:gap-12 py-12 hover:bg-neutral-900/30 transition-colors -mx-6 px-6">
                            <div class="flex flex-col gap-2">
                                <time datetime="{{ $post->isoDate() }}"
                                      class="font-mono text-xs text-neutral-400 uppercase tracking-widest">
                                    {{ $post->publishedAt->format('M j, Y') }}
                                </time>
                                <span class="font-mono text-[10px] text-neutral-400 uppercase tracking-widest">
                                    {{ $post->readMinutes }} min read
                                </span>
                            </div>
                            <div>
                                <h2 class="font-display text-3xl md:text-4xl tracking-wide text-neutral-100 group-hover:text-accent transition-colors mb-4 leading-tight"
                                    style="view-transition-name: post-{{ $post->slug }}; view-transition-class: post-title">
                                    {{ $post->title }}
                                </h2>
                                <p class="text-neutral-400 leading-relaxed mb-5 max-w-2xl">
                                    {{ $post->excerpt }}
                                </p>
                                <div class="flex flex-wrap items-center gap-4">
                                    @foreach($post->tags as $tag)
                                        <span class="font-mono text-[10px] text-neutral-400 uppercase tracking-widest border border-neutral-800 px-2 py-1">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                    <span class="font-mono text-xs text-accent ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                                        Read <span class="arrow-nudge inline-block">→</span>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="mt-16 pt-10 border-t border-neutral-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6" data-reveal>
                <div>
                    <p class="font-display text-2xl text-neutral-100 tracking-wide mb-1">Follow along</p>
                    <p class="text-neutral-400 text-sm max-w-md">New essays land here first. Subscribe with your reader of choice — the feed is open and always will be.</p>
                </div>
                <a href="{{ route('feed') }}"
                   class="inline-flex items-center gap-2 shrink-0 font-mono text-xs text-accent border border-accent/40 hover:bg-accent/10 px-5 py-3 uppercase tracking-widest transition-colors">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M6.18 15.64a2.18 2.18 0 1 1 0 4.36 2.18 2.18 0 0 1 0-4.36ZM4 4.44A15.56 15.56 0 0 1 19.56 20h-2.83A12.73 12.73 0 0 0 4 7.27V4.44Zm0 5.66A9.9 9.9 0 0 1 13.9 20h-2.83A7.07 7.07 0 0 0 4 12.93V10.1Z"/>
                    </svg>
                    Subscribe via RSS
                </a>
            </div>
        @endif
    </div>
</section>

@endsection
