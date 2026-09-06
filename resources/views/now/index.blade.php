@extends('layouts.site', ['meta' => $meta])

@push('head')
    <x-site.json-ld :data="\App\Support\PersonJsonLd::forNamedPage('now', '/now')" />
@endpush

@section('content')
    @php
        $bookingUrl = config('site.booking.url');
        $bookingEmbed = config('site.booking.embed_src');
        $bookingLabel = config('site.booking.label');
        $recruiters = $now['recruiters'] ?? null;
    @endphp

    <x-site.page-hero eyebrow="Current focus" :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Now'],
    ]">
        <x-slot:title>Now</x-slot:title>

        <p class="text-neutral-300 text-base sm:text-lg leading-relaxed max-w-2xl">
            {{ $now['lede'] }}
        </p>

        @if(! empty($now['updated']))
            <p class="mt-6 font-mono text-caption text-neutral-400 uppercase tracking-widest">
                Updated {{ $now['updated'] }}
            </p>
        @endif
    </x-site.page-hero>

    <section id="focus" class="site-section site-section--soft border-t border-neutral-800/50" aria-label="Focus areas">
        <div class="site-shell space-y-12">
            @foreach($now['focus'] as $item)
                <div class="grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
                    <h2 class="font-sans font-semibold text-xl sm:text-2xl tracking-tight leading-snug text-neutral-100">{{ $item['title'] }}</h2>
                    <div class="max-w-2xl">
                        <p class="text-neutral-400 text-base leading-relaxed">{{ $item['body'] }}</p>
                        @if(! empty($item['link']))
                            <a href="{{ $item['link'] }}"
                               class="inline-flex items-center min-h-11 mt-4 font-mono text-xs text-accent uppercase tracking-widest hover:underline underline-offset-4">
                                {{ $item['link_label'] ?? 'Read more' }} →
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    @if($recruiters)
        <section id="recruiters" class="site-section border-t border-neutral-800/50" aria-label="For recruiters and hiring managers">
            <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
                <p class="font-mono text-accent text-xs tracking-widest uppercase pt-1">{{ $recruiters['eyebrow'] ?? 'For recruiters' }}</p>
                <div class="max-w-2xl">
                    <p class="text-neutral-200 text-lg leading-relaxed">{{ $recruiters['body'] }}</p>
                    @if(! empty($recruiters['bullets']))
                        <ul class="mt-6 space-y-2 text-neutral-400 text-sm leading-relaxed list-disc pl-5">
                            @foreach($recruiters['bullets'] as $bullet)
                                <li>{{ $bullet }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-3 mt-8">
                        @if(filled($bookingUrl))
                            <a href="#book"
                               data-idle-cta
                               data-analytics-event="booking_cta_clicked"
                               data-analytics-location="now-intro"
                               class="btn-accent-fill inline-flex items-center justify-center min-h-11 gap-2 font-mono text-xs px-5 py-3 uppercase tracking-widest">
                                {{ $bookingLabel }}
                            </a>
                        @endif
                        <a href="#contact"
                           class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                            Contact
                        </a>
                        <a href="/resume"
                           class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                            Resume
                        </a>
                        <a href="/lead"
                           class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                            How I run delivery
                        </a>
                        <a href="/kit"
                           class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                            Recruiter kit
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if(filled($bookingEmbed))
        <section id="book" class="site-section border-t border-neutral-800/50 scroll-mt-28" aria-label="{{ $bookingLabel }}">
            <div class="site-shell" data-reveal>
                <div class="grid md:grid-cols-[220px_1fr] gap-6 md:gap-12 mb-8">
                    <p class="font-mono text-accent text-xs tracking-widest uppercase pt-1">Book time</p>
                    <div class="max-w-2xl">
                        <h2 class="font-sans font-semibold text-2xl sm:text-3xl tracking-tight text-neutral-100 mb-3">{{ $bookingLabel }}</h2>
                        <p class="text-neutral-400 text-sm leading-relaxed">
                            Pick a slot below — or
                            <a href="#contact" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">email me</a>
                            if that works better.
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
                        Embed not loading?
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
