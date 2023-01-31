<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full font-sans antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="framework" content="Laravel v{{ app()->version() }}">
    <meta name="php-version" content="{{ PHP_VERSION }}">
    <title>{{ config('app.name', 'Karl Hill | Full Stack Engineer') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">
    @vite('resources/css/app.css')
</head>
<body class="font-sans bg-white">

@include('partials.header')

<section id="portfolio1">

    <main>

        <div class="sm:px-8 mt-4 pb-10">
            <div class="mx-auto max-w-7xl lg:px-8">
                <div class="relative px-4 sm:px-8 lg:px-12">
                    <div class="mx-auto max-w-2xl lg:max-w-5xl">

                        <div class="max-w-2xl"><h1
                                class="text-4xl font-bold tracking-tight text-zinc-800 dark:text-zinc-100 sm:text-5xl">
                                Software engineer, volunteer, and musician.</h1>
                            <p class="mt-6 text-base text-zinc-600 dark:text-zinc-400">I’m Karl, a full-stack software
                                engineer at <a href="https://www.nasa.gov/goddard/">NASA Goddard Space Flight Center</a>.
                                I am responsible for building and maintaining some of the agency’s
                                best internal and external web applications. Besides working for NASA, I volunteer for
                                <a href="https://americanart.si.edu/visit/renwick" target="_blank">
                                    The Renwick Gallery
                                </a>, a branch museum of the
                                <a href="https://americanart.si.edu/" target="_blank">
                                    Smithsonian American Art Museum</a>. I also
                                volunteer for several other non-profit organizations. In my free time, I enjoy playing
                                music.
                            </p>
                            <div class="mt-6 flex gap-6">
                                <a class="group -m-1 p-1" aria-label="Follow on Twitter"
                                   href="ttps://twitter.com/karl_hill" target="_blank">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"
                                         class="h-6 w-6 fill-zinc-500 transition group-hover:fill-zinc-600 dark:fill-zinc-400 dark:group-hover:fill-zinc-300">
                                        <path
                                            d="M20.055 7.983c.011.174.011.347.011.523 0 5.338-3.92 11.494-11.09 11.494v-.003A10.755 10.755 0 0 1 3 18.186c.308.038.618.057.928.058a7.655 7.655 0 0 0 4.841-1.733c-1.668-.032-3.13-1.16-3.642-2.805a3.753 3.753 0 0 0 1.76-.07C5.07 13.256 3.76 11.6 3.76 9.676v-.05a3.77 3.77 0 0 0 1.77.505C3.816 8.945 3.288 6.583 4.322 4.737c1.98 2.524 4.9 4.058 8.034 4.22a4.137 4.137 0 0 1 1.128-3.86A3.807 3.807 0 0 1 19 5.274a7.657 7.657 0 0 0 2.475-.98c-.29.934-.9 1.729-1.713 2.233A7.54 7.54 0 0 0 22 5.89a8.084 8.084 0 0 1-1.945 2.093Z"></path>
                                    </svg>
                                </a><a class="group -m-1 p-1" aria-label="Follow on GitHub"
                                       href="https://github.com/karlhillx" target="_blank">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"
                                         class="h-6 w-6 fill-zinc-500 transition group-hover:fill-zinc-600 dark:fill-zinc-400 dark:group-hover:fill-zinc-300">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M12 2C6.475 2 2 6.588 2 12.253c0 4.537 2.862 8.369 6.838 9.727.5.09.687-.218.687-.487 0-.243-.013-1.05-.013-1.91C7 20.059 6.35 18.957 6.15 18.38c-.113-.295-.6-1.205-1.025-1.448-.35-.192-.85-.667-.013-.68.788-.012 1.35.744 1.538 1.051.9 1.551 2.338 1.116 2.912.846.088-.666.35-1.115.638-1.371-2.225-.256-4.55-1.14-4.55-5.062 0-1.115.387-2.038 1.025-2.756-.1-.256-.45-1.307.1-2.717 0 0 .837-.269 2.75 1.051.8-.23 1.65-.346 2.5-.346.85 0 1.7.115 2.5.346 1.912-1.333 2.75-1.05 2.75-1.05.55 1.409.2 2.46.1 2.716.637.718 1.025 1.628 1.025 2.756 0 3.934-2.337 4.806-4.562 5.062.362.32.675.936.675 1.897 0 1.371-.013 2.473-.013 2.82 0 .268.188.589.688.486a10.039 10.039 0 0 0 4.932-3.74A10.447 10.447 0 0 0 22 12.253C22 6.588 17.525 2 12 2Z"></path>
                                    </svg>
                                </a><a class="group -m-1 p-1" aria-label="Follow on LinkedIn"
                                       href="https://www.linkedin.com/in/khill/" target="_blank">
                                    <svg viewBox="0 0 24 24"
                                         class="h-6 w-6 fill-zinc-500 transition group-hover:fill-zinc-600 dark:fill-zinc-400 dark:group-hover:fill-zinc-300">
                                        <path
                                            d="M18.335 18.339H15.67v-4.177c0-.996-.02-2.278-1.39-2.278-1.389 0-1.601 1.084-1.601 2.205v4.25h-2.666V9.75h2.56v1.17h.035c.358-.674 1.228-1.387 2.528-1.387 2.7 0 3.2 1.778 3.2 4.091v4.715zM7.003 8.575a1.546 1.546 0 01-1.548-1.549 1.548 1.548 0 111.547 1.549zm1.336 9.764H5.666V9.75H8.34v8.589zM19.67 3H4.329C3.593 3 3 3.58 3 4.297v15.406C3 20.42 3.594 21 4.328 21h15.338C20.4 21 21 20.42 21 19.703V4.297C21 3.58 20.4 3 19.666 3h.003z"></path>
                                    </svg>

                                    <p>
                                        <a href="{{ route('welcome'); }}" class="inline-block rounded-lg px-4 py-1.5 text-base font-semibold leading-7
                           text-gray-900 ring-1 ring-gray-900/10 hover:ring-gray-900/20">
                                            <span class="text-gray-400" aria-hidden="true">←</span>
                                            Go Back
                                        </a>
                                    </p>
                                </a></div>

                        </div>


                    </div>
                </div>
            </div>
        </div>
        <div class="mt-16 sm:mt-20">
            <div class="-my-4 flex justify-center gap-5 overflow-hidden py-4 sm:gap-8">
                <div
                    class="relative aspect-[9/10] w-44 flex-none overflow-hidden rounded-xl bg-zinc-100 dark:bg-zinc-800 sm:w-72 sm:rounded-2xl -rotate-2">
                    <a href="https://earthobservatory.nasa.gov" target="_blank">
                        <img alt="" sizes="(min-width: 640px) 18rem, 11rem"
                             src="/img/small-earth-observatory.png"
                             width="3936" height="2624" decoding="async" data-nimg="future"
                             class="absolute inset-0 h-full w-full object-cover" loading="lazy"
                             style="color:transparent">
                    </a>
                </div>
                <div
                    class="relative aspect-[9/10] w-44 flex-none overflow-hidden rounded-xl bg-zinc-100 dark:bg-zinc-800 sm:w-72 sm:rounded-2xl -rotate-2">
                    <img alt="" sizes="(min-width: 640px) 18rem, 11rem"
                         src="/img/small-informeddna.png"
                         width="4240" height="2384" decoding="async" data-nimg="future"
                         class="absolute inset-0 h-full w-full object-cover" loading="lazy" style="color:transparent">
                </div>
                <div
                    class="relative aspect-[9/10] w-44 flex-none overflow-hidden rounded-xl bg-zinc-100 dark:bg-zinc-800 sm:w-72 sm:rounded-2xl rotate-2">
                    <img alt="" sizes="(min-width: 640px) 18rem, 11rem"
                         src="/img/small-direct-readout.png"
                         width="3744" height="5616" decoding="async" data-nimg="future"
                         class="absolute inset-0 h-full w-full object-cover" loading="lazy" style="color:transparent">
                </div>
                <div
                    class="relative aspect-[9/10] w-44 flex-none overflow-hidden rounded-xl bg-zinc-100 dark:bg-zinc-800 sm:w-72 sm:rounded-2xl rotate-2">
                    <a href="#"><img alt="" sizes="(min-width: 640px) 18rem, 11rem"
                                     src="/img/small-flood.png"
                                     width="5760" height="3840" decoding="async" data-nimg="future"
                                     class="absolute inset-0 h-full w-full object-cover" loading="lazy"
                                     style="color:transparent"></a>
                </div>
                <div
                    class="relative aspect-[9/10] w-44 flex-none overflow-hidden rounded-xl bg-zinc-100 dark:bg-zinc-800 sm:w-72 sm:rounded-2xl rotate-2">
                    <img alt="" sizes="(min-width: 640px) 18rem, 11rem"
                         src="/img/small-mci-verizon.png"
                         width="2400" height="3000" decoding="async" data-nimg="future"
                         class="absolute inset-0 h-full w-full object-cover" loading="lazy" style="color:transparent">
                </div>
            </div>
        </div>
        <div class="sm:px-8 mt-24 md:mt-28">
            <div class="mx-auto max-w-7xl lg:px-8">
                <div class="relative px-4 sm:px-8 lg:px-12">
                    <div class="mx-auto max-w-2xl lg:max-w-5xl">
                        <div class="mx-auto grid max-w-xl grid-cols-1 gap-y-20 lg:max-w-none lg:grid-cols-2">
                            <div class="flex flex-col gap-16">


                                <article class="group relative flex flex-col items-start">
                                    <h2 class="text-base font-semibold tracking-tight text-zinc-800 dark:text-zinc-100">
                                        <div
                                            class="absolute -inset-y-6 -inset-x-4 z-0 scale-95 bg-zinc-50 opacity-0 transition group-hover:scale-100 group-hover:opacity-100 dark:bg-zinc-800/50 sm:-inset-x-6 sm:rounded-2xl"></div>
                                        <a href="https://laravel-livewire.com/" target="_blank"><span
                                                class="absolute -inset-y-6 -inset-x-4 z-20 sm:-inset-x-6 sm:rounded-2xl"></span><span
                                                class="relative z-10">Livewire + Inertia</span></a>
                                    </h2>
                                    <time
                                        class="relative z-10 order-first mb-3 flex items-center text-sm text-orange-400 dark:text-zinc-500 pl-3.5"
                                        datetime="2022-07-14"><span class="absolute inset-y-0 left-0 flex items-center"
                                                                    aria-hidden="true"><span
                                                class="h-4 w-0.5 rounded-full bg-zinc-200 dark:bg-zinc-500"></span></span>December
                                        20, 2022
                                    </time>
                                    <p class="relative z-10 mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                                        If you're a Laravel developer, you may be interested in using Laravel Livewire
                                        and Inertia to build interactive and dynamic web applications. Livewire is a
                                        full-stack framework that allows you to create dynamic, reactive components
                                        using PHP. In contrast, Inertia enables you to use those components on the front
                                        end with minimal JavaScript. Together, these tools can make building modern,
                                        responsive web applications with minimal code easier. Livewire and Inertia are
                                        easy to learn and use, and they can be a great addition to your toolkit as a
                                        Laravel developer. Give them a try and see how they can improve your workflow.
                                    </p>

                                </article>


                                <article class="group relative flex flex-col items-start">
                                    <h2 class="text-base font-semibold tracking-tight text-zinc-800 dark:text-zinc-100">
                                        <a href="https://laravel-vite.dev/" target="_blank">
                                            <div
                                                class="absolute -inset-y-6 -inset-x-4 z-0 scale-95 bg-zinc-50 opacity-0 transition group-hover:scale-100 group-hover:opacity-100 dark:bg-zinc-800/50 sm:-inset-x-6 sm:rounded-2xl"></div>
                                            <div>
                                            <span
                                                class="absolute -inset-y-6 -inset-x-4 z-20 sm:-inset-x-6 sm:rounded-2xl"></span><span
                                                    class="relative z-10">Transitioning from Laravel Mix to Vite</span>
                                            </div>
                                        </a>
                                    </h2>
                                    <time
                                        class="relative z-10 order-first mb-3 flex items-center text-sm text-orange-400 dark:text-zinc-500 pl-3.5"
                                        datetime="2022-09-05"><span class="absolute inset-y-0 left-0 flex items-center"
                                                                    aria-hidden="true"><span
                                                class="h-4 w-0.5 rounded-full bg-zinc-200 dark:bg-zinc-500"></span></span>September
                                        5, 2022
                                    </time>
                                    <p class="relative z-10 mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                                        Are you tired of dealing with the slow build times and complicated configuration
                                        of Laravel Mix? If so, it might be time to consider transitioning to Vite.

                                        Vite is a modern frontend build tool that provides an extremely fast development
                                        environment and bundles your code for production.

                                        When building applications
                                        with Laravel, you will typically use Vite to bundle your application's CSS and
                                        JavaScript files into production ready assets.

                                        Laravel integrates seamlessly with Vite by providing an official plugin and
                                        Blade directives to load your assets for development and production.
                                    </p>
                                </article>

                                <article class="group relative flex flex-col items-start">
                                    <h2 class="text-base font-semibold tracking-tight text-zinc-800 dark:text-zinc-100">
                                        <div
                                            class="absolute -inset-y-6 -inset-x-4 z-0 scale-95 bg-zinc-50 opacity-0 transition group-hover:scale-100 group-hover:opacity-100 dark:bg-zinc-800/50 sm:-inset-x-6 sm:rounded-2xl"></div>
                                        <a href="https://tailwindcss.com/" target="_blank"><span
                                                class="absolute -inset-y-6 -inset-x-4 z-20 sm:-inset-x-6 sm:rounded-2xl"></span><span
                                                class="relative z-10">Rewriting interfaces with TailwindCSS</span></a>
                                    </h2>
                                    <time
                                        class="relative z-10 order-first mb-3 flex items-center text-sm text-orange-400 dark:text-zinc-500 pl-3.5"
                                        datetime="2022-07-14"><span class="absolute inset-y-0 left-0 flex items-center"
                                                                    aria-hidden="true"><span
                                                class="h-4 w-0.5 rounded-full bg-zinc-200 dark:bg-zinc-500"></span></span>February
                                        14, 2022
                                    </time>
                                    <p class="relative z-10 mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                                        If you're tired of the same old UI designs and want to give your interface a
                                        fresh, modern look, consider rewriting it with TailwindCSS.
                                        When I first came across <a href="https://tailwindcss.com/" target="_blank">TailwindCSS</a>,
                                        I was amazed by the framework's simplicity. I rewrote my interfaces in a matter
                                        of minutes with efficiency and maintainability, leaving me more time to focus on
                                        the core of my applications. I have since used TailwindCSS in all of my new
                                        projects.
                                    </p>

                                </article>

                                <article class="group relative flex flex-col items-start">
                                    <h2 class="text-base font-semibold tracking-tight text-zinc-800 dark:text-zinc-100">
                                        <div
                                            class="absolute -inset-y-6 -inset-x-4 z-0 scale-95 bg-zinc-50 opacity-0 transition group-hover:scale-100 group-hover:opacity-100 dark:bg-zinc-800/50 sm:-inset-x-6 sm:rounded-2xl"></div>
                                        <a href="https://www.karlhill.com" target="_blank">
                                            <span
                                                class="absolute -inset-y-6 -inset-x-4 z-20 sm:-inset-x-6 sm:rounded-2xl">
                                            </span>
                                            <span
                                                class="relative z-10">Introducing my personal portfolio
                                            </span>
                                        </a>
                                    </h2>
                                    <time
                                        class="relative z-10 order-first mb-3 flex items-center text-sm text-orange-400 dark:text-zinc-500 pl-3.5"
                                        datetime="2022-09-02"><span class="absolute inset-y-0 left-0 flex items-center"
                                                                    aria-hidden="true"><span
                                                class="h-4 w-0.5 rounded-full bg-zinc-200 dark:bg-zinc-500"></span></span>January
                                        2, 2022
                                    </time>
                                    <p class="relative z-10 mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                                        I am professionally showcasing my portfolio, highlighting the breadth of my
                                        experience and the depth of my skills across multiple industries and verticals,
                                        establishing myself as a go-to resource for new business opportunities.
                                    </p>
                                </article>

                            </div>
                            <div class="space-y-10 lg:pl-16 xl:pl-24">
                                <div class="rounded-2xl border border-zinc-100 p-6 dark:border-zinc-700/40">
                                    <h2 class="flex text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round"
                                             stroke-linejoin="round" aria-hidden="true" class="h-6 w-6 flex-none">
                                            <path
                                                d="M2.75 9.75a3 3 0 0 1 3-3h12.5a3 3 0 0 1 3 3v8.5a3 3 0 0 1-3 3H5.75a3 3 0 0 1-3-3v-8.5Z"
                                                class="fill-zinc-100 stroke-zinc-400 dark:fill-zinc-100/10 dark:stroke-zinc-500"></path>
                                            <path
                                                d="M3 14.25h6.249c.484 0 .952-.002 1.316.319l.777.682a.996.996 0 0 0 1.316 0l.777-.682c.364-.32.832-.319 1.316-.319H21M8.75 6.5V4.75a2 2 0 0 1 2-2h2.5a2 2 0 0 1 2 2V6.5"
                                                class="stroke-zinc-400 dark:stroke-zinc-500"></path>
                                        </svg>
                                        <span class="ml-3">Work</span></h2>
                                    <ol class="mt-6 space-y-4">
                                        <li class="flex gap-4">
                                            <div
                                                class="relative mt-1 flex h-10 w-10 flex-none items-center justify-center rounded-full shadow-md shadow-zinc-800/5 ring-1 ring-zinc-900/5 dark:border dark:border-zinc-700/50 dark:bg-zinc-800 dark:ring-0">
                                                <a href="https://www.nasa.gov/" target="_blank"><img alt=""
                                                                                                     src="/img/logo-nasa.png"
                                                                                                     width="32"
                                                                                                     height="32"
                                                                                                     decoding="async"
                                                                                                     data-nimg="future"
                                                                                                     class="h-7 w-7"
                                                                                                     loading="lazy"
                                                                                                     style="color:transparent">
                                                </a>
                                            </div>
                                            <dl class="flex flex-auto flex-wrap gap-x-2">
                                                <dt class="sr-only">Company</dt>
                                                <dd class="w-full flex-none text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                    <a href="https://www.nasa.gov/" target="_blank">NASA</a>
                                                </dd>
                                                <dt class="sr-only">Role</dt>
                                                <dd class="text-xs text-zinc-500 dark:text-zinc-400">
                                                    Senior Full Stack Engineer
                                                </dd>
                                                <dt class="sr-only">Date</dt>
                                                <dd class="ml-auto text-xs text-zinc-400 dark:text-zinc-500"
                                                    aria-label="2019 until Present">
                                                    <time datetime="2019">2017</time>
                                                    <span aria-hidden="true">—</span>
                                                    <time datetime="2022">Present</time>
                                                </dd>
                                            </dl>
                                        </li>
                                        <li class="flex gap-4">
                                            <div
                                                class="relative mt-1 flex h-10 w-10 flex-none items-center justify-center rounded-full shadow-md shadow-zinc-800/5 ring-1 ring-zinc-900/5 dark:border dark:border-zinc-700/50 dark:bg-zinc-800 dark:ring-0">
                                                <a href="https://informeddna.com/" target="_blank">
                                                    <img alt="" src="/img/logo-informeddna.png" width="28"
                                                         height="28" decoding="async" data-nimg="future" class="h-7 w-7"
                                                         loading="lazy" style="color:transparent">
                                                </a>
                                            </div>
                                            <dl class="flex flex-auto flex-wrap gap-x-2">
                                                <dt class="sr-only">Company</dt>
                                                <dd class="w-full flex-none text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                    <a href="https://informeddna.com/" target="_blank">InformedDNA</a>
                                                </dd>
                                                <dt class="sr-only">Role</dt>
                                                <dd class="text-xs text-zinc-500 dark:text-zinc-400">
                                                    Senior Software Engineer, Laravel
                                                </dd>
                                                <dt class="sr-only">Date</dt>
                                                <dd class="ml-auto text-xs text-zinc-400 dark:text-zinc-500"
                                                    aria-label="2014 until 2019">
                                                    <time datetime="2014">2016</time>
                                                    <span aria-hidden="true">—</span>
                                                    <time datetime="2019">2017</time>
                                                </dd>
                                            </dl>
                                        </li>
                                        <li class="flex gap-4">
                                            <div
                                                class="relative mt-1 flex h-10 w-10 flex-none items-center justify-center rounded-full shadow-md shadow-zinc-800/5 ring-1 ring-zinc-900/5 dark:border dark:border-zinc-700/50 dark:bg-zinc-800 dark:ring-0">
                                                <a href="https://www.ticomix.com/" target="_blank">
                                                    <img alt="" src="/img/logo-ticomix-small.png" width="28"
                                                         height="28" decoding="async" data-nimg="future" class="h-7 w-7"
                                                         loading="lazy" style="color:transparent">
                                                </a>
                                            </div>
                                            <dl class="flex flex-auto flex-wrap gap-x-2">
                                                <dt class="sr-only">Company</dt>
                                                <dd class="w-full flex-none text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                    <a href="https://www.ticomix.com/" target="_blank">Ticomix</a>
                                                </dd>
                                                <dt class="sr-only">Role</dt>
                                                <dd class="text-xs text-zinc-500 dark:text-zinc-400">
                                                    Senior Software Engineer, CRM
                                                </dd>
                                                <dt class="sr-only">Date</dt>
                                                <dd class="ml-auto text-xs text-zinc-400 dark:text-zinc-500"
                                                    aria-label="2012 until 2015">
                                                    <time datetime="2012">2012</time>
                                                    <span aria-hidden="true">—</span>
                                                    <time datetime="2015">2015</time>
                                                </dd>
                                            </dl>
                                        </li>
                                        <li class="flex gap-4">
                                            <div
                                                class="relative mt-1 flex h-10 w-10 flex-none items-center justify-center rounded-full shadow-md shadow-zinc-800/5 ring-1 ring-zinc-900/5 dark:border dark:border-zinc-700/50 dark:bg-zinc-800 dark:ring-0">
                                                <a href="https://www.sabre.com/" target="_blank">
                                                    <img alt="" src="/img/logo-sabre-small.png" width="28"
                                                         height="28" decoding="async" data-nimg="future" class="h-7 w-7"
                                                         loading="lazy" style="color:transparent">
                                                </a>
                                            </div>
                                            <dl class="flex flex-auto flex-wrap gap-x-2">
                                                <dt class="sr-only">Company</dt>
                                                <dd class="w-full flex-none text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                    <a href="https://www.sabre.com/" target="_blank">Sabre
                                                        Corporation</a>
                                                </dd>
                                                <dt class="sr-only">Role</dt>
                                                <dd class="text-xs text-zinc-500 dark:text-zinc-400">
                                                    Software Engineer
                                                </dd>
                                                <dt class="sr-only">Date</dt>
                                                <dd class="ml-auto text-xs text-zinc-400 dark:text-zinc-500"
                                                    aria-label="2010 until 2012">
                                                    <time datetime="2010">2010</time>
                                                    <span aria-hidden="true">—</span>
                                                    <time datetime="2012">2012</time>
                                                </dd>
                                            </dl>
                                        </li>
                                        <li class="flex gap-4">
                                            <div
                                                class="relative mt-1 flex h-10 w-10 flex-none items-center justify-center rounded-full shadow-md shadow-zinc-800/5 ring-1 ring-zinc-900/5 dark:border dark:border-zinc-700/50 dark:bg-zinc-800 dark:ring-0">
                                                <a href="https://www.danteinc.com/" target="_blank">
                                                    <img alt="" src="/img/logo-dante-small.png" width="28"
                                                         height="28" decoding="async" data-nimg="future" class="h-7 w-7"
                                                         loading="lazy" style="color:transparent">
                                                </a>
                                            </div>
                                            <dl class="flex flex-auto flex-wrap gap-x-2">
                                                <dt class="sr-only">Company</dt>
                                                <dd class="w-full flex-none text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                    <a href="https://www.danteinc.com/" target="_blank">Dante Inc.</a>
                                                </dd>
                                                <dt class="sr-only">Role</dt>
                                                <dd class="text-xs text-zinc-500 dark:text-zinc-400">
                                                    Software Engineer
                                                </dd>
                                                <dt class="sr-only">Date</dt>
                                                <dd class="ml-auto text-xs text-zinc-400 dark:text-zinc-500"
                                                    aria-label="2007 until 2010">
                                                    <time datetime="2007">2007</time>
                                                    <span aria-hidden="true">—</span>
                                                    <time datetime="2010">2010</time>
                                                </dd>
                                            </dl>
                                        </li>
                                        <li class="flex gap-4">
                                            <div
                                                class="relative mt-1 flex h-10 w-10 flex-none items-center justify-center rounded-full shadow-md shadow-zinc-800/5 ring-1 ring-zinc-900/5 dark:border dark:border-zinc-700/50 dark:bg-zinc-800 dark:ring-0">
                                                <a href="https://www.verizon.com/business/" target="_blank">
                                                    <img alt="" src="/img/logo-verizon-small.png" width="28"
                                                         height="28" decoding="async" data-nimg="future" class="h-7 w-7"
                                                         loading="lazy" style="color:transparent">
                                                </a>
                                            </div>
                                            <dl class="flex flex-auto flex-wrap gap-x-2">
                                                <dt class="sr-only">Company</dt>
                                                <dd class="w-full flex-none text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                    <a href="https://www.verizon.com/business/" target="_blank">Verizon
                                                        Business</a>
                                                </dd>
                                                <dt class="sr-only">Role</dt>
                                                <dd class="text-xs text-zinc-500 dark:text-zinc-400">Software Developer
                                                </dd>
                                                <dt class="sr-only">Date</dt>
                                                <dd class="ml-auto text-xs text-zinc-400 dark:text-zinc-500"
                                                    aria-label="2008 until 2011">
                                                    <time datetime="1999">1999</time>
                                                    <span aria-hidden="true">—</span>
                                                    <time datetime="2005">2005</time>
                                                </dd>
                                            </dl>
                                        </li>
                                    </ol>
                                    <a href="{{ asset('files/karlhill-resume.pdf') }}" role="button" target="_blank"
                                       class="inline-flex items-center gap-2 justify-center rounded-md py-2 px-3 text-sm outline-offset-2 transition active:transition-none bg-zinc-50 font-medium text-zinc-900 hover:bg-zinc-100 active:bg-zinc-100 active:text-zinc-900/60 dark:bg-zinc-800/50 dark:text-zinc-300 dark:hover:bg-zinc-800 dark:hover:text-zinc-50 dark:active:bg-zinc-800/50 dark:active:text-zinc-50/70 group mt-6 w-full">Download
                                        CV
                                        <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"
                                             class="h-4 w-4 stroke-zinc-400 transition group-active:stroke-zinc-600 dark:group-hover:stroke-zinc-50 dark:group-active:stroke-zinc-50">
                                            <path d="M4.75 8.75 8 12.25m0 0 3.25-3.5M8 12.25v-8.5" stroke-width="1.5"
                                                  stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </a></div>

                                <form action="/contact" method="post"
                                      class="flex flex-col gap-4 rounded-2xl border border-zinc-100 p-6 dark:border-zinc-700/40 mb-20">
                                    @csrf
                                    <h2 class="flex text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round"
                                             stroke-linejoin="round" aria-hidden="true" class="h-6 w-6 flex-none">
                                            <path
                                                d="M2.75 7.75a3 3 0 0 1 3-3h12.5a3 3 0 0 1 3 3v8.5a3 3 0 0 1-3 3H5.75a3 3 0 0 1-3-3v-8.5Z"
                                                class="fill-zinc-100 stroke-zinc-400 dark:fill-zinc-100/10 dark:stroke-zinc-500"></path>
                                            <path d="m4 6 6.024 5.479a2.915 2.915 0 0 0 3.952 0L20 6"
                                                  class="stroke-zinc-400 dark:stroke-zinc-500"></path>
                                        </svg>
                                        <span class="ml-3">Stay up to date</span></h2>
                                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Get notified when I publish
                                        something new, and unsubscribe at any time.</p>
                                    <div class="mt-6 flex">
                                        <input type="email" name="email" placeholder="Email address"
                                               aria-label="Email address" required
                                               class="min-w-0 flex-auto appearance-none rounded-md border border-zinc-900/10 bg-white px-3 py-[calc(theme(spacing.2)-1px)] shadow-md shadow-zinc-800/5 placeholder:text-zinc-400 focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 dark:border-zinc-700 dark:bg-zinc-700/[0.15] dark:text-zinc-200 dark:placeholder:text-zinc-500 dark:focus:border-teal-400 dark:focus:ring-teal-400/10 sm:text-sm">
                                        <button
                                            class="inline-flex items-center gap-2 justify-center rounded-md py-2 px-3 text-sm outline-offset-2 transition active:transition-none bg-zinc-800 font-semibold text-zinc-100 hover:bg-zinc-700 active:bg-zinc-800 active:text-zinc-100/70 dark:bg-zinc-700 dark:hover:bg-zinc-600 dark:active:bg-zinc-700 dark:active:text-zinc-100/70 ml-4 flex-none"
                                            type="submit">Join
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="relative bg-gray-50 pb-20 pt-20 pl-5 pr-5">
        <div class="absolute inset-0">
            <div class="bg-white h-1/3 sm:h-2/3"></div>
        </div>
        <div class="relative max-w-7xl mx-auto">
            <div class="text-center">
                <div>
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                             viewBox="0 0 48 48" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A10.003 10.003 0 0124 26c4.21 0 7.813 2.602 9.288 6.286M30 14a6 6 0 11-12 0 6 6 0 0112 0zm12 6a4 4 0 11-8 0 4 4 0 018 0zm-28 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
                <h2 class="text-3xl tracking-tight font-bold text-gray-900 sm:text-4xl sm:tracking-tight">Portfolio</h2>
                <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                    A brief, curated list of projects I've worked on over the years in professional settings.
                </p>
            </div>
            <div class="mt-12 max-w-lg mx-auto grid gap-5 lg:grid-cols-3 lg:max-w-none">
                <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                    <div class="flex-shrink-0">
                        <img class="h-48 w-full object-cover"
                             src="/img/ss-esccor.png"
                             alt="">
                    </div>
                    <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-indigo-600">
                                <a href="#" class="hover:underline"> Application </a>
                            </p>
                            <a href="#" class="block mt-2">
                                <p class="text-xl font-semibold text-gray-900">
                                    <a href="https://www.earthdata.nasa.gov/esds" target="_blank">
                                        Earth Science Data Systems
                                    </a>
                                </p>
                                <p class="mt-3 text-base text-gray-500">
                                    NASA's ESDS program oversees the lifecycle of NASA's Earth science data — from
                                    acquisition through processing and distribution. ESDS aims to maximize the
                                    scientific return from NASA's missions for research and applied scientists,
                                    decision-makers, and society at large.
                                </p>
                            </a>
                            <a href="#">
                                <div class="font-bold tracking-tight text-sm text-blue-400 mt-4 border-blue-400
                                        rounded border uppercase px-2 py-1 inline-block
                                        hover:text-blue-600 hover:border-blue-600">
                                    <a href="/img/ss-esccor.png" target="_blank">
                                        VIEW SCREENSHOT
                                    </a>
                                </div>
                            </a>
                            <div class="mt-4">
                                <hr>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center">
                            <div class="flex-shrink-0">
                                <a href="#">
                                    <img class="h-10 w-10 rounded-full" src="/img/logo-nasa.png" alt="">
                                </a>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    <a href="#" class="hover:underline"> Laravel, Apache Nutch, ElasticSearch </a>
                                </p>
                                <div class="flex space-x-1 text-sm text-gray-500">
                                    <time datetime="2020-03-16"> NASA Goddard Space Flight Center</time>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                    <div class="flex-shrink-0">
                        <img class="h-48 w-full object-cover" src="/img/ss-earth-observatory.png" alt="">
                    </div>
                    <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-indigo-600">
                                <a href="#" class="hover:underline"> Website & Backend Administration </a>
                            </p>
                            <a href="#" class="block mt-2">
                                <p class="text-xl font-semibold text-gray-900"><a
                                        href="https://earthobservatory.nasa.gov/"
                                        alt="" target="_blank">Earth
                                        Observatory</a></p>
                                <p class="mt-3 text-base text-gray-500">
                                    The Earth Observatory’s mission is to share with the public the images, stories, and
                                    discoveries about the environment, Earth systems, and climate that emerge from NASA
                                    research, including its satellite missions, in-the-field research, and models.
                                </p>
                            </a>
                            <a href="#">
                                <div class="font-bold tracking-tight text-sm text-blue-400 mt-4 border-blue-400
                                        rounded border uppercase px-2 py-1 inline-block
                                        hover:text-blue-600 hover:border-blue-600">
                                    <a href="/img/screencapture-earthobservatory-nasa-gov.png" target="_blank">
                                        VIEW SCREENSHOT
                                    </a>
                                </div>
                            </a>
                        </div>
                        <div class="mt-4">
                            <hr>
                        </div>
                        <div class="mt-6 flex items-center">
                            <div class="flex-shrink-0">
                                <a href="#">
                                    <img class="h-10 w-10 rounded-full" src="/img/logo-nasa.png" alt="">
                                </a>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    <a href="#" class="hover:underline"> Laravel, Bootstrap, Laravel Mix </a>
                                </p>
                                <div class="flex space-x-1 text-sm text-gray-500">
                                    <time datetime="2020-03-10"> NASA Goddard Space Flight Center</time>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                    <div class="flex-shrink-0">
                        <img class="h-48 w-full object-cover" src="/img/ss-direct-readout2.png" alt="">
                    </div>
                    <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-indigo-600">
                                <a href="#" class="hover:underline"> Case Study </a>
                            </p>
                            <a href="#" class="block mt-2">
                                <p class="text-xl font-semibold text-gray-900"><a
                                        href="https://directreadout.sci.gsfc.nasa.gov/" alt="" target="_blank">Direct
                                        Readout Laboratory</a></p>
                                <p class="mt-3 text-base text-gray-500">The Direct Readout Laboratory (DRL) is a
                                    technology
                                    and information conduit for the Direct Broadcast (DB) community. The DRL acts as an
                                    intermediary between missions and DB community members that are not directly
                                    involved in
                                    the missions.</p>
                            </a>

                            <a href="/img/ss-direct-readout.png" alt="" target="_blank">
                                <div class="font-bold tracking-tight text-sm text-blue-400 mt-4 border-blue-400
                                        rounded border uppercase px-2 py-1 inline-block
                                        hover:text-blue-600 hover:border-blue-600">
                                    VIEW SCREENSHOT
                                </div>
                            </a>
                        </div>
                        <div class="mt-4">
                            <hr>
                        </div>
                        <div class="mt-6 flex items-center">
                            <div class="flex-shrink-0">
                                <a href="#">
                                    <img class="h-10 w-10 rounded-full" src="/img/logo-nasa.png" alt="">
                                </a>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    <a href="#" class="hover:underline"> Laravel, Tailwind, Vite</a>
                                </p>
                                <div class="flex space-x-1 text-sm text-gray-500">
                                    <time datetime="2020-02-12"> NASA Goddard Space Flight Center</time>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="portfolio2">
    <div class="relative bg-gray-50 pb-20 border border-gray-200 pl-5 pr-5">
        <div class="absolute inset-0">
            <div class="bg-white h-1/3 sm:h-2/3"></div>
        </div>
        <div class="relative max-w-7xl mx-auto">
            <div class="mt-12 max-w-lg mx-auto grid gap-5 lg:grid-cols-3 lg:max-w-none">
                <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                    <div class="flex-shrink-0">

                        <img class="h-48 w-full object-cover"
                             src="/img/ss-mci-verizon.png"
                             alt="">

                    </div>
                    <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-indigo-600">
                                <a href="#" class="hover:underline"> Application & Backend Administration</a>
                            </p>
                            <a href="#" class="block mt-2">
                                <p class="text-xl font-semibold text-gray-900"> Verizon Business Finium&#8482;</p>
                                <p class="mt-3 text-base text-gray-500">
                                    Verizon Business' suite of security services powered by Finium&#8482;, a co-managed
                                    platform
                                    driven by flexible technologies, proven processes and expert staff; operated via a
                                    disaster-resilient Security Operations Center. Finium&#8482; integrates threat,
                                    vulnerability and event information via a centralized, secure web console, enabling
                                    analysts and managers to better manage security as part of their business
                                    operations.
                                </p>
                            </a>

                            <a href="#">
                                <div class="font-bold tracking-tight text-sm text-blue-400 mt-4 border-blue-400
                                        rounded border uppercase px-2 py-1 inline-block
                                        hover:text-blue-600 hover:border-blue-600">
                                    <a href="/img/ss-mci-verizon.png" target="_blank">VIEW SCREENSHOT</a>
                                </div>
                            </a>
                            <div class="mt-4">
                                <hr>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center">
                            <div class="flex-shrink-0">
                                <a href="#">
                                    <img class="h-10 w-10 rounded-full" src="/img/logo-verizon.png" alt="">
                                </a>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    <a href="#" class="hover:underline"> JAVA, JSF, Struts </a>
                                </p>
                                <div class="flex space-x-1 text-sm text-gray-500">
                                    <time datetime="2020-03-16"> Verizon Business</time>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                    <div class="flex-shrink-0">
                        <img class="h-48 object-cover object-left-top" src="/img/ss-informeddna.png" alt="">
                    </div>
                    <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-indigo-600">
                                <a class="hover:underline"> Application </a>
                            </p>
                            <a href="#" class="block mt-2">
                                <p class="text-xl font-semibold text-gray-900"><a
                                        href="https://idnaportal.com/"
                                        alt="" target="_blank">InformedDNA</a></p>
                                <p class="mt-3 text-base text-gray-500">
                                    InformedDNA optimizes clinical decisions through impactful solutions leveraging the
                                    most current genomics expertise. We are the nation’s leading applied genomics
                                    company, with the largest independent team of genetics specialists representing the
                                    full breadth of specialties and sub-specialties. In addition, we are backed by more
                                    than 14 years of clinical data and financial proof of effectiveness.

                                </p>
                            </a>

                            <a href="/img/ss-informeddna.png" target="_blank">
                                <div class="font-bold tracking-tight text-sm text-blue-400 mt-4 border-blue-400
                                        rounded border uppercase px-2 py-1 inline-block
                                        hover:text-blue-600 hover:border-blue-600">
                                    VIEW SCREENSHOT
                                </div>
                            </a>
                            <div class="mt-4">
                                <hr>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center">
                            <div class="flex-shrink-0">
                                <a href="#">
                                    <img class="h-10 w-10 rounded-full" src="/img/logo-informeddna.png" alt="">
                                </a>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    <a href="#" class="hover:underline"> Laravel, Bootstrap, SugarCRM </a>
                                </p>
                                <div class="flex space-x-1 text-sm text-gray-500">
                                    <time datetime="2020-03-10"> InformedDNA</time>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                    <div class="flex-shrink-0">

                        <img class="h-48 w-full object-cover" src="/img/ss-dante.png" alt="">

                    </div>
                    <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-indigo-600">
                                <a href="#" class="hover:underline">Tool for Building Enterprise Applications</a>
                            </p>
                            <span class="block mt-2">
                                <p class="text-xl font-semibold text-gray-900"><a
                                        href="https://www.danteinc.com/" alt=""
                                        target="_blank">Dante Inc's Taylor</a></p>
                                <p class="mt-3 text-base text-gray-500">
Taylor MDA is a specialized UML modeling tool based on Eclipse designed to support the development of multi-tiered, distributed systems. Used to model and document complex systems design, it uses convention-based techniques to generate the maximum code from streamlined UML models. Templates for creating JEE applications based on JPA/EJB3 and JSF/Seam/Facelets are included.                                </p>
                            </span>

                            <a href="#">
                                <div class="font-bold tracking-tight text-sm text-blue-400 mt-4 border-blue-400
                                        rounded border uppercase px-2 py-1 inline-block
                                        hover:text-blue-600 hover:border-blue-600">
                                    <a href="img/ss-dante.png" target="_blank">
                                        VIEW SCREENSHOT
                                    </a>
                                </div>
                            </a>
                        </div>
                        <div class="mt-4">
                            <hr>
                        </div>
                        <div class="mt-6 flex items-center">
                            <div class="flex-shrink-0">
                                <a href="#">
                                    <img class="h-10 w-10 rounded-full" src="/img/logo-dante-small.png" alt="">
                                </a>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    <a href="#" class="hover:underline"> JAVA, JSP, RichFaces</a>
                                </p>
                                <div class="flex space-x-1 text-sm text-gray-500">
                                    <time datetime="2020-02-12"> Dante Inc.</time>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@include('partials.footer')

@vite('resources/js/app.js')
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-85045253-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        let dataLayer;
        dataLayer.push(arguments);
    }

    gtag('js', new Date());
    gtag('config', 'UA-85045253-1');
</script>
</body>
</html>
