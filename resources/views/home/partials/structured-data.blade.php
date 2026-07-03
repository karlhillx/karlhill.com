<script type="application/ld+json" nonce="{{ Vite::cspNonce() }}">
{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
