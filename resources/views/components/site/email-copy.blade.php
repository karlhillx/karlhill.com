{{-- Email address with a copy-to-clipboard affordance. `location` feeds the
     analytics placement prop; `arrow` adds the accent → used in rails. --}}
@props([
    'location' => 'footer',
    'arrow' => false,
    'muted' => true,
])

@php($email = config('site.person.email'))

<div {{ $attributes->class(['flex items-center gap-2 min-w-0']) }}>
    <a href="mailto:{{ $email }}"
       data-analytics-event="email_clicked"
       data-analytics-location="{{ $location }}"
       @class([
           'inline-flex items-center gap-3 min-h-11 font-mono text-sm hover:text-accent transition-colors min-w-0',
           'text-neutral-400' => $muted,
           'text-neutral-300' => ! $muted,
       ])>
        @if($arrow)
            <span class="text-accent text-base arrow-nudge shrink-0" aria-hidden="true">→</span>
        @endif
        <span class="truncate">{{ $email }}</span>
    </a>
    <button type="button" data-copy-text="{{ $email }}" aria-label="Copy email address"
            class="relative isolate inline-flex items-center justify-center min-h-11 min-w-11 text-neutral-500 hover:text-accent transition-colors shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V5a2 2 0 012-2h9a2 2 0 012 2v9a2 2 0 01-2 2h-2M5 8h9a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2v-9a2 2 0 012-2z"/>
        </svg>
        <span data-copy-feedback role="status" aria-live="polite"
              class="copy-feedback pointer-events-none absolute inline-flex items-center gap-1.5 whitespace-nowrap px-2.5 py-1 font-mono text-caption uppercase tracking-widest opacity-0 transition-opacity duration-200">
            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            Copied to clipboard
        </span>
    </button>
</div>
