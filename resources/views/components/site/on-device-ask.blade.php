@props([
    'source' => '[data-ask-source]',
    'context' => '',
    'label' => 'Ask this page',
    'placeholder' => 'A question this page can answer',
])

@php
    $inputId = $attributes->get('id', 'on-device-ask').'-q';
    $outputId = $attributes->get('id', 'on-device-ask').'-output';
@endphp

<aside {{ $attributes->class('on-device-ask on-device-summary')->except('id') }}
     hidden
     data-on-device-ask
     data-ask-source="{{ $source }}"
     data-ask-context="{{ $context }}">
    <form class="on-device-ask__form" data-ask-form>
        <label for="{{ $inputId }}" class="sr-only">{{ $label }}</label>
        <div class="on-device-ask__row">
            <input id="{{ $inputId }}"
                   type="search"
                   name="q"
                   enterkeyhint="go"
                   autocomplete="off"
                   spellcheck="false"
                   maxlength="280"
                   placeholder="{{ $placeholder }}"
                   class="on-device-ask__input font-sans text-sm"
                   data-ask-input>
            <button type="submit"
                    class="on-device-summary__btn font-mono text-caption uppercase tracking-widest"
                    data-ask-run
                    aria-expanded="false"
                    aria-controls="{{ $outputId }}">
                {{ $label }}
            </button>
            <button type="button"
                    class="on-device-summary__cancel font-mono text-caption uppercase tracking-widest"
                    data-ask-cancel
                    hidden>
                Cancel
            </button>
        </div>
    </form>
    <p class="on-device-summary__hint font-mono text-caption uppercase tracking-widest"
       data-ask-status>
        Chrome on-device · nothing leaves this device
    </p>
    <div id="{{ $outputId }}"
         class="on-device-summary__output"
         data-ask-output
         hidden
         role="status"
         aria-live="polite"></div>
</aside>
