@props(['activeNav' => null])

@php
    $navLinkClass = static function (string $key) use ($activeNav): string {
        return 'transition-colors duration-200 '.($activeNav === $key ? 'text-orange-500' : 'hover:text-orange-500');
    };
@endphp

<nav aria-label="Primary" class="fixed top-0 left-0 right-0 z-50 border-b border-neutral-800/60 bg-[#080808]/90 backdrop-blur-sm nav-enter">
    <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="/" class="font-display text-2xl tracking-wider text-orange-500" style="view-transition-name: brand">KARL HILL</a>
        <div class="hidden md:flex items-center gap-6 lg:gap-8 font-mono text-xs text-neutral-500 uppercase tracking-widest">
            <a href="/#experience" data-nav-section="experience" class="{{ $navLinkClass('experience') }}">Experience</a>
            <a href="/#work" data-nav-section="work" class="{{ $navLinkClass('work') }}">Work</a>
            <a href="/blog" data-nav-section="writing" class="{{ $navLinkClass('writing') }}">Writing</a>
            <a href="/#contact" data-nav-section="contact" class="{{ $navLinkClass('contact') }}">Contact</a>
            <details class="relative group/nav-more nav-more">
                <summary class="{{ $navLinkClass('more') }} cursor-pointer select-none list-none flex items-center gap-1 [&::-webkit-details-marker]:hidden">
                    More <span class="text-[10px] opacity-60" aria-hidden="true">▾</span>
                </summary>
                <div class="absolute right-0 top-full mt-2 py-2 min-w-[13rem] border border-neutral-800 bg-[#080808]/98 backdrop-blur-md shadow-xl z-[60]">
                    <a href="/#research" data-nav-section="research" class="block px-4 py-2.5 hover:text-orange-500 hover:bg-neutral-900/50 transition-colors {{ $activeNav === 'research' ? 'text-orange-500' : '' }}">Research</a>
                    <a href="/#stack" data-nav-section="stack" class="block px-4 py-2.5 hover:text-orange-500 hover:bg-neutral-900/50 transition-colors {{ $activeNav === 'stack' ? 'text-orange-500' : '' }}">Stack</a>
                    <a href="/#open-source" data-nav-section="open-source" class="block px-4 py-2.5 hover:text-orange-500 hover:bg-neutral-900/50 transition-colors">Open Source</a>
                    <a href="/#certs" data-nav-section="certs" class="block px-4 py-2.5 hover:text-orange-500 hover:bg-neutral-900/50 transition-colors">Certifications</a>
                </div>
            </details>
        </div>
        <div class="flex items-center gap-2 sm:gap-3">
            <button type="button"
                    data-command-palette-trigger
                    aria-label="Jump to section"
                    title="Jump to section"
                    class="md:hidden inline-flex items-center justify-center w-9 h-9 border border-neutral-700 hover:border-orange-500 text-neutral-400 hover:text-orange-500 transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                </svg>
            </button>
            <button type="button"
                    data-command-palette-trigger
                    aria-label="Open command palette"
                    title="Search (⌘K)"
                    class="hidden md:inline-flex items-center gap-2 font-mono text-[10px] text-neutral-500 border border-neutral-800 px-3 py-2.5 uppercase tracking-widest hover:border-orange-500 hover:text-orange-500 transition-colors duration-200">
                <span aria-hidden="true">⌘K</span>
                <span class="sr-only">Open command palette</span>
            </button>
            <a href="mailto:{{ config('site.person.email') }}"
               class="hidden sm:inline-flex text-xs font-semibold text-neutral-300 border border-neutral-700 px-5 py-2.5 uppercase tracking-widest hover:border-orange-500 hover:text-orange-500 transition-colors duration-200">
                Get in Touch
            </a>
            <button id="nav-toggle" aria-controls="mobile-menu" aria-expanded="false" aria-label="Toggle navigation"
                    class="md:hidden flex flex-col justify-center items-center w-9 h-9 gap-1.5 border border-neutral-700 hover:border-orange-500 transition-colors shrink-0">
                <span class="block w-4 h-px bg-current" aria-hidden="true"></span>
                <span class="block w-4 h-px bg-current" aria-hidden="true"></span>
                <span class="block w-4 h-px bg-current" aria-hidden="true"></span>
            </button>
        </div>
    </div>
    <div id="mobile-menu" hidden class="md:hidden border-t border-neutral-800 bg-[#080808]/98 backdrop-blur-sm">
        <div class="max-w-6xl mx-auto px-6 py-4 flex flex-col gap-1 font-mono text-xs text-neutral-500 uppercase tracking-widest">
            <a href="/#experience" data-nav-section="experience" class="py-3 border-b border-neutral-800/50 hover:text-orange-500 transition-colors">Experience</a>
            <a href="/#work" data-nav-section="work" class="py-3 border-b border-neutral-800/50 hover:text-orange-500 transition-colors">Work</a>
            <a href="/blog" data-nav-section="writing" class="py-3 border-b border-neutral-800/50 hover:text-orange-500 transition-colors">Writing</a>
            <a href="/#contact" data-nav-section="contact" class="py-3 border-b border-neutral-800/50 hover:text-orange-500 transition-colors">Contact</a>
            <p class="font-mono text-[10px] text-neutral-600 pt-2 pb-1">More</p>
            <a href="/#research" data-nav-section="research" class="py-2 pl-2 border-b border-neutral-800/50 hover:text-orange-500 transition-colors">Research</a>
            <a href="/#stack" data-nav-section="stack" class="py-2 pl-2 border-b border-neutral-800/50 hover:text-orange-500 transition-colors">Stack</a>
            <a href="/#open-source" class="py-2 pl-2 border-b border-neutral-800/50 hover:text-orange-500 transition-colors">Open Source</a>
            <a href="/#certs" data-nav-section="certs" class="py-2 pl-2 border-b border-neutral-800/50 hover:text-orange-500 transition-colors">Certifications</a>
            <button type="button"
                    data-command-palette-trigger
                    class="py-3 text-left hover:text-orange-500 transition-colors">
                Jump to section · ⌘K
            </button>
        </div>
    </div>
</nav>
