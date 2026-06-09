@extends('layouts.site', [
    'title'         => 'Writing — Karl Hill',
    'description'   => 'Reflections on engineering leadership, mission software, and the work that turns code into something people depend on — by Karl Hill.',
    'canonical'     => rtrim(config('app.url', 'https://karlhill.com'), '/') . '/blog',
    'ogTitle'       => 'Writing — Karl Hill',
    'ogDescription' => 'Reflections on engineering leadership, mission software, and the work that turns code into something people depend on.',
    'ogImage'       => rtrim(config('app.url', 'https://karlhill.com'), '/') . '/img/og-home.jpg',
    'activeNav'     => 'writing',
])

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
        <p class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-6">Writing</p>
        <h1 class="font-display text-[clamp(3.5rem,12vw,9rem)] leading-none tracking-wide text-white mb-8">
            Notes from<br>the field
        </h1>
        <p class="text-neutral-400 text-base leading-relaxed max-w-2xl">
            Reflections on engineering leadership, mission software, and the overlooked work that turns code into something people can depend on.
        </p>
    </div>
</section>

<section class="px-6 pb-32 border-t border-neutral-800">
    <div class="max-w-6xl mx-auto pt-20">
        @if($posts->isEmpty())
            <p class="font-mono text-sm text-neutral-500">No posts yet — check back soon.</p>
        @else
            <ul class="divide-y divide-neutral-800/70">
                @foreach($posts as $post)
                    <li class="group" data-reveal>
                        <a href="{{ $post->url() }}" class="grid md:grid-cols-[200px_1fr] gap-6 md:gap-12 py-12 hover:bg-neutral-900/30 transition-colors -mx-6 px-6">
                            <div class="flex flex-col gap-2">
                                <time datetime="{{ $post->isoDate() }}"
                                      class="font-mono text-xs text-neutral-500 uppercase tracking-widest">
                                    {{ $post->publishedAt->format('M j, Y') }}
                                </time>
                                <span class="font-mono text-[10px] text-neutral-600 uppercase tracking-widest">
                                    {{ $post->readMinutes }} min read
                                </span>
                            </div>
                            <div>
                                <h2 class="font-display text-3xl md:text-4xl tracking-wide text-neutral-100 group-hover:text-orange-500 transition-colors mb-4 leading-tight">
                                    {{ $post->title }}
                                </h2>
                                <p class="text-neutral-400 leading-relaxed mb-5 max-w-2xl">
                                    {{ $post->excerpt }}
                                </p>
                                <div class="flex flex-wrap items-center gap-4">
                                    @foreach($post->tags as $tag)
                                        <span class="font-mono text-[10px] text-neutral-600 uppercase tracking-widest border border-neutral-800 px-2 py-1">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                    <span class="font-mono text-xs text-orange-500 ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                                        Read <span class="arrow-nudge inline-block">→</span>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>

@endsection

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'Blog',
    'name'     => 'Karl Hill — Writing',
    'url'      => rtrim(config('app.url', 'https://karlhill.com'), '/') . '/blog',
    'author'   => [
        '@type' => 'Person',
        'name'  => 'Karl Hill',
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
@endpush
