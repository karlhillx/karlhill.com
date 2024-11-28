<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full font-sans antialiased">
<head>
    @include('partials.head')
</head>
<body class="font-sans bg-white">
<div class="flex flex-col min-h-screen">
    @include('partials.header')

    <main class="flex-grow pt-32">
        <section id="recent-work">
            <main>
                <x-hero-section/>
                <div class="sm:mt-20">
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
                                 class="absolute inset-0 h-full w-full object-cover" loading="lazy"
                                 style="color:transparent">
                        </div>
                        <div
                            class="relative aspect-[9/10] w-44 flex-none overflow-hidden rounded-xl bg-zinc-100 dark:bg-zinc-800 sm:w-72 sm:rounded-2xl rotate-2">
                            <img alt="" sizes="(min-width: 640px) 18rem, 11rem"
                                 src="/img/small-direct-readout.png"
                                 width="3744" height="5616" decoding="async" data-nimg="future"
                                 class="absolute inset-0 h-full w-full object-cover" loading="lazy"
                                 style="color:transparent">
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
                                 class="absolute inset-0 h-full w-full object-cover" loading="lazy"
                                 style="color:transparent">
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
                                                <a href="https://tailwindcss.com/blog/tailwindcss-v4-alpha"
                                                   target="_blank">
                                            <span
                                                class="absolute -inset-y-6 -inset-x-4 z-20 sm:-inset-x-6 sm:rounded-2xl"></span>
                                                    <span
                                                        class="relative z-10">Embracing the Future with Tailwind CSS v4 Beta</span>
                                                </a>
                                            </h2>
                                            <time
                                                class="relative z-10 order-first mb-3 flex items-center text-sm text-orange-400 dark:text-zinc-500 pl-3.5"
                                                datetime="2022-07-14"><span
                                                    class="absolute inset-y-0 left-0 flex items-center"
                                                    aria-hidden="true"><span
                                                        class="h-4 w-0.5 rounded-full bg-zinc-200 dark:bg-zinc-500"></span></span>November
                                                21, 2024
                                            </time>
                                            <p class="relative z-10 mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                                                I've recently integrated Tailwind CSS v4 Beta into my portfolio, pushing
                                                the
                                                boundaries of modern web design. This cutting-edge version brings
                                                exciting
                                                new
                                                features like native color opacity, simplified custom values, and
                                                improved
                                                performance. By adopting v4 Alpha, I'm staying ahead of the curve and
                                                delivering
                                                a more efficient, flexible, and visually appealing user experience.
                                                Explore
                                                my
                                                portfolio to see Tailwind CSS v4 Alpha in action and witness the future
                                                of
                                                web
                                                styling firsthand.
                                            </p>
                                        </article>

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
                                                datetime="2022-07-14"><span
                                                    class="absolute inset-y-0 left-0 flex items-center"
                                                    aria-hidden="true"><span
                                                        class="h-4 w-0.5 rounded-full bg-zinc-200 dark:bg-zinc-500"></span></span>December
                                                20, 2022
                                            </time>

                                            <p class="relative z-10 mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                                                If you're a Laravel developer, you may be interested in using Laravel
                                                Livewire
                                                and Inertia to build interactive and dynamic web applications. Livewire
                                                is a
                                                full-stack framework that allows you to create dynamic, reactive
                                                components
                                                using PHP. In contrast, Inertia enables you to use those components on
                                                the
                                                front
                                                end with minimal JavaScript. Together, these tools can make building
                                                modern,
                                                responsive web applications with minimal code easier. Livewire and
                                                Inertia
                                                are
                                                easy to learn and use, and they can be a great addition to your toolkit
                                                as a
                                                Laravel developer. Give them a try and see how they can improve your
                                                workflow.
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
                                                datetime="2022-09-05"><span
                                                    class="absolute inset-y-0 left-0 flex items-center"
                                                    aria-hidden="true"><span
                                                        class="h-4 w-0.5 rounded-full bg-zinc-200 dark:bg-zinc-500"></span></span>September
                                                5, 2022
                                            </time>
                                            <p class="relative z-10 mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                                                Are you tired of dealing with the slow build times and complicated
                                                configuration
                                                of Laravel Mix? If so, it might be time to consider transitioning to
                                                Vite.

                                                Vite is a modern frontend build tool that provides an extremely fast
                                                development
                                                environment and bundles your code for production.

                                                When building applications
                                                with Laravel, you will typically use Vite to bundle your application's
                                                CSS
                                                and
                                                JavaScript files into production ready assets.

                                                Laravel integrates seamlessly with Vite by providing an official plugin
                                                and
                                                Blade directives to load your assets for development and production.
                                            </p>
                                        </article>

                                        <article class="group relative flex flex-col items-start">
                                            <h2 class="text-base font-semibold tracking-tight text-zinc-800 dark:text-zinc-100">
                                                <div
                                                    class="absolute -inset-y-6 -inset-x-4 z-0 scale-95 bg-zinc-50 opacity-0 transition group-hover:scale-100 group-hover:opacity-100 dark:bg-zinc-800/50 sm:-inset-x-6 sm:rounded-2xl">
                                                </div>
                                                <a href="https://tailwindcss.com/" target="_blank"><span
                                                        class="absolute -inset-y-6 -inset-x-4 z-20 sm:-inset-x-6 sm:rounded-2xl"></span><span
                                                        class="relative z-10">Rewriting interfaces with TailwindCSS</span></a>
                                            </h2>
                                            <time
                                                class="relative z-10 order-first mb-3 flex items-center text-sm text-orange-400 dark:text-zinc-500 pl-3.5"
                                                datetime="2022-07-14"><span
                                                    class="absolute inset-y-0 left-0 flex items-center"
                                                    aria-hidden="true"><span
                                                        class="h-4 w-0.5 rounded-full bg-zinc-200 dark:bg-zinc-500"></span></span>February
                                                14, 2022
                                            </time>
                                            <p class="relative z-10 mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                                                If you're tired of the same old UI designs and want to give your
                                                interface a
                                                fresh, modern look, consider rewriting it with TailwindCSS.
                                                When I first came across <a href="https://tailwindcss.com/"
                                                                            target="_blank">TailwindCSS</a>,
                                                I was amazed by the framework's simplicity. I rewrote my interfaces in a
                                                matter
                                                of minutes with efficiency and maintainability, leaving me more time to
                                                focus on
                                                the core of my applications. I have since used TailwindCSS in all of my
                                                new
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
                                                datetime="2022-09-02"><span
                                                    class="absolute inset-y-0 left-0 flex items-center"
                                                    aria-hidden="true"><span
                                                        class="h-4 w-0.5 rounded-full bg-zinc-200 dark:bg-zinc-500"></span></span>January
                                                2, 2022
                                            </time>
                                            <p class="relative z-10 mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                                                I am professionally showcasing my portfolio, highlighting the breadth of
                                                my
                                                experience and the depth of my skills across multiple industries and
                                                verticals,
                                                establishing myself as a go-to resource for new business opportunities.
                                            </p>
                                        </article>

                                    </div>
                                    <div class="space-y-10 lg:pl-16 xl:pl-24">
                                        <div class="rounded-2xl border border-zinc-100 p-6 dark:border-zinc-700/40">
                                            <h2 class="flex text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round" aria-hidden="true"
                                                     class="h-6 w-6 flex-none">
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
                                                        <a href="https://www.nasa.gov/" target="_blank">
                                                            <img alt=""
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
                                                                 height="28" decoding="async" data-nimg="future"
                                                                 class="h-7 w-7"
                                                                 loading="lazy" style="color:transparent">
                                                        </a>
                                                    </div>
                                                    <dl class="flex flex-auto flex-wrap gap-x-2">
                                                        <dt class="sr-only">Company</dt>
                                                        <dd class="w-full flex-none text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                            <a href="https://informeddna.com/"
                                                               target="_blank">InformedDNA</a>
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
                                                                 height="28" decoding="async" data-nimg="future"
                                                                 class="h-7 w-7"
                                                                 loading="lazy" style="color:transparent">
                                                        </a>
                                                    </div>
                                                    <dl class="flex flex-auto flex-wrap gap-x-2">
                                                        <dt class="sr-only">Company</dt>
                                                        <dd class="w-full flex-none text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                            <a href="https://www.ticomix.com/"
                                                               target="_blank">Ticomix</a>
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
                                                                 height="28" decoding="async" data-nimg="future"
                                                                 class="h-7 w-7"
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
                                                                 height="28" decoding="async" data-nimg="future"
                                                                 class="h-7 w-7"
                                                                 loading="lazy" style="color:transparent">
                                                        </a>
                                                    </div>
                                                    <dl class="flex flex-auto flex-wrap gap-x-2">
                                                        <dt class="sr-only">Company</dt>
                                                        <dd class="w-full flex-none text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                            <a href="https://www.danteinc.com/" target="_blank">Dante
                                                                Inc.</a>
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
                                                                 height="28" decoding="async" data-nimg="future"
                                                                 class="h-7 w-7"
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
                                                        <dd class="text-xs text-zinc-500 dark:text-zinc-400">Software
                                                            Developer
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
                                            <a href="{{ asset('files/karlhill-resume.pdf') }}" role="button"
                                               target="_blank"
                                               class="inline-flex items-center gap-2 justify-center rounded-md py-2 px-3 text-sm outline-offset-2 transition active:transition-none bg-zinc-50 font-medium text-zinc-900 hover:bg-zinc-100 active:bg-zinc-100 active:text-zinc-900/60 dark:bg-zinc-800/50 dark:text-zinc-300 dark:hover:bg-zinc-800 dark:hover:text-zinc-50 dark:active:bg-zinc-800/50 dark:active:text-zinc-50/70 group mt-6 w-full">Download
                                                CV
                                                <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"
                                                     class="h-4 w-4 stroke-zinc-400 transition group-active:stroke-zinc-600 dark:group-hover:stroke-zinc-50 dark:group-active:stroke-zinc-50">
                                                    <path d="M4.75 8.75 8 12.25m0 0 3.25-3.5M8 12.25v-8.5"
                                                          stroke-width="1.5"
                                                          stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </a></div>

                                        <form action="/contact" method="post"
                                              class="flex flex-col gap-4 rounded-2xl border border-zinc-100 p-6 dark:border-zinc-700/40 mb-20">
                                            @csrf
                                            <h2 class="flex text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round" aria-hidden="true"
                                                     class="h-6 w-6 flex-none">
                                                    <path
                                                        d="M2.75 7.75a3 3 0 0 1 3-3h12.5a3 3 0 0 1 3 3v8.5a3 3 0 0 1-3 3H5.75a3 3 0 0 1-3-3v-8.5Z"
                                                        class="fill-zinc-100 stroke-zinc-400 dark:fill-zinc-100/10 dark:stroke-zinc-500"></path>
                                                    <path d="m4 6 6.024 5.479a2.915 2.915 0 0 0 3.952 0L20 6"
                                                          class="stroke-zinc-400 dark:stroke-zinc-500"></path>
                                                </svg>
                                                <span class="ml-3">Stay up to date</span></h2>
                                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Get notified when I
                                                publish
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
                        <h2 class="text-3xl tracking-tight font-bold text-gray-900 sm:text-4xl sm:tracking-tight">
                            Portfolio</h2>
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
                                                Earth Science Communications Content Registry (ESCCOR)
                                            </a>
                                        </p>
                                        <p class="mt-3 text-base text-gray-500">
                                            ESCCOR is a cutting-edge platform revolutionizing Earth science data
                                            management.
                                            This system integrates advanced taxonomy, AI-powered indexing, and a
                                            high-performance catalog, accessible through a user-friendly portal. ESCCOR
                                            enhances
                                            NASA's Earth Sciences Division's reporting, product access, and cross-team
                                            collaboration, positioning NASA at the forefront of Earth science data
                                            management.
                                            My work significantly improved NASA's handling of vast Earth science
                                            information,
                                            benefiting multiple NASA groups and advancing the agency's mission.
                                        </p>
                                    </a>

                                    <div class="mt-4">
                                        <div
                                            class="font-bold tracking-tight text-sm text-blue-400 border-blue-400 rounded border uppercase px-4 py-1 inline-block hover:text-blue-600 hover:border-blue-600">
                                            <a href="/img/ss-esccor.png" target="_blank">
                                                VIEW SCREENSHOT
                                            </a>
                                        </div>
                                    </div>
                                    <hr class="border-slate-100 border-t mt-4">
                                </div>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <a href="#">
                                            <img class="h-10 w-10 rounded-full" src="/img/logo-nasa.png" alt="">
                                        </a>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">
                                            <a href="#" class="hover:underline"> Laravel, Apache Nutch,
                                                ElasticSearch </a>
                                        </p>
                                        <div class="flex space-x-1 text-sm text-gray-500">
                                            <time datetime="2020-03-16"> NASA Goddard Space Flight Center</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                            <picture>
                                <source srcset="/img/webp/small-earth-observatory.webp" type="image/webp">
                                <img class="h-48 w-full object-cover" src="/img/small-earth-observatory.png"
                                     alt="Earth Observatory">
                            </picture>

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
                                            I helped develop NASA's Earth Observatory website and its advanced back-end
                                            system, creating a digital gateway to NASA's environmental research. This
                                            platform showcases stunning imagery, compelling stories, and crucial
                                            discoveries about Earth's ecosystems and climate from NASA's satellite
                                            missions and field research. Using cutting-edge web technologies, we crafted
                                            an immersive interface that brings complex scientific data to life for a
                                            global audience, supporting NASA's mission to foster public understanding of
                                            our planet's intricate systems and environmental changes. The site serves as
                                            an invaluable resource for scientists, educators, policymakers, and the
                                            public alike.
                                        </p>
                                    </a>
                                    <div class="mt-4">
                                        <div
                                            class="font-bold tracking-tight text-sm text-blue-400 border-blue-400 rounded border uppercase px-4 py-1 inline-block hover:text-blue-600 hover:border-blue-600">

                                            <a href="/img/screencapture-earthobservatory-nasa-gov.png" target="_blank">
                                                VIEW SCREENSHOT
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <hr class="border-slate-100 border-t mt-4">
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
                                        <a href="#" class="hover:underline"> Website Case Study </a>
                                    </p>
                                    <a href="#" class="block mt-2">
                                        <p class="text-xl font-semibold text-gray-900"><a
                                                href="https://directreadout.sci.gsfc.nasa.gov/" alt="" target="_blank">Direct
                                                Readout Laboratory</a></p>
                                        <p class="mt-3 text-base text-gray-500">
                                            The Direct Readout Laboratory (DRL) plays a vital role in connecting NASA's Earth observation missions with data users worldwide. I designed a concept website for the DRL, envisioning a modern and dynamic platform built with cutting-edge web technologies like Laravel, Tailwind CSS, and Vite. This responsive design would effectively showcase the DRL's innovative work in facilitating real-time data transmission from Earth-observing satellites directly to ground stations across the globe. The website's intuitive interface would provide users with seamless access to mission-critical information, valuable tools, and essential resources, like data visualization tools and educational materials, enhancing environmental monitoring capabilities and supporting vital scientific research on a global scale. By empowering users with timely and accessible data, the DRL website aims to strengthen international collaboration and contribute to a deeper understanding of our changing planet.                                    <div class="mt-4">
                                        <div
                                            class="font-bold tracking-tight text-sm text-blue-400 border-blue-400 rounded border uppercase px-4 py-1 inline-block hover:text-blue-600 hover:border-blue-600">

                                            <a href="/img/ss-direct-readout.png" alt="" target="_blank">
                                                VIEW SCREENSHOT
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <hr class="border-slate-100 border-t mt-4">
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
                                        <p class="text-xl font-semibold text-gray-900"> Verizon Business
                                            Finium™</p>
                                        <p class="mt-3 text-base text-gray-500">
                                            Verizon Business' Finium™ platform revolutionizes cybersecurity by
                                            integrating
                                            threat intelligence, vulnerability data, and security events in a
                                            centralized
                                            web
                                            console. Operating from a disaster-resilient Security Operations Center,
                                            this
                                            cutting-edge system empowers analysts and business leaders to proactively
                                            manage
                                            security as a strategic asset. My work on Finium™ contributed to
                                            transforming
                                            enterprise cybersecurity, making it an accessible, integral part of business
                                            operations in our complex digital landscape.
                                        </p>
                                    </a>


                                    <div class="mt-4">
                                        <div
                                            class="font-bold tracking-tight text-sm text-blue-400 border-blue-400 rounded border uppercase px-4 py-1 inline-block hover:text-blue-600 hover:border-blue-600">
                                            <a href="/img/ss-mci-verizon.png" target="_blank">
                                                VIEW SCREENSHOT
                                            </a>
                                        </div>
                                    </div>

                                    <hr class="border-slate-100 border-t mt-4">
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
                                                alt="" target="_blank">IDNAPortal</a></p>
                                        <p class="mt-3 text-base text-gray-500">
                                            InformedDNA leads the applied genomics field, optimizing clinical decisions
                                            with
                                            cutting-edge genomics expertise. Their IDNAPortal, which I developed, is a
                                            sophisticated platform that analyzes and interprets genomic data. It
                                            provides
                                            healthcare providers with actionable insights from years of clinical and
                                            financial
                                            data, enabling personalized care decisions. This innovative tool streamlines
                                            complex
                                            genomic information, enhancing treatment strategies in precision medicine.
                                        </p>
                                    </a>

                                    <div class="mt-4">
                                        <div
                                            class="font-bold tracking-tight text-sm text-blue-400 border-blue-400 rounded border uppercase px-4 py-1 inline-block hover:text-blue-600 hover:border-blue-600">

                                            <a href="/img/ss-informeddna.png" target="_blank">
                                                VIEW SCREENSHOT
                                            </a>
                                        </div>
                                    </div>
                                    <hr class="border-slate-100 border-t mt-4">
                                </div>
                                <div class="mt-4 flex items-center">
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
                                            <time datetime="2020-03-10">InformedDNA</time>
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
                                        <a href="#" class="hover:underline">Tool for Building Enterprise
                                            Applications</a>
                                    </p>
                                    <span class="block mt-2">
                                        <p class="text-xl font-semibold text-gray-900"><a
                                                href="https://www.danteinc.com/" alt=""
                                                target="_blank">Taylor MDA</a></p>
                                        <p class="mt-3 text-base text-gray-500">
                                            At Dante Inc., I contributed to Taylor MDA, an advanced Eclipse-based UML modeling tool for multi-tiered, distributed systems. It streamlines complex system design and maximizes code generation from UML models. Taylor MDA features pre-designed templates for JEE applications, integrating JPA/EJB3 and JSF/Seam/Facelets, accelerating enterprise application development through innovative model-driven architecture techniques. My work enhanced its capabilities and user experience.
                                        </p>
<div class="mt-4">
    <div
        class="font-bold tracking-tight text-sm text-blue-400 border-blue-400 rounded border uppercase px-4 py-1 inline-block hover:text-blue-600 hover:border-blue-600">

                                                <a href="img/ss-dante.png" target="_blank">
                                                    VIEW SCREENSHOT
                                                </a>
                                            </div>
</div>


                                <hr class="border-slate-100 border-t mt-4">
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
    </main> @include('partials.footer')
</div>
@include('partials.back-to-top')
@include('partials.scripts')
@include('partials.schema')
</body>
</html>
