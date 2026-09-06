@extends('layouts.site', ['meta' => $meta])

@push('head')
    <x-site.json-ld :data="\App\Support\PersonJsonLd::forNamedPage('lead', '/lead')" />
@endpush

@section('content')
    @php
        $bookingUrl = config('site.booking.url');
        $bookingLabel = config('site.booking.label');
    @endphp

    <article class="lead-doc">
        {{-- Print-only identity so a forwarded PDF still names the author. --}}
        <header class="kit-print-masthead" aria-hidden="true">
            <p class="kit-print-masthead__name">{{ $person['name'] }}</p>
            <p class="kit-print-masthead__role">{{ $person['job_title'] }} · {{ $person['employer_display'] ?? $person['employer'] }} · {{ $person['location'] }}</p>
            <p class="kit-print-masthead__open">{{ $lead['title'] }}</p>
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
                <a href="{{ url('/lead') }}">karlhill.com/lead</a>
            </p>
        </header>

        <x-site.page-hero :eyebrow="$lead['eyebrow'] ?? 'Forward this page'" :breadcrumbs="[
            ['label' => 'Home', 'url' => '/'],
            ['label' => 'How I run delivery'],
        ]">
            <x-slot:title>{{ $lead['title'] }}</x-slot:title>

            <p class="text-neutral-300 text-base sm:text-lg leading-relaxed max-w-2xl">
                {{ $lead['lede'] }}
            </p>

            <div class="lead-screen-actions flex flex-wrap items-center gap-x-4 gap-y-3 mt-8 sm:mt-10">
                @if(filled($bookingUrl))
                    <a href="{{ url('/now#book') }}"
                       data-analytics-event="booking_cta_clicked"
                       data-analytics-location="lead-hero"
                       class="btn-accent-fill magnetic-btn inline-flex items-center justify-center min-h-11 gap-2 font-mono text-xs uppercase tracking-widest px-5 py-3">
                        {{ $bookingLabel }}
                    </a>
                @endif
                <a href="/kit"
                   class="btn-sweep inline-flex items-center justify-center min-h-11 gap-2 font-mono text-xs text-accent border border-accent/40 px-5 py-3 uppercase tracking-widest transition-colors">
                    Recruiter kit
                </a>
                <button type="button"
                        data-print
                        title="Print or save as PDF"
                        class="cursor-pointer inline-flex items-center justify-center min-h-11 font-mono text-xs text-neutral-300 border border-neutral-700 hover:border-accent hover:text-accent px-4 uppercase tracking-widest transition-colors">
                    Print packet
                </button>
            </div>

            @if(! empty($lead['updated']))
                <p class="mt-6 font-mono text-caption text-neutral-400 uppercase tracking-widest">
                    Updated {{ $lead['updated'] }}
                </p>
            @endif
        </x-site.page-hero>

        @if(! empty($lead['why']))
            <section class="site-section site-section--soft border-t border-neutral-800/50" aria-label="Why this page exists">
                <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
                    <h2 class="font-mono text-accent text-xs tracking-widest uppercase pt-1">Why this exists</h2>
                    <div class="max-w-2xl" data-summary-source>
                        <p class="text-neutral-300 text-base leading-relaxed">{{ $lead['why'] }}</p>
                        <x-site.on-device-summary
                            class="mt-6"
                            type="key-points"
                            length="short"
                            label="Summarize this packet"
                            :context="$lead['title'].'. '.$lead['lede']"
                        />
                    </div>
                </div>
            </section>
        @endif

        @foreach($lead['sections'] as $section)
            <section id="{{ $section['id'] }}" class="site-section border-t border-neutral-800/50 scroll-mt-24" aria-labelledby="lead-{{ $section['id'] }}-heading">
                <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
                    <h2 id="lead-{{ $section['id'] }}-heading" class="font-sans font-semibold text-lg sm:text-xl tracking-tight leading-snug text-neutral-100">
                        {{ $section['title'] }}
                    </h2>
                    <div class="max-w-2xl">
                        @if(! empty($section['intro']))
                            <p class="text-neutral-400 text-base leading-relaxed mb-6">{{ $section['intro'] }}</p>
                        @endif
                        <ul class="lead-packet-list">
                            @foreach($section['items'] as $item)
                                <li class="lead-packet-list__item">
                                    @if(is_array($item))
                                        <p class="lead-packet-list__title">{{ $item['title'] }}</p>
                                        <p class="lead-packet-list__body">{{ $item['body'] ?? '' }}</p>
                                    @else
                                        <p class="lead-packet-list__body">{{ $item }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </section>
        @endforeach

        @if(! empty($lead['not']))
            <section class="site-section border-t border-neutral-800/50" aria-label="What this page is not">
                <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
                    <h2 class="font-mono text-accent text-xs tracking-widest uppercase pt-1">What this is not</h2>
                    <div class="max-w-2xl">
                        <p class="text-neutral-400 text-base leading-relaxed">{{ $lead['not'] }}</p>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-3 mt-8">
                            <a href="/work/jacobs-mission-software"
                               class="inline-flex items-center min-h-11 font-mono text-xs text-accent uppercase tracking-widest hover:underline underline-offset-4">
                                Current work →
                            </a>
                            <a href="/work#chapters"
                               class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                                NASA proof
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
    </article>
@endsection
