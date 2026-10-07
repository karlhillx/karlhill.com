@extends('layouts.site', ['meta' => $meta])

@push('head')
    <x-site.json-ld :data="\App\Support\PersonJsonLd::forNamedPage('about', '/about')" />
@endpush

@section('content')
    @php
        $lede = config('site.about.lede');
        $ledeParagraphs = is_array($lede) ? $lede : array_filter([$lede]);
        $beyond = config('site.about.beyond');
        $beyondParagraphs = is_array($beyond) ? $beyond : array_filter([$beyond]);
        $discogs = collect(config('site.social'))->first(fn ($link) => ($link['icon'] ?? '') === 'discogs');
    @endphp

    <x-site.page-hero :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'About'],
    ]">
        <x-slot:title>About</x-slot:title>

        @if($ledeParagraphs !== [])
            <div class="about-lede max-w-2xl">
                @foreach($ledeParagraphs as $paragraph)
                    <p class="text-neutral-300 text-base sm:text-lg leading-relaxed">{{ $paragraph }}</p>
                @endforeach
            </div>
        @endif

        <div class="site-page-hero__actions">
            <x-site.button variant="primary" href="/work">Explore the portfolio</x-site.button>
            <x-site.button variant="secondary" href="/resume">Resume</x-site.button>
        </div>
    </x-site.page-hero>

    @include('about.partials.arc', ['sectionNumber' => '01'])

    <x-site.section id="focus" section-label="Current focus" border="soft">
        <div class="grid md:grid-cols-[200px_1fr] gap-8 md:gap-14" data-reveal>
            <h2 class="eyebrow">Current focus</h2>
            <div class="about-lede max-w-2xl">
                <p>{{ config('site.now.lede') }} {{ config('site.now.body') }}</p>
                <p>{{ config('site.now.focus') }}</p>
            </div>
        </div>
    </x-site.section>

    <x-site.section id="approach" section-label="How I work" border="soft">
        <div class="grid md:grid-cols-[200px_1fr] gap-8 md:gap-14" data-reveal>
            <h2 class="eyebrow">How I work</h2>
            <div class="about-lede max-w-2xl">
                @foreach(config('site.about.approach') as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
                <p>{{ config('site.person.availability') }}</p>
                <a href="/#contact" class="portfolio-text-link">Get in touch <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </x-site.section>

    @if($beyondParagraphs !== [])
        <section id="beyond" aria-label="Beyond the work" class="site-section site-section--soft border-t border-neutral-800/40">
            <div class="site-shell grid md:grid-cols-[200px_1fr] gap-8 md:gap-14" data-reveal>
                <p class="eyebrow pt-1">Beyond the work</p>
                <div class="about-lede max-w-2xl">
                    @foreach($beyondParagraphs as $paragraph)
                        <p class="text-neutral-300 text-lg leading-relaxed">
                            @if($discogs && str_contains($paragraph, 'Discogs'))
                                {!! str_replace(
                                    'Discogs',
                                    '<a href="'.e($discogs['url']).'" target="_blank" rel="me noopener noreferrer" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">Discogs</a>',
                                    e($paragraph)
                                ) !!}
                            @else
                                {{ $paragraph }}
                            @endif
                        </p>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
