@props(['activeNav' => null])

@php
    $isActive = static fn (?string $key): bool => filled($key) && $activeNav === $key;
    $navLinkClass = static function (string $key) use ($isActive): string {
        return 'nav-link transition-colors duration-200 '.($isActive($key) ? 'text-accent' : 'hover:text-accent');
    };
    $mobileLinkClass = static function (string $key) use ($isActive): string {
        return 'min-h-11 flex items-center py-3.5 border-b border-neutral-800/50 transition-colors '
            .($isActive($key) ? 'text-accent' : 'hover:text-accent');
    };
@endphp

<nav aria-label="Primary" class="fixed top-0 left-0 right-0 z-50 border-b border-neutral-800/60 bg-bg/90 backdrop-blur-sm nav-enter">
    <div class="nav-bar site-shell site-gutter flex items-center justify-between gap-4">
        <div class="flex items-center gap-6 lg:gap-10 min-w-0">
            <a href="/" class="brand-lockup font-display tracking-wider text-accent shrink-0" style="view-transition-name: brand" @if($isActive('home')) aria-current="page" @endif>
                <x-site.mark :size="28" class="brand-lockup__mark" />
                <span>KARL HILL</span>
            </a>
            <div class="hidden xl:flex items-center gap-5 font-mono text-xs text-neutral-400 uppercase tracking-widest">
                <a href="/work" class="nav-work {{ $navLinkClass('work') }}" @if($isActive('work')) aria-current="page" @endif>Work</a>
                <a href="/about" class="{{ $navLinkClass('about') }}" @if($isActive('about')) aria-current="page" @endif>About</a>
            </div>
        </div>
        <div class="flex items-center gap-1.5 sm:gap-2.5 shrink-0">
            <button type="button"
                    command="toggle-popover"
                    commandfor="command-palette"
                    popovertarget="command-palette"
                    aria-label="Search pages and sections"
                    aria-keyshortcuts="Meta+K Control+K"
                    title="Search pages and sections (⌘K)"
                    data-mod-shortcut-host
                    class="hidden sm:inline-flex items-center justify-center gap-1.5 min-h-11 px-2.5 border border-[color:var(--border-strong)] hover:border-accent text-neutral-400 hover:text-accent transition-colors shrink-0">
                <x-site.icons.search class="w-4 h-4 shrink-0" />
                <kbd class="nav-shortcut hidden lg:inline" data-mod-shortcut aria-hidden="true">⌘K</kbd>
            </button>

            <x-site.theme-toggle />

            <a href="/#contact"
               data-nav-section="contact"
               class="btn-sweep inline-flex items-center min-h-11 font-mono text-caption md:text-xs text-neutral-300 border border-neutral-700 px-3.5 md:px-5 uppercase tracking-widest shrink-0">
                Contact
            </a>

            <button id="nav-toggle" type="button"
                    command="toggle-popover"
                    commandfor="mobile-menu"
                    popovertarget="mobile-menu"
                    aria-controls="mobile-menu" aria-expanded="false" aria-label="Open menu"
                    class="xl:hidden flex flex-col justify-center items-center min-h-11 min-w-11 gap-1.5 border border-[color:var(--border-strong)] hover:border-accent transition-colors shrink-0">
                <span class="nav-toggle-bar" aria-hidden="true"></span>
                <span class="nav-toggle-bar" aria-hidden="true"></span>
                <span class="nav-toggle-bar" aria-hidden="true"></span>
            </button>
        </div>
    </div>
    <div id="mobile-menu" popover="auto" class="xl:hidden border-t border-neutral-800 bg-bg">
        <div class="site-shell site-gutter py-4 pb-[max(2rem,env(safe-area-inset-bottom))] flex flex-col font-mono text-xs text-neutral-400 uppercase tracking-widest">
            <button type="button"
                    command="show-popover"
                    commandfor="command-palette"
                    popovertarget="command-palette"
                    class="mb-3 min-h-11 w-full inline-flex items-center px-3.5 py-2.5 surface-chip border-neutral-700/80 text-neutral-300 hover:text-accent hover:border-accent transition-colors text-left normal-case">
                <span class="inline-flex items-center gap-2.5 font-mono text-xs uppercase tracking-wider">
                    <x-site.icons.search class="w-4 h-4 shrink-0 text-accent" />
                    <span>Search pages &amp; sections</span>
                </span>
            </button>

            <div class="flex flex-col divide-y divide-neutral-800/80">
                <a href="/work" class="nav-work {{ $mobileLinkClass('work') }}" @if($isActive('work')) aria-current="page" @endif>Work</a>
                <a href="/about" class="{{ $mobileLinkClass('about') }}" @if($isActive('about')) aria-current="page" @endif>About</a>
            </div>

        </div>
    </div>
</nav>
