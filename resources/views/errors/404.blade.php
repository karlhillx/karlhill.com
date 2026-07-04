@extends('layouts.site', ['meta' => \App\Support\PageMeta::notFound()])

@section('content')
    <section class="relative min-h-[80vh] flex items-center px-6 py-32 overflow-hidden" aria-labelledby="page-not-found-heading">
        <div class="hero-dot-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
        <x-site.glow-orb :drift="1" class="-top-32 -left-32 w-[600px] h-[600px]" />
        <x-site.glow-orb :drift="2" :strength="0.09" class="-bottom-40 -right-40 w-[500px] h-[500px]" />

        <div class="relative z-10 max-w-3xl mx-auto text-center">
            <p class="font-mono text-accent text-xs tracking-widest uppercase mb-6 hero-enter" style="animation-delay:100ms">
                Error &nbsp;·&nbsp; 404
            </p>
            <h1 id="page-not-found-heading"
                class="font-display text-[clamp(6rem,22vw,15rem)] leading-none tracking-wide text-white mb-6 hero-enter"
                style="animation-delay:200ms">
                <span class="hero-shine">404</span>
            </h1>
            <p class="font-display text-[clamp(1.4rem,3.5vw,2.25rem)] text-neutral-300 tracking-widest uppercase mb-5 hero-enter"
               style="animation-delay:300ms">
                Page not found
            </p>
            <p class="text-neutral-400 text-base leading-relaxed max-w-md mx-auto mb-10 hero-enter" style="animation-delay:380ms">
                The URL may be mistyped, or this page may have moved. Here's the way back.
            </p>
            <div class="hero-enter" style="animation-delay:480ms">
                <a href="/"
                   class="inline-block font-bold px-8 py-3.5 text-xs uppercase tracking-widest transition-colors duration-200 bg-accent text-black hover:bg-accent/80">
                    Back home
                </a>
                <div class="flex flex-wrap items-center justify-center gap-6 mt-8 font-mono text-xs uppercase tracking-widest">
                    <a href="/work" class="text-neutral-400 hover:text-accent transition-colors">Work →</a>
                    <a href="/about" class="text-neutral-400 hover:text-accent transition-colors">About →</a>
                    <a href="/blog" class="text-neutral-400 hover:text-accent transition-colors">Writing →</a>
                </div>
                <p class="font-mono text-[11px] text-neutral-600 uppercase tracking-widest mt-8">
                    Or press <kbd class="border border-neutral-700/80 rounded px-1.5 py-0.5 text-[10px] text-neutral-500 normal-case tracking-normal">⌘K</kbd> to jump anywhere
                </p>
            </div>
        </div>
    </section>
@endsection
