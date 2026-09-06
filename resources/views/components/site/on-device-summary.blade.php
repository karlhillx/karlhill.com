@props([
    'source' => '[data-summary-source]',
    'context' => '',
    'type' => 'tldr',
    'length' => 'short',
    'label' => 'Summarize on-device',
])

<aside {{ $attributes->class('on-device-summary') }}
     hidden
     data-on-device-summary
     data-summary-source="{{ $source }}"
     data-summary-context="{{ $context }}"
     data-summary-type="{{ $type }}"
     data-summary-length="{{ $length }}">
    <div class="on-device-summary__bar">
        <button type="button"
                class="on-device-summary__btn font-mono text-caption uppercase tracking-widest"
                data-summary-run
                aria-expanded="false"
                aria-controls="on-device-summary-output">
            {{ $label }}
        </button>
        <button type="button"
                class="on-device-summary__cancel font-mono text-caption uppercase tracking-widest"
                data-summary-cancel
                hidden>
            Cancel
        </button>
    </div>
    <p class="on-device-summary__hint font-mono text-caption uppercase tracking-widest"
       data-summary-status>
        Chrome on-device · nothing leaves this device
    </p>
    <div id="on-device-summary-output"
         class="on-device-summary__output"
         data-summary-output
         hidden
         role="status"
         aria-live="polite"></div>
</aside>
