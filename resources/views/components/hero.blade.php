<section id="hero">
    <div class="sm:px-8 mt-4 pb-10"
         x-data="{ show: false }"
         x-init="setTimeout(() => show = true, 200)">
        <div class="mx-auto max-w-7xl lg:px-8">
            <div class="relative px-4 sm:px-8 lg:px-12">
                <!-- Decorative gradient background -->
                <div class="pointer-events-none absolute inset-x-0 -top-24 -z-10 overflow-hidden blur-3xl" aria-hidden="true">
                    <div class="relative left-1/2 -translate-x-1/2 aspect-[1155/678] w-[36rem] sm:w-[72rem] bg-gradient-to-tr from-sky-400 to-indigo-400 opacity-30 dark:opacity-20 animate-blob-slow will-change-transform"></div>
                </div>
                <div class="mx-auto max-w-2xl lg:max-w-5xl">
                    <div class="px-8 mt-20 sm:mt-32 md:mt-40">
                        <!-- Hero Title -->
                        <h2
                            x-show="show"
                            x-transition:enter="transition ease-out duration-1000"
                            x-transition:enter-start="opacity-0 transform -translate-y-4"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            class="text-4xl sm:text-6xl md:text-7xl font-extrabold tracking-tight leading-tight text-transparent bg-clip-text bg-gradient-to-r from-slate-900 via-sky-600 to-indigo-600 dark:from-white dark:via-sky-400 dark:to-indigo-400">
                            Building reliable, accessible web platforms.
                        </h2>

                        <!-- Bio Section -->
                        <div class="mt-6 max-w-3xl"
                             x-show="show"
                             x-transition:enter="transition ease-out duration-1000 delay-300"
                             x-transition:enter-start="opacity-0 transform translate-x-4"
                             x-transition:enter-end="opacity-100 transform translate-x-0">
                            <p class="text-lg sm:text-xl text-slate-600 dark:text-slate-300 link-underline">
                                I’m Karl — a full‑stack engineer building performant, accessible platforms at
                                <x-link href="https://www.nasa.gov/goddard/">NASA’s Goddard Space Flight Center.</x-link>
                                I ship mission‑critical apps, mentor teams, and turn complex ideas into usable products.
                                Beyond code, I volunteer with the
                                <x-link href="https://americanart.si.edu/visit/renwick" external>Renwick Gallery</x-link>
                                and the
                                <x-link href="https://americanart.si.edu/" external>Smithsonian American Art Museum,</x-link>
                                and I compose and perform
                                <x-link href="https://www.discogs.com/artist/1286669-Karl-Hill" external>music.</x-link>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
