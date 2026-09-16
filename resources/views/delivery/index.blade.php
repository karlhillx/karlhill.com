@extends('layouts.site', ['meta' => $meta])

@push('head')
    <x-site.json-ld :data="\App\Support\PersonJsonLd::forNamedPage('delivery', '/delivery')" />
@endpush

@section('content')
    @php
        $bookingUrl = config('site.booking.url');
        $bookingLabel = config('site.booking.label');
    @endphp

    <article class="delivery-doc">
        {{-- Print-only identity so a forwarded PDF still names the author. --}}
        <header class="kit-print-masthead" aria-hidden="true">
            <p class="kit-print-masthead__name">{{ $person['name'] }}</p>
            <p class="kit-print-masthead__role">{{ $person['job_title'] }} · {{ $person['employer_display'] ?? $person['employer'] }} · {{ $person['location'] }}</p>
            <p class="kit-print-masthead__open">{{ $delivery['title'] }}</p>
            <p class="kit-print-masthead__contact">
                <a href="mailto:{{ $person['email'] }}">{{ $person['email'] }}</a>
                @if($linkedin)
                    <span aria-hidden="true"> · </span>
                    <a href="{{ $linkedin['url'] }}">LinkedIn</a>
                @endif
                @if($github)
                    <span aria-hidden="true"> · </span>
                    <a href="{{ $github['url'] }}">GitHub</a>
                @endif
                @if($pdfHref)
                    <span aria-hidden="true"> · </span>
                    <a href="{{ $pdfHref }}">Resume PDF</a>
                @endif
                <span aria-hidden="true"> · </span>
                <a href="{{ url('/delivery') }}">karlhill.com/delivery</a>
            </p>
        </header>

        <x-site.page-hero :eyebrow="$delivery['eyebrow'] ?? 'Forward this page'" :breadcrumbs="[
            ['label' => 'Home', 'url' => '/'],
            ['label' => 'Engineering delivery'],
        ]">
            <x-slot:title>{{ $delivery['title'] }}</x-slot:title>

            <p class="text-neutral-300 text-base sm:text-lg leading-relaxed max-w-2xl">
                {{ $delivery['lede'] }}
            </p>

            <div class="delivery-screen-actions flex flex-wrap items-center gap-x-4 gap-y-3 mt-6 sm:mt-8">
                @if(filled($bookingUrl))
                    <x-site.button variant="primary" :href="url('/now#book')"
                        data-analytics-event="booking_cta_clicked"
                        data-analytics-location="delivery-hero">
                        {{ $bookingLabel }}
                    </x-site.button>
                @endif
                <x-site.button variant="secondary" href="/kit">Recruiter kit</x-site.button>
                <x-site.button variant="link" href="/about">About</x-site.button>
                <x-site.button variant="link" data-print title="Print or save as PDF">Print packet</x-site.button>
            </div>

            @if(! empty($delivery['updated']))
                <p class="mt-5 font-mono text-caption text-neutral-400 uppercase tracking-widest">
                    Updated {{ $delivery['updated'] }}
                </p>
            @endif
        </x-site.page-hero>

        @if(! empty($delivery['why']))
            <section class="site-section site-section--soft border-t border-neutral-800/50" aria-label="Why this page exists">
                <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
                    <h2 class="font-mono text-accent text-xs tracking-widest uppercase pt-1">Why this exists</h2>
                    <div class="max-w-2xl" data-summary-source>
                        <p class="text-neutral-300 text-base leading-relaxed">{{ $delivery['why'] }}</p>
                        <x-site.on-device-summary
                            type="key-points"
                            length="short"
                            label="Summarize this packet"
                            :context="$delivery['title'].'. '.$delivery['lede']"
                        />
                    </div>
                </div>
            </section>
        @endif

        @foreach($delivery['sections'] as $section)
            <section id="{{ $section['id'] }}" class="site-section border-t border-neutral-800/50 scroll-mt-24" aria-labelledby="delivery-{{ $section['id'] }}-heading">
                <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
                    <h2 id="delivery-{{ $section['id'] }}-heading" class="font-sans font-semibold text-xl sm:text-2xl tracking-tight leading-snug text-neutral-100">
                        {{ $section['title'] }}
                    </h2>
                    <div class="max-w-2xl">
                        @if(! empty($section['intro']))
                            <p class="text-neutral-400 text-base leading-relaxed mb-6">{{ $section['intro'] }}</p>
                        @endif
                        <ul class="delivery-packet-list">
                            @foreach($section['items'] as $item)
                                <li class="delivery-packet-list__item">
                                    @if(is_array($item))
                                        <p class="delivery-packet-list__title">{{ $item['title'] }}</p>
                                        <p class="delivery-packet-list__body">{{ $item['body'] ?? '' }}</p>
                                    @else
                                        <p class="delivery-packet-list__body">{{ $item }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </section>
        @endforeach

        @if(! empty($delivery['not']))
            <section class="site-section border-t border-neutral-800/50" aria-label="What this page is not">
                <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
                    <h2 class="font-mono text-accent text-xs tracking-widest uppercase pt-1">What this is not</h2>
                    <div class="max-w-2xl">
                        <p class="text-neutral-400 text-base leading-relaxed">{{ $delivery['not'] }}</p>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-3 mt-8">
                            <a href="/work/jacobs-mission-software"
                               class="inline-flex items-center min-h-11 font-mono text-xs text-accent uppercase tracking-widest hover:underline underline-offset-4">
                                Current work →
                            </a>
                            <a href="/work#chapters"
                               class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                                NASA proof
                            </a>
                            <a href="/about"
                               class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                                About
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </article>
@endsection
