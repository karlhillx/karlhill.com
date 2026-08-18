@props(['mentions' => []])

@php
    $enabled = filled(config('site.push.public_key'));
@endphp

@if($enabled)
    <button type="button"
            data-push-subscribe
            class="inline-flex items-center gap-2 font-mono text-xs text-neutral-400 hover:text-accent uppercase tracking-widest transition-colors"
            hidden>
        Notify me of new essays
    </button>
@endif
