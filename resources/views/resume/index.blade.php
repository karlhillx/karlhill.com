@extends('layouts.site', ['meta' => $meta])

@section('content')
    <x-site.page-hero eyebrow="Curriculum vitae" :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Resume'],
    ]">
        <x-slot:title>Resume</x-slot:title>

        <p class="text-neutral-300 text-base sm:text-lg leading-relaxed max-w-2xl">
            Canonical HTML CV from the same source as <a href="/about" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">About</a>.
            Prefer this page when the static PDF disagrees. Use <strong class="text-neutral-200 font-semibold">Print / Save PDF</strong> for a clean letter-size export, or download the ATS PDF.
        </p>
        <p class="mt-4 font-mono text-[11px] text-neutral-500 uppercase tracking-widest max-w-2xl">
            Print tip: turn off <span class="text-neutral-400">Headers and footers</span> in the dialog for a clean CV.
        </p>

        <div class="flex flex-wrap items-center gap-x-4 gap-y-3 mt-8 sm:mt-10">
            <button type="button"
                    onclick="window.print()"
                    class="btn-sweep inline-flex items-center justify-center min-h-11 gap-2 font-mono text-xs text-accent border border-accent/40 px-5 py-3 uppercase tracking-widest transition-colors">
                Print / Save PDF
            </button>
            @if(! empty($pdf))
                <a href="{{ $pdf }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                    Download ATS PDF
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
        <div class="site-shell max-w-3xl">
            <header class="resume-header mb-10 sm:mb-12" data-reveal>
                <h2 class="resume-name font-display text-4xl sm:text-5xl tracking-wide text-white">{{ $person['name'] }}</h2>
                <p class="resume-tagline mt-3 font-mono text-sm text-accent uppercase tracking-widest">
                    {{ $person['tagline'] }}
                </p>
                <p class="resume-contact mt-3 font-mono text-xs text-neutral-500">
                    {{ $person['location'] }}
                    ·
                    <a href="mailto:{{ $person['email'] }}" class="hover:text-accent transition-colors">{{ $person['email'] }}</a>
                    ·
                    <a href="{{ url('/') }}" class="hover:text-accent transition-colors">karlhill.com</a>
                    @if(! empty($linkedin))
                        ·
                        <a href="{{ $linkedin['url'] }}" target="_blank" rel="me noopener noreferrer" class="hover:text-accent transition-colors">LinkedIn</a>
                    @endif
                    @if(! empty($github))
                        ·
                        <a href="{{ $github['url'] }}" target="_blank" rel="me noopener noreferrer" class="hover:text-accent transition-colors">GitHub</a>
                    @endif
                </p>
            </header>

            <section class="resume-section mb-10" aria-labelledby="resume-summary" data-reveal>
                <h3 id="resume-summary" class="resume-section-title font-mono text-accent text-xs tracking-widest uppercase mb-4">Summary</h3>
                <p class="resume-summary text-neutral-300 text-base leading-relaxed">{{ $experience['intro'] }}</p>
            </section>

            <section class="resume-section mb-10" aria-labelledby="resume-experience" data-reveal>
                <h3 id="resume-experience" class="resume-section-title font-mono text-accent text-xs tracking-widest uppercase mb-6">Professional Experience</h3>

                <div class="resume-roles space-y-8">
                    <div class="resume-role">
                        <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1 sm:gap-4">
                            <h4 class="resume-role-title text-white text-lg font-semibold">
                                {{ $experience['current']['title'] }}
                                <span class="text-neutral-500 font-normal">· {{ $experience['current']['company'] }}</span>
                            </h4>
                            <p class="resume-meta font-mono text-[11px] text-neutral-500 uppercase tracking-wider shrink-0">
                                {{ $experience['current']['period'] }}
                            </p>
                        </div>
                        <p class="resume-meta mt-1 font-mono text-[11px] text-neutral-600 uppercase tracking-wider">
                            {{ $experience['current']['location'] }}
                        </p>
                        <x-site.role-highlights class="resume-bullets mt-4" :items="$experience['current']['highlights']" plain />
                    </div>

                    @foreach($experience['roles'] as $role)
                        <div class="resume-role">
                            <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1 sm:gap-4">
                                <h4 class="resume-role-title text-white text-lg font-semibold">
                                    {{ $role['title'] }}
                                    <span class="text-neutral-500 font-normal">· {{ $role['company'] }}</span>
                                </h4>
                                <p class="resume-meta font-mono text-[11px] text-neutral-500 uppercase tracking-wider shrink-0">
                                    {{ $role['period'] }}
                                </p>
                            </div>
                            <p class="resume-meta mt-1 font-mono text-[11px] text-neutral-600 uppercase tracking-wider">
                                {{ $role['location'] }}
                            </p>
                            <x-site.role-highlights class="resume-bullets mt-4" :items="$role['highlights']" plain />
                        </div>
                    @endforeach

                    @if(! empty($experience['earlier']['entries']))
                        <div class="resume-role">
                            <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1 sm:gap-4">
                                <h4 class="resume-role-title text-white text-lg font-semibold">{{ $experience['earlier']['title'] }}</h4>
                                <p class="resume-meta font-mono text-[11px] text-neutral-500 uppercase tracking-wider shrink-0">
                                    {{ $experience['earlier']['period'] }}
                                </p>
                            </div>
                            <ul class="mt-4 space-y-4">
                                @foreach($experience['earlier']['entries'] as $entry)
                                    <li>
                                        <p class="text-neutral-200 font-medium">{{ $entry['company'] }}</p>
                                        <p class="resume-meta font-mono text-[11px] text-neutral-500 uppercase tracking-wider mt-0.5">{{ $entry['meta'] }}</p>
                                        <p class="resume-prose mt-2 text-neutral-400 text-sm leading-relaxed">
                                            {{ \App\Support\PlainText::fromHtml($entry['detail']) }}
                                        </p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </section>

            @if(! empty($education))
                <section class="resume-section mb-10" aria-labelledby="resume-education" data-reveal>
                    <h3 id="resume-education" class="resume-section-title font-mono text-accent text-xs tracking-widest uppercase mb-4">Education</h3>
                    <ul class="resume-education space-y-3">
                        @foreach($education as $item)
                            <li class="text-sm">
                                <span class="text-neutral-200">{{ $item['degree'] }}</span>
                                <span class="text-neutral-500"> — {{ $item['school'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if(! empty($certifications))
                <section class="resume-section mb-10" aria-labelledby="resume-certifications" data-reveal>
                    <h3 id="resume-certifications" class="resume-section-title font-mono text-accent text-xs tracking-widest uppercase mb-4">Certifications</h3>
                    <ul class="resume-certs space-y-2">
                        @foreach($certifications as $cert)
                            <li class="text-sm text-neutral-300">
                                {{ $cert['name'] }}{{ ! empty($cert['issuer']) ? ', '.$cert['issuer'] : '' }}
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if(! empty($stack))
                <section class="resume-section" aria-labelledby="resume-stack" data-reveal>
                    <h3 id="resume-stack" class="resume-section-title font-mono text-accent text-xs tracking-widest uppercase mb-4">Technical Leadership, Platforms &amp; Engineering Stack</h3>
                    <div class="space-y-2">
                        @foreach($stack as $group)
                            <p class="resume-stack-line text-sm text-neutral-300 leading-relaxed">
                                <span class="resume-stack-label text-neutral-200">{{ $group['category'] }}:</span>
                                {{ implode(', ', $group['skills']) }}
                            </p>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </article>
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
