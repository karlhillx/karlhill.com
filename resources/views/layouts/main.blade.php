<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    @hasSection('dark-mode')
    x-data="darkMode"
    :class="{ 'dark': darkMode }"
    class="scroll-smooth"
    @else
    class="h-full font-sans antialiased"
    @endif
>
<head>
    @include('partials.head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans @yield('body-bg', 'bg-white') antialiased">
    <div class="flex flex-col min-h-screen">
        <x-header/>

        <main class="flex-grow @yield('main-classes')" role="main">
            @yield('content')
        </main>

        <x-footer/>
    </div>

    <x-back-to-top/>
    @stack('scripts')
    <x-schema/>
</body>
</html>
