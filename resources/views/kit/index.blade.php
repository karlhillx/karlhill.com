@extends('layouts.site', ['meta' => $meta])

@section('content')
    <x-site.page-hero :eyebrow="$kit['eyebrow'] ?? 'Recruiter kit'" :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Kit'],
    ]">
        <x-slot:title>Recruiter kit</x-slot:title>

        <p class="text-neutral-300 text-base sm:text-lg leading-relaxed max-w-2xl">
            {{ $kit['lede'] }}
        </p>

        <div class="flex flex-wrap items-center gap-x-4 gap-y-3 mt-8 sm:mt-10">
            @if(! empty($pdf))
                <a href="{{ $pdf }}"
                   class="btn-sweep inline-flex items-center justify-center min-h-11 gap-2 font-mono text-xs text-accent border border-accent/40 px-5 py-3 uppercase tracking-widest transition-colors">
                    Download resume PDF
                </a>
            @endif
            @if(filled($bookingUrl))
                <a href="/now#book"
                   class="btn-sweep magnetic-btn inline-flex items-center justify-center min-h-11 gap-2 font-mono text-xs text-bg bg-accent border border-accent px-5 py-3 uppercase tracking-widest transition-colors">
                    {{ $bookingLabel }}
                </a>
            @endif
            <a href="/#contact"
               class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                Contact
            </a>
        </div>
    </x-site.page-hero>

    <section class="site-section site-section--soft border-t border-neutral-800/50" aria-label="Bio and highlights">
        <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
            <p class="font-mono text-accent text-xs tracking-widest uppercase pt-1">At a glance</p>
            <div class="max-w-2xl">
                <p class="text-neutral-200 text-lg leading-relaxed">{{ $kit['bio'] }}</p>
                <dl class="mt-8 grid sm:grid-cols-2 gap-4 text-sm">
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
                    <ul class="mt-8 space-y-2 text-neutral-400 text-sm leading-relaxed list-disc pl-5">
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
            <p class="font-mono text-accent text-xs tracking-widest uppercase pt-1">Links</p>
            <ul class="max-w-2xl divide-y divide-neutral-800/80">
                @if(! empty($pdf))
                    <li class="py-4 first:pt-0">
                        <a href="{{ $pdf }}" class="group flex flex-wrap items-baseline justify-between gap-2">
                            <span class="text-neutral-200 group-hover:text-accent transition-colors">Resume PDF</span>
                            <span class="font-mono text-[10px] text-neutral-500 uppercase tracking-widest">Download</span>
                        </a>
                    </li>
                @endif
                <li class="py-4">
                    <a href="/resume" class="group flex flex-wrap items-baseline justify-between gap-2">
                        <span class="text-neutral-200 group-hover:text-accent transition-colors">Live resume (HTML)</span>
                        <span class="font-mono text-[10px] text-neutral-500 uppercase tracking-widest">/resume</span>
                    </a>
                </li>
                <li class="py-4">
                    <a href="/now" class="group flex flex-wrap items-baseline justify-between gap-2">
                        <span class="text-neutral-200 group-hover:text-accent transition-colors">Now — focus & booking</span>
                        <span class="font-mono text-[10px] text-neutral-500 uppercase tracking-widest">/now</span>
                    </a>
                </li>
                @if(filled($bookingUrl))
                    <li class="py-4">
                        <a href="/now#book" class="group flex flex-wrap items-baseline justify-between gap-2">
                            <span class="text-neutral-200 group-hover:text-accent transition-colors">{{ $bookingLabel }}</span>
                            <span class="font-mono text-[10px] text-neutral-500 uppercase tracking-widest">#book</span>
                        </a>
                    </li>
                @endif
                @if($linkedin)
                    <li class="py-4">
                        <a href="{{ $linkedin['url'] }}" target="_blank" rel="me noopener noreferrer"
                           class="group flex flex-wrap items-baseline justify-between gap-2">
                            <span class="text-neutral-200 group-hover:text-accent transition-colors">LinkedIn</span>
                            <span class="font-mono text-[10px] text-neutral-500 uppercase tracking-widest">Profile</span>
                        </a>
                    </li>
                @endif
                @if($github)
                    <li class="py-4">
                        <a href="{{ $github['url'] }}" target="_blank" rel="me noopener noreferrer"
                           class="group flex flex-wrap items-baseline justify-between gap-2">
                            <span class="text-neutral-200 group-hover:text-accent transition-colors">GitHub</span>
                            <span class="font-mono text-[10px] text-neutral-500 uppercase tracking-widest">Code</span>
                        </a>
                    </li>
                @endif
                <li class="py-4">
                    <a href="/work/nasa-earth-observatory" class="group flex flex-wrap items-baseline justify-between gap-2">
                        <span class="text-neutral-200 group-hover:text-accent transition-colors">Case study — NASA Earth Observatory</span>
                        <span class="font-mono text-[10px] text-neutral-500 uppercase tracking-widest">Flagship</span>
                    </a>
                </li>
                <li class="py-4">
                    <a href="/work/flood-mapping-system" class="group flex flex-wrap items-baseline justify-between gap-2">
                        <span class="text-neutral-200 group-hover:text-accent transition-colors">Case study — Flood Mapping System</span>
                        <span class="font-mono text-[10px] text-neutral-500 uppercase tracking-widest">Flagship</span>
                    </a>
                </li>
                <li class="py-4 last:pb-0">
                    <a href="mailto:{{ $person['email'] }}" class="group flex flex-wrap items-baseline justify-between gap-2">
                        <span class="text-neutral-200 group-hover:text-accent transition-colors">{{ $person['email'] }}</span>
                        <span class="font-mono text-[10px] text-neutral-500 uppercase tracking-widest">Email</span>
                    </a>
                </li>
            </ul>
        </div>
    </section>
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
