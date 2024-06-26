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
<body class="font-sans bg-maroon-dream">

@include('partials.header')

<!-- Hero -->
<section class="bg-hero-pattern min-h-screen w-full pt-48 overflow-hidden px-0 text-white">
    <div class="max-w-7xl mx-auto space-y-6 px-4 py-5 sm:flex sm:space-y-0 sm:space-x-10 sm:px-6 lg:px-8">
        <div class="container mx-auto py-6">
            <div data-aos="fade-left" data-aos-duration="1200" data-aos-delay="200"
                 data-aos-easing="ease-in-out-back">
                <h2 class="text-4xl font-extrabold tracking-tight sm:text-5xl">Karl Hill</h2>
            </div>
            <div data-aos="fade-left" data-aos-duration="1100" data-aos-delay="400" class="mt-2 mb-3"
                 data-aos-easing="ease-in-out-back">
                <h2 class="text-4xl font-extrabold tracking-tight sm:text-5xl">Full Stack Engineer</h2>
            </div>
            <div data-aos="fade-left" data-aos-duration="1000" data-aos-delay="600" class="mt-2 mb-3"
                 data-aos-easing="ease-in-out-back">
                <h6 class="text-lg sm:text-xl uppercase font-medium tracking-tight">
                    PHP, Laravel, Tailwind, Inertia, Vite
                </h6>
            </div>

            <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="500" data-aos-easing="ease-in-out-back">
                <span class="relative inline-flex rounded-md shadow-sm">
                  <a href="/portfolio" type="button"
                     class="hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 inline-flex items-center px-4 py-2 border-2 border-white text-base leading-6 font-medium rounded-md text-white focus:border-white transition ease-in-out duration-150">
                    Portfolio
                  </a>
                  <span class="flex absolute h-3 w-3 top-0 right-0 -mt-1 -mr-1">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-300 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-400"></span>
                  </span>
                </span>

                <a href="{{ asset('files/karlhill-resume.pdf') }}" role="button" target="_blank"
                   class="inline-flex items-center px-4 py-2 border-solid border-2 border-white text-base font-medium
                   rounded-md shadow-sm text-white hover:bg-yellow-600 focus:outline-none focus:ring-2
                   focus:ring-offset-2 focus:ring-yellow-500">
                    Resume
                </a>
                <a href="#feature"
                   class="inline-flex items-center px-4 py-2 border-solid border-2 border-white text-base font-medium
                   rounded-md shadow-sm text-white hover:bg-black focus:outline-none focus:ring-2
                   focus:ring-offset-2 focus:ring-gray-500">
                    Learn More
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Feature -->
<section id="feature" class="bg-white px-0">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="bg-orange-500 rounded-lg shadow-xl overflow-hidden lg:grid lg:grid-cols-2 lg:gap-4">
            <div class="pt-10 pb-12 px-6 sm:pt-16 sm:px-16 lg:py-16 lg:pr-0 xl:py-20 xl:px-20">
                <div class="lg:self-center">
                    <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                        <span class="block">Why you should hire me.</span>
                    </h2>
                    <p class="mt-4 text-lg leading-6 text-white">
                        As a senior technology leader with over 20 years of experience developing and launching
                        breakthrough solutions in Agile environments, I have cultivated a strong track record of
                        developing successful solutions quickly while working within distributed and virtual teams.
                        In addition, I have the technical skills to accomplish complex programming tasks and the
                        expertise to produce engaging interfaces. Hungry for learning new technologies, quickly
                        ramping up on new projects, and working independently or in team situations to drive
                        product development. Professional, motivated, detail-oriented, wildly creative, and
                        possesses excellent verbal and written skills.
                    </p>
                </div>
            </div>
            <div class="-mt-6 aspect-w-5 aspect-h-3 md:aspect-w-2 md:aspect-h-1">
                <img
                    class="transform translate-x-6 translate-y-6 rounded-md object-cover object-left-top sm:translate-x-16 lg:translate-y-20"
                    src="/img/bg-drl-screenshot.png" alt="App screenshot">
            </div>
        </div>
    </div>
</section>

<!-- Map -->
<div class="bg-gray-200 border-t border-yellow-400">
    <iframe class="h-96 w-full"
            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA1U9kPUx3SN0u33kKiaCSXl7plnfA3y8Q&q=Brightwood+Park,+Washington,+DC+20011"
            allowfullscreen>
    </iframe>
</div>

@include('partials.footer')

@vite('resources/js/app.js')
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-EZZNL8KY8P"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-EZZNL8KY8P');
</script>
</body>
</html>
