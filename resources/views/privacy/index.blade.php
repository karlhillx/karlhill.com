@extends('layouts.site', ['meta' => $meta])

@section('content')
    <x-site.page-hero :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Privacy'],
    ]">
        <x-slot:title>Privacy</x-slot:title>

        <p class="text-neutral-100 text-lg sm:text-xl leading-relaxed max-w-2xl">
            {{ $privacy['lede'] }}
        </p>

        @if(! empty($privacy['updated']))
            <p class="mt-5 eyebrow eyebrow--muted">
                Updated {{ $privacy['updated'] }}
            </p>
        @endif
    </x-site.page-hero>

    <section id="contact" class="site-section border-t border-neutral-800/50 scroll-mt-24" aria-labelledby="privacy-contact-heading">
        <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
            <p class="eyebrow pt-1">01 · Contact</p>
            <div class="max-w-2xl">
                <h2 id="privacy-contact-heading" class="font-sans font-semibold text-xl sm:text-2xl tracking-tight text-neutral-100 mb-3">
                    Email
                </h2>
                <p class="text-neutral-300 text-base leading-relaxed">
                    The way to reach me is
                    <a href="mailto:{{ $person['email'] }}" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">{{ $person['email'] }}</a>.
                    That address is the only contact method on this site. Messages are used only to reply.
                </p>
            </div>
        </div>
    </section>

    <section id="analytics" class="site-section border-t border-neutral-800/50 scroll-mt-24" aria-labelledby="privacy-analytics-heading">
        <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
            <p class="eyebrow pt-1">02 · Analytics</p>
            <div class="max-w-2xl">
                <h2 id="privacy-analytics-heading" class="font-sans font-semibold text-xl sm:text-2xl tracking-tight text-neutral-100 mb-3">
                    How visits are measured
                </h2>
                <p class="text-neutral-300 text-base leading-relaxed">
                    @if($analyticsProvider === 'plausible')
                        Site analytics use Plausible: aggregate pageviews and named interaction events (for example email or resume downloads). Event properties do not include personal identifiers.
                    @elseif($analyticsProvider === 'google')
                        Site analytics use Google Analytics 4 for aggregate traffic and named interaction events. Event properties do not include message content.
                    @else
                        No third-party analytics provider is currently enabled.
                    @endif
                </p>
            </div>
        </div>
    </section>

    <section id="also" class="site-section border-t border-neutral-800/50 scroll-mt-24" aria-labelledby="privacy-also-heading">
        <div class="site-shell grid md:grid-cols-[220px_1fr] gap-6 md:gap-12" data-reveal>
            <p class="eyebrow pt-1">03 · Also</p>
            <div class="max-w-2xl">
                <h2 id="privacy-also-heading" class="font-sans font-semibold text-xl sm:text-2xl tracking-tight text-neutral-100 mb-3">
                    Optional features
                </h2>
                <p class="text-neutral-300 text-base leading-relaxed">
                    @if($pushEnabled)
                        Essay notifications are opt-in Web Push; a subscription endpoint is stored until you turn notifications off.
                    @else
                        Essay notifications, when offered, are opt-in Web Push and can be turned off from the same control.
                    @endif
                    Technical browser reports may be collected for security hardening. Nothing here is sold or used for advertising.
                </p>
                <p class="mt-5 text-neutral-400 text-base leading-relaxed">
                    {{ $privacy['closing'] }}
                    <a href="mailto:{{ $person['email'] }}" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">{{ $person['email'] }}</a>.
                </p>
            </div>
        </div>
    </section>
@endsection
