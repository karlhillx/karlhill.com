<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Karl Hill | Full Stack Engineer with 20+ years experience in NASA projects, Laravel enterprise solutions, and AI integration. Proven track record in scalable architecture design and high-performance applications.">
<meta name="keywords" content="Karl Hill, NASA software engineer, Laravel architect, full stack developer, enterprise solutions, PHP expert, Tailwind CSS specialist, AI integration">
<meta name="author" content="Karl Hill">
<meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
<meta name="theme-color" content="#18181b" media="(prefers-color-scheme: dark)">

<!-- Security Headers -->
<meta http-equiv="X-Content-Type-Options" content="nosniff">
<meta http-equiv="Permissions-Policy" content="interest-cohort=()">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="Karl Hill | Full Stack Engineer">
<meta property="og:description" content="Sofware Engineer specializing in Laravel, AI integration, and scalable solutions">
<meta property="og:image" content="{{ asset('img/profile.jpg') }}">
<meta property="og:site_name" content="Karl Hill - Portfolio">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:creator" content="@karlhill">
<meta name="twitter:title" content="Karl Hill | Full Stack Engineer">
<meta name="twitter:description" content="20+ years building enterprise solutions for NASA and beyond">
<meta name="twitter:image" content="{{ asset('img/profile.jpg') }}">

<!-- Technology Stack & Expertise -->
<meta name="technology-stack" content="Laravel {{ app()->version() }}, PHP {{ PHP_VERSION }}, Tailwind CSS v4.0, Vite 6">

<!-- Cache Control -->
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

<title>{{ config('app.name', 'Karl Hill | Full Stack Engineer') }}</title>

<!-- Favicon -->
<link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.svg') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon-16x16.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-touch-icon.png') }}">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">

@vite(['resources/css/app.css', 'resources/js/app.js'])


