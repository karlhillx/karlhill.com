<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="darkMode" :class="{ 'dark': darkMode }">
<head>
    @include('partials.head')
</head>
<body class="font-sans bg-maroon-dream dark:bg-maroon-dream text-gray-900 dark:text-white antialiased">
<div class="flex flex-col min-h-screen">
    @include('partials.header')
    <main class="flex-grow">
        <section id="hero"
                 class="bg-hero-pattern min-h-screen w-full pt-48 overflow-hidden px-0">
            <div class="max-w-7xl mx-auto space-y-6 px-4 py-5 sm:flex sm:space-y-0 sm:space-x-10 sm:px-6 lg:px-8">
                <div class="text-container py-6">
                    <div x-data="{ show: false }"
                         x-init="setTimeout(() => show = true, 200)">
                        <h2 x-show="show"
                            x-transition:enter="transition ease-out duration-800"
                            x-transition:enter-start="opacity-0 transform -translate-y-4"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl">Karl Hill</h2>
                    </div>

                    <div x-data="{ show: false }"
                         x-init="setTimeout(() => show = true, 400)">
                        <h2 x-show="show"
                            x-transition:enter="transition ease-out duration-800"
                            x-transition:enter-start="opacity-0 transform translate-y-4"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl mt-2 mb-3">Full Stack
                            Engineer</h2>
                    </div>

                    <div x-data="{ show: false }"
                         x-init="setTimeout(() => show = true, 600)">
                        <h6 x-show="show"
                            x-transition:enter="transition ease-out duration-800"
                            x-transition:enter-start="opacity-0 transform translate-x-4"
                            x-transition:enter-end="opacity-100 transform translate-x-0"
                            class="text-lg sm:text-xl uppercase font-medium tracking-tight text-white mt-2 mb-3">
                            PHP, Laravel, Tailwind, Alpine, Vite
                        </h6>
                    </div>

                    <div x-data="{ show: false }"
                         x-init="setTimeout(() => show = true, 800)">
                        <div x-show="show"
                             x-transition:enter="transition ease-out duration-800"
                             x-transition:enter-start="opacity-0 transform translate-y-4"
                             x-transition:enter-end="opacity-100 transform translate-y-0">
                              <span class="relative inline-flex rounded-md shadow-sm">
                                <a href="/portfolio" type="button"
                                   class="hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 inline-flex items-center px-4 py-2 border-2 border-white text-base leading-6 font-medium rounded-md text-white focus:border-white transition ease-in-out duration-150">
                                  Portfolio
                                </a>
                                <span class="flex absolute h-3 w-3 top-0 right-0 -mt-1 -mr-1">
                                  <span
                                      class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-300 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-red-400"></span>
                                </span>
                              </span>
                            <a href="{{ asset('files/karlhill-resume.pdf') }}" role="button" target="_blank"
                               class="inline-flex items-center px-4 py-2 border-2 border-white text-base font-medium rounded-md shadow-sm text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                Resume
                            </a>
                            <a href="#feature"
                               class="inline-flex items-center px-4 py-2 border-2 border-white text-base font-medium rounded-md shadow-sm text-white hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                               aria-label="Learn more about Karl Hill">
                                Learn More
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Add prefers-reduced-motion support -->
        <div x-data="{ show: false }"
             x-init="setTimeout(() => show = true, 400)"
             class="motion-safe:transition-all motion-reduce:transition-none">
            <section id="feature" class="bg-white dark:bg-maroon-dream px-0">
                <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
                    <div
                        class="bg-orange-500 dark:bg-orange-600 rounded-lg shadow-xl overflow-hidden lg:grid lg:grid-cols-2 lg:gap-4">
                        <div class="pt-10 pb-12 px-6 sm:pt-16 sm:px-16 lg:py-16 lg:pr-0 xl:py-20 xl:px-20">
                            <div class="lg:self-center">
                                <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                                    <span class="block">Why you should hire me.</span>
                                </h2>
                                <p class="mt-4 text-lg leading-6 text-white">
                                    With over two decades of experience, I've spearheaded the development and launch of
                                    innovative tech solutions within Agile environments. I excel at leading distributed
                                    teams to deliver results quickly, possessing both the coding skills for complex
                                    projects
                                    and the design eye for compelling user interfaces. I'm passionate about staying
                                    ahead of
                                    the curve, learning new technologies, and contributing to product
                                    development—whether
                                    working solo or collaborating with a team. My professional approach combines
                                    motivation,
                                    attention to detail, creativity, and strong communication skills.
                                </p>
                            </div>
                        </div>
                        <div class="-mt-6 aspect-w-5 aspect-h-3 md:aspect-w-2 md:aspect-h-1 lg:self-end">
                            <picture>
                                <source srcset="/img/webp/bg-drl-screenshot.webp" type="image/webp">
                                <img loading="lazy" class="transform rounded-md object-cover lg:object-right-bottom"
                                     src="/img/bg-drl-screenshot.png" alt="App screenshot">
                            </picture>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Skills Grid -->
            <section class="bg-white dark:bg-maroon-dream px-0 py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl mb-12">
                        Technical Expertise
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <!-- Backend -->
                        <div
                            class="group relative flex flex-col space-y-4 p-6 bg-zinc-50 dark:bg-zinc-800/50 rounded-2xl transition hover:bg-zinc-100 dark:hover:bg-zinc-700/50">
                            <div
                                class="absolute -inset-x-4 -inset-y-6 z-0 scale-95 bg-zinc-50 opacity-0 transition group-hover:scale-100 group-hover:opacity-100 dark:bg-zinc-800/50 sm:-inset-x-6 sm:rounded-2xl"></div>
                            <h3 class="relative z-10 text-xl font-bold text-zinc-800 dark:text-zinc-100 flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 7H7v6h6V7z"/>
                                </svg>
                                Backend
                            </h3>
                            <div class="relative z-10 space-y-4">
                                <div class="skill-item">
                                    <span class="text-zinc-600 dark:text-zinc-400">PHP/Laravel</span>
                                    <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2.5 mt-2">
                                        <div
                                            class="bg-teal-500 dark:bg-teal-400 h-2.5 rounded-full transition-all duration-1000 ease-in-out"
                                            style="width: 95%"></div>
                                    </div>
                                </div>
                                <div class="skill-item">
                                    <span class="text-zinc-600 dark:text-zinc-400">Node.js</span>
                                    <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2.5 mt-2">
                                        <div
                                            class="bg-teal-500 dark:bg-teal-400 h-2.5 rounded-full transition-all duration-1000 ease-in-out"
                                            style="width: 85%"></div>
                                    </div>
                                </div>
                                <div class="skill-item">
                                    <span class="text-zinc-600 dark:text-zinc-400">Python</span>
                                    <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2.5 mt-2">
                                        <div
                                            class="bg-teal-500 dark:bg-teal-400 h-2.5 rounded-full transition-all duration-1000 ease-in-out"
                                            style="width: 80%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Frontend -->
                        <div
                            class="group relative flex flex-col space-y-4 p-6 bg-zinc-50 dark:bg-zinc-800/50 rounded-2xl transition hover:bg-zinc-100 dark:hover:bg-zinc-700/50">
                            <div
                                class="absolute -inset-x-4 -inset-y-6 z-0 scale-95 bg-zinc-50 opacity-0 transition group-hover:scale-100 group-hover:opacity-100 dark:bg-zinc-800/50 sm:-inset-x-6 sm:rounded-2xl"></div>
                            <h3 class="relative z-10 text-xl font-bold text-zinc-800 dark:text-zinc-100 flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                                Frontend
                            </h3>
                            <div class="relative z-10 space-y-4">
                                <div class="skill-item">
                                    <span class="text-zinc-600 dark:text-zinc-400">Vue/React</span>
                                    <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2.5 mt-2">
                                        <div
                                            class="bg-teal-500 dark:bg-teal-400 h-2.5 rounded-full transition-all duration-1000 ease-in-out"
                                            style="width: 90%"></div>
                                    </div>
                                </div>
                                <div class="skill-item">
                                    <span class="text-zinc-600 dark:text-zinc-400">Tailwind/Alpine</span>
                                    <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2.5 mt-2">
                                        <div
                                            class="bg-teal-500 dark:bg-teal-400 h-2.5 rounded-full transition-all duration-1000 ease-in-out"
                                            style="width: 95%"></div>
                                    </div>
                                </div>
                                <div class="skill-item">
                                    <span class="text-zinc-600 dark:text-zinc-400">TypeScript</span>
                                    <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2.5 mt-2">
                                        <div
                                            class="bg-teal-500 dark:bg-teal-400 h-2.5 rounded-full transition-all duration-1000 ease-in-out"
                                            style="width: 85%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cloud & DevOps -->
                        <div
                            class="group relative flex flex-col space-y-4 p-6 bg-zinc-50 dark:bg-zinc-800/50 rounded-2xl transition hover:bg-zinc-100 dark:hover:bg-zinc-700/50">
                            <div
                                class="absolute -inset-x-4 -inset-y-6 z-0 scale-95 bg-zinc-50 opacity-0 transition group-hover:scale-100 group-hover:opacity-100 dark:bg-zinc-800/50 sm:-inset-x-6 sm:rounded-2xl"></div>
                            <h3 class="relative z-10 text-xl font-bold text-zinc-800 dark:text-zinc-100 flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M5.5 16a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 16h-8z"/>
                                </svg>
                                Cloud & DevOps
                            </h3>
                            <div class="relative z-10 space-y-4">
                                <div class="skill-item">
                                    <span class="text-zinc-600 dark:text-zinc-400">AWS/GCP</span>
                                    <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2.5 mt-2">
                                        <div
                                            class="bg-teal-500 dark:bg-teal-400 h-2.5 rounded-full transition-all duration-1000 ease-in-out"
                                            style="width: 85%"></div>
                                    </div>
                                </div>
                                <div class="skill-item">
                                    <span class="text-zinc-600 dark:text-zinc-400">Docker/K8s</span>
                                    <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2.5 mt-2">
                                        <div
                                            class="bg-teal-500 dark:bg-teal-400 h-2.5 rounded-full transition-all duration-1000 ease-in-out"
                                            style="width: 90%"></div>
                                    </div>
                                </div>
                                <div class="skill-item">
                                    <span class="text-zinc-600 dark:text-zinc-400">CI/CD</span>
                                    <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2.5 mt-2">
                                        <div
                                            class="bg-teal-500 dark:bg-teal-400 h-2.5 rounded-full transition-all duration-1000 ease-in-out"
                                            style="width: 90%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Database & Cache -->
                        <div
                            class="group relative flex flex-col space-y-4 p-6 bg-zinc-50 dark:bg-zinc-800/50 rounded-2xl transition hover:bg-zinc-100 dark:hover:bg-zinc-700/50">
                            <div
                                class="absolute -inset-x-4 -inset-y-6 z-0 scale-95 bg-zinc-50 opacity-0 transition group-hover:scale-100 group-hover:opacity-100 dark:bg-zinc-800/50 sm:-inset-x-6 sm:rounded-2xl"></div>
                            <h3 class="relative z-10 text-xl font-bold text-zinc-800 dark:text-zinc-100 flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z"/>
                                    <path
                                        d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z"/>
                                    <path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z"/>
                                </svg>
                                Database & Cache
                            </h3>
                            <div class="relative z-10 space-y-4">
                                <div class="skill-item">
                                    <span class="text-zinc-600 dark:text-zinc-400">MySQL/PostgreSQL</span>
                                    <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2.5 mt-2">
                                        <div
                                            class="bg-teal-500 dark:bg-teal-400 h-2.5 rounded-full transition-all duration-1000 ease-in-out"
                                            style="width: 95%"></div>
                                    </div>
                                </div>
                                <div class="skill-item">
                                    <span class="text-zinc-600 dark:text-zinc-400">MongoDB/Redis</span>
                                    <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2.5 mt-2">
                                        <div
                                            class="bg-teal-500 dark:bg-teal-400 h-2.5 rounded-full transition-all duration-1000 ease-in-out"
                                            style="width: 85%"></div>
                                    </div>
                                </div>
                                <div class="skill-item">
                                    <span class="text-zinc-600 dark:text-zinc-400">ElasticSearch</span>
                                    <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2.5 mt-2">
                                        <div
                                            class="bg-teal-500 dark:bg-teal-400 h-2.5 rounded-full transition-all duration-1000 ease-in-out"
                                            style="width: 80%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            </section>


            <section id="map">
                <div id="map" class="bg-gray-200 dark:bg-gray-900 border-t border-yellow-400">
                    <iframe class="w-full" style="height: 45vh;"
                            loading="lazy"
                            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA1U9kPUx3SN0u33kKiaCSXl7plnfA3y8Q&q=Brightwood+Park,+Washington,+DC+20011"
                            allowfullscreen>
                    </iframe>
                </div>
            </section>
        </div>
    </main>

    @include('partials.footer')
</div>
@include('partials.back-to-top')
@include('partials.scripts')
@include('partials.schema')
</body>
</html>

