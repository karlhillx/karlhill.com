@extends('layouts.site', ['meta' => $meta])

@push('head')
    <x-site.json-ld :data="\App\Support\PersonJsonLd::forNamedPage('kit', '/kit')" />
@endpush

@section('content')
    @php
        $primaryLinks = collect($links)->where('group', 'primary')->values();
        $moreLinks = collect($links)->where('group', 'more')->values();
    @endphp

    <div class="kit-doc">
    {{-- Print-only masthead: name + reachability first (screen uses the page hero). --}}
    <header class="kit-print-masthead" aria-hidden="true">
        <p class="kit-print-masthead__name">{{ $person['name'] }}</p>
        <p class="kit-print-masthead__role">{{ $person['job_title'] }} · {{ $person['employer_display'] ?? $person['employer'] }} · {{ $person['location'] }}</p>
        <p class="kit-print-masthead__open">{{ $person['availability'] }}</p>
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
        </p>
    </header>

    <x-site.page-hero class="kit-screen-hero" :eyebrow="$kit['eyebrow'] ?? 'Recruiter kit'" :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Kit'],
    ]">
        <x-slot:title>Recruiter kit</x-slot:title>

        <p class="text-neutral-300 text-base sm:text-lg leading-relaxed max-w-2xl">
            {{ $kit['lede'] }}
        </p>

        <div class="kit-screen-actions mt-6 sm:mt-8">
            <div class="kit-screen-actions__buttons">
                @if(filled($bookingUrl))
                    <x-site.button variant="primary" :href="url('/now#book')"
                        data-analytics-event="booking_cta_clicked"
                        data-analytics-location="kit-actions">
                        {{ $bookingLabel }}
                    </x-site.button>
                @endif
                @if($pdfHref)
                    <x-site.button variant="secondary" :href="$pdfHref"
                        download="Karl-Hill-Resume.pdf"
                        data-analytics-event="resume_downloaded"
                        data-analytics-location="kit-actions">
                        Download resume PDF
                    </x-site.button>
                @endif
            </div>
            <div class="kit-screen-actions__links">
                <x-site.button variant="link" class="kit-print-btn" data-print title="Print or save as PDF">
                    Print kit
                </x-site.button>
                <x-site.button variant="link" href="#contact">Contact</x-site.button>
            </div>
        </div>
    </x-site.page-hero>

    <section class="site-section site-section--soft border-t border-neutral-800/50" aria-labelledby="kit-glance-heading">
        <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
            <h2 id="kit-glance-heading" class="kit-section-label font-mono text-accent text-xs tracking-widest uppercase pt-1 md:sticky md:top-24 md:self-start">At a glance</h2>
            <div class="kit-glance max-w-3xl">
                <p class="kit-bio">{{ $kit['bio'] ?? $person['bio'] }}</p>

                <dl class="kit-facts">
                    <div class="kit-facts__item">
                        <dt class="kit-facts__label">Name</dt>
                        <dd class="kit-facts__value kit-facts__value--name">{{ $person['name'] }}</dd>
                    </div>
                    <div class="kit-facts__item">
                        <dt class="kit-facts__label">Title</dt>
                        <dd class="kit-facts__value">{{ $person['job_title'] }} · {{ $person['employer_display'] ?? $person['employer'] }}</dd>
                    </div>
                    <div class="kit-facts__item">
                        <dt class="kit-facts__label">Location</dt>
                        <dd class="kit-facts__value">{{ $person['location'] }}</dd>
                    </div>
                    <div class="kit-facts__item kit-facts__item--wide">
                        <dt class="kit-facts__label">Open to</dt>
                        <dd class="kit-facts__value kit-facts__value--open">
                            {{ $person['availability'] }}
                        </dd>
                    </div>
                </dl>

                @php($jobScope = config('site.experience.current.scope') ?? [])
                <x-site.job-scope
                    class="mt-8"
                    heading="Current scope"
                    heading-id="kit-scope-heading"
                    :scope="$jobScope"
                />

                @if(! empty($kit['highlights']))
                    <ul class="kit-highlights" aria-label="Hiring packet">
                        @foreach($kit['highlights'] as $item)
                            <li class="kit-highlights__item">
                                <span class="kit-highlights__mark text-accent" aria-hidden="true">→</span>
                                <span class="kit-highlights__text">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </section>

    <section class="site-section border-t border-neutral-800/50" aria-labelledby="kit-links-heading">
        <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
            <h2 id="kit-links-heading" class="kit-section-label font-mono text-accent text-xs tracking-widest uppercase pt-1">Links</h2>
            <div class="max-w-2xl">
                <ul class="kit-links divide-y divide-neutral-800/80">
                    @foreach($primaryLinks as $link)
                        @include('kit.partials.link-row', ['link' => $link])
                    @endforeach
                    @if(\App\Support\SiteFeatures::contentCredentials())
                        <li class="py-1">
                            <a href="{{ url('/api/credentials.json') }}"
                               class="group flex flex-wrap items-center justify-between gap-2 min-h-11 py-3">
                                <span class="kit-link-label text-neutral-200 group-hover:text-accent transition-colors">
                                    Content credentials
                                </span>
                                <span class="kit-link-meta font-mono text-caption text-neutral-500 uppercase tracking-widest">C2PA sidecar</span>
                            </a>
                        </li>
                    @endif
                </ul>

                @if($moreLinks->isNotEmpty())
                    <details class="kit-links-more mt-2">
                        <summary class="kit-links-more__summary font-mono text-xs text-neutral-400 uppercase tracking-widest min-h-11 flex items-center cursor-pointer hover:text-accent transition-colors">
                            More links
                            <span class="text-neutral-500 normal-case tracking-normal ml-2">({{ $moreLinks->count() }})</span>
                        </summary>
                        <ul class="kit-links divide-y divide-neutral-800/80 mt-1">
                            @foreach($moreLinks as $link)
                                @include('kit.partials.link-row', ['link' => $link])
                            @endforeach
                        </ul>
                    </details>
                @endif
            </div>
        </div>
    </section>
    </div>
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
