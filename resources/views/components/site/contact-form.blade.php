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
        <label>Company <input type="text" name="company" tabindex="-1" autocomplete="off"></label>
    </div>

    <div class="grid sm:grid-cols-2 gap-4">
        <div>
            <label for="{{ $nameId }}" class="block font-mono text-[10px] text-neutral-500 uppercase tracking-widest mb-1.5">Your name</label>
            <input id="{{ $nameId }}" name="name" type="text" required maxlength="120"
                   value="{{ old('name') }}" placeholder="Your name" autocomplete="name"
                   @if($errors->has('name')) aria-invalid="true" aria-describedby="{{ $nameId }}-error" @endif
                   @class([
                       'w-full bg-neutral-900/50 border text-neutral-200 placeholder-neutral-600 px-4 py-3 text-sm outline-none transition-colors focus:border-accent',
                       'border-red-500/60' => $errors->has('name'),
                       'border-neutral-800' => ! $errors->has('name'),
                   ])>
            @error('name')<p id="{{ $nameId }}-error" class="mt-1 font-mono text-[11px] text-red-400">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="{{ $emailId }}" class="block font-mono text-[10px] text-neutral-500 uppercase tracking-widest mb-1.5">Your email</label>
            <input id="{{ $emailId }}" name="email" type="email" required maxlength="190"
                   value="{{ old('email') }}" placeholder="you@company.com" autocomplete="email"
                   @if($errors->has('email')) aria-invalid="true" aria-describedby="{{ $emailId }}-error" @endif
                   @class([
                       'w-full bg-neutral-900/50 border text-neutral-200 placeholder-neutral-600 px-4 py-3 text-sm outline-none transition-colors focus:border-accent',
                       'border-red-500/60' => $errors->has('email'),
                       'border-neutral-800' => ! $errors->has('email'),
                   ])>
            @error('email')<p id="{{ $emailId }}-error" class="mt-1 font-mono text-[11px] text-red-400">{{ $message }}</p>@enderror
        </div>
    </div>
    <div>
        <label for="{{ $messageId }}" class="block font-mono text-[10px] text-neutral-500 uppercase tracking-widest mb-1.5">Message</label>
        <textarea id="{{ $messageId }}" name="message" required minlength="10" maxlength="4000" rows="4"
                  placeholder="{{ config('site.footer.contact_placeholder', 'What are you building, and how can I help?') }}"
                  @if($errors->has('message')) aria-invalid="true" aria-describedby="{{ $messageId }}-error" @endif
                  @class([
                      'contact-textarea w-full bg-neutral-900/50 border text-neutral-200 placeholder-neutral-600 px-4 py-3 text-sm outline-none transition-colors focus:border-accent resize-y',
                      'border-red-500/60' => $errors->has('message'),
                      'border-neutral-800' => ! $errors->has('message'),
                  ])>{{ old('message') }}</textarea>
        @error('message')<p id="{{ $messageId }}-error" class="mt-1 font-mono text-[11px] text-red-400">{{ $message }}</p>@enderror
    </div>
    <button type="submit" id="{{ $submitId }}" data-contact-submit
            class="btn-sweep inline-flex items-center gap-2 border border-accent/50 text-accent font-mono text-xs uppercase tracking-widest px-6 py-3">
        Send message <span aria-hidden="true">→</span>
    </button>
</form>
