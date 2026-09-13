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
    $bookingUrl = config('site.booking.url');
    $bookingLabel = config('site.booking.label');
    $linkedin = collect(config('site.social'))->first(fn ($link) => ($link['icon'] ?? '') === 'linkedin');
@endphp

<nav aria-label="Primary" class="fixed top-0 left-0 right-0 z-50 border-b border-neutral-800/60 bg-bg/90 backdrop-blur-sm nav-enter">
    <div class="nav-bar site-shell site-gutter flex items-center justify-between gap-4">
        <div class="flex items-center gap-6 lg:gap-10 min-w-0">
            <a href="/" class="brand-lockup font-display tracking-wider text-accent shrink-0" style="view-transition-name: brand" @if($isActive('home')) aria-current="page" @endif>
                <x-site.mark :size="28" class="brand-lockup__mark" />
                <span>KARL HILL</span>
            </a>
            {{-- Hire path: Work → Kit → Writing. About from lg. Book is the CTA. --}}
            <div class="hidden md:flex items-center gap-5 lg:gap-7 font-mono text-xs text-neutral-500 uppercase tracking-widest">
                <a href="/work" class="{{ $navLinkClass('work') }}" @if($isActive('work')) aria-current="page" @endif>Work</a>
                <a href="/kit" class="{{ $navLinkClass('kit') }}" @if($isActive('kit')) aria-current="page" @endif>Kit</a>
                <a href="/blog" class="{{ $navLinkClass('writing') }}" @if($isActive('writing')) aria-current="page" @endif>Writing</a>
                <a href="/about" class="max-lg:hidden {{ $navLinkClass('about') }}" @if($isActive('about')) aria-current="page" @endif>About</a>
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
                    class="hidden lg:inline-flex items-center justify-center gap-1.5 min-h-11 px-2.5 border border-[color:var(--border-strong)] hover:border-accent text-neutral-400 hover:text-accent transition-colors shrink-0">
                <x-site.icons.search class="w-4 h-4 shrink-0" />
                <kbd class="nav-shortcut" data-mod-shortcut aria-hidden="true">⌘K</kbd>
            </button>

            @if(filled($bookingUrl))
                <a href="/now#book"
                   data-analytics-event="booking_cta_clicked"
                   data-analytics-location="nav"
                   class="btn-accent-fill inline-flex items-center min-h-11 font-mono text-caption lg:text-xs px-3.5 lg:px-5 uppercase tracking-widest shrink-0"
                   aria-label="{{ $bookingLabel }}">
                    <span class="lg:hidden">Book</span>
                    <span class="hidden lg:inline">{{ $bookingLabel }}</span>
                </a>
            @else
                <a href="/#contact"
                   data-nav-section="contact"
                   class="btn-sweep inline-flex items-center min-h-11 font-mono text-caption md:text-xs text-neutral-300 border border-neutral-700 px-3.5 md:px-5 uppercase tracking-widest shrink-0">
                    Contact
                </a>
            @endif

            <button id="nav-toggle" type="button"
                    command="toggle-popover"
                    commandfor="mobile-menu"
                    popovertarget="mobile-menu"
                    aria-controls="mobile-menu" aria-expanded="false" aria-label="Open menu"
                    class="md:hidden flex flex-col justify-center items-center min-h-11 min-w-11 gap-1.5 border border-[color:var(--border-strong)] hover:border-accent transition-colors shrink-0">
                <span class="nav-toggle-bar" aria-hidden="true"></span>
                <span class="nav-toggle-bar" aria-hidden="true"></span>
                <span class="nav-toggle-bar" aria-hidden="true"></span>
            </button>
        </div>
    </div>
    <div id="mobile-menu" popover="auto" class="md:hidden border-t border-neutral-800 bg-bg">
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
                <a href="/work" class="{{ $mobileLinkClass('work') }}" @if($isActive('work')) aria-current="page" @endif>Work</a>
                <a href="/kit" class="{{ $mobileLinkClass('kit') }}" @if($isActive('kit')) aria-current="page" @endif>Kit</a>
                <a href="/blog" class="{{ $mobileLinkClass('writing') }}" @if($isActive('writing')) aria-current="page" @endif>Writing</a>
                <a href="/about" class="{{ $mobileLinkClass('about') }}" @if($isActive('about')) aria-current="page" @endif>About</a>
                <a href="/resume" class="{{ $mobileLinkClass('resume') }}" @if($isActive('resume')) aria-current="page" @endif>Resume</a>
            </div>

            <div class="pt-4 mt-2 border-t border-neutral-800/80 flex flex-wrap items-center gap-x-6 gap-y-2">
                @if($linkedin)
                    <a href="{{ $linkedin['url'] }}" target="_blank" rel="me noopener noreferrer"
                       class="min-h-11 inline-flex items-center text-neutral-400 hover:text-accent transition-colors">
                        LinkedIn ↗
                    </a>
                @endif
                <a href="mailto:{{ config('site.person.email') }}"
                   class="min-h-11 inline-flex items-center text-neutral-400 hover:text-accent transition-colors normal-case">
                    {{ config('site.person.email') }}
                </a>
            </div>
        </div>
    </div>
</nav>
