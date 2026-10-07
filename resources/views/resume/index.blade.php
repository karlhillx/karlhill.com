@extends('layouts.site', ['meta' => $meta])

@push('head')
    <x-site.json-ld :data="\App\Support\PersonJsonLd::forNamedPage('resume', '/resume')" />
@endpush

@section('content')
    @php
        $bookingUrl = config('site.booking.url');
        $bookingLabel = config('site.booking.label');
    @endphp

    <x-site.page-hero :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Resume'],
    ]">
        <x-slot:title>Resume</x-slot:title>

        <p class="site-page-hero__lede text-neutral-300">
            The complete chronology, technical background, and credentials. For problems, decisions, and shipped outcomes, <a href="/work" class="portfolio-text-link">explore the portfolio</a>.
        </p>

        {{-- The PDF is this page's purpose, so it takes the fill; booking is the secondary. --}}
        <div class="site-page-hero__actions">
            @if(! empty($pdf))
                <x-site.button variant="primary" :href="$pdf"
                    download="Karl-Hill-Resume.pdf"
                    data-analytics-event="resume_downloaded"
                    data-analytics-location="resume-hero">
                    Download PDF
                </x-site.button>
            @endif
            @if(filled($bookingUrl))
                <x-site.button variant="secondary" href="/#book"
                    data-analytics-event="booking_cta_clicked"
                    data-analytics-location="resume-hero">
                    {{ $bookingLabel }}
                </x-site.button>
            @endif
            @if(\App\Support\SiteFeatures::contentCredentials())
                <x-site.button variant="link" href="/api/credentials.json">
                    Content credentials
                </x-site.button>
            @endif
        </div>
    </x-site.page-hero>

    <article class="resume-doc site-section site-section--soft border-t border-neutral-800/40" aria-label="Resume" data-ask-source>
        <div class="site-shell resume-shell">
            {{-- Sidebar first in DOM so print float:right sits beside the main column like the classic PDF. --}}
            <aside class="resume-aside" aria-label="Contact and expertise">
                <nav class="resume-aside-block resume-nav print:hidden" aria-label="Resume sections">
                    <h2 class="resume-aside-title">Jump to</h2>
                    <ul class="resume-aside-list resume-jump-list">
                        <li><a href="#resume-summary">Summary</a></li>
                        @if(! empty($resume['impact']))
                            <li><a href="#resume-impact">Impact</a></li>
                        @endif
                        <li><a href="#resume-experience">Experience</a></li>
                        @if(! empty($resume['products']))
                            <li><a href="#resume-products">Independent Products</a></li>
                        @endif
                        @if(! empty($resume['tooling']))
                            <li><a href="#resume-open-source">Open Source</a></li>
                        @endif
                        @if(! empty($research['identity']))
                            <li><a href="#resume-publications">Publications</a></li>
                        @endif
                        @if(! empty($education))
                            <li><a href="#resume-education">Education</a></li>
                        @endif
                        @if(! empty($certifications))
                            <li><a href="#credentials">Certifications</a></li>
                        @endif
                        @if(! empty($stack))
                            <li><a href="#stack">Technical Stack</a></li>
                        @endif
                    </ul>
                </nav>

                <section class="resume-aside-block">
                    <h2 class="resume-aside-title">Details</h2>
                    <ul class="resume-aside-list">
                        <li>{{ $person['location'] }}{{ ! empty($resume['postal']) ? ' '.$resume['postal'] : '' }}</li>
                        @if(! empty($resume['phone_on_web']) && ! empty($resume['phone']))
                            <li><a href="tel:+1{{ preg_replace('/\D+/', '', $resume['phone']) }}">{{ $resume['phone'] }}</a></li>
                        @else
                            <li class="text-neutral-500">Phone on the PDF</li>
                        @endif
                        <li><a href="mailto:{{ $person['email'] }}">{{ $person['email'] }}</a></li>
                    </ul>
                </section>

                <section class="resume-aside-block">
                    <h2 class="resume-aside-title">Links</h2>
                    <ul class="resume-aside-list resume-aside-links">
                        @if(! empty($linkedin))
                            <li>
                                <a href="{{ $linkedin['url'] }}" target="_blank" rel="me noopener noreferrer">
                                    <span class="resume-link-label">LinkedIn</span>
                                    <span class="resume-link-url">{{ $linkedin['url'] }}</span>
                                </a>
                            </li>
                        @endif
                        @if(! empty($github))
                            <li>
                                <a href="{{ $github['url'] }}" target="_blank" rel="me noopener noreferrer">
                                    <span class="resume-link-label">GitHub</span>
                                    <span class="resume-link-url">{{ $github['url'] }}</span>
                                </a>
                            </li>
                        @endif
                        <li>
                            <a href="https://karlhill.com" target="_blank" rel="noopener noreferrer">
                                <span class="resume-link-label">Website</span>
                                <span class="resume-link-url">https://karlhill.com</span>
                            </a>
                        </li>
                    </ul>
                </section>

                @if(! empty($resume['expertise']))
                    <section class="resume-aside-block">
                        <h2 class="resume-aside-title">Leadership &amp; Engineering Scope</h2>
                        <ul class="resume-expertise">
                            @foreach($resume['expertise'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if(! empty($stack))
                    <section class="resume-aside-block resume-aside-stack" id="stack">
                        <h2 class="resume-aside-title">Technical Expertise</h2>
                        @foreach($stack as $group)
                            <p class="resume-stack-line">
                                <span class="resume-stack-label">{{ $group['category'] }}:</span>
                                {{ implode(', ', $group['skills']) }}@if(! empty($group['note'])) — {{ $group['note'] }}@endif
                            </p>
                        @endforeach
                    </section>
                @endif
            </aside>

            <div class="resume-main">
                {{-- Print-only masthead. On screen the page hero above is the one
                     heading (a second display-size name directly under "Resume" read
                     as two stacked heroes, and gave the page two h1s); print hides
                     the hero and shows this instead. Contact details live once, in
                     the Details/Links sidebar, which print floats beside this. --}}
                <header class="resume-header" aria-hidden="true">
                    <p class="resume-name font-display text-4xl sm:text-5xl tracking-wide text-white">{{ $person['name'] }}</p>
                    <p class="resume-tagline mt-3 font-mono text-sm sm:text-base text-accent uppercase tracking-widest">
                        {{ $resume['tagline'] }}
                    </p>
                </header>

                <section class="resume-section" aria-labelledby="resume-summary" data-reveal>
                    <h2 id="resume-summary" class="resume-section-title eyebrow">Summary</h2>
                    <p class="resume-summary text-neutral-300 text-lg leading-relaxed">{{ $experience['intro'] }}</p>
                </section>

                @if(! empty($resume['impact']))
                    <section class="resume-section" aria-labelledby="resume-impact" data-reveal>
                        <h2 id="resume-impact" class="resume-section-title eyebrow">Selected Leadership Impact</h2>
                        <ul class="resume-bullets resume-impact list-disc pl-5 text-neutral-300">
                            @foreach($resume['impact'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                <section class="resume-section" aria-labelledby="resume-experience" data-reveal>
                    <h2 id="resume-experience" class="resume-section-title eyebrow">Professional Experience</h2>

                    <div class="resume-roles">
                        <div class="resume-role resume-role--current">
                            @if(! empty($experience['current']['label']))
                                <p class="resume-role-kicker">{{ $experience['current']['label'] }}</p>
                            @endif
                            <h3 class="resume-role-title">
                                {{ $experience['current']['title'] }}, {{ $experience['current']['company'] }}, {{ $experience['current']['location'] }}
                            </h3>
                            <p class="resume-meta resume-dates">
                                {{ $experience['current']['period'] }}
                            </p>
                            <x-site.role-highlights class="resume-bullets" :items="$experience['current']['highlights']" plain />
                        </div>

                        @foreach($experience['roles'] as $role)
                            <div @class(['resume-role', 'resume-role--anchor' => $loop->first, 'resume-role--compact' => ! $loop->first])>
                                <h3 class="resume-role-title">
                                    {{ $role['title'] }}, {{ $role['company'] }}, {{ $role['location'] }}
                                </h3>
                                <p class="resume-meta resume-dates">
                                    {{ $role['period'] }}
                                </p>
                                <x-site.role-highlights class="resume-bullets" :items="$role['highlights']" plain />
                                @if($supportingProjects->has($role['portfolio_group'] ?? ''))
                                    <nav class="mt-3 print:hidden" aria-label="{{ $role['company'] }} case studies">
                                        <p class="portfolio-caption">Additional case studies</p>
                                        <ul class="flex flex-wrap gap-x-4 gap-y-1">
                                            @foreach($supportingProjects[$role['portfolio_group']] as $project)
                                                <li><a href="/work/{{ $project['slug'] }}" class="portfolio-text-link text-sm">{{ $project['title'] }}</a></li>
                                            @endforeach
                                        </ul>
                                    </nav>
                                @endif
                            </div>
                        @endforeach

                        @if(! empty($experience['earlier']['highlights']))
                            <div class="resume-role resume-role--compact">
                                <h3 class="resume-role-title">
                                    {{ $experience['earlier']['title'] }}
                                </h3>
                                <p class="resume-meta">
                                    {{ $experience['earlier']['company'] }}
                                </p>
                                <p class="resume-meta resume-dates">
                                    {{ $experience['earlier']['period'] }}
                                </p>
                                <x-site.role-highlights class="resume-bullets" :items="$experience['earlier']['highlights']" plain />
                            </div>
                        @endif
                    </div>
                </section>

                @foreach(['products' => ['resume-products', 'Independent Products'], 'tooling' => ['resume-open-source', 'Open Source']] as $key => [$sectionId, $sectionTitle])
                    @continue(empty($resume[$key]))
                    <section class="resume-section" aria-labelledby="{{ $sectionId }}" data-reveal>
                        <h2 id="{{ $sectionId }}" class="resume-section-title eyebrow">{{ $sectionTitle }}</h2>
                        <ul class="resume-bullets list-disc pl-5 text-neutral-300">
                            @foreach($resume[$key] as $item)
                                <li>
                                    @if(! empty($item['url']))
                                        <a href="{{ $item['url'] }}" target="_blank" rel="me noopener noreferrer" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">{{ $item['name'] }}</a>
                                    @else
                                        <strong class="text-neutral-200 font-medium">{{ $item['name'] }}</strong>
                                    @endif
                                    — {{ $item['note'] }}
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endforeach

                @php
                    $research = $research ?? config('site.research', []);
                @endphp
                @if(! empty($research['identity']))
                    <section class="resume-section" aria-labelledby="resume-publications" data-reveal>
                        <h2 id="resume-publications" class="resume-section-title eyebrow">Publications</h2>
                        <p class="font-mono text-xs text-accent uppercase tracking-widest mb-3">{{ $research['identity_label'] ?? 'Peer-reviewed research' }}</p>
                        <p class="text-neutral-200 font-medium leading-snug max-w-3xl mb-3">
                            <a href="{{ $research['path'] ?? '/research/global-flood-mapping' }}" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">{{ $research['title'] }}</a>
                        </p>
                        <p class="text-neutral-300 leading-relaxed max-w-3xl">{{ $research['identity'] }}</p>
                        @if(! empty($research['credit']))
                            <p class="mt-3 text-neutral-500 text-sm leading-relaxed">
                                {{ $research['credit_label'] ?? 'CRediT' }}: {{ $research['credit'] }}
                            </p>
                        @endif
                        @if(! empty($research['doi']))
                            <p class="mt-3">
                                <a href="{{ $research['doi'] }}" target="_blank" rel="noopener noreferrer" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">{{ $research['doi_id'] ?? $research['doi'] }}</a>
                            </p>
                        @endif
                    </section>
                @endif

                @if(! empty($education))
                    <section class="resume-section" aria-labelledby="resume-education" data-reveal>
                        <h2 id="resume-education" class="resume-section-title eyebrow">Education</h2>
                        <ul class="resume-education">
                            @foreach($education as $item)
                                <li class="text-neutral-300">
                                    <strong class="text-neutral-200 font-medium">{{ $item['degree'] }}</strong> — {{ $item['school'] }}
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if(! empty($certifications))
                    <section id="credentials" class="resume-section" aria-labelledby="resume-certifications" data-reveal>
                        <h2 id="resume-certifications" class="resume-section-title eyebrow">Certifications</h2>
                        <ul class="resume-certs list-disc pl-5">
                            @foreach($certifications as $cert)
                                <li class="text-neutral-300">
                                    @if(! empty($cert['url']))
                                        <a href="{{ $cert['url'] }}" target="_blank" rel="noopener noreferrer" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">{{ $cert['name'] }}</a>
                                    @else
                                        {{ $cert['name'] }}
                                    @endif
                                    {{ ! empty($cert['issuer']) ? ', '.$cert['issuer'] : '' }}{{ ! empty($cert['status']) ? ' ('.strtolower($cert['status']).')' : '' }}
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            </div>
        </div>
    </article>

    {{-- Reader tool after the document: hidden unless Chrome's Prompt API can run. --}}
    <div class="reader-tools site-section site-section--soft border-t border-neutral-800/50">
        <div class="site-shell">
            <x-site.on-device-ask
                id="resume-ask"
                source="[data-ask-source]"
                :context="$askBrief"
                :prompts="$askPrompts"
                heading="Quick answers · experimental"
                label="Ask"
                placeholder="What is the current role?"
            />
        </div>
    </div>
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
