@php
    $google = config('site.analytics.google', []);
    $plausible = config('site.analytics.plausible', []);
@endphp

@if(($google['enabled'] ?? false) && filled($google['id'] ?? null))
    @php($measurementId = $google['id'])
    <script async nonce="{{ Vite::cspNonce() }}" src="https://www.googletagmanager.com/gtag/js?id={{ $measurementId }}"></script>
    <script nonce="{{ Vite::cspNonce() }}">
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', @json($measurementId));
    </script>
@endif

@if(($plausible['enabled'] ?? false) && filled($plausible['domain'] ?? null))
    <script defer nonce="{{ Vite::cspNonce() }}" data-domain="{{ $plausible['domain'] }}" src="https://plausible.io/js/script.js"></script>
@endif
