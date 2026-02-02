@php
    $enabled = (bool) config('services.google_analytics.enabled', false);
    $measurementId = (string) config('services.google_analytics.measurement_id', '');
@endphp

@if($enabled && filled($measurementId))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $measurementId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', '{{ $measurementId }}');
    </script>
@endif

