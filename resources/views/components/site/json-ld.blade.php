@props(['data'])

<script type="application/ld+json" nonce="{{ Vite::cspNonce() }}">
{!! json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
