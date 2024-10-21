<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth"
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
      :class="{ 'dark': darkMode }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
          content="Full-stack engineer passionate about building robust web solutions. I leverage PHP, Laravel, Tailwind CSS, Alpine, and Vite to translate ideas into functional, user-friendly applications.">
    <meta name="author" content="Karl Hill">
    <meta name="technology-stack"
          content="Laravel v{{ app()->version() }}, PHP v{{ PHP_VERSION }}, Tailwind CSS v4.0, Vite">
    <title>{{ config('app.name', 'Karl Hill | Full Stack Engineer') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">
    @vite('resources/css/app.css')
</head>
<body class="font-sans
      bg-maroon-dream
      dark:bg-light-bg
      text-gray-900
      dark:text-light-text
      antialiased"
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))">
<div class="flex flex-col min-h-screen">
    @include('partials.header')

    <main class="flex-grow">
        <section id="hero" class="bg-hero-pattern min-h-screen w-full pt-48 overflow-hidden px-0 text-white dark:text-gray-100">
            <div class="max-w-7xl mx-auto space-y-6 px-4 py-5 sm:flex sm:space-y-0 sm:space-x-10 sm:px-6 lg:px-8">
                <div class="container mx-auto py-6">
                    <div x-data="{ show: false }" x-intersect="show = true">
                        <h2 x-show="show" x-transition:enter="transition ease-out duration-1200"
                            x-transition:enter-start="opacity-0 transform translate-x-8"
                            x-transition:enter-end="opacity-100 transform translate-x-0"
                            class="text-4xl font-extrabold tracking-tight sm:text-5xl">Karl Hill</h2>
                    </div>
                    <div x-data="{ show: false }" x-intersect="show = true">
                        <h2 x-show="show" x-transition:enter="transition ease-out duration-1100"
                            x-transition:enter-start="opacity-0 transform translate-x-8"
                            x-transition:enter-end="opacity-100 transform translate-x-0"
                            class="text-4xl font-extrabold tracking-tight sm:text-5xl mt-2 mb-3">Full Stack Engineer</h2>
                    </div>
                    <div x-data="{ show: false }" x-intersect="show = true">
                        <h6 x-show="show" x-transition:enter="transition ease-out duration-1000"
                            x-transition:enter-start="opacity-0 transform translate-x-8"
                            x-transition:enter-end="opacity-100 transform translate-x-0"
                            class="text-lg sm:text-xl uppercase font-medium tracking-tight mt-2 mb-3">
                            PHP, Laravel, Tailwind, Alpine, Vite
                        </h6>
                    </div>

                    <div x-data="{ show: false }" x-intersect="show = true">
                        <div x-show="show" x-transition:enter="transition ease-out duration-1000"
                             x-transition:enter-start="opacity-0 transform translate-y-8"
                             x-transition:enter-end="opacity-100 transform translate-y-0">
                    <span class="relative inline-flex rounded-md shadow-sm">
                      <a href="/portfolio" type="button"
                         class="hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 inline-flex items-center px-4 py-2 border-2 border-white dark:border-gray-300 text-base leading-6 font-medium rounded-md text-white dark:text-gray-100 focus:border-white transition ease-in-out duration-150">
                        Portfolio
                      </a>
                      <span class="flex absolute h-3 w-3 top-0 right-0 -mt-1 -mr-1">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-300 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-400"></span>
                      </span>
                    </span>
                            <a href="{{ asset('files/karlhill-resume.pdf') }}" role="button" target="_blank"
                               class="inline-flex items-center px-4 py-2 border-solid border-2 border-white dark:border-gray-300 text-base font-medium rounded-md shadow-sm text-white dark:text-gray-100 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                Resume
                            </a>
                            <a href="#feature"
                               class="inline-flex items-center px-4 py-2 border-solid border-2 border-white dark:border-gray-300 text-base font-medium rounded-md shadow-sm text-white dark:text-gray-100 hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Learn More
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="feature" class="bg-white px-0">
            <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
                <div class="bg-orange-500 rounded-lg shadow-xl overflow-hidden lg:grid lg:grid-cols-2 lg:gap-4">
                    <div class="pt-10 pb-12 px-6 sm:pt-16 sm:px-16 lg:py-16 lg:pr-0 xl:py-20 xl:px-20">
                        <div class="lg:self-center">
                            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                                <span class="block">Why you should hire me.</span>
                            </h2>
                            <p class="mt-4 text-lg leading-6 text-white">
                                With over two decades of experience, I've spearheaded the development and launch of
                                innovative tech solutions within Agile environments. I excel at leading distributed
                                teams to deliver results quickly, possessing both the coding skills for complex projects
                                and the design eye for compelling user interfaces. I'm passionate about staying ahead of
                                the curve, learning new technologies, and contributing to product development—whether
                                working solo or collaborating with a team. My professional approach combines motivation,
                                attention to detail, creativity, and strong communication skills.
                            </p>
                        </div>
                    </div>
                    <div class="-mt-6 aspect-w-5 aspect-h-3 md:aspect-w-2 md:aspect-h-1 lg:self-end">
                        <img loading="lazy"
                             class="transform rounded-md object-cover lg:object-right-bottom"
                             src="/img/bg-drl-screenshot.png" alt="App screenshot">
                    </div>
                </div>
            </div>
        </section>

        <div id="map" class="bg-gray-200 border-t border-yellow-400">
            <iframe class="w-full" style="height: 45vh;"
                    loading="lazy"
                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA1U9kPUx3SN0u33kKiaCSXl7plnfA3y8Q&q=Brightwood+Park,+Washington,+DC+20011"
                    allowfullscreen>
            </iframe>
        </div>
    </main>

    @include('partials.footer')
</div>

@include('partials.scripts')
</body>
</html>
