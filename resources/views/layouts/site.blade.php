<!DOCTYPE html>
<html lang="en" class="scroll-smooth"
      data-features="{{ implode(' ', $pageFeatures ?? []) }}"
      @if(! app()->isProduction()) data-sw="off" @endif
      @if(filled(config('site.push.public_key')))
          data-vapid-public="{{ config('site.push.public_key') }}"
      @endif
>
<head>
    @php($siteUrl = \App\Support\PageMeta::siteUrl())
    @php($defaultTitle = config('site.seo.home.title'))
    @php($twitterHandle = config('site.person.twitter_handle'))
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? $defaultTitle }}</title>
    <meta name="description" content="{{ $description ?? config('site.seo.home.description') }}">
    @if(filled(config('site.seo.google_site_verification')))
        <meta name="google-site-verification" content="{{ config('site.seo.google_site_verification') }}">
    @endif
    @if($noindex ?? false)
        <meta name="robots" content="noindex">
    @endif

    {{-- Open Graph --}}
    @php($ogImageUrl = $ogImage ?? $siteUrl.'/img/og-home.jpg')
    @php($ogPath = strtolower((string) parse_url($ogImageUrl, PHP_URL_PATH)))
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:url" content="{{ $canonical ?? $siteUrl }}">
    <meta property="og:site_name" content="Karl Hill">
    <meta property="og:locale" content="en_US">
    <meta property="og:title" content="{{ $ogTitle ?? ($title ?? $defaultTitle) }}">
    <meta property="og:description" content="{{ $ogDescription ?? ($description ?? '') }}">
    <meta property="og:image" content="{{ $ogImageUrl }}">
    <meta property="og:image:type" content="{{ str_ends_with($ogPath, '.png') ? 'image/png' : (str_ends_with($ogPath, '.webp') ? 'image/webp' : 'image/jpeg') }}">
    @if($ogImageAlt ?? null)
        <meta property="og:image:alt" content="{{ $ogImageAlt }}">
    @endif
    @if(($ogImageWidth ?? null) && ($ogImageHeight ?? null))
        <meta property="og:image:width" content="{{ $ogImageWidth }}">
        <meta property="og:image:height" content="{{ $ogImageHeight }}">
    @endif
    <link rel="image_src" href="{{ $ogImageUrl }}">
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
    <meta name="twitter:image" content="{{ $ogImageUrl }}">

    <meta name="theme-color" content="#0d1520">
    <meta name="color-scheme" content="dark">
    {{-- Pre-paint theme pin: dark unless a stored light preference exists. --}}
    <script nonce="{{ Vite::cspNonce() }}">
        (() => {
            let theme = 'dark';
            try {
                if (localStorage.getItem('theme') === 'light') theme = 'light';
            } catch {}
            document.documentElement.dataset.theme = theme;
            const color = theme === 'light' ? '#f4f6f8' : '#0d1520';
            document.querySelector('meta[name="theme-color"]')?.setAttribute('content', color);
            document.querySelector('meta[name="color-scheme"]')?.setAttribute('content', theme);
        })();
    </script>
    @if($canonical ?? null)
        <link rel="canonical" href="{{ $canonical }}">
    @endif

    {{-- Preload site fonts before CSS parses so display, body, and mono
         faces are ready on first paint. Paths live in PreloadLinks. --}}
    @foreach(\App\Support\PreloadLinks::fontUrls() as $fontUrl)
        <link rel="preload" as="font" type="font/woff2" href="{{ $fontUrl }}" crossorigin>
    @endforeach

    {{-- Listing favicon = rocket. Google Search uses these square ≥48px
         files (one per hostname). The portrait lives on Person JSON-LD. --}}
    @php($iconV = filemtime(public_path('img/favicon.svg')))
    <link rel="icon" href="/img/favicon.svg?v={{ $iconV }}" type="image/svg+xml">
    <link rel="icon" href="/favicon.ico" sizes="48x48">
    <link rel="icon" type="image/png" sizes="48x48" href="/img/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/img/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/img/android-chrome-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png?v={{ $iconV }}">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="alternate" type="application/atom+xml" title="Karl Hill — Writing" href="/feed.xml">
    <link rel="alternate" type="application/feed+json" title="Karl Hill — Writing (JSON Feed)" href="/feed.json">
    <link rel="describedby" href="/llms.txt">
    <link rel="alternate" type="application/json" title="Karl Hill — Hire packet" href="/api/site.json">
    <link rel="author" href="/.well-known/mcp.json">
    <link rel="alternate" type="application/json" title="Karl Hill — Agent card" href="/.well-known/agent-card.json">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/print.css') }}" media="print">
    <link rel="stylesheet" href="{{ asset('css/progressive.css') }}?v={{ filemtime(public_path('css/progressive.css')) }}">
    <x-site.analytics />
    @stack('head')
</head>
<body class="bg-bg text-neutral-100 antialiased">

    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[100] focus:px-4 focus:py-2 focus:bg-accent focus:text-black focus:font-semibold focus:text-xs focus:uppercase focus:tracking-widest">
        Skip to content
    </a>

    <x-site.nav :active-nav="$activeNav ?? null" />

    <main id="main-content" class="relative z-10">
        @yield('content')
    </main>

    @hasSection('page_footer')
        @yield('page_footer')
    @else
        <x-site.footer />
    @endif

    @php($toastStatus = session('status'))
    @if(in_array($toastStatus, ['contact-sent', 'contact-failed'], true))
        <div id="site-toast"
             class="site-toast {{ $toastStatus === 'contact-failed' ? 'site-toast--error' : 'site-toast--success' }}"
             role="{{ $toastStatus === 'contact-failed' ? 'alert' : 'status' }}"
             data-toast
             data-toast-duration="5200"
             style="--toast-duration: 5200ms">
            @if($toastStatus === 'contact-sent')
                <p class="font-mono text-xs uppercase tracking-widest">
                    Thanks — message sent. I'll reply from {{ config('site.person.email') }}.
                </p>
            @else
                <p class="font-mono text-xs uppercase tracking-widest normal-case">
                    Couldn't send that. Email me at
                    <a href="mailto:{{ config('site.person.email') }}" class="underline">{{ config('site.person.email') }}</a>.
                </p>
            @endif
            <button type="button" class="site-toast__dismiss" data-toast-dismiss aria-label="Dismiss">×</button>
            <span class="site-toast__progress" aria-hidden="true"></span>
        </div>
    @endif

</body>
</html>
