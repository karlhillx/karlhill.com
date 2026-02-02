<section id="hero">
    <div class="sm:px-8 mt-4 pb-10"
         x-data="{ show: false }"
         x-init="setTimeout(() => show = true, 200)">
        <div class="mx-auto max-w-7xl lg:px-8">
            <div class="relative px-4 sm:px-8 lg:px-12">
                <!-- Decorative gradient background -->
                <div class="pointer-events-none absolute inset-x-0 -top-24 -z-10 overflow-hidden blur-3xl" aria-hidden="true">
                    <div class="relative left-1/2 -translate-x-1/2 aspect-[1155/678] w-[36rem] sm:w-[72rem] bg-gradient-to-tr from-sky-400 to-indigo-400 opacity-30 animate-blob-slow will-change-transform"></div>
                </div>
                <div class="mx-auto max-w-2xl lg:max-w-5xl">
                    <div class="px-8 mt-20 sm:mt-32 md:mt-40">
                        <!-- Hero Title -->
                        <h2
                            x-show="show"
                            x-transition:enter="transition ease-out duration-1000"
                            x-transition:enter-start="opacity-0 transform -translate-y-4"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            class="text-4xl sm:text-6xl md:text-7xl font-extrabold tracking-tight leading-tight text-transparent bg-clip-text bg-gradient-to-r from-slate-900 via-sky-600 to-indigo-600">
                            Engineering mission-ready software for space systems.
                        </h2>

                        <!-- Bio Section -->
                        <div class="mt-6 max-w-3xl"
                             x-show="show"
                             x-transition:enter="transition ease-out duration-1000 delay-300"
                             x-transition:enter-start="opacity-0 transform translate-x-4"
                             x-transition:enter-end="opacity-100 transform translate-x-0">
                            <p class="text-lg sm:text-xl text-slate-600 link-underline">
                                I’m Karl — a software engineer shaping high-assurance, cloud-native ground systems and mission simulation platforms used in national-security and space-operations environments. I lead DevSecOps adoption, mentor teams, and translate complex mission needs into reliable, traceable, and operational capabilities.
                                Beyond engineering, I stay creative through writing and performing
                                <x-link href="https://www.discogs.com/artist/1286669-Karl-Hill" external>music.</x-link>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
