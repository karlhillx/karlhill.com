<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    @php($siteUrl = rtrim(config('app.url', 'https://karlhill.com'), '/'))
    @php($defaultTitle = config('site.seo.home.title'))
    @php($twitterHandle = config('site.person.twitter_handle'))
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? $defaultTitle }}</title>
    <meta name="description" content="{{ $description ?? config('site.seo.home.description') }}">
    @if($noindex ?? false)
        <meta name="robots" content="noindex">
    @endif

    {{-- Open Graph --}}
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:url" content="{{ $canonical ?? $siteUrl }}">
    <meta property="og:site_name" content="Karl Hill">
    <meta property="og:locale" content="en_US">
    <meta property="og:title" content="{{ $ogTitle ?? ($title ?? $defaultTitle) }}">
    <meta property="og:description" content="{{ $ogDescription ?? ($description ?? '') }}">
    <meta property="og:image" content="{{ $ogImage ?? $siteUrl.'/img/og-home.jpg' }}">
    @if($ogImageAlt ?? null)
        <meta property="og:image:alt" content="{{ $ogImageAlt }}">
    @endif
    @if(($ogImageWidth ?? null) && ($ogImageHeight ?? null))
        <meta property="og:image:width" content="{{ $ogImageWidth }}">
        <meta property="og:image:height" content="{{ $ogImageHeight }}">
    @endif
    @if(($ogType ?? 'website') === 'article')
        @if($articlePublishedTime ?? null)
            <meta property="article:published_time" content="{{ $articlePublishedTime }}">
        @endif
        @if($articleModifiedTime ?? null)
            <meta property="article:modified_time" content="{{ $articleModifiedTime }}">
        @endif
        @if($articleAuthor ?? null)
            <meta property="article:author" content="{{ $articleAuthor }}">
        @endif
    @endif

    {{-- Twitter / X --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="{{ $twitterHandle }}">
    <meta name="twitter:creator" content="{{ $twitterHandle }}">
    <meta name="twitter:title" content="{{ $ogTitle ?? ($title ?? $defaultTitle) }}">
    <meta name="twitter:description" content="{{ $ogDescription ?? ($description ?? '') }}">
    <meta name="twitter:image" content="{{ $ogImage ?? $siteUrl.'/img/og-home.jpg' }}">

    <meta name="theme-color" content="#080808">
    <meta name="color-scheme" content="dark">
    @if($canonical ?? null)
        <link rel="canonical" href="{{ $canonical }}">
    @endif

    {{-- Preload the hero/display font (Bebas Neue) — it renders the LCP element,
         so fetching it before the CSS parses shaves first-paint latency. --}}
    <link rel="preload" as="font" type="font/woff2" href="{{ Vite::asset('resources/fonts/bebas-neue-latin-400-normal.woff2') }}" crossorigin>

    {{-- Favicons --}}
    <link rel="icon" type="image/png" sizes="96x96" href="/img/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="alternate" type="application/atom+xml" title="Karl Hill — Writing" href="/feed.xml">
    <link rel="alternate" type="text/markdown" title="Karl Hill — LLM-friendly overview" href="/llms.txt">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <x-site.analytics />
    <script id="command-index" type="application/json" nonce="{{ Vite::cspNonce() }}">
{!! json_encode($commandIndex, JSON_UNESCAPED_SLASHES) !!}
    </script>
    @stack('head')
</head>
<body class="bg-bg text-neutral-100 antialiased">

    <div class="scroll-progress" aria-hidden="true"></div>

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
       class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[100] focus:px-4 focus:py-2 focus:bg-accent focus:text-black focus:font-semibold focus:text-xs focus:uppercase focus:tracking-widest">
        Skip to content
    </a>

    <x-site.nav :active-nav="$activeNav ?? null" />

    @isset($sectionRail)
        <x-site.section-rail :sections="$sectionRail" />
    @endisset

    <main id="main-content" class="relative z-10">
        @yield('content')
    </main>

    @hasSection('page_footer')
        @yield('page_footer')
    @else
        <x-site.footer />
    @endif

    <button id="quick-back-top" type="button"
            class="quick-back-top font-mono text-[10px] uppercase tracking-widest"
            aria-label="Back to top">
        ↑ Top
    </button>

    <div id="command-palette" popover="auto" class="command-palette" aria-label="Command palette">
        <input id="command-input" type="text"
               class="command-input font-mono"
               placeholder="Jump to a section…"
               aria-label="Search commands"
               role="combobox"
               aria-expanded="false"
               aria-controls="command-results"
               aria-autocomplete="list"
               autocomplete="off"
               spellcheck="false">
        <div id="command-results" class="command-results mt-3" role="listbox" aria-label="Commands"></div>
        <div class="command-hint" aria-hidden="true">
            <span><kbd>↑</kbd><kbd>↓</kbd> navigate</span>
            <span><kbd>↵</kbd> select</span>
            <span><kbd>esc</kbd> close</span>
        </div>
    </div>
</body>
</html>
