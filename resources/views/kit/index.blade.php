@extends('layouts.site', ['meta' => $meta])

@php
    $origin = rtrim((string) config('app.url'), '/');
    $pdfHref = filled($pdf) ? (str_starts_with($pdf, 'http') ? $pdf : $origin.$pdf) : null;
@endphp

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
                    class="kit-print-btn cursor-pointer inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                Print kit
            </button>
            <a href="/#contact"
               class="cursor-pointer inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                Contact
            </a>
        </div>
    </x-site.page-hero>

    <section class="site-section site-section--soft border-t border-neutral-800/50" aria-label="Bio and highlights">
        <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
            <p class="kit-section-label font-mono text-accent text-xs tracking-widest uppercase pt-1">At a glance</p>
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

    <section class="site-section border-t border-neutral-800/50" aria-label="Canonical links">
        <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
            <p class="kit-section-label font-mono text-accent text-xs tracking-widest uppercase pt-1">Links</p>
            <ul class="kit-links max-w-2xl divide-y divide-neutral-800/80">
                @if($pdfHref)
                    <li class="py-4 first:pt-0">
                        <a href="{{ $pdfHref }}" class="group flex flex-wrap items-baseline justify-between gap-2">
                            <span class="kit-link-label text-neutral-200 group-hover:text-accent transition-colors">Resume PDF</span>
                            <span class="kit-link-meta font-mono text-[10px] text-neutral-500 uppercase tracking-widest">Download</span>
                        </a>
                    </li>
                @endif
                <li class="py-4">
                    <a href="{{ url('/resume') }}" class="group flex flex-wrap items-baseline justify-between gap-2">
                        <span class="kit-link-label text-neutral-200 group-hover:text-accent transition-colors">Live resume (HTML)</span>
                        <span class="kit-link-meta font-mono text-[10px] text-neutral-500 uppercase tracking-widest">/resume</span>
                    </a>
                </li>
                <li class="py-4">
                    <a href="{{ url('/now') }}" class="group flex flex-wrap items-baseline justify-between gap-2">
                        <span class="kit-link-label text-neutral-200 group-hover:text-accent transition-colors">Now — focus & booking</span>
                        <span class="kit-link-meta font-mono text-[10px] text-neutral-500 uppercase tracking-widest">/now</span>
                    </a>
                </li>
                @if(filled($bookingUrl))
                    <li class="py-4">
                        <a href="{{ url('/now#book') }}" class="group flex flex-wrap items-baseline justify-between gap-2">
                            <span class="kit-link-label text-neutral-200 group-hover:text-accent transition-colors">{{ $bookingLabel }}</span>
                            <span class="kit-link-meta font-mono text-[10px] text-neutral-500 uppercase tracking-widest">#book</span>
                        </a>
                    </li>
                @endif
                @if($linkedin)
                    <li class="py-4">
                        <a href="{{ $linkedin['url'] }}" target="_blank" rel="me noopener noreferrer"
                           class="group flex flex-wrap items-baseline justify-between gap-2">
                            <span class="kit-link-label text-neutral-200 group-hover:text-accent transition-colors">LinkedIn</span>
                            <span class="kit-link-meta font-mono text-[10px] text-neutral-500 uppercase tracking-widest">Profile</span>
                        </a>
                    </li>
                @endif
                @if($github)
                    <li class="py-4">
                        <a href="{{ $github['url'] }}" target="_blank" rel="me noopener noreferrer"
                           class="group flex flex-wrap items-baseline justify-between gap-2">
                            <span class="kit-link-label text-neutral-200 group-hover:text-accent transition-colors">GitHub</span>
                            <span class="kit-link-meta font-mono text-[10px] text-neutral-500 uppercase tracking-widest">Code</span>
                        </a>
                    </li>
                @endif
                <li class="py-4">
                    <a href="{{ url('/work/nasa-earth-observatory') }}" class="group flex flex-wrap items-baseline justify-between gap-2">
                        <span class="kit-link-label text-neutral-200 group-hover:text-accent transition-colors">Case study — NASA Earth Observatory</span>
                        <span class="kit-link-meta font-mono text-[10px] text-neutral-500 uppercase tracking-widest">Flagship</span>
                    </a>
                </li>
                <li class="py-4">
                    <a href="{{ url('/work/flood-mapping-system') }}" class="group flex flex-wrap items-baseline justify-between gap-2">
                        <span class="kit-link-label text-neutral-200 group-hover:text-accent transition-colors">Case study — Flood Mapping System</span>
                        <span class="kit-link-meta font-mono text-[10px] text-neutral-500 uppercase tracking-widest">Flagship</span>
                    </a>
                </li>
                <li class="kit-link-email py-4 last:pb-0">
                    <a href="mailto:{{ $person['email'] }}" class="group flex flex-wrap items-baseline justify-between gap-2">
                        <span class="kit-link-label text-neutral-200 group-hover:text-accent transition-colors">{{ $person['email'] }}</span>
                        <span class="kit-link-meta font-mono text-[10px] text-neutral-500 uppercase tracking-widest">Email</span>
                    </a>
                </li>
            </ul>
        </div>
    </section>
    </div>
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
