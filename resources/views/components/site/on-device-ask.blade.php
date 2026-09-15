@props([
    'source' => '[data-ask-source]',
    'context' => '',
    'prompts' => [],
    'heading' => null,
    'label' => 'Ask',
    'placeholder' => '',
])

@php
    $prompts = collect($prompts)
        ->filter(fn ($prompt): bool => is_string($prompt) && $prompt !== '')
        ->values()
        ->all();
    $heading = is_string($heading) && $heading !== '' ? $heading : 'Ask this page';
    $placeholder = is_string($placeholder) && $placeholder !== ''
        ? $placeholder
        : ($prompts[0] ?? 'A question this page can answer');
    $inputId = $attributes->get('id', 'on-device-ask').'-q';
    $outputId = $attributes->get('id', 'on-device-ask').'-output';
@endphp

<aside {{ $attributes->class('on-device-ask on-device-summary')->except('id') }}
     hidden
     data-on-device-ask
     data-ask-from="{{ $source }}">
    @if($context !== '')
        <template data-ask-brief>{{ $context }}</template>
    @endif
    <div class="on-device-ask__intro">
        <div class="on-device-ask__head">
            <span class="summary-panel__live" aria-hidden="true"></span>
            <p class="on-device-ask__title" id="{{ $inputId }}-heading">{{ $heading }}</p>
        </div>
        <p class="on-device-ask__lede">
            Ask a hiring question. Answers stay in this browser and use the text on this page.
        </p>
    </div>
    @if($prompts !== [])
        <div class="on-device-ask__prompts" role="group" aria-label="Suggested questions">
            @foreach($prompts as $prompt)
                <button type="button"
                        class="on-device-ask__prompt"
                        data-ask-prompt="{{ $prompt }}">
                    {{ $prompt }}
                </button>
            @endforeach
        </div>
    @endif
    <form class="on-device-ask__form" data-ask-form>
        <label for="{{ $inputId }}" class="sr-only">{{ $heading }}</label>
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
