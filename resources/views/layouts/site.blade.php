<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    @php($siteUrl = rtrim(config('app.url', 'https://karlhill.com'), '/'))
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Karl Hill — Staff Software Engineer' }}</title>
    <meta name="description" content="{{ $description ?? config('site.seo.home.description') }}">
    @if($noindex ?? false)
        <meta name="robots" content="noindex">
    @endif

    {{-- Open Graph --}}
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:url" content="{{ $canonical ?? $siteUrl }}">
    <meta property="og:site_name" content="Karl Hill">
    <meta property="og:title" content="{{ $ogTitle ?? ($title ?? 'Karl Hill — Staff Software Engineer') }}">
    <meta property="og:description" content="{{ $ogDescription ?? ($description ?? '') }}">
    <meta property="og:image" content="{{ $ogImage ?? $siteUrl.'/img/og-home.jpg' }}">
    <meta property="og:image:width" content="{{ $ogImageWidth ?? 1200 }}">
    <meta property="og:image:height" content="{{ $ogImageHeight ?? 630 }}">
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
    <meta name="twitter:site" content="@karl_hill">
    <meta name="twitter:creator" content="@karl_hill">
    <meta name="twitter:title" content="{{ $ogTitle ?? ($title ?? 'Karl Hill — Staff Software Engineer') }}">
    <meta name="twitter:description" content="{{ $ogDescription ?? ($description ?? '') }}">
    <meta name="twitter:image" content="{{ $ogImage ?? $siteUrl.'/img/og-home.jpg' }}">

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
    <x-site.analytics />
    @stack('head')
</head>
<body class="bg-bg text-neutral-100 antialiased">

    <div class="scroll-progress" role="progressbar" aria-hidden="true" aria-valuemin="0" aria-valuemax="100"></div>

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
