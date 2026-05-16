<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    @php($siteUrl = rtrim(config('app.url', 'https://karlhill.com'), '/'))
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Karl Hill — Staff Software Engineer' }}</title>
    <meta name="description" content="{{ $description ?? 'Karl Hill is a Staff Software Engineer with 25+ years of experience.' }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:url" content="{{ $canonical ?? $siteUrl }}">
    <meta property="og:title" content="{{ $ogTitle ?? ($title ?? 'Karl Hill — Staff Software Engineer') }}">
    <meta property="og:description" content="{{ $ogDescription ?? ($description ?? '') }}">
    <meta property="og:image" content="{{ $ogImage ?? $siteUrl.'/img/profile.jpg' }}">
    @if(! ($ogImage ?? false))
    <meta property="og:image:width" content="800">
    <meta property="og:image:height" content="800">
    @endif

    {{-- Twitter / X --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@karl_hill">
    <meta name="twitter:creator" content="@karl_hill">
    <meta name="twitter:title" content="{{ $ogTitle ?? ($title ?? 'Karl Hill — Staff Software Engineer') }}">
    <meta name="twitter:description" content="{{ $ogDescription ?? ($description ?? '') }}">
    <meta name="twitter:image" content="{{ $ogImage ?? $siteUrl.'/img/profile.jpg' }}">

    <meta name="theme-color" content="#080808">

    <link rel="canonical" href="{{ $canonical ?? $siteUrl }}">

    {{-- Favicons --}}
    <link rel="icon" type="image/svg+xml" href="/img/favicon.svg">
    <link rel="icon" type="image/png" sizes="96x96" href="/img/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">

    <link rel="alternate" type="application/atom+xml" title="Karl Hill — Writing" href="/feed.xml">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body class="bg-[#080808] text-neutral-100 antialiased">

    <div class="cursor-spotlight" aria-hidden="true"></div>

    <svg class="grain" aria-hidden="true" preserveAspectRatio="none">
        <filter id="grain-noise">
            <feTurbulence type="fractalNoise" baseFrequency="0.9" numOctaves="2" stitchTiles="stitch"/>
            <feColorMatrix values="0 0 0 0 1
                                   0 0 0 0 1
                                   0 0 0 0 1
                                   0 0 0 0.045 0"/>
        </filter>
        <rect width="100%" height="100%" filter="url(#grain-noise)"/>
    </svg>

    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[100] focus:px-4 focus:py-2 focus:bg-orange-500 focus:text-black focus:font-semibold focus:text-xs focus:uppercase focus:tracking-widest">
        Skip to content
    </a>

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
                        <a href="/#research" data-nav-section="research" class="block px-4 py-2.5 hover:text-orange-500 hover:bg-neutral-900/50 transition-colors {{ ($activeNav ?? null) === 'research' ? 'text-orange-500' : '' }}">Research</a>
                        <a href="/#stack" data-nav-section="stack" class="block px-4 py-2.5 hover:text-orange-500 hover:bg-neutral-900/50 transition-colors {{ ($activeNav ?? null) === 'stack' ? 'text-orange-500' : '' }}">Stack</a>
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
                        class="md:hidden inline-flex items-center justify-center w-9 h-9 border border-neutral-700 hover:border-orange-500 text-neutral-400 hover:text-orange-500 transition-colors shrink-0"
                >
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
                <a href="mailto:karlhillx@gmail.com"
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

    <main id="main-content" class="relative z-10">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @hasSection('page_footer')
        @yield('page_footer')
    @else
    <footer class="relative z-10 border-t border-neutral-800 py-16 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-12">
                <div class="max-w-xl">
                    <p class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-4">Get in Touch</p>
                    <p class="font-display text-[clamp(2rem,5vw,3.5rem)] leading-none tracking-wide mb-4">
                        Let's Work<br>Together
                    </p>
                    <p class="text-neutral-500 text-sm leading-relaxed max-w-sm">
                        Building something important and need an engineer who can lead, architect, and deliver?
                        I'd like to hear about it.
                    </p>
                </div>
                <div class="flex flex-col gap-4 shrink-0">
                    <a href="mailto:karlhillx@gmail.com"
                       class="flex items-center gap-4 font-mono text-sm text-neutral-400 hover:text-orange-500 transition-colors group">
                        <span class="text-orange-500 text-base arrow-nudge" aria-hidden="true">→</span>
                        karlhillx@gmail.com
                    </a>
                    <a href="/files/karlhill-resume.pdf" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-3 border border-neutral-700 text-neutral-300 font-semibold px-6 py-3 text-xs uppercase tracking-widest hover:border-orange-500 hover:text-orange-500 transition-colors duration-200 w-fit">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download Resume
                    </a>

                    <div class="flex items-center gap-5 pt-2">
                        <a href="https://www.linkedin.com/in/khill/" target="_blank" rel="noopener noreferrer"
                           aria-label="LinkedIn" class="text-neutral-500 hover:text-orange-500 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                        <a href="https://github.com/karlhillx" target="_blank" rel="noopener noreferrer"
                           aria-label="GitHub" class="text-neutral-500 hover:text-orange-500 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com/karl_hill/" target="_blank" rel="noopener noreferrer"
                           aria-label="X / Twitter" class="text-neutral-500 hover:text-orange-500 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        <a href="/feed.xml" aria-label="RSS feed"
                           class="text-neutral-500 hover:text-orange-500 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M6.18 15.64a2.18 2.18 0 0 1 2.18 2.18C8.36 19 7.38 20 6.18 20A2.18 2.18 0 0 1 4 17.82a2.18 2.18 0 0 1 2.18-2.18M4 4.44A15.56 15.56 0 0 1 19.56 20h-2.83A12.73 12.73 0 0 0 4 7.27V4.44m0 5.66a9.9 9.9 0 0 1 9.9 9.9h-2.83A7.07 7.07 0 0 0 4 12.93V10.1Z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-neutral-800/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <p class="font-display text-2xl tracking-widest text-neutral-600">Karl Hill</p>
                <p class="font-mono text-xs text-neutral-500">Washington, DC &nbsp;·&nbsp; Staff Software Engineer &nbsp;·&nbsp; 25+ Years</p>
                <p class="font-mono text-xs text-neutral-700">Laravel {{ app()->version() }}</p>
            </div>
        </div>
    </footer>
    @endif

    <button id="quick-back-top" type="button"
            class="quick-back-top font-mono text-[10px] uppercase tracking-widest"
            aria-label="Back to top">
        ↑ Top
    </button>

    <div id="command-palette" class="command-palette hidden" role="dialog" aria-modal="true" aria-labelledby="command-palette-title">
        <div class="command-palette-panel">
            <h2 id="command-palette-title" class="sr-only">Command palette</h2>
            <input id="command-input" type="text"
                   class="command-input font-mono"
                   placeholder="Jump to a section…"
                   role="combobox"
                   aria-expanded="true"
                   aria-controls="command-results"
                   aria-autocomplete="list"
                   autocomplete="off"
                   spellcheck="false">
            <div id="command-results" class="command-results mt-3" role="listbox" aria-label="Commands"></div>
        </div>
    </div>
</body>
</html>
