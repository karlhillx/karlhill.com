@props(['activeNav' => null])

@php
    $navLinkClass = static function (string $key) use ($activeNav): string {
        return 'nav-link transition-colors duration-200 '.($activeNav === $key ? 'text-accent' : 'hover:text-accent');
    };
    $mobileLinkClass = static function (string $key) use ($activeNav): string {
        return 'py-3 border-b border-neutral-800/50 transition-colors '
            .($activeNav === $key ? 'text-accent' : 'hover:text-accent');
    };
@endphp

<nav aria-label="Primary" class="fixed top-0 left-0 right-0 z-50 border-b border-neutral-800/60 bg-bg/90 backdrop-blur-sm nav-enter">
    <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="/" class="font-display text-2xl tracking-wider text-accent" style="view-transition-name: brand">KARL HILL</a>
        <div class="hidden md:flex items-center gap-6 lg:gap-8 font-mono text-xs text-neutral-500 uppercase tracking-widest">
            <a href="/work" class="{{ $navLinkClass('work') }}">Work</a>
            <a href="/about" class="{{ $navLinkClass('about') }}">About</a>
            <a href="/blog" class="{{ $navLinkClass('writing') }}">Writing</a>
            <a href="/#contact" data-nav-section="contact" class="{{ $navLinkClass('contact') }}">Contact</a>
        </div>
        <div class="flex items-center gap-2 sm:gap-3">
            <button type="button"
                    popovertarget="command-palette"
                    aria-label="Jump to a page or section"
                    aria-keyshortcuts="Meta+K"
                    title="Jump to a page or section"
                    class="md:hidden inline-flex items-center gap-1.5 justify-center border border-neutral-700 hover:border-accent text-neutral-400 hover:text-accent transition-colors shrink-0 pl-2.5 pr-3 py-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                </svg>
                <span class="font-mono text-[9px] uppercase tracking-widest">Jump</span>
            </button>
            <button type="button"
                    popovertarget="command-palette"
                    aria-label="Jump to a page or section"
                    aria-keyshortcuts="Meta+K"
                    title="Jump to a page or section (⌘K)"
                    class="hidden md:inline-flex items-center gap-2 font-mono text-[10px] text-neutral-400 border border-neutral-800 pl-3 pr-2 py-2 uppercase tracking-widest hover:border-accent hover:text-accent transition-colors duration-200">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                </svg>
                <span>Jump to&hellip;</span>
                <span aria-hidden="true"
                      class="surface-chip ml-1 px-1.5 py-0.5 text-[9px] leading-none text-neutral-500 normal-case tracking-normal">⌘K</span>
            </button>
            <a href="mailto:{{ config('site.person.email') }}"
               class="btn-sweep hidden sm:inline-flex text-xs font-semibold text-neutral-300 border border-neutral-700 px-5 py-2.5 uppercase tracking-widest">
                Get in Touch
            </a>
            <button id="nav-toggle" type="button" popovertarget="mobile-menu"
                    aria-controls="mobile-menu" aria-expanded="false" aria-label="Toggle navigation"
                    class="md:hidden flex flex-col justify-center items-center w-9 h-9 gap-1.5 border border-neutral-700 hover:border-accent transition-colors shrink-0">
                <span class="block w-4 h-px bg-current" aria-hidden="true"></span>
                <span class="block w-4 h-px bg-current" aria-hidden="true"></span>
                <span class="block w-4 h-px bg-current" aria-hidden="true"></span>
            </button>
        </div>
    </div>
    <div id="mobile-menu" popover="auto" class="md:hidden border-t border-neutral-800 bg-bg/98 backdrop-blur-sm">
        <div class="max-w-6xl mx-auto px-6 py-4 flex flex-col gap-1 font-mono text-xs text-neutral-500 uppercase tracking-widest">
            <a href="/work" class="{{ $mobileLinkClass('work') }}">Work</a>
            <a href="/about" class="{{ $mobileLinkClass('about') }}">About</a>
            <a href="/blog" class="{{ $mobileLinkClass('writing') }}">Writing</a>
            <a href="/#contact" class="{{ $mobileLinkClass('contact') }}">Contact</a>
            <button type="button"
                    popovertarget="command-palette"
                    class="py-3 text-left hover:text-accent transition-colors">
                Jump to section · ⌘K
            </button>
        </div>
    </div>
</nav>
