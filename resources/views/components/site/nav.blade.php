@props(['activeNav' => null])

@php
    $navLinkClass = static function (string $key) use ($activeNav): string {
        return 'transition-colors duration-200 '.($activeNav === $key ? 'text-accent' : 'hover:text-accent');
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
                    data-command-palette-trigger
                    aria-label="Jump to section"
                    title="Jump to section"
                    class="md:hidden inline-flex items-center justify-center w-9 h-9 border border-neutral-700 hover:border-accent text-neutral-400 hover:text-accent transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                </svg>
            </button>
            <button type="button"
                    data-command-palette-trigger
                    aria-label="Open command palette"
                    title="Search (⌘K)"
                    class="hidden md:inline-flex items-center gap-2 font-mono text-[10px] text-neutral-500 border border-neutral-800 px-3 py-2.5 uppercase tracking-widest hover:border-accent hover:text-accent transition-colors duration-200">
                <span aria-hidden="true">⌘K</span>
                <span class="sr-only">Open command palette</span>
            </button>
            <a href="mailto:{{ config('site.person.email') }}"
               class="hidden sm:inline-flex text-xs font-semibold text-neutral-300 border border-neutral-700 px-5 py-2.5 uppercase tracking-widest hover:border-accent hover:text-accent transition-colors duration-200">
                Get in Touch
            </a>
            <button id="nav-toggle" aria-controls="mobile-menu" aria-expanded="false" aria-label="Toggle navigation"
                    class="md:hidden flex flex-col justify-center items-center w-9 h-9 gap-1.5 border border-neutral-700 hover:border-accent transition-colors shrink-0">
                <span class="block w-4 h-px bg-current" aria-hidden="true"></span>
                <span class="block w-4 h-px bg-current" aria-hidden="true"></span>
                <span class="block w-4 h-px bg-current" aria-hidden="true"></span>
            </button>
        </div>
    </div>
    <div id="mobile-menu" hidden class="md:hidden border-t border-neutral-800 bg-bg/98 backdrop-blur-sm">
        <div class="max-w-6xl mx-auto px-6 py-4 flex flex-col gap-1 font-mono text-xs text-neutral-500 uppercase tracking-widest">
            <a href="/work" class="py-3 border-b border-neutral-800/50 hover:text-accent transition-colors">Work</a>
            <a href="/about" class="py-3 border-b border-neutral-800/50 hover:text-accent transition-colors">About</a>
            <a href="/blog" class="py-3 border-b border-neutral-800/50 hover:text-accent transition-colors">Writing</a>
            <a href="/#contact" class="py-3 border-b border-neutral-800/50 hover:text-accent transition-colors">Contact</a>
            <button type="button"
                    data-command-palette-trigger
                    class="py-3 text-left hover:text-accent transition-colors">
                Jump to section · ⌘K
            </button>
        </div>
    </div>
</nav>
