@extends('layouts.site', ['meta' => $meta])

@section('content')
    <x-site.page-hero eyebrow="Background" :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'About'],
    ]">
        <x-slot:title>About Karl</x-slot:title>

        <p class="text-neutral-300 text-base leading-relaxed max-w-2xl">
            {{ config('site.hero.bio') }}
        </p>

        <div class="flex flex-wrap items-center gap-4 mt-8">
            <a href="{{ config('site.footer.resume') }}" download
               class="btn-sweep inline-flex items-center gap-2 font-mono text-xs text-accent border border-accent/40 px-5 py-3 uppercase tracking-widest transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16"/>
                </svg>
                Download résumé
            </a>
            <a href="/#contact"
               class="font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                Get in touch →
            </a>
        </div>
    </x-site.page-hero>

    @if(config('site.about.beyond'))
        <section aria-label="Beyond the work" class="px-6 border-t border-neutral-800">
            <div class="max-w-6xl mx-auto py-16 grid md:grid-cols-[200px_1fr] gap-6 md:gap-12" data-reveal>
                <p class="font-mono text-accent text-xs tracking-widest uppercase pt-1">Beyond the work</p>
                <p class="text-neutral-300 text-lg leading-relaxed max-w-2xl">
                    {{ config('site.about.beyond') }}
                </p>
            </div>
        </section>
    @endif

    @include('partials.experience', ['sectionNumber' => '01'])
    @include('partials.research', ['sectionNumber' => '02'])
    @include('partials.stack', ['sectionNumber' => '03'])
    @include('about.partials.credentials', ['sectionNumber' => '04'])
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
