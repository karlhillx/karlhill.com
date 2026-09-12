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

        <p class="text-neutral-300 text-base sm:text-lg leading-relaxed max-w-2xl">
            Aerospace &amp; national security software. Engineering systems. Technical leadership.
        </p>

        {{-- The PDF is this page's purpose, so it takes the fill; booking is the secondary. --}}
        <div class="flex flex-wrap items-center gap-x-4 gap-y-3 mt-6 sm:mt-8">
            @if(! empty($pdf))
                <x-site.button variant="primary" :href="$pdf"
                    download="Karl-Hill-Resume.pdf"
                    data-analytics-event="resume_downloaded"
                    data-analytics-location="resume-hero">
                    Download PDF
                </x-site.button>
            @endif
            @if(filled($bookingUrl))
                <x-site.button variant="secondary" href="/now#book"
                    data-analytics-event="booking_cta_clicked"
                    data-analytics-location="resume-hero">
                    {{ $bookingLabel }}
                </x-site.button>
            @endif
            @if(! empty($linkedin))
                <x-site.button variant="link" :href="$linkedin['url']" target="_blank" rel="me noopener noreferrer">
                    LinkedIn
                </x-site.button>
            @endif
        </div>
    </x-site.page-hero>

    <article class="resume-doc site-section site-section--soft border-t border-neutral-800/50" aria-label="Resume">
        <div class="site-shell resume-shell">
            {{-- Sidebar first in DOM so print float:right sits beside the main column like the classic PDF. --}}
            <aside class="resume-aside" aria-label="Contact and expertise">
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
                        <h2 class="resume-aside-title">Areas of Expertise</h2>
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
                                {{ implode(', ', $group['skills']) }}
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
                    <h2 id="resume-summary" class="resume-section-title font-mono text-accent text-xs tracking-widest uppercase">Summary</h2>
                    <p class="resume-summary text-neutral-300 text-lg leading-relaxed">{{ $experience['intro'] }}</p>
                </section>

                @if(! empty($resume['impact']))
                    <section class="resume-section" aria-labelledby="resume-impact" data-reveal>
                        <h2 id="resume-impact" class="resume-section-title font-mono text-accent text-xs tracking-widest uppercase">Selected Leadership Impact</h2>
                        <ul class="resume-bullets resume-impact list-disc pl-5 text-neutral-300">
                            @foreach($resume['impact'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                <section class="resume-section" aria-labelledby="resume-experience" data-reveal>
                    <h2 id="resume-experience" class="resume-section-title font-mono text-accent text-xs tracking-widest uppercase">Professional Experience</h2>

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

                @if(! empty($resume['tooling']))
                    <section class="resume-section" aria-labelledby="resume-tooling" data-reveal>
                        <h2 id="resume-tooling" class="resume-section-title font-mono text-accent text-xs tracking-widest uppercase">Developer Tooling</h2>
                        <ul class="resume-bullets list-disc pl-5 text-neutral-300">
                            @foreach($resume['tooling'] as $item)
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
                @endif

                @if(! empty($education))
                    <section class="resume-section" aria-labelledby="resume-education" data-reveal>
                        <h2 id="resume-education" class="resume-section-title font-mono text-accent text-xs tracking-widest uppercase">Education</h2>
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
                        <h2 id="resume-certifications" class="resume-section-title font-mono text-accent text-xs tracking-widest uppercase">Certifications</h2>
                        <ul class="resume-certs list-disc pl-5">
                            @foreach($certifications as $cert)
                                <li class="text-neutral-300">
                                    {{ $cert['name'] }}{{ ! empty($cert['issuer']) ? ', '.$cert['issuer'] : '' }}{{ ! empty($cert['status']) ? ' ('.strtolower($cert['status']).')' : '' }}
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            </div>
        </div>
    </article>
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
