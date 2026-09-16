@extends('layouts.site', ['meta' => $meta])

@push('head')
    <x-site.json-ld :data="\App\Support\PersonJsonLd::forNamedPage('kit', '/kit')" />
@endpush

@section('content')
    @php
        $primaryLinks = collect($links)->where('group', 'primary')->values();
        $moreLinks = collect($links)->where('group', 'more')->values();
        $credentials = \App\Support\SiteFeatures::contentCredentials();
        $moreCount = $moreLinks->count() + ($credentials ? 1 : 0);
    @endphp

    <div class="kit-doc" data-ask-source>
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

        <p class="site-page-hero__lede text-neutral-300">
            {{ $kit['lede'] }}
        </p>

        <div class="kit-screen-actions mt-6 sm:mt-7">
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
            </div>
        </div>
    </x-site.page-hero>

    <section class="site-section site-section--soft border-t border-neutral-800/50" aria-labelledby="kit-glance-heading">
        <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
            <h2 id="kit-glance-heading" class="kit-section-label font-mono text-accent text-xs tracking-widest uppercase pt-1 md:sticky md:top-24 md:self-start">At a glance</h2>
            <div class="kit-glance max-w-3xl">
                <div class="kit-bio">
                    @foreach($kit['glance'] ?? [] as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>

                <dl class="kit-facts">
                    <div class="kit-facts__item">
                        <dt class="kit-facts__label">Current role</dt>
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

                <x-site.on-device-ask
                    class="mt-8"
                    id="kit-ask"
                    source="[data-ask-source]"
                    :context="$askBrief"
                    :prompts="$askPrompts"
                    heading="Ask this kit"
                    label="Ask"
                    placeholder="What is Karl open to?"
                />

                <x-site.job-scope
                    class="kit-print-only mt-8"
                    heading="Current scope"
                    heading-id="kit-scope-heading"
                    :rows="$kit['scope'] ?? []"
                />
            </div>
        </div>
    </section>

    @if(! empty($kit['evidence']))
        <section class="site-section border-t border-neutral-800/50" aria-labelledby="kit-evidence-heading">
            <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
                <h2 id="kit-evidence-heading" class="kit-section-label font-mono text-accent text-xs tracking-widest uppercase pt-1 md:sticky md:top-24 md:self-start">Selected evidence</h2>
                <ul class="kit-highlights kit-highlights--flush max-w-3xl" aria-label="Selected evidence">
                    @foreach($kit['evidence'] as $item)
                        @php
                            $href = $item['url'] ?? (isset($item['path']) ? url($item['path']) : null);
                            $external = isset($item['url']);
                        @endphp
                        <li class="kit-highlights__item">
                            <span class="kit-highlights__mark text-accent" aria-hidden="true">→</span>
                            <span class="kit-highlights__text">
                                @if($href)
                                    <a href="{{ $href }}"
                                       @if($external) target="_blank" rel="noopener noreferrer" @endif
                                       class="text-neutral-200 hover:text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">
                                        {{ $item['label'] }}
                                    </a>
                                @else
                                    {{ $item['label'] }}
                                @endif
                                @if(! empty($item['meta']))
                                    <span class="kit-highlights__meta">{{ $item['meta'] }}</span>
                                @endif
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    @if(! empty($kit['direction']))
        <section class="kit-print-only site-section site-section--soft border-t border-neutral-800/50" aria-labelledby="kit-direction-heading">
            <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
                <h2 id="kit-direction-heading" class="kit-section-label font-mono text-accent text-xs tracking-widest uppercase pt-1 md:sticky md:top-24 md:self-start">Career direction</h2>
                <div class="kit-bio max-w-3xl">
                    @foreach($kit['direction'] as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="site-section border-t border-neutral-800/50" aria-labelledby="kit-links-heading">
        <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
            <h2 id="kit-links-heading" class="kit-section-label font-mono text-accent text-xs tracking-widest uppercase pt-1">Links</h2>
            <div class="max-w-2xl">
                <ul class="kit-links divide-y divide-neutral-800/80">
                    @foreach($primaryLinks as $link)
                        @include('kit.partials.link-row', ['link' => $link])
                    @endforeach
                </ul>

                @if($moreCount > 0)
                    <details class="kit-links-more mt-2">
                        <summary class="kit-links-more__summary font-mono text-xs text-neutral-400 uppercase tracking-widest min-h-11 flex items-center cursor-pointer hover:text-accent transition-colors">
                            More links
                            <span class="text-neutral-500 normal-case tracking-normal ml-2">({{ $moreCount }})</span>
                        </summary>
                        <ul class="kit-links divide-y divide-neutral-800/80 mt-1" hidden="until-found">
                            @foreach($moreLinks as $link)
                                @include('kit.partials.link-row', ['link' => $link])
                            @endforeach
                            @if($credentials)
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
