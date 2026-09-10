@extends('layouts.site', ['meta' => $meta])

@section('content')
    <x-site.page-hero eyebrow="Staging" :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Clients'],
    ]">
        <x-slot:title>Client staging</x-slot:title>

        <p class="text-neutral-300 text-base sm:text-lg leading-relaxed max-w-2xl">
            Previews of client websites in progress.
        </p>
    </x-site.page-hero>

    <section class="site-section site-section--soft border-t border-neutral-800/50" aria-label="Staged client sites">
        <div class="site-shell max-w-3xl">
            @if($clients->isEmpty())
                <p class="text-neutral-400 text-base leading-relaxed" data-reveal>
                    No previews are available.
                </p>
            @else
                <ul class="divide-y divide-neutral-800" data-reveal>
                    @foreach($clients as $client)
                        <li class="py-5 flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-2">
                            <div>
                                <a href="{{ $client['path'] }}"
                                   class="text-white text-lg font-semibold hover:text-accent transition-colors">
                                    {{ $client['slug'] }}
                                </a>
                                <p class="mt-1 text-neutral-500 text-sm leading-relaxed max-w-xl">
                                    {{ $client['title'] }}
                                </p>
                            </div>
                            <a href="{{ $client['path'] }}"
                               class="font-mono text-xs text-accent uppercase tracking-widest shrink-0">
                                Open preview →
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
