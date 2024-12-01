<section id="hero">
    <div class="sm:px-8 mt-4 pb-10"
         x-data="{ show: false }"
         x-init="setTimeout(() => show = true, 200)">
        <div class="mx-auto max-w-7xl lg:px-8">
            <div class="relative px-4 sm:px-8 lg:px-12">
                <div class="mx-auto max-w-2xl lg:max-w-5xl">
                    <div class="px-8 mt-20 sm:mt-32 md:mt-40">
                        <!-- Hero Title -->
                        <h2
                            x-show="show"
                            x-transition:enter="transition ease-out duration-1000"
                            x-transition:enter-start="opacity-0 transform -translate-y-4"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            class="text-4xl tracking-tight font-extrabold sm:text-5xl text-slate-900 dark:text-white">
                            Crafting digital experiences through code, community, and creativity.
                        </h2>

                        <!-- Bio Section -->
                        <div class="mt-6 max-w-3xl"
                             x-show="show"
                             x-transition:enter="transition ease-out duration-1000 delay-300"
                             x-transition:enter-start="opacity-0 transform translate-x-4"
                             x-transition:enter-end="opacity-100 transform translate-x-0">
                            <p class="text-lg">
                                I'm Karl, a passionate full-stack software engineer at
                                <x-link href="https://www.nasa.gov/goddard/">NASA Goddard Space Flight Center</x-link>
                                where I architect and develop mission-critical web applications that help share NASA's
                                groundbreaking research with the world. When I'm not coding, I dedicate my time to the
                                arts as a
                                volunteer at
                                <x-link href="https://americanart.si.edu/visit/renwick" external>The Renwick Gallery
                                </x-link>
                                and the
                                <x-link href="https://americanart.si.edu/" external>Smithsonian American Art Museum
                                </x-link>
                                .
                                My diverse interests extend into music, where I find creative expression through
                                performance and
                                composition.
                            </p>
                        </div>

                        <!-- Social Links -->
                        <div class="mt-6 flex gap-6"
                             x-show="show"
                             x-transition:enter="transition ease-out duration-1000 delay-500"
                             x-transition:enter-start="opacity-0 transform translate-y-4"
                             x-transition:enter-end="opacity-100 transform translate-y-0">
                            @foreach([
                                ['href' => 'https://twitter.com/karl_hill', 'label' => 'Twitter', 'icon' => 'twitter'],
                                ['href' => 'https://github.com/karlhillx', 'label' => 'GitHub', 'icon' => 'github'],
                                ['href' => 'https://www.linkedin.com/in/khill/', 'label' => 'LinkedIn', 'icon' => 'linkedin'],
                            ] as $social)
                                <x-social-link
                                    :href="$social['href']"
                                    :label="'Follow on ' . $social['label']"
                                    :icon="$social['icon']"
                                    class="hover:scale-110 transition duration-300"
                                />
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
