{{-- Standard page section: shared vertical rhythm, border, and max-width
     container. Pass `number` + `label` for the standard mono heading, or
     leave them out and render a custom heading row in the slot. --}}
@props([
    'id' => null,
    'number' => null,
    'label' => null,
    'border' => 'default',
    'sectionLabel' => null,
    'headingClass' => '!mb-0',
])

@php
    $borderClass = match ($border) {
        'soft' => 'border-t border-neutral-800/50',
        'none' => '',
        default => 'border-t border-neutral-800',
    };
    $minimapLabel = $sectionLabel ?? $label;
    $hasActions = isset($actions) && trim((string) $actions) !== '';
@endphp

<section @if($id) id="{{ $id }}" @endif
         @if($id && $minimapLabel) data-section-label="{{ $minimapLabel }}" @endif
         {{ $attributes->merge(['class' => "site-section {$borderClass}"]) }}>
    <div class="site-shell">
        @if($number !== null && $label !== null)
            <div @class([
                'flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5 site-heading-space' => $hasActions,
            ]) @if($hasActions) data-reveal @endif>
                <x-site.section-heading :number="$number" :label="$label" :class="$headingClass" />
                @if($hasActions)
                    {{ $actions }}
                @endif
            </div>
        @endif
        {{ $slot }}
    </div>
</section>
