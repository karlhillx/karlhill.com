@props([
    'idPrefix' => 'contact',
    'returnTo' => null,
])

@php
    $nameId = $idPrefix.'-name';
    $emailId = $idPrefix.'-email';
    $messageId = $idPrefix.'-message';
    $formId = $idPrefix.'-form';
    $submitId = $idPrefix.'-submit';
    $returnPath = $returnTo ?? url()->current();
    // Error pages (and some edge renders) may not share a ViewErrorBag.
    $errorBag = isset($errors) && $errors instanceof \Illuminate\Support\ViewErrorBag
        ? $errors
        : new \Illuminate\Support\ViewErrorBag;
@endphp

<form id="{{ $formId }}"
      method="POST"
      action="{{ route('contact.store') }}"
      class="js-contact-form mt-10 space-y-5 max-w-md"
      aria-label="Send a message"
      data-contact-form>
    @csrf
    <input type="hidden" name="return_to" value="{{ $returnPath }}">

    {{-- Honeypot: hidden from people, irresistible to bots. --}}
    <div class="absolute -left-[9999px] w-px h-px overflow-hidden" aria-hidden="true">
        <label>Company <input type="text" name="company" tabindex="-1" autocomplete="off" inputmode="none"></label>
    </div>

    <div class="grid sm:grid-cols-2 gap-4">
        <div>
            <label for="{{ $nameId }}" class="block font-mono text-[10px] text-neutral-500 uppercase tracking-widest mb-1.5">Your name</label>
            <input id="{{ $nameId }}" name="name" type="text" required maxlength="120"
                   value="{{ old('name') }}" placeholder="Your name" autocomplete="name"
                   @if($errorBag->has('name')) aria-invalid="true" aria-describedby="{{ $nameId }}-error" @endif
                   @class([
                       'w-full bg-neutral-900/50 border text-neutral-200 placeholder-neutral-600 px-4 py-3 text-sm outline-none transition-colors focus:border-accent',
                       'border-red-500/60' => $errorBag->has('name'),
                       'border-neutral-800' => ! $errorBag->has('name'),
                   ])>
            @if($errorBag->has('name'))
                <p id="{{ $nameId }}-error" class="mt-1 font-mono text-[11px] text-red-400">{{ $errorBag->first('name') }}</p>
            @endif
        </div>
        <div>
            <label for="{{ $emailId }}" class="block font-mono text-[10px] text-neutral-500 uppercase tracking-widest mb-1.5">Your email</label>
            <input id="{{ $emailId }}" name="email" type="email" required maxlength="190"
                   value="{{ old('email') }}" placeholder="you@company.com" autocomplete="email"
                   @if($errorBag->has('email')) aria-invalid="true" aria-describedby="{{ $emailId }}-error" @endif
                   @class([
                       'w-full bg-neutral-900/50 border text-neutral-200 placeholder-neutral-600 px-4 py-3 text-sm outline-none transition-colors focus:border-accent',
                       'border-red-500/60' => $errorBag->has('email'),
                       'border-neutral-800' => ! $errorBag->has('email'),
                   ])>
            @if($errorBag->has('email'))
                <p id="{{ $emailId }}-error" class="mt-1 font-mono text-[11px] text-red-400">{{ $errorBag->first('email') }}</p>
            @endif
        </div>
    </div>
    <div>
        <label for="{{ $messageId }}" class="block font-mono text-[10px] text-neutral-500 uppercase tracking-widest mb-1.5">Message</label>
        <textarea id="{{ $messageId }}" name="message" required minlength="10" maxlength="4000" rows="4"
                  placeholder="{{ config('site.footer.contact_placeholder', 'What are you building, and how can I help?') }}"
                  @if($errorBag->has('message')) aria-invalid="true" aria-describedby="{{ $messageId }}-error" @endif
                  @class([
                      'contact-textarea w-full bg-neutral-900/50 border text-neutral-200 placeholder-neutral-600 px-4 py-3 text-sm outline-none transition-colors focus:border-accent resize-y',
                      'border-red-500/60' => $errorBag->has('message'),
                      'border-neutral-800' => ! $errorBag->has('message'),
                  ])>{{ old('message') }}</textarea>
        @if($errorBag->has('message'))
            <p id="{{ $messageId }}-error" class="mt-1 font-mono text-[11px] text-red-400">{{ $errorBag->first('message') }}</p>
        @endif
    </div>

    @if(\App\Support\Turnstile::enabled())
        <div>
            <div class="cf-turnstile"
                 data-sitekey="{{ config('site.turnstile.site_key') }}"
                 data-theme="dark"
                 data-size="flexible"></div>
            @if($errorBag->has('turnstile'))
                <p id="{{ $idPrefix }}-turnstile-error" class="mt-2 font-mono text-[11px] text-red-400" role="alert">
                    {{ $errorBag->first('turnstile') }}
                </p>
            @endif
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer nonce="{{ Vite::cspNonce() }}"></script>
        </div>
    @endif

    <button type="submit" id="{{ $submitId }}" data-contact-submit
            class="btn-sweep inline-flex items-center gap-2 border border-accent/50 text-accent font-mono text-xs uppercase tracking-widest px-6 py-3">
        Send message <span aria-hidden="true">→</span>
    </button>

    <div data-contact-status class="contact-form-status" hidden tabindex="-1" aria-live="polite"></div>
</form>
