@extends('layouts.site', ['meta' => $meta])

@section('content')
    @php
        $bookingUrl = config('site.booking.url');
        $linkedin = collect(config('site.social'))->first(fn ($link) => ($link['icon'] ?? '') === 'linkedin');
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
            <p class="mt-6 font-mono text-[10px] text-neutral-500 uppercase tracking-widest">
                Updated {{ $now['updated'] }}
            </p>
        @endif
    </x-site.page-hero>

    @if($recruiters)
        <section class="site-section site-section--soft border-t border-neutral-800/50" aria-label="For recruiters and hiring managers">
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
                            <a href="{{ $bookingUrl }}"
                               target="_blank" rel="noopener noreferrer"
                               class="btn-sweep inline-flex items-center justify-center min-h-11 gap-2 font-mono text-xs text-accent border border-accent/40 px-5 py-3 uppercase tracking-widest transition-colors">
                                {{ config('site.booking.label') }} →
                            </a>
                        @endif
                        @if($linkedin)
                            <a href="{{ $linkedin['url'] }}"
                               target="_blank" rel="me noopener noreferrer"
                               class="btn-sweep inline-flex items-center justify-center min-h-11 gap-2 font-mono text-xs text-neutral-300 border border-neutral-700 px-5 py-3 uppercase tracking-widest transition-colors">
                                LinkedIn →
                            </a>
                        @endif
                        <a href="#contact"
                           class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                            Send a message
                        </a>
                        <a href="/resume"
                           class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-500 hover:text-accent uppercase tracking-widest transition-colors">
                            Resume
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="site-section {{ $recruiters ? 'border-t border-neutral-800/50' : 'site-section--soft border-t border-neutral-800/50' }}" aria-label="Focus areas">
        <div class="site-shell space-y-12">
            @foreach($now['focus'] as $item)
                <div class="grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
                    <h2 class="font-display text-2xl tracking-wide leading-tight text-white">{{ $item['title'] }}</h2>
                    <p class="text-neutral-400 text-base leading-relaxed max-w-2xl">{{ $item['body'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    @if(! empty($now['reading']))
        <section class="site-section border-t border-neutral-800/50" aria-label="Open to">
            <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
                <p class="font-mono text-accent text-xs tracking-widest uppercase pt-1">Open to</p>
                <div class="max-w-2xl">
                    <p class="text-neutral-300 text-lg leading-relaxed">{{ $now['reading'] }}</p>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-3 mt-8">
                        <a href="#contact"
                           class="btn-sweep inline-flex items-center justify-center min-h-11 gap-2 font-mono text-xs text-accent border border-accent/40 px-5 py-3 uppercase tracking-widest transition-colors">
                            Start a conversation →
                        </a>
                        <a href="/about#how-i-lead"
                           class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                            How I lead
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <x-site.contact-section id-prefix="now-contact" />
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
