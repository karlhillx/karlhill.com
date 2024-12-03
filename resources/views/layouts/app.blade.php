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
    <x-meta/>
</head>
<body class="font-sans @yield('body-bg', 'bg-white') antialiased">

<x-header/>

<main class="flex-grow @yield('main-classes')" role="main">
    @yield('content')
</main>

<x-footer/>

<x-back-to-top/>

@stack('scripts')

<x-schema/>
</body>
</html>
