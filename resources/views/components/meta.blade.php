@props([
    'title' => config('app.name', 'Karl Hill · Staff Software Engineer · Cloud-Native & DevSecOps'),
    'description' => 'Software Engineer and Agile Leader with 25+ years building aerospace, cloud-native, and enterprise-scale solutions.',
    'image' => asset('img/profile.jpg'),
    'type' => 'website',
    'url' => url()->current(),
    'siteName' => 'Karl Hill · Staff Software Engineer',
    'author' => 'Karl Hill',
    'keywords' => 'Karl Hill, software engineer, aerospace software, cloud-native architecture, DevSecOps, Laravel expert, full stack developer, CI/CD pipelines, Agile leadership, enterprise solutions, NASA projects, principal engineer',
    'twitterCard' => 'summary_large_image',
    'twitterCreator' => '@karlhill',
    'themeColorLight' => '#ffffff',
    'themeColorDark' => '#18181b',
    'noindex' => false,
    'canonical' => null,
    'robots' => null,
])

@php
    $canonicalUrl = $canonical ?? $url;
    $robotsTag = $robots ?? ($noindex ? 'noindex, nofollow' : 'index, follow');
@endphp

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="color-scheme" content="light dark">
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">
<meta name="author" content="{{ $author }}">
<meta name="theme-color" content="{{ $themeColorLight }}" media="(prefers-color-scheme: light)">
<meta name="theme-color" content="{{ $themeColorDark }}" media="(prefers-color-scheme: dark)">
<meta name="robots" content="{{ $robotsTag }}">
<meta name="referrer" content="no-referrer-when-downgrade">

<!-- Security Headers -->
<meta http-equiv="X-Content-Type-Options" content="nosniff">
<meta http-equiv="Permissions-Policy" content="browsing-topics=()">

<!-- CSRF -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Canonical -->
<link rel="canonical" href="{{ $canonicalUrl }}">

<!-- Open Graph -->
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $url }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:image:alt" content="{{ $title }}">
<meta property="og:site_name" content="{{ $siteName }}">

<!-- Twitter Card -->
<meta name="twitter:card" content="{{ $twitterCard }}">
<meta name="twitter:creator" content="{{ $twitterCreator }}">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">

<!-- Technology Stack & Expertise -->
<meta name="technology-stack" content="Laravel {{ app()->version() }}, PHP {{ PHP_VERSION }}, Tailwind CSS, Vite">

<!-- Cache Control -->
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

<title>{{ $title }}</title>

<!-- Favicon -->
<link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.svg') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon-16x16.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-touch-icon.png') }}">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">

@vite(['resources/css/app.css', 'resources/js/app.js'])

{{ $slot ?? '' }}


