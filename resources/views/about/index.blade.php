@extends('layouts.site', ['meta' => $meta])

@push('head')
    <x-site.json-ld :data="\App\Support\PersonJsonLd::forNamedPage('about', '/about')" />
@endpush

@section('content')
    @php
        $bookingUrl = config('site.booking.url');
        $bookingLabel = config('site.booking.label');
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

        <div class="flex flex-wrap items-center gap-x-4 gap-y-3 mt-6 sm:mt-8">
            @if(filled($bookingUrl))
                <x-site.button variant="primary" href="/now#book"
                    data-analytics-event="booking_cta_clicked"
                    data-analytics-location="about-hero">
                    {{ $bookingLabel }}
                </x-site.button>
            @endif
            <x-site.button variant="secondary" href="/kit">Recruiter kit</x-site.button>
            <x-site.button variant="link" href="/resume">Resume</x-site.button>
            <x-site.button variant="link" href="#contact">Contact</x-site.button>
        </div>

        <nav class="about-jump mt-8 sm:mt-10" aria-label="On this page">
            <ul class="flex flex-wrap gap-x-5 gap-y-2 font-mono text-caption uppercase tracking-widest text-neutral-500">
                <li><a href="#how-i-lead" class="hover:text-accent transition-colors">Leadership</a></li>
                <li><a href="#delivery" class="hover:text-accent transition-colors">Delivery</a></li>
                <li><a href="#experience" class="hover:text-accent transition-colors">Career</a></li>
                <li><a href="#impact" class="hover:text-accent transition-colors">Numbers</a></li>
                <li><a href="#research" class="hover:text-accent transition-colors">Research</a></li>
                <li><a href="#beyond" class="hover:text-accent transition-colors">Beyond the work</a></li>
            </ul>
        </nav>
    </x-site.page-hero>

    @include('about.partials.how-i-lead', ['sectionNumber' => '01'])
    @include('about.partials.delivery', ['sectionNumber' => '02'])
    @include('about.partials.arc', ['sectionNumber' => '03'])
    @include('about.partials.impact', ['sectionNumber' => '04'])
    @include('partials.research', ['sectionNumber' => '05'])

    @if($beyondParagraphs !== [])
        <section id="beyond" aria-label="Beyond the work" class="site-section site-section--soft border-t border-neutral-800/50">
            <div class="site-shell grid md:grid-cols-[200px_1fr] gap-8 md:gap-14" data-reveal>
                <p class="font-mono text-accent text-xs tracking-widest uppercase pt-1">Beyond the work</p>
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
