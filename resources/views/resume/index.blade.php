@extends('layouts.site', ['meta' => $meta])

@section('content')
    @php
        $strip = static fn (string $html): string => trim(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    @endphp

    <x-site.page-hero eyebrow="Curriculum vitae" :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Resume'],
    ]">
        <x-slot:title>Resume</x-slot:title>

        <p class="text-neutral-300 text-base sm:text-lg leading-relaxed max-w-2xl">
            Generated from the same source as <a href="/about" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">About</a>
            and <a href="/now" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">Now</a>
            — so this page stays aligned with the live career narrative. Prefer it when the PDF disagrees.
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
                    Download static PDF
                </a>
            @endif
            @if(! empty($linkedin))
                <a href="{{ $linkedin['url'] }}" target="_blank" rel="me noopener noreferrer"
                   class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-500 hover:text-accent uppercase tracking-widest transition-colors">
                    LinkedIn
                </a>
            @endif
            <a href="#contact"
               class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-500 hover:text-accent uppercase tracking-widest transition-colors">
                Contact
            </a>
        </div>
    </x-site.page-hero>

    <article class="resume-doc site-section site-section--soft border-t border-neutral-800/50" aria-label="Resume">
        <div class="site-shell max-w-3xl">
            <header class="mb-10 sm:mb-12" data-reveal>
                <h2 class="font-display text-4xl sm:text-5xl tracking-wide text-white">{{ $person['name'] }}</h2>
                <p class="mt-3 font-mono text-sm text-accent uppercase tracking-widest">
                    {{ $person['job_title'] }} · {{ $person['location'] }}
                </p>
                <p class="mt-4 text-neutral-400 text-base leading-relaxed">
                    {{ $availability }}
                </p>
                <p class="mt-3 font-mono text-xs text-neutral-500">
                    <a href="mailto:{{ $person['email'] }}" class="hover:text-accent transition-colors">{{ $person['email'] }}</a>
                    ·
                    <a href="{{ url('/') }}" class="hover:text-accent transition-colors">karlhill.com</a>
                    @if(! empty($linkedin))
                        ·
                        <a href="{{ $linkedin['url'] }}" target="_blank" rel="me noopener noreferrer" class="hover:text-accent transition-colors">LinkedIn</a>
                    @endif
                </p>
            </header>

            <section class="mb-10" aria-labelledby="resume-summary" data-reveal>
                <h3 id="resume-summary" class="font-mono text-accent text-xs tracking-widest uppercase mb-4">Summary</h3>
                <p class="text-neutral-300 text-base leading-relaxed">{{ $experience['intro'] }}</p>
            </section>

            <section class="mb-10" aria-labelledby="resume-experience" data-reveal>
                <h3 id="resume-experience" class="font-mono text-accent text-xs tracking-widest uppercase mb-6">Experience</h3>

                <div class="space-y-8">
                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1 sm:gap-4">
                            <h4 class="text-white text-lg font-semibold">
                                {{ $experience['current']['title'] }}
                                <span class="text-neutral-500 font-normal">· {{ $experience['current']['company'] }}</span>
                            </h4>
                            <p class="font-mono text-[11px] text-neutral-500 uppercase tracking-wider shrink-0">
                                {{ $experience['current']['period'] }}
                            </p>
                        </div>
                        <p class="mt-1 font-mono text-[11px] text-neutral-600 uppercase tracking-wider">
                            {{ $experience['current']['location'] }}
                        </p>
                        <ul class="mt-4 space-y-2 text-neutral-400 text-sm leading-relaxed list-disc pl-5">
                            @foreach($experience['current']['highlights'] as $highlight)
                                <li>{{ $strip($highlight) }}</li>
                            @endforeach
                        </ul>
                    </div>

                    @foreach($experience['roles'] as $role)
                        <div>
                            <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1 sm:gap-4">
                                <h4 class="text-white text-lg font-semibold">
                                    {{ $role['title'] }}
                                    <span class="text-neutral-500 font-normal">· {{ $role['company'] }}</span>
                                </h4>
                                <p class="font-mono text-[11px] text-neutral-500 uppercase tracking-wider shrink-0">
                                    {{ $role['period'] }}
                                </p>
                            </div>
                            <p class="mt-1 font-mono text-[11px] text-neutral-600 uppercase tracking-wider">
                                {{ $role['location'] }}
                            </p>
                            <ul class="mt-4 space-y-2 text-neutral-400 text-sm leading-relaxed list-disc pl-5">
                                @foreach($role['highlights'] as $highlight)
                                    <li>{{ $strip($highlight) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach

                    @if(! empty($experience['earlier']['entries']))
                        <div>
                            <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1 sm:gap-4">
                                <h4 class="text-white text-lg font-semibold">{{ $experience['earlier']['title'] }}</h4>
                                <p class="font-mono text-[11px] text-neutral-500 uppercase tracking-wider shrink-0">
                                    {{ $experience['earlier']['period'] }}
                                </p>
                            </div>
                            <ul class="mt-4 space-y-4">
                                @foreach($experience['earlier']['entries'] as $entry)
                                    <li>
                                        <p class="text-neutral-200 font-medium">{{ $entry['company'] }}</p>
                                        <p class="font-mono text-[11px] text-neutral-500 uppercase tracking-wider mt-0.5">{{ $entry['meta'] }}</p>
                                        <p class="mt-2 text-neutral-400 text-sm leading-relaxed">{{ $entry['detail'] }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </section>

            @if(! empty($education))
                <section aria-labelledby="resume-education" data-reveal>
                    <h3 id="resume-education" class="font-mono text-accent text-xs tracking-widest uppercase mb-4">Education</h3>
                    <ul class="space-y-3">
                        @foreach($education as $item)
                            <li class="text-sm">
                                <span class="text-neutral-200">{{ $item['degree'] }}</span>
                                <span class="text-neutral-500"> — {{ $item['school'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>
    </article>

    <x-site.contact-section
        id-prefix="resume-contact"
        eyebrow="Next step"
        headline="Let's talk"
        body="Open to Engineering Manager and Staff+ leadership conversations. Prefer Calendly for a short intro, or send a note below."
    />
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
