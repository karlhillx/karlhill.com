@props([
    'name',
    'class' => 'w-4 h-4',
])

@php
    $aliases = [
        'k8s' => 'kubernetes',
        'golang' => 'go',
        'ci/cd' => 'pipeline',
        'ci' => 'pipeline',
        'devex' => 'terminal',
        'cli' => 'terminal',
        'bash' => 'terminal',
        'devsecops' => 'shield-check',
        'security' => 'shield-check',
        'arrow' => 'arrow-right',
        'external' => 'external-link',
        'architecture' => 'layers',
    ];
    $resolved = $aliases[strtolower($name)] ?? strtolower($name);
@endphp

<x-dynamic-component :component="'site.icons.'.$resolved" {{ $attributes->merge(['class' => $class, 'aria-hidden' => 'true']) }} />
