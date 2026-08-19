@extends('layouts.site', ['meta' => $meta])

@section('content')
    <x-site.page-hero eyebrow="Curriculum vitae" :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Resume'],
    ]">
        <x-slot:title>Resume</x-slot:title>

        <p class="text-neutral-300 text-base sm:text-lg leading-relaxed max-w-2xl">
            Canonical HTML CV from the same source as <a href="/about" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">About</a>.
            Download the generated <strong class="text-neutral-200 font-semibold">2-page PDF</strong> for applications (classic navy layout, ATS-readable text).
        </p>

        <div class="flex flex-wrap items-center gap-x-4 gap-y-3 mt-8 sm:mt-10">
            @if(! empty($pdf))
                <a href="{{ $pdf }}"
                   download="Karl-Hill-Resume.pdf"
                   class="btn-sweep inline-flex items-center justify-center min-h-11 gap-2 font-mono text-xs text-accent border border-accent/40 px-5 py-3 uppercase tracking-widest transition-colors">
                    Download PDF
                </a>
            @endif
            @if(! empty($linkedin))
                <a href="{{ $linkedin['url'] }}" target="_blank" rel="me noopener noreferrer"
                   class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-500 hover:text-accent uppercase tracking-widest transition-colors">
                    LinkedIn
                </a>
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
                        <li><a href="tel:+1{{ preg_replace('/\D+/', '', $resume['phone']) }}">{{ $resume['phone'] }}</a></li>
                        <li><a href="mailto:{{ $person['email'] }}">{{ $person['email'] }}</a></li>
                    </ul>
                </section>

                <section class="resume-aside-block">
                    <h2 class="resume-aside-title">Links</h2>
                    <ul class="resume-aside-list resume-aside-links">
                        @if(! empty($linkedin))
                            <li><a href="{{ $linkedin['url'] }}" target="_blank" rel="me noopener noreferrer">{{ $linkedin['url'] }}</a></li>
                        @endif
                        @if(! empty($github))
                            <li><a href="{{ $github['url'] }}" target="_blank" rel="me noopener noreferrer">{{ $github['url'] }}</a></li>
                        @endif
                        <li><a href="https://karlhill.com" target="_blank" rel="noopener noreferrer">https://karlhill.com</a></li>
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
                    <section class="resume-aside-block resume-aside-stack">
                        <h2 class="resume-aside-title">Tech Stack</h2>
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
                <header class="resume-header" data-reveal>
                    <h1 class="resume-name font-display text-4xl sm:text-5xl tracking-wide text-white">{{ $person['name'] }}</h1>
                    <p class="resume-tagline mt-3 font-mono text-sm text-accent uppercase tracking-widest">
                        {{ $resume['tagline'] }}
                    </p>
                    {{-- Screen-only contact strip; print uses the navy sidebar. --}}
                    <p class="resume-contact resume-contact--screen mt-4 font-mono text-xs text-neutral-500">
                        <span>{{ $person['location'] }}{{ ! empty($resume['postal']) ? ' '.$resume['postal'] : '' }}</span>
                        <span aria-hidden="true"> · </span>
                        <a href="tel:+1{{ preg_replace('/\D+/', '', $resume['phone']) }}" class="hover:text-accent">{{ $resume['phone'] }}</a>
                        <span aria-hidden="true"> · </span>
                        <a href="mailto:{{ $person['email'] }}" class="hover:text-accent">{{ $person['email'] }}</a>
                        <span aria-hidden="true"> · </span>
                        <a href="https://karlhill.com" target="_blank" rel="noopener noreferrer" class="hover:text-accent underline underline-offset-2">karlhill.com</a>
                    </p>
                </header>

                <section class="resume-section" aria-labelledby="resume-summary" data-reveal>
                    <h2 id="resume-summary" class="resume-section-title font-mono text-accent text-xs tracking-widest uppercase">Summary</h2>
                    <p class="resume-summary text-neutral-300 text-base leading-relaxed">{{ $experience['intro'] }}</p>
                </section>

                @if(! empty($resume['impact']))
                    <section class="resume-section" aria-labelledby="resume-impact" data-reveal>
                        <h2 id="resume-impact" class="resume-section-title font-mono text-accent text-xs tracking-widest uppercase">Selected Leadership Impact</h2>
                        <ul class="resume-bullets resume-impact list-disc pl-5 text-neutral-300 text-sm leading-relaxed">
                            @foreach($resume['impact'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                <section class="resume-section" aria-labelledby="resume-experience" data-reveal>
                    <h2 id="resume-experience" class="resume-section-title font-mono text-accent text-xs tracking-widest uppercase">Professional Experience</h2>

                    <div class="resume-roles">
                        <div class="resume-role">
                            <h3 class="resume-role-title text-white text-lg font-semibold">
                                {{ $experience['current']['title'] }}, {{ $experience['current']['company'] }}, {{ $experience['current']['location'] }}
                            </h3>
                            <p class="resume-meta resume-dates font-mono text-[11px] text-neutral-500 uppercase tracking-wider">
                                {{ $experience['current']['period'] }}
                            </p>
                            <x-site.role-highlights class="resume-bullets" :items="$experience['current']['highlights']" plain />
                        </div>

                        @foreach($experience['roles'] as $role)
                            <div class="resume-role">
                                <h3 class="resume-role-title text-white text-lg font-semibold">
                                    {{ $role['title'] }}, {{ $role['company'] }}, {{ $role['location'] }}
                                </h3>
                                <p class="resume-meta resume-dates font-mono text-[11px] text-neutral-500 uppercase tracking-wider">
                                    {{ $role['period'] }}
                                </p>
                                <x-site.role-highlights class="resume-bullets" :items="$role['highlights']" plain />
                            </div>
                        @endforeach

                        @if(! empty($experience['earlier']['highlights']))
                            <div class="resume-role">
                                <h3 class="resume-role-title text-white text-lg font-semibold">
                                    {{ $experience['earlier']['title'] }}
                                </h3>
                                <p class="resume-meta text-neutral-300 text-sm">
                                    {{ $experience['earlier']['company'] }}
                                </p>
                                <p class="resume-meta resume-dates font-mono text-[11px] text-neutral-500 uppercase tracking-wider">
                                    {{ $experience['earlier']['period'] }}
                                </p>
                                <x-site.role-highlights class="resume-bullets" :items="$experience['earlier']['highlights']" plain />
                            </div>
                        @endif
                    </div>
                </section>

                @if(! empty($education))
                    <section class="resume-section" aria-labelledby="resume-education" data-reveal>
                        <h2 id="resume-education" class="resume-section-title font-mono text-accent text-xs tracking-widest uppercase">Education</h2>
                        <ul class="resume-education">
                            @foreach($education as $item)
                                <li class="text-sm text-neutral-300">
                                    {{ $item['degree'] }}, {{ $item['school'] }}
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if(! empty($certifications))
                    <section class="resume-section" aria-labelledby="resume-certifications" data-reveal>
                        <h2 id="resume-certifications" class="resume-section-title font-mono text-accent text-xs tracking-widest uppercase">Certifications</h2>
                        <ul class="resume-certs list-disc pl-5">
                            @foreach($certifications as $cert)
                                <li class="text-sm text-neutral-300">
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
