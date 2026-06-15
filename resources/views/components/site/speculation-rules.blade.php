@props(['rules' => []])

@if($rules !== [])
<script type="speculationrules">
{!! json_encode($rules, JSON_UNESCAPED_SLASHES) !!}
</script>
@endif
