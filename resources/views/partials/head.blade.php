<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="description" content="Karl Hill | Full Stack Engineer with 20+ years experience in NASA projects, Laravel enterprise solutions, and AI integration. Proven track record in scalable architecture design and high-performance applications.">
<meta name="keywords" content="Karl Hill, NASA software engineer, Laravel architect, full stack developer, enterprise solutions, PHP expert, Tailwind CSS specialist, AI integration">
<meta name="author" content="Karl Hill">
<meta name="robots" content="index, follow">
<meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
<meta name="theme-color" content="#18181b" media="(prefers-color-scheme: dark)">

<!-- Preconnect to Critical Origins -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- Open Graph / Social Media -->
<meta property="og:title" content="Karl Hill | Full Stack Engineer">
<meta property="og:description" content="Sofwate Engineer specializing in Laravel, AI integration, and scalable solutions">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ asset('img/profile.jpg') }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="Karl Hill - Full Stack Engineer">
<meta property="og:site_name" content="Karl Hill - Portfolio">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:creator" content="@karlhill">
<meta name="twitter:title" content="Karl Hill | Full Stack Engineer">
<meta name="twitter:description"  content="Sofwate Engineer specializing in Laravel, AI integration, and scalable solutions">
<meta name="twitter:image" content="{{ asset('img/profile.jpg') }}">

<!-- Technology Stack & Expertise -->
<meta name="technology-stack" content="Laravel {{ app()->version() }}, PHP {{ PHP_VERSION }}, Tailwind CSS v4.0, Vite 6">
<meta name="expertise" content="Enterprise Architecture, NASA Projects, AI Integration, Scalable Solutions">

<!-- DNS Prefetch -->
<link rel="dns-prefetch" href="//fonts.googleapis.com">
<link rel="dns-prefetch" href="//fonts.gstatic.com">

<title>{{ config('app.name', 'Karl Hill | Full Stack Engineer') }}</title>

<!-- Favicon -->
<link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.svg') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon-16x16.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-touch-icon.png') }}">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">

<!-- Canonical -->
<link rel="canonical" href="{{ url()->current() }}">

<!-- Preload Critical Assets -->
<link rel="preload" href="{{ asset('fonts/inter.woff2') }}" as="font" type="font/woff2" crossorigin>

@vite('resources/css/app.css')
