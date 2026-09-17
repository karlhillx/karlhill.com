@extends('layouts.site', ['meta' => $meta])

@push('head')
    <x-site.json-ld :data="\App\Support\PersonJsonLd::forNamedPage('now', '/now')" />
@endpush

@section('content')
    @php
        $bookingUrl = config('site.booking.url');
        $bookingEmbed = config('site.booking.embed_src');
        $bookingLabel = config('site.booking.label');
    @endphp

    <x-site.page-hero class="now-hero" :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Now'],
    ]">
        <x-slot:title>Now</x-slot:title>

        <div class="now-status">
            @if(! empty($now['updated']))
                <p class="now-status__updated">
                    Updated {{ $now['updated'] }}
                </p>
            @endif

            <p class="now-status__lede">
                {{ $now['lede'] }}
            </p>

            @if(! empty($now['body']))
                <p class="now-status__body">
                    {{ $now['body'] }}
                </p>
            @endif

            @if(! empty($now['focus']) && is_string($now['focus']))
                <p class="now-status__focus">
                    {{ $now['focus'] }}
                </p>
            @endif

            <div class="now-status__actions">
                @if(filled($bookingUrl) || filled($bookingEmbed))
                    <x-site.button variant="primary" href="#book"
                        data-analytics-event="booking_cta_clicked"
                        data-analytics-location="now-hero">
                        {{ $bookingLabel }}
                    </x-site.button>
                @endif
                <x-site.button variant="link" href="/kit"
                    data-analytics-event="recruiter_link_opened"
                    data-analytics-location="now-hero"
                    data-analytics-target="kit">
                    Recruiter kit
                </x-site.button>
            </div>
        </div>
    </x-site.page-hero>

    @if(filled($bookingEmbed))
        <section id="book" class="now-book site-section border-t border-neutral-800/50 scroll-mt-28" aria-labelledby="now-book-heading">
            <div class="site-shell" data-reveal>
                <header class="now-book__mast">
                    <h2 id="now-book-heading" class="now-book__title">Book time</h2>
                    <p class="now-book__lede">
                        Choose a time below, or
                        <a href="#contact" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">email me</a>.
                    </p>
                </header>
                <div class="booking-embed">
                    <iframe
                        class="booking-embed__frame"
                        src="{{ $bookingEmbed }}"
                        title="{{ $bookingLabel }}"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allow="payment *"
                    ></iframe>
                </div>
                @if(filled($bookingUrl))
                    <p class="mt-4 font-mono text-caption text-neutral-500 uppercase tracking-widest">
                        Scheduler not loading?
                        <a href="{{ $bookingUrl }}" target="_blank" rel="noopener noreferrer" data-no-ext data-analytics-event="scheduler_opened" data-analytics-location="now-embed-fallback" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">
                            Open scheduler ↗
                        </a>
                    </p>
                @endif
            </div>
        </section>
    @endif
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
