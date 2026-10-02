@props(['activeNav' => null])

@php
    $isActive = static fn (?string $key): bool => filled($key) && $activeNav === $key;
    $navLinkClass = static function (string $key) use ($isActive): string {
        return 'nav-link transition-colors duration-200 '.($isActive($key) ? 'text-accent' : 'hover:text-accent');
    };
    $mobileLinkClass = static function (string $key) use ($isActive): string {
        return 'nav-mobile-link min-h-11 flex items-center py-3.5 transition-colors '
            .($isActive($key) ? 'text-accent' : 'hover:text-accent');
    };
    $email = config('site.person.email');
@endphp

<nav aria-label="Primary" class="fixed top-0 left-0 right-0 z-50 border-b border-neutral-800/50 bg-bg/90 backdrop-blur-sm nav-enter">
    <div class="nav-bar site-shell site-gutter flex items-center justify-between gap-4">
        <div class="flex items-center gap-6 lg:gap-8 min-w-0">
            <a href="/" class="brand-lockup font-display tracking-wider text-accent shrink-0" style="view-transition-name: brand" @if($isActive('home')) aria-current="page" @endif>
                <x-site.mark :size="28" class="brand-lockup__mark" />
                <span>KARL HILL</span>
            </a>
            <div class="hidden lg:flex items-center gap-5 font-sans text-[0.8125rem] font-medium text-neutral-400 uppercase tracking-wide">
                <a href="/work" class="{{ $navLinkClass('work') }}" @if($isActive('work')) aria-current="page" @endif>Work</a>
                <a href="/blog" class="{{ $navLinkClass('writing') }}" @if($isActive('writing')) aria-current="page" @endif>Writing</a>
                <a href="/about" class="{{ $navLinkClass('about') }}" @if($isActive('about')) aria-current="page" @endif>About</a>
            </div>
        </div>
        <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
            <button type="button"
                    command="toggle-popover"
                    commandfor="command-palette"
                    popovertarget="command-palette"
                    aria-label="Search pages and sections"
                    aria-keyshortcuts="Meta+K Control+K"
                    title="Search pages and sections (⌘K)"
                    data-mod-shortcut-host
                    class="hidden sm:inline-flex items-center justify-center gap-1.5 min-h-11 px-2.5 border border-neutral-700/80 hover:border-accent text-neutral-400 hover:text-accent transition-colors shrink-0">
                <x-site.icons.search class="w-4 h-4 shrink-0" />
                <kbd class="nav-shortcut hidden lg:inline" data-mod-shortcut aria-hidden="true">⌘K</kbd>
            </button>

            <x-site.theme-toggle />

            <a href="/resume" class="hidden lg:inline-flex items-center min-h-11 px-2 font-sans text-[0.8125rem] font-medium text-neutral-400 hover:text-accent uppercase tracking-wide"
               @if($isActive('resume')) aria-current="page" @endif>Resume</a>

            <a href="/#contact"
               data-nav-section="contact"
               class="btn-base btn-sweep text-neutral-300 border border-neutral-700/80 px-3.5 md:px-4 shrink-0">
                Contact
            </a>

            <button id="nav-toggle" type="button"
                    command="toggle-popover"
                    commandfor="mobile-menu"
                    popovertarget="mobile-menu"
                    aria-controls="mobile-menu" aria-expanded="false" aria-label="Open menu"
                    class="lg:hidden flex flex-col justify-center items-center min-h-11 min-w-11 gap-1.5 border border-neutral-700/80 hover:border-accent transition-colors shrink-0">
                <span class="nav-toggle-bar" aria-hidden="true"></span>
                <span class="nav-toggle-bar" aria-hidden="true"></span>
                <span class="nav-toggle-bar" aria-hidden="true"></span>
            </button>
        </div>
    </div>
    <div id="mobile-menu" popover="auto" class="lg:hidden border-t border-neutral-800 bg-bg">
        <div class="site-shell site-gutter py-4 pb-[max(2rem,env(safe-area-inset-bottom))] flex flex-col font-sans text-[0.8125rem] font-medium text-neutral-400 uppercase tracking-wide">
            <button type="button"
                    command="show-popover"
                    commandfor="command-palette"
                    popovertarget="command-palette"
                    class="mb-3 min-h-11 w-full inline-flex items-center px-3.5 py-2.5 surface-chip border-neutral-700/80 text-neutral-300 hover:text-accent hover:border-accent transition-colors text-left normal-case tracking-normal">
                <span class="inline-flex items-center gap-2.5">
                    <x-site.icons.search class="w-4 h-4 shrink-0 text-accent" />
                    <span>Search pages &amp; sections</span>
                </span>
            </button>

            <div class="flex flex-col divide-y divide-neutral-800/60">
                <a href="/work" class="{{ $mobileLinkClass('work') }}" @if($isActive('work')) aria-current="page" @endif>Work</a>
                <a href="/blog" class="{{ $mobileLinkClass('writing') }}" @if($isActive('writing')) aria-current="page" @endif>Writing</a>
                <a href="/about" class="{{ $mobileLinkClass('about') }}" @if($isActive('about')) aria-current="page" @endif>About</a>
                <a href="/resume" class="{{ $mobileLinkClass('resume') }}" @if($isActive('resume')) aria-current="page" @endif>Resume</a>
            </div>

            <div class="mt-8 pt-6 border-t border-neutral-800/60 flex flex-col gap-1 normal-case tracking-normal">
                <p class="eyebrow eyebrow--faint">Email directly</p>
                <a href="mailto:{{ $email }}"
                   data-analytics-event="email_clicked"
                   data-analytics-location="mobile-menu"
                   class="inline-flex items-center gap-3 min-h-11 font-mono text-sm text-neutral-300 hover:text-accent transition-colors">
                    <span class="text-accent arrow-nudge" aria-hidden="true">→</span>
                    <span class="truncate">{{ $email }}</span>
                </a>
            </div>

        </div>
    </div>
</nav>
