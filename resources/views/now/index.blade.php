@extends('layouts.site', ['meta' => $meta])

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
            <p class="mt-6 font-mono text-[10px] text-neutral-400 uppercase tracking-widest">
                Updated {{ $now['updated'] }}
            </p>
        @endif
    </x-site.page-hero>

    <section class="site-section site-section--soft border-t border-neutral-800/50" aria-label="Focus areas">
        <div class="site-shell space-y-12">
            @foreach($now['focus'] as $item)
                <div class="grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
                    <h2 class="font-display text-2xl tracking-wide leading-tight text-white">{{ $item['title'] }}</h2>
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
        <section class="site-section border-t border-neutral-800/50" aria-label="For recruiters and hiring managers">
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
                               class="btn-sweep inline-flex items-center justify-center min-h-11 gap-2 font-mono text-xs text-accent border border-accent/40 px-5 py-3 uppercase tracking-widest transition-colors">
                                {{ $bookingLabel }} →
                            </a>
                        @endif
                        <a href="#contact"
                           class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                            Send a message
                        </a>
                        <a href="/resume"
                           class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                            Resume
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
                        <h2 class="font-display text-3xl tracking-wide text-white mb-3">{{ $bookingLabel }}</h2>
                        <p class="text-neutral-400 text-sm leading-relaxed">
                            Pick a slot below — or
                            <a href="#contact" class="text-accent hover:underline underline-offset-2">send a message</a>
                            if email works better. I reply personally.
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
                    <p class="mt-4 font-mono text-[10px] text-neutral-500 uppercase tracking-widest">
                        Embed not loading?
                        <a href="{{ $bookingUrl }}" target="_blank" rel="noopener noreferrer" class="text-accent hover:underline">
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
