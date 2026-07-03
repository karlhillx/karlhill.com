@props(['rules' => []])

@if($rules !== [])
<script type="speculationrules" nonce="{{ Vite::cspNonce() }}">
{!! json_encode($rules, JSON_UNESCAPED_SLASHES) !!}
</script>
@endif
