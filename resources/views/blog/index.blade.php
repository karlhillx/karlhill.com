@extends('layouts.site', ['meta' => $meta])

@push('head')
<script type="application/ld+json">
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
        <x-site.breadcrumbs :items="[
            ['label' => 'Home', 'url' => '/'],
            ['label' => 'Writing'],
        ]" class="mb-6" />
        <p class="font-mono text-accent text-xs tracking-widest uppercase mb-6">Writing</p>
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
        @if($allTags->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-12" data-reveal>
                <a href="/blog"
                   @class([
                       'font-mono text-[10px] uppercase tracking-widest px-3 py-1.5 border transition-colors',
                       'border-accent text-accent' => ! $activeTag,
                       'border-neutral-800 text-neutral-500 hover:border-accent hover:text-accent' => $activeTag,
                   ])>
                    All
                </a>
                @foreach($allTags as $tag)
                    <a href="/blog?tag={{ urlencode($tag) }}"
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
            <p class="font-mono text-sm text-neutral-500">
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
                                      class="font-mono text-xs text-neutral-500 uppercase tracking-widest">
                                    {{ $post->publishedAt->format('M j, Y') }}
                                </time>
                                <span class="font-mono text-[10px] text-neutral-600 uppercase tracking-widest">
                                    {{ $post->readMinutes }} min read
                                </span>
                            </div>
                            <div>
                                <h2 class="font-display text-3xl md:text-4xl tracking-wide text-neutral-100 group-hover:text-accent transition-colors mb-4 leading-tight">
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
                                    <span class="font-mono text-xs text-accent ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
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
