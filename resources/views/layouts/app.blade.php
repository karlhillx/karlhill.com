<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ in_array(app()->getLocale(), ['ar','fa','he','ur']) ? 'rtl' : 'ltr' }}"
    @hasSection('dark-mode') x-data="darkMode" :class="{ 'dark': darkMode }" @endif
    class="scroll-smooth h-full font-sans antialiased"
    @yield('html-attrs')
>
<head>
    <x-meta/>
</head>
<body class="font-sans @yield('body-bg', 'bg-white') antialiased">

<x-header/>

<x-flash-message />

<main class="flex-grow @yield('main-classes')" role="main">
    @yield('content')
</main>

<x-footer/>

<x-back-to-top/>

@stack('scripts')

<x-schema/>
</body>
</html>

