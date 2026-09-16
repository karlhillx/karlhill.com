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

    <x-site.page-hero :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Now'],
    ]">
        <x-slot:title>Now</x-slot:title>

        @if(! empty($now['updated']))
            <p class="font-mono text-caption text-neutral-400 uppercase tracking-widest">
                Updated {{ $now['updated'] }}
            </p>
        @endif

        <p class="mt-5 text-neutral-100 text-lg sm:text-xl leading-relaxed max-w-2xl">
            {{ $now['lede'] }}
        </p>

        @if(! empty($now['body']))
            <p class="mt-5 text-neutral-300 text-base sm:text-lg leading-relaxed max-w-2xl">
                {{ $now['body'] }}
            </p>
        @endif

        @if(! empty($now['focus']) && is_string($now['focus']))
            <p class="mt-5 text-neutral-400 text-base leading-relaxed max-w-2xl">
                {{ $now['focus'] }}
            </p>
        @endif

        <div class="flex flex-wrap items-center gap-x-4 gap-y-3 mt-6 sm:mt-8">
            <x-site.button variant="link" href="/kit"
                data-analytics-event="recruiter_link_opened"
                data-analytics-location="now-hero"
                data-analytics-target="kit">
                Recruiter kit
            </x-site.button>
        </div>
    </x-site.page-hero>

    @if(filled($bookingEmbed))
        <section id="book" class="site-section border-t border-neutral-800/50 scroll-mt-28" aria-label="{{ $bookingLabel }}">
            <div class="site-shell" data-reveal>
                <div class="grid md:grid-cols-[220px_1fr] gap-6 md:gap-12 mb-8">
                    <p class="font-mono text-accent text-xs tracking-widest uppercase pt-1">Book time</p>
                    <div class="max-w-2xl">
                        <h2 class="font-sans font-semibold text-2xl sm:text-3xl tracking-tight text-neutral-100 mb-3">{{ $bookingLabel }}</h2>
                        <p class="text-neutral-400 text-sm leading-relaxed">
                            Choose a time below, or
                            <a href="#contact" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">email me</a>.
                        </p>
                    </div>
                </div>
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
