@extends('layouts.site', ['meta' => $meta])

@section('content')
    <x-site.page-hero eyebrow="Background" :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'About'],
    ]">
        <x-slot:title>About Karl</x-slot:title>

        <p class="text-neutral-300 text-base sm:text-lg leading-relaxed max-w-2xl">
            {{ config('site.about.lede') }}
        </p>

        <div class="flex flex-wrap items-center gap-x-4 gap-y-3 mt-8 sm:mt-10">
            <a href="/resume"
               class="btn-sweep inline-flex items-center justify-center min-h-11 gap-2 font-mono text-xs text-accent border border-accent/40 px-5 py-3 uppercase tracking-widest transition-colors">
                View resume →
            </a>
            <a href="#contact"
               class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors">
                Talk about leadership
            </a>
            <a href="/now"
               class="inline-flex items-center min-h-11 font-mono text-xs text-neutral-500 hover:text-accent uppercase tracking-widest transition-colors">
                What I'm doing now
            </a>
        </div>
    </x-site.page-hero>

    @include('about.partials.how-i-lead', ['sectionNumber' => '01'])
    @include('about.partials.social-proof', ['sectionNumber' => '02'])
    @include('about.partials.arc', ['sectionNumber' => '03'])
    @include('partials.research', ['sectionNumber' => '04'])

    @if(config('site.about.beyond'))
        @php
            $discogs = collect(config('site.social'))->first(fn ($link) => ($link['icon'] ?? '') === 'discogs');
            $beyond = config('site.about.beyond');
        @endphp
        <section aria-label="Beyond the work" class="site-section site-section--soft border-t border-neutral-800/50">
            <div class="site-shell grid md:grid-cols-[200px_1fr] gap-8 md:gap-14" data-reveal>
                <p class="font-mono text-accent text-xs tracking-widest uppercase pt-1">Beyond the work</p>
                <p class="text-neutral-300 text-lg leading-relaxed max-w-2xl">
                    @if($discogs && str_contains($beyond, 'Discogs'))
                        {!! str_replace(
                            'Discogs',
                            '<a href="'.e($discogs['url']).'" target="_blank" rel="me noopener noreferrer" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">Discogs</a>',
                            e($beyond)
                        ) !!}
                    @else
                        {{ $beyond }}
                    @endif
                </p>
            </div>
        </section>
    @endif
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
