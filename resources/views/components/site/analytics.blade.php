@php
    $google = config('site.analytics.google', []);
    $plausible = config('site.analytics.plausible', []);
@endphp

@if(($google['enabled'] ?? false) && filled($google['id'] ?? null))
    @php($measurementId = $google['id'])
    <link rel="preconnect" href="https://www.googletagmanager.com">
    <script async nonce="{{ Vite::cspNonce() }}" src="https://www.googletagmanager.com/gtag/js?id={{ $measurementId }}"></script>
    <script nonce="{{ Vite::cspNonce() }}">
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', @json($measurementId));
    </script>
@endif

@if(($plausible['enabled'] ?? false) && filled($plausible['domain'] ?? null))
    <script nonce="{{ Vite::cspNonce() }}">
        window.__siteAnalytics = { provider: 'plausible', domain: @json($plausible['domain']) };
        window.plausible = window.plausible || function() {
            (window.plausible.q = window.plausible.q || []).push(arguments);
        };
    </script>
@endif
