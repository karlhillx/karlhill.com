@php
    $study = $caseStudy;
    $liveUrl = ($project['url'] ?? null) && str_starts_with($project['url'], 'http') ? $project['url'] : null;
    $canonical = rtrim(config('app.url', 'https://karlhill.com'), '/').'/work/'.$project['slug'];
    $ogImage = $meta->ogImage;
@endphp

@extends('layouts.site', ['meta' => $meta])

@push('head')
<script type="application/ld+json" nonce="{{ Vite::cspNonce() }}">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'CreativeWork',
    'name' => $project['title'],
    'description' => $study['lede'] ?? $project['description'],
    'image' => $ogImage,
    'url' => $canonical,
    'author' => [
        '@type' => 'Person',
        'name' => config('site.person.name'),
        'url' => rtrim(config('app.url', 'https://karlhill.com'), '/'),
    ],
    'keywords' => implode(', ', $project['tags'] ?? []),
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
<x-site.speculation-rules :rules="\App\Support\SpeculationRules::forCaseStudy($project, $previousProject, $nextProject)" />
@endpush

@section('content')
    <article class="relative pt-36 pb-20 px-6">
        <div class="glow-orb-1 pointer-events-none absolute top-24 -left-48 w-[500px] h-[500px] rounded-full"
             style="background: radial-gradient(ellipse, rgba(249,115,22,0.10) 0%, transparent 65%);"
             aria-hidden="true"></div>

        <div class="relative z-10 max-w-3xl mx-auto">
            <x-site.breadcrumbs :items="[
                ['label' => 'Home', 'url' => '/'],
                ['label' => 'Work', 'url' => '/work'],
                ['label' => $project['title']],
            ]" />

            <p class="font-mono text-accent text-xs tracking-widest uppercase mb-4">{{ $project['meta'] }}</p>
            <h1 class="font-display text-[clamp(2rem,5vw,3.5rem)] leading-[1.05] tracking-wide text-white mb-5">
                {{ $project['title'] }}
            </h1>
            <p class="text-neutral-400 text-base leading-relaxed mb-8 max-w-2xl">{{ $study['lede'] }}</p>

            <div class="flex flex-wrap items-center gap-2 mb-10">
                @foreach($project['tags'] as $tag)
                    <a href="{{ route('work.tag', \App\Support\ProjectCatalog::tagSlug($tag)) }}"
                       class="font-mono text-[10px] text-neutral-500 uppercase tracking-widest border border-neutral-800 px-2 py-1 hover:border-accent hover:text-accent transition-colors">
                        {{ $tag }}
                    </a>
                @endforeach
            </div>

            <figure class="mb-12 -mx-6 sm:mx-0">
                <img src="{{ $project['image'] }}"
                     alt="{{ $project['title'] }}"
                     loading="eager" decoding="async" fetchpriority="high"
                     style="view-transition-name: work-img-{{ $project['slug'] }}; view-transition-class: card-media"
                     class="w-full aspect-[16/9] object-cover {{ $project['imagePosition'] ?? 'object-center' }} sm:rounded-sm border-y sm:border border-neutral-800/70">
            </figure>

            @if(! empty($study['metrics']))
                @php($metricCols = count($study['metrics']) >= 3 ? 'sm:grid-cols-3' : 'sm:grid-cols-2')
                <div class="grid grid-cols-2 {{ $metricCols }} gap-px bg-neutral-800 mb-12" data-reveal>
                    @foreach($study['metrics'] as $metric)
                        <div class="bg-bg p-6 text-center">
                            <p class="font-display text-3xl sm:text-4xl text-accent mb-1"
                               data-counter
                               data-final="{{ $metric['value'] }}"
                               aria-label="{{ $metric['value'] }} — {{ $metric['label'] }}">{{ $metric['value'] }}</p>
                            <p class="font-mono text-[10px] text-neutral-500 uppercase tracking-widest">{{ $metric['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="space-y-12">
                <section data-reveal>
                    <h2 class="font-mono text-accent text-xs tracking-widest uppercase mb-4">Problem</h2>
                    <x-site.arrow-list :items="$study['problem']" />
                </section>

                <section data-reveal>
                    <h2 class="font-mono text-accent text-xs tracking-widest uppercase mb-4">Approach</h2>
                    <x-site.arrow-list :items="$study['approach']" />
                </section>

                <section data-reveal>
                    <h2 class="font-mono text-accent text-xs tracking-widest uppercase mb-4">Outcome</h2>
                    <x-site.arrow-list :items="$study['outcome']" />
                </section>
            </div>

            @if($relatedProjects->isNotEmpty())
                <div class="mt-14 pt-8 border-t border-neutral-800" data-reveal>
                    <p class="font-mono text-accent text-xs tracking-widest uppercase mb-4">Related projects</p>
                    <ul class="space-y-4">
                        @foreach($relatedProjects as $related)
                            <li>
                                <a href="/work/{{ $related['slug'] }}" class="group block border border-neutral-800 p-5 hover:border-accent/40 transition-colors">
                                    <p class="font-display text-xl text-neutral-100 group-hover:text-accent tracking-wide transition-colors">{{ $related['title'] }}</p>
                                    <p class="text-neutral-500 text-sm mt-2 line-clamp-2">{{ $related['description'] }}</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($previousProject || $nextProject)
                <nav class="mt-14 pt-8 border-t border-neutral-800 grid sm:grid-cols-2 gap-6" aria-label="Case study navigation" data-reveal>
                    @if($previousProject)
                        <a href="/work/{{ $previousProject['slug'] }}" class="group border border-neutral-800 p-5 hover:border-accent/40 transition-colors">
                            <p class="font-mono text-[10px] text-neutral-600 uppercase tracking-widest mb-2">← Previous</p>
                            <p class="font-display text-lg text-neutral-200 group-hover:text-accent tracking-wide transition-colors">{{ $previousProject['title'] }}</p>
                        </a>
                    @else
                        <div></div>
                    @endif
                    @if($nextProject)
                        <a href="/work/{{ $nextProject['slug'] }}" class="group border border-neutral-800 p-5 hover:border-accent/40 transition-colors sm:text-right">
                            <p class="font-mono text-[10px] text-neutral-600 uppercase tracking-widest mb-2">Next →</p>
                            <p class="font-display text-lg text-neutral-200 group-hover:text-accent tracking-wide transition-colors">{{ $nextProject['title'] }}</p>
                        </a>
                    @endif
                </nav>
            @endif

            <div class="mt-14 pt-8 border-t border-neutral-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4" data-reveal>
                <a href="/work" class="font-mono text-xs text-neutral-500 hover:text-accent uppercase tracking-widest transition-colors">
                    ← All work
                </a>
                @if($liveUrl)
                    <a href="{{ $liveUrl }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 font-mono text-xs text-accent border border-accent/40 hover:bg-accent/10 px-5 py-3 uppercase tracking-widest transition-colors">
                        Visit live project <span aria-hidden="true">↗</span>
                    </a>
                @endif
            </div>
        </div>
    </article>
@endsection
