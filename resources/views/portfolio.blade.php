<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="framework" content="Laravel v{{ app()->version() }}">
    <meta name="php-version" content="{{ PHP_VERSION }}">
    <title>{{ config('app.name', 'Karl Hill | Full Stack Engineer') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="antialiased font-sans bg-white">

@include('partials.header')

<section>

    <div class="" style="">
        <div class="bg-white px-4 py-12">

            <div class="max-w-md mx-auto sm:max-w-3xl">
                <div>
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                             viewBox="0 0 48 48" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A10.003 10.003 0 0124 26c4.21 0 7.813 2.602 9.288 6.286M30 14a6 6 0 11-12 0 6 6 0 0112 0zm12 6a4 4 0 11-8 0 4 4 0 018 0zm-28 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <h2 class="mt-2 text-lg font-medium text-gray-900">Portfolio</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            A curated list of projects I've worked on over the years in professional settings.
                        </p>
                    </div>
                </div>
                <div class="mt-10">
                    <ul class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                        <li>
                            <a type="button" href="#direct-readout"
                               class="group p-2 w-full flex items-center justify-between rounded-full border border-gray-300 shadow-sm space-x-3 text-left hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                          <span class="min-w-0 flex-1 flex items-center space-x-3">
                            <span class="block flex-shrink-0">
                              <img class="h-10 w-10 rounded-full" src="img/logo-nasa.png" alt="">
                            </span>
                            <span class="block min-w-0 flex-1">
                              <span class="block text-sm font-medium text-gray-900 truncate">NASA Direct Readout Laboratory</span>
                              <span class="block text-sm font-medium text-gray-500 truncate">directreadout.sci.gsfc.nasa.gov</span>
                            </span>
                          </span>
                                <span class="flex-shrink-0 h-10 w-10 inline-flex items-center justify-center">
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-500"
                                 x-description="Heroicon name: solid/plus"
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                 aria-hidden="true">
                                  <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                        clip-rule="evenodd"></path>
                            </svg>
                            </span>
                            </a>
                        </li>

                        <li>
                            <button type="button"
                                    class="group p-2 w-full flex items-center justify-between rounded-full border border-gray-300 shadow-sm space-x-3 text-left hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              <span class="min-w-0 flex-1 flex items-center space-x-3">
                <span class="block flex-shrink-0">
                   <img class="h-10 w-10 rounded-full" src="img/logo-informeddna.png" alt="">
                </span>
                <span class="block min-w-0 flex-1">
                  <span class="block text-sm font-medium text-gray-900 truncate">InformedDNA</span>
                  <span class="block text-sm font-medium text-gray-500 truncate">informeddna.com</span>
                </span>
              </span>
                                <span class="flex-shrink-0 h-10 w-10 inline-flex items-center justify-center">
                <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: solid/plus"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
  <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
        clip-rule="evenodd"></path>
</svg>
              </span>
                            </button>
                        </li>

                        <li>
                            <a href="#earth-observatory" type="button"
                               class="group p-2 w-full flex items-center justify-between rounded-full border border-gray-300 shadow-sm space-x-3 text-left hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              <span class="min-w-0 flex-1 flex items-center space-x-3">
                <span class="block flex-shrink-0">
                      <img class="h-10 w-10 rounded-full"
                           src="img/logo-earth-observatory.png"
                           alt="">
                </span>
                <span class="block min-w-0 flex-1">
                  <span class="block text-sm font-medium text-gray-900 truncate">NASA Earth Observatory</span>
                  <span class="block text-sm font-medium text-gray-500 truncate">earthobservatory.nasa.gov</span>
                </span>
              </span>
                                <span class="flex-shrink-0 h-10 w-10 inline-flex items-center justify-center">
                <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: solid/plus"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
  <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
        clip-rule="evenodd"></path>
</svg>
              </span>
                            </a>
                        </li>

                        <li>
                            <button type="button"
                                    class="group p-2 w-full flex items-center justify-between rounded-full border border-gray-300 shadow-sm space-x-3 text-left hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              <span class="min-w-0 flex-1 flex items-center space-x-3">
                <span class="block flex-shrink-0">
                  <img class="h-10 w-10 rounded-full" src="img/logo-verizon.png" alt="">
                </span>
                <span class="block min-w-0 flex-1">
                  <span class="block text-sm font-medium text-gray-900 truncate">Verizon Business</span>
                  <span class="block text-sm font-medium text-gray-500 truncate">verizon.com</span>
                </span>
              </span>
                                <span class="flex-shrink-0 h-10 w-10 inline-flex items-center justify-center">
                <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: solid/plus"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
  <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
        clip-rule="evenodd"></path>
</svg>
              </span>
                            </button>
                        </li>

                        <li>
                            <button type="button"
                                    class="group p-2 w-full flex items-center justify-between rounded-full border border-gray-300 shadow-sm space-x-3 text-left hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              <span class="min-w-0 flex-1 flex items-center space-x-3">
                <span class="block flex-shrink-0">
                             <img class="h-10 w-10 rounded-full" src="img/logo-nyc-mentors.png" alt="">
                </span>
                <span class="block min-w-0 flex-1">
                  <span class="block text-sm font-medium text-gray-900 truncate">NYC Mentors, Inc.</span>
                  <span class="block text-sm font-medium text-gray-500 truncate">nycmentors.org</span>
                </span>
              </span>
                                <span class="flex-shrink-0 h-10 w-10 inline-flex items-center justify-center">
                <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: solid/plus"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
  <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
        clip-rule="evenodd"></path>
</svg>
              </span>
                            </button>
                        </li>

                        <li>
                            <button type="button"
                                    class="group p-2 w-full flex items-center justify-between rounded-full border border-gray-300 shadow-sm space-x-3 text-left hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                  <span class="min-w-0 flex-1 flex items-center space-x-3">
                                    <span class="block flex-shrink-0">
                                 <img class="h-10 w-10 rounded-full" src="img/logo-comcast-xfinity.jpg" alt="">
                                    </span>
                                    <span class="block min-w-0 flex-1">
                                      <span class="block text-sm font-medium text-gray-900 truncate">Comcast</span>
                                      <span class="block text-sm font-medium text-gray-500 truncate">comcast.com</span>
                                    </span>
                                  </span>
                                <span class="flex-shrink-0 h-10 w-10 inline-flex items-center justify-center">
                <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: solid/plus"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
  <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
        clip-rule="evenodd"></path>
</svg>
              </span>
                            </button>
                        </li>

                    </ul>
                </div>
            </div>

        </div>
    </div>

</section>

<section id="direct-readout" class="flex items-center justify-center h-screen">
    <div class="max-w-2xl transition-colors ease-linear rounded-lg border-0 shadow-md p-10">
        <div
            class="w-full h-10 rounded-t-lg bg-gray-200 dark:bg-gray-900 flex justify-start items-center space-x-1.5 px-4">
            <span
                class="w-3 h-3 border-2 border-transparent dark:border-red-400 rounded-full bg-red-400 dark:bg-transparent"></span>
            <span
                class="w-3 h-3 border-2 border-transparent dark:border-yellow-400 rounded-full bg-yellow-400 dark:bg-transparent"></span>
            <span
                class="w-3 h-3 border-2 border-transparent dark:border-green-400 rounded-full bg-green-400 dark:bg-transparent"></span>
        </div>
        <div class="bg-gray-100 dark:bg-gray-700 border-t-0 w-full h-96 rounded-b-lg">
            <img src="/img/ss-direct-readout.png" alt="">
        </div>
        <div class="">
            <div class="mt-4 flex">
                <a href="https://directreadout.sci.gsfc.nasa.gov/" target="_blank"
                   class="text-sm font-semibold text-gray-400 hover:text-blue-500">NASA Direct Readout Laboratory<span
                        aria-hidden="true"> →</span></a>
            </div>
        </div>
    </div>
</section>

<section id="earth-observatory" class="flex items-center justify-center h-screen">
    <div class="max-w-2xl transition-colors ease-linear rounded-lg border-0 shadow-md p-10">
        <div
            class="w-full h-10 rounded-t-lg bg-gray-200 dark:bg-gray-900 flex justify-start items-center space-x-1.5 px-4">
            <span
                class="w-3 h-3 border-2 border-transparent dark:border-red-400 rounded-full bg-red-400 dark:bg-transparent"></span>
            <span
                class="w-3 h-3 border-2 border-transparent dark:border-yellow-400 rounded-full bg-yellow-400 dark:bg-transparent"></span>
            <span
                class="w-3 h-3 border-2 border-transparent dark:border-green-400 rounded-full bg-green-400 dark:bg-transparent"></span>
        </div>
        <div class="bg-gray-100 dark:bg-gray-700 border-t-0 w-full h-96 rounded-b-lg">
            <img src="/img/ss-earth-observatory.png" alt="">
        </div>
        <div class="">
            <div class="mt-4 flex">
                <a href="https://earthobservatory.nasa.gov/" target="_blank"
                   class="text-sm font-semibold text-gray-400 hover:text-blue-500">NASA Earth Observatory<span
                        aria-hidden="true"> →</span></a>
            </div>
        </div>
    </div>
</section>

@include('partials.footer')

<script src="{{ mix('js/app.js') }}"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-85045253-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());
    gtag('config', 'UA-85045253-1');

    AOS.init();
</script>
</body>
</html>
