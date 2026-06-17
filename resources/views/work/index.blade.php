@extends('layouts.site', ['meta' => $meta])

@section('content')
    <section class="relative pt-40 pb-16 px-6 overflow-hidden">
        <div class="hero-dot-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
        <div class="relative z-10 max-w-6xl mx-auto">
            <x-site.breadcrumbs :items="[
                ['label' => 'Home', 'url' => '/'],
                ['label' => 'Work'],
            ]" />
            <p class="font-mono text-accent text-xs tracking-widest uppercase mb-6">Portfolio</p>
            <h1 class="font-display text-[clamp(3.5rem,12vw,9rem)] leading-none tracking-wide text-white mb-6">
                Selected Work
            </h1>
            <p class="text-neutral-400 text-base leading-relaxed max-w-2xl">
                Mission-critical platforms across NASA Earth science, disaster response, clinical genomics, and enterprise security.
            </p>
        </div>
    </section>

    @include('home.partials.work', [
        'projects' => config('site.projects'),
        'sectionNumber' => '01',
        'heading' => 'Projects',
    ])

    @include('home.partials.open-source', ['sectionNumber' => '02'])
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
