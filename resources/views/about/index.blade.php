@extends('layouts.site', ['meta' => $meta])

@push('head')
    <x-site.json-ld :data="\App\Support\PersonJsonLd::forNamedPage('about', '/about')" />
@endpush

@section('content')
    @php
        $bookingUrl = config('site.booking.url');
        $bookingLabel = config('site.booking.label');
    @endphp

    <x-site.page-hero :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'About'],
    ]">
        <x-slot:title>About Karl</x-slot:title>

        <p class="text-neutral-300 text-base sm:text-lg leading-relaxed max-w-2xl">
            {{ config('site.about.lede') }}
        </p>

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
                <li><a href="#experience" class="hover:text-accent transition-colors">Career</a></li>
                <li><a href="#impact" class="hover:text-accent transition-colors">Selected impact</a></li>
                <li><a href="#research" class="hover:text-accent transition-colors">Research</a></li>
                <li><a href="#beyond" class="hover:text-accent transition-colors">Beyond the work</a></li>
            </ul>
        </nav>
    </x-site.page-hero>

    @include('about.partials.how-i-lead', ['sectionNumber' => '01'])
    @include('about.partials.arc', ['sectionNumber' => '02'])
    @include('about.partials.impact', ['sectionNumber' => '03'])
    @include('partials.research', ['sectionNumber' => '04'])

    @if(config('site.about.beyond'))
        @php
            $discogs = collect(config('site.social'))->first(fn ($link) => ($link['icon'] ?? '') === 'discogs');
            $beyond = config('site.about.beyond');
        @endphp
        <section id="beyond" aria-label="Beyond the work" class="site-section site-section--soft border-t border-neutral-800/50">
            <div class="site-shell grid md:grid-cols-[200px_1fr] gap-8 md:gap-14" data-reveal>
                <p class="font-mono text-accent text-xs tracking-widest uppercase pt-1">Beyond the work</p>
                <p class="text-neutral-300 text-lg leading-relaxed max-w-2xl">
                    @if($discogs && str_contains($beyond, 'Discogs'))
                        {!! str_replace(
                            'Discogs',
                            '<a href="'.e($discogs['url']).'" target="_blank" rel="me noopener noreferrer" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">Discogs</a>',
                            e($beyond)
                        ) !!}
                    @else
                        {{ $beyond }}
                    @endif
                </p>
            </div>
        </section>
    @endif
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
