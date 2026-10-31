@props([
    'variant' => 'compact',
])

@php
    $person = config('site.person');
    $isHome = $variant === 'home';
@endphp

<footer id="contact" @if($isHome) data-section-label="Contact" @endif @class([
    'relative z-10 border-t border-neutral-800/50 site-footer',
    'site-footer--home' => $isHome,
    'site-footer--compact' => ! $isHome,
])>
    @if($isHome)
        <div class="site-shell contact-close">
            <h2>{{ config('site.footer.headline') }}</h2>
            <p class="contact-close__lead">{{ config('site.footer.body') }}</p>
        </div>
    @endif
    <div class="site-shell site-footer__bar">
        <p class="site-footer__id">
            <span class="site-footer__name">{{ $person['name'] }}</span>
            <span class="site-footer__place">{{ $person['location'] }}</span>
        </p>
        <x-site.footer-explore />
        <x-site.email-copy :location="$isHome ? 'footer-home' : 'footer'" :muted="false" />
    </div>
</footer>
