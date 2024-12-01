<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="darkMode"
    :class="{ 'dark': darkMode }"
    class="scroll-smooth">
<head>
    @include('partials.head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-maroon-dream dark:bg-maroon-dream text-gray-900 dark:text-white antialiased">
    <div class="flex flex-col min-h-screen">
        <x-header/>

        <main class="flex-grow" role="main">
            <x-landing/>
            <x-feature/>
            <x-skills/>
            <x-core-competencies/>
            <x-map/>
        </main>

        <x-footer/>
    </div>

    <x-back-to-top/>

    @stack('scripts')
    <x-schema/>
</body>
</html>
