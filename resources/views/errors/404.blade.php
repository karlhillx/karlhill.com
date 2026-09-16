@extends('layouts.site', ['meta' => \App\Support\PageMeta::notFound()])

@section('content')
    <section class="relative min-h-[80vh] flex items-center site-page-hero overflow-x-clip" aria-labelledby="page-not-found-heading">
        <div class="hero-dot-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>

        <div class="relative z-10 site-shell w-full">
            <div class="site-prose text-center">
                <p class="font-mono text-accent text-xs tracking-widest uppercase mb-6 hero-enter" style="animation-delay:80ms">
                    Error · 404
                </p>
                <x-site.mark :size="72" class="site-mark--page mb-6 hero-enter" style="animation-delay:120ms" />
                <h1 id="page-not-found-heading"
                    class="font-display text-[clamp(6rem,22vw,15rem)] leading-none tracking-wide text-white mb-6 hero-enter"
                    style="animation-delay:160ms">
                    <span class="hero-shine">404</span>
                </h1>
                <p class="font-display text-[clamp(1.4rem,3.5vw,2.25rem)] text-neutral-300 tracking-widest uppercase mb-5 hero-enter"
                   style="animation-delay:300ms">
                    Page not found
                </p>
                <p class="text-neutral-400 text-base leading-relaxed max-w-md mx-auto mb-10 hero-enter" style="animation-delay:380ms">
                    The address may be incorrect, or the page may have moved.
                </p>
                <div class="hero-enter" style="animation-delay:480ms">
                    <a href="/"
                       class="inline-block font-bold px-8 py-3.5 text-xs uppercase tracking-widest transition-colors duration-200 btn-accent-fill">
                        Back home
                    </a>
                    <div class="flex flex-wrap items-center justify-center gap-6 mt-8 font-mono text-xs uppercase tracking-widest">
                        <a href="/kit" class="text-neutral-400 hover:text-accent transition-colors">Recruiter kit →</a>
                        <a href="/work" class="text-neutral-400 hover:text-accent transition-colors">Work →</a>
                        <a href="/blog" class="text-neutral-400 hover:text-accent transition-colors">Writing →</a>
                        <a href="/now#book" class="text-neutral-400 hover:text-accent transition-colors">Book →</a>
                    </div>
                    <p class="hidden sm:block font-mono text-caption text-neutral-500 uppercase tracking-widest mt-8">
                        Or press <kbd class="surface-chip px-1.5 py-0.5 text-caption text-neutral-400 normal-case tracking-normal" data-mod-shortcut>⌘K</kbd> to search the site
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
