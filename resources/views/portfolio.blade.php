<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="framework" content="Laravel v{{ app()->version() }}">
    <meta name="php-version" content="{{ PHP_VERSION }}">
    <title>{{ config('app.name', 'Karl Hill | Full Stack Engineer') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">
    @vite('resources/css/app.css')
</head>
<body class="antialiased font-sans bg-white">

@include('partials.header')

<section id="portfolio1">

    <div class="relative bg-gray-50 pb-20 ">
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
                             src="/img/ss-mci-verizon.png"
                             alt="">
                    </div>
                    <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-indigo-600">
                                <a href="#" class="hover:underline"> Application </a>
                            </p>
                            <a href="#" class="block mt-2">
                                <p class="text-xl font-semibold text-gray-900">Earth Science Data Systems </p>
                                <p class="mt-3 text-base text-gray-500">
                                    NASA's ESDS program oversees the life cycle of NASA's Earth science data — from
                                    acquisition through processing and distribution. The primary goal of ESDS is to
                                    maximize the scientific return from NASA's missions and experiments for
                                    research and applied scientists, decision makers, and society at large.
                                </p>
                            </a>
                            <a href="#">
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
                                    <img class="h-10 w-10 rounded-full" src="/img/logo-nasa.png" alt="">
                                </a>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    <a href="#" class="hover:underline"> Laravel 8, Apache Nutch, ElasticSearch </a>
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
                                    <a href="#" class="hover:underline"> Laravel 7, Bootstrap, Laravel Mix </a>
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
                        <img class="h-48 w-full object-cover" src="/img/ss-direct-readout.png" alt="">
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
                                    <a href="#" class="hover:underline"> Laravel 9, Tailwind, Vite</a>
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
    <div class="relative bg-gray-50 pt-16 pb-20 px-4 sm:px-6 lg:pt-24 lg:pb-28 lg:px-8">
        <div class="absolute inset-0">
            <div class="bg-white h-1/3 sm:h-2/3"></div>
        </div>
        <div class="relative max-w-7xl mx-auto">
            <div class="mt-12 max-w-lg mx-auto grid gap-5 lg:grid-cols-3 lg:max-w-none">
                <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                    <div class="flex-shrink-0">
                        <img class="w-48 h-48 object-cover object-left-top"
                             src="/img/ss-mci-verizon.png"
                             alt="">
                    </div>
                    <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-indigo-600">
                                <a href="#" class="hover:underline"> Application & Backend Administration</a>
                            </p>
                            <a href="#" class="block mt-2">
                                <p class="text-xl font-semibold text-gray-900"> MCI's Finium Security Suite </p>
                                <p class="mt-3 text-base text-gray-500">
                                    MCI's suite of security services powered by FiniumTM, a co-managed platform driven
                                    by flexible technologies, proven processes and expert staff; operated via a
                                    disaster-resilient Security Operations Center. FiniumTM integrates threat,
                                    vulnerability and event information via a centralized, secure Web Console, enabling
                                    analysts and managers to better manage security as part of their business.
                                </p>
                            </a>

                            <a href="#">
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
                                    <img class="h-10 w-10 rounded-full" src="/img/logo-verizon.png" alt="">
                                </a>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    <a href="#" class="hover:underline"> JAVA, JSF, Struts </a>
                                </p>
                                <div class="flex space-x-1 text-sm text-gray-500">
                                    <time datetime="2020-03-16"> MCI/Verizon Business</time>
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
                                    full breadth of specialties and sub-specialties, and backed by more than 14 years of
                                    clinical data and financial proof of effectiveness.
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
                                    <a href="#" class="hover:underline"> Laravel 5, Bootstrap, SugarCRM </a>
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
                        <img class="object-left-top overflow-hidden" src="/img/ss-direct-readout.png" alt="">
                    </div>
                    <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-indigo-600">
                                <a href="#" class="hover:underline"> Case Study </a>
                            </p>
                            <span class="block mt-2">
                                <p class="text-xl font-semibold text-gray-900"><a
                                        href="https://directreadout.sci.gsfc.nasa.gov/" alt="" target="_blank">Direct
                                        Readout Laboratory</a></p>
                                <p class="mt-3 text-base text-gray-500">The Direct Readout Laboratory (DRL) is a
                                    technology
                                    and information conduit for the Direct Broadcast (DB) community. The DRL acts as an
                                    intermediary between missions and DB community members that are not directly
                                    involved in
                                    the missions.</p>
                            </span>

                            <a href="#">
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
                                    <a href="#" class="hover:underline"> Laravel 9, Tailwind, Vite</a>
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
