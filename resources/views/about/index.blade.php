@extends('layouts.site', ['meta' => $meta])

@section('content')
    <section class="relative pt-40 pb-16 px-6 overflow-hidden">
        <div class="hero-dot-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
        <div class="relative z-10 max-w-6xl mx-auto">
            <x-site.breadcrumbs :items="[
                ['label' => 'Home', 'url' => '/'],
                ['label' => 'About'],
            ]" />
            <p class="font-mono text-accent text-xs tracking-widest uppercase mb-6">Background</p>
            <h1 class="font-display text-[clamp(3.5rem,12vw,9rem)] leading-none tracking-wide text-white mb-6">
                About Karl
            </h1>
            <p class="text-neutral-400 text-base leading-relaxed max-w-2xl">
                {{ config('site.hero.bio') }}
            </p>
        </div>
    </section>

    @include('home.partials.experience', ['sectionNumber' => '01'])
    @include('home.partials.research', ['sectionNumber' => '02'])
    @include('home.partials.stack', ['sectionNumber' => '03'])
    @include('about.partials.credentials', ['sectionNumber' => '04'])
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
