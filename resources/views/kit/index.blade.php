@extends('layouts.site', ['meta' => $meta])

@section('content')
    <div class="kit-doc">
    {{-- Print-only masthead: name + reachability first (screen uses the page hero). --}}
    <header class="kit-print-masthead" aria-hidden="true">
        <p class="kit-print-masthead__name">{{ $person['name'] }}</p>
        <p class="kit-print-masthead__role">{{ $person['job_title'] }} · {{ $person['employer'] }} · {{ $person['location'] }}</p>
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

        <div class="kit-screen-actions flex flex-wrap items-center gap-x-4 gap-y-3 mt-8 sm:mt-10">
            @if($pdfHref)
                <a href="{{ $pdfHref }}"
                   download
                   class="btn-sweep inline-flex items-center justify-center min-h-11 gap-2 font-mono text-xs text-accent border border-accent/40 px-5 py-3 uppercase tracking-widest transition-colors">
                    Download resume PDF
                </a>
            @endif
            @if(filled($bookingUrl))
                <a href="{{ url('/now#book') }}"
                   class="btn-sweep magnetic-btn inline-flex items-center justify-center min-h-11 gap-2 font-mono text-xs text-bg bg-accent border border-accent px-5 py-3 uppercase tracking-widest transition-colors">
                    {{ $bookingLabel }}
                </a>
            @endif
            <button type="button"
                    data-print
                    title="Print or save as PDF"
                    class="kit-print-btn cursor-pointer inline-flex items-center justify-center min-h-11 font-mono text-xs text-neutral-300 border border-neutral-700 hover:border-accent hover:text-accent px-4 uppercase tracking-widest transition-colors">
                Print kit
            </button>
            <a href="#contact"
               class="cursor-pointer inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                Contact
            </a>
        </div>
    </x-site.page-hero>

    <section class="site-section site-section--soft border-t border-neutral-800/50" aria-labelledby="kit-glance-heading">
        <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
            <h2 id="kit-glance-heading" class="kit-section-label font-mono text-accent text-xs tracking-widest uppercase pt-1">At a glance</h2>
            <div class="max-w-2xl">
                <p class="kit-bio text-neutral-200 text-lg leading-relaxed">{{ $kit['bio'] }}</p>
                <dl class="kit-facts mt-8 grid sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="font-mono text-[10px] text-neutral-400 uppercase tracking-widest mb-1">Name</dt>
                        <dd class="text-neutral-300">{{ $person['name'] }}</dd>
                    </div>
                    <div>
                        <dt class="font-mono text-[10px] text-neutral-400 uppercase tracking-widest mb-1">Title</dt>
                        <dd class="text-neutral-300">{{ $person['job_title'] }} · {{ $person['employer'] }}</dd>
                    </div>
                    <div>
                        <dt class="font-mono text-[10px] text-neutral-400 uppercase tracking-widest mb-1">Location</dt>
                        <dd class="text-neutral-300">{{ $person['location'] }}</dd>
                    </div>
                    <div>
                        <dt class="font-mono text-[10px] text-neutral-400 uppercase tracking-widest mb-1">Open to</dt>
                        <dd class="text-neutral-300">{{ $person['availability'] }}</dd>
                    </div>
                </dl>
                @if(! empty($kit['highlights']))
                    <ul class="kit-highlights mt-8 space-y-2 text-neutral-400 text-sm leading-relaxed list-disc pl-5">
                        @foreach($kit['highlights'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </section>

    <section class="site-section border-t border-neutral-800/50" aria-labelledby="kit-links-heading">
        <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
            <h2 id="kit-links-heading" class="kit-section-label font-mono text-accent text-xs tracking-widest uppercase pt-1">Links</h2>
            <ul class="kit-links max-w-2xl divide-y divide-neutral-800/80">
                @foreach($links as $link)
                    <li @class(['kit-link-email' => $link['email'], 'py-1'])>
                        <a href="{{ $link['href'] }}"
                           @if($link['external']) target="_blank" rel="me noopener noreferrer" @endif
                           @if($link['download']) download @endif
                           class="group flex flex-wrap items-center justify-between gap-2 min-h-11 py-3">
                            <span class="kit-link-label text-neutral-200 group-hover:text-accent transition-colors">
                                {{ $link['label'] }}
                                @if($link['external'])
                                    <span class="sr-only"> (opens in a new tab)</span>
                                @endif
                            </span>
                            @if($link['meta'] !== '')
                                <span class="kit-link-meta font-mono text-[10px] text-neutral-500 uppercase tracking-widest">{{ $link['meta'] }}</span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
    </div>
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
