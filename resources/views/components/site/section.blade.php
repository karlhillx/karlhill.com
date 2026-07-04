{{-- Standard page section: shared vertical rhythm, border, and max-width
     container. Pass `number` + `label` for the standard mono heading, or
     leave them out and render a custom heading row in the slot. --}}
@props([
    'id' => null,
    'number' => null,
    'label' => null,
])

<section @if($id) id="{{ $id }}" @endif {{ $attributes->merge(['class' => 'py-28 px-6 border-t border-neutral-800']) }}>
    <div class="max-w-6xl mx-auto">
        @if($number !== null && $label !== null)
            <x-site.section-heading :number="$number" :label="$label" />
        @endif
        {{ $slot }}
    </div>
</section>
