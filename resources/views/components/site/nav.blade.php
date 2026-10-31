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
    $items = [
        'work' => ['/work', 'Work'],
        'open-source' => ['/open-source', 'Open Source'],
        'about' => ['/about', 'About'],
        'writing' => ['/blog', 'Writing'],
        'resume' => ['/resume', 'Resume'],
    ];
@endphp

<nav aria-label="Primary" class="fixed top-0 left-0 right-0 z-50 nav-enter">
    <div class="nav-bar site-shell site-gutter flex items-center justify-between gap-4">
        <div class="flex items-center gap-6 lg:gap-10 min-w-0">
            <a href="/" class="brand-lockup shrink-0" @if($isActive('home')) aria-current="page" @endif>
                <span class="brand-lockup__name">Karl Hill</span>
            </a>
            <div class="nav-destinations hidden min-[1180px]:flex items-center gap-4 text-neutral-400">
                @foreach($items as $key => [$href, $label])
                    <a href="{{ $href }}" class="{{ $navLinkClass($key) }}" @if($isActive($key)) aria-current="page" @endif>{{ $label }}</a>
                @endforeach
            </div>
        </div>
        <div class="flex items-center gap-1.5 sm:gap-3 shrink-0">
            <x-site.theme-toggle />

            <a href="/#contact"
               data-nav-section="contact"
               class="masthead__contact hidden min-[1180px]:inline-flex">
                Contact
            </a>

            <button id="nav-toggle" type="button"
                    command="toggle-popover"
                    commandfor="mobile-menu"
                    popovertarget="mobile-menu"
                    aria-controls="mobile-menu" aria-expanded="false" aria-label="Open menu"
                    class="min-[1180px]:hidden flex flex-col justify-center items-center min-h-11 min-w-11 gap-1.5 border border-neutral-700/80 hover:border-accent transition-colors shrink-0">
                <span class="nav-toggle-bar" aria-hidden="true"></span>
                <span class="nav-toggle-bar" aria-hidden="true"></span>
                <span class="nav-toggle-bar" aria-hidden="true"></span>
            </button>
        </div>
    </div>
    <div id="mobile-menu" popover="auto" class="min-[1180px]:hidden border-t border-neutral-800 bg-bg">
        <div class="site-shell site-gutter py-4 pb-[max(2rem,env(safe-area-inset-bottom))] flex flex-col text-neutral-400">
            <div class="flex flex-col divide-y divide-neutral-800/60">
                @foreach($items as $key => [$href, $label])
                    <a href="{{ $href }}" class="{{ $mobileLinkClass($key) }}" @if($isActive($key)) aria-current="page" @endif>{{ $label }}</a>
                @endforeach
                <a href="/#contact" class="{{ $mobileLinkClass('contact') }}">Contact</a>
            </div>

            <div class="mt-8 pt-6 border-t border-neutral-800/60 flex flex-col gap-1">
                <p class="eyebrow">Email directly</p>
                <a href="mailto:{{ $email }}"
                   data-analytics-event="email_clicked"
                   data-analytics-location="mobile-menu"
                   class="inline-flex items-center gap-3 min-h-11 text-sm text-neutral-300 hover:text-accent transition-colors">
                    <span class="truncate">{{ $email }}</span>
                </a>
            </div>
        </div>
    </div>
</nav>
