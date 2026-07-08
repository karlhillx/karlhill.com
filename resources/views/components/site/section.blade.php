{{-- Standard page section: shared vertical rhythm, border, and max-width
     container. Pass `number` + `label` for the standard mono heading, or
     leave them out and render a custom heading row in the slot. --}}
@props([
    'id' => null,
    'number' => null,
    'label' => null,
    'border' => 'default',
    'sectionLabel' => null,
])

@php
    $borderClass = match ($border) {
        'soft' => 'border-t border-neutral-800/50',
        'none' => '',
        default => 'border-t border-neutral-800',
    };
    $minimapLabel = $sectionLabel ?? $label;
@endphp

<section @if($id) id="{{ $id }}" @endif
         @if($id && $minimapLabel) data-section-label="{{ $minimapLabel }}" @endif
         {{ $attributes->merge(['class' => "py-28 px-6 {$borderClass}"]) }}>
    <div class="max-w-6xl mx-auto">
        @if($number !== null && $label !== null)
            <x-site.section-heading :number="$number" :label="$label" />
        @endif
        {{ $slot }}
    </div>
</section>
