<button type="button"
        data-theme-toggle
        aria-label="Switch theme"
        title="Switch theme"
        {{ $attributes->merge([
            'class' => 'inline-flex items-center justify-center min-h-11 min-w-11 border border-[color:var(--border-strong)] hover:border-accent text-neutral-400 hover:text-accent transition-colors shrink-0',
        ]) }}>
    <x-site.icons.sun class="theme-toggle__icon--sun" />
    <x-site.icons.moon class="theme-toggle__icon--moon" />
</button>
