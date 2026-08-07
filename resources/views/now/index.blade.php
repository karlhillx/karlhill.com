@extends('layouts.site', ['meta' => $meta])

@section('content')
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

    <section class="site-section site-section--soft border-t border-neutral-800/50" aria-label="Focus areas">
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
                        @if(filled(config('site.booking.url')))
                            <a href="{{ config('site.booking.url') }}"
                               target="_blank" rel="noopener noreferrer"
                               class="btn-sweep inline-flex items-center justify-center min-h-11 gap-2 font-mono text-xs text-accent border border-accent/40 px-5 py-3 uppercase tracking-widest transition-colors">
                                {{ config('site.booking.label') }} →
                            </a>
                            <a href="/#contact"
                               class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                                Or send a message
                            </a>
                        @else
                            <a href="/#contact"
                               class="btn-sweep inline-flex items-center justify-center min-h-11 gap-2 font-mono text-xs text-accent border border-accent/40 px-5 py-3 uppercase tracking-widest transition-colors">
                                Start a conversation →
                            </a>
                        @endif
                        <a href="/about#how-i-lead"
                           class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                            How I lead
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
