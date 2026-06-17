@extends('layouts.site', ['meta' => $meta])

@push('head')
<x-site.speculation-rules :rules="\App\Support\SpeculationRules::forWorkIndex()" />
@endpush

@section('content')
    <section class="relative pt-40 pb-16 px-6 overflow-hidden">
        <div class="hero-dot-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
        <div class="relative z-10 max-w-6xl mx-auto">
            @php
                $breadcrumbs = [
                    ['label' => 'Home', 'url' => '/'],
                ];
                if ($activeTag) {
                    $breadcrumbs[] = ['label' => 'Work', 'url' => '/work'];
                    $breadcrumbs[] = ['label' => $activeTag];
                } else {
                    $breadcrumbs[] = ['label' => 'Work'];
                }
            @endphp
            <x-site.breadcrumbs :items="$breadcrumbs" />
            <p class="font-mono text-accent text-xs tracking-widest uppercase mb-6">Portfolio</p>
            <h1 class="font-display text-[clamp(3.5rem,12vw,9rem)] leading-none tracking-wide text-white mb-6">
                Selected Work
            </h1>
            <p class="text-neutral-400 text-base leading-relaxed max-w-2xl">
                Mission-critical platforms across NASA Earth science, disaster response, clinical genomics, and enterprise security.
            </p>
        </div>
    </section>

    @if($allTags->isNotEmpty())
        <section class="px-6 pb-4 border-t border-neutral-800">
            <div class="max-w-6xl mx-auto pt-10">
                <div class="flex flex-wrap gap-2" data-reveal>
                    <a href="{{ route('work') }}"
                       @class([
                           'font-mono text-[10px] uppercase tracking-widest px-3 py-1.5 border transition-colors',
                           'border-accent text-accent' => ! $activeTag,
                           'border-neutral-800 text-neutral-500 hover:border-accent hover:text-accent' => $activeTag,
                       ])>
                        All
                    </a>
                    @foreach($allTags as $tag)
                        <a href="{{ route('work.tag', \App\Support\ProjectCatalog::tagSlug($tag)) }}"
                           @class([
                               'font-mono text-[10px] uppercase tracking-widest px-3 py-1.5 border transition-colors',
                               'border-accent text-accent' => $activeTag === $tag,
                               'border-neutral-800 text-neutral-500 hover:border-accent hover:text-accent' => $activeTag !== $tag,
                           ])>
                            {{ $tag }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('home.partials.work', [
        'projects' => $projects,
        'sectionNumber' => '01',
        'heading' => $activeTag ? "Projects · {$activeTag}" : 'Projects',
    ])

    @include('home.partials.open-source', ['sectionNumber' => '02'])
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
