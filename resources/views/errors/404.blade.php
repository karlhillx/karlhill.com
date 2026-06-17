@extends('layouts.site', ['meta' => \App\Support\PageMeta::notFound()])

@section('content')
    <section class="relative pt-44 pb-36 px-6 md:pt-52 md:pb-44 lg:pt-56 lg:pb-52" aria-labelledby="page-not-found-heading">
        <div class="relative z-10 max-w-3xl mx-auto text-center">
            <p class="font-mono text-accent text-xs tracking-widest uppercase mb-6">404</p>
            <h1 id="page-not-found-heading" class="font-display text-[clamp(2rem,6vw,3.5rem)] leading-tight tracking-wide text-white mb-5">
                Page not found
            </h1>
            <p class="text-neutral-400 text-sm leading-relaxed max-w-md mx-auto mb-10">
                The URL may be mistyped, or this page may have moved. Try one of the main sections below.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="/"
                   class="inline-flex items-center justify-center font-mono text-xs text-neutral-900 bg-accent hover:bg-accent/80 uppercase tracking-widest px-6 py-3 transition-colors w-full sm:w-auto">
                    Home
                </a>
                <a href="/work"
                   class="inline-flex items-center justify-center font-mono text-xs border border-neutral-700 text-neutral-300 hover:border-accent hover:text-accent uppercase tracking-widest px-6 py-3 transition-colors w-full sm:w-auto">
                    Work
                </a>
                <a href="/about"
                   class="inline-flex items-center justify-center font-mono text-xs border border-neutral-700 text-neutral-300 hover:border-accent hover:text-accent uppercase tracking-widest px-6 py-3 transition-colors w-full sm:w-auto">
                    About
                </a>
                <a href="/blog"
                   class="inline-flex items-center justify-center font-mono text-xs border border-neutral-700 text-neutral-300 hover:border-accent hover:text-accent uppercase tracking-widest px-6 py-3 transition-colors w-full sm:w-auto">
                    Writing
                </a>
            </div>
        </div>
    </section>
@endsection
