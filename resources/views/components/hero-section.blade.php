<div class="sm:px-8 mt-4 pb-10" x-data="{ show: false }" x-init="setTimeout(() => show = true, 200)">
    <div class="mx-auto max-w-7xl lg:px-8">
        <div class="relative px-4 sm:px-8 lg:px-12">
            <div class="mx-auto max-w-2xl lg:max-w-5xl">
                <div class="max-w-2xl">
                    <h1
                        x-show="show"
                        x-transition:enter="transition ease-out duration-1000"
                        x-transition:enter-start="opacity-0 transform -translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        class="text-4xl font-bold tracking-tight text-zinc-800 dark:text-zinc-100 sm:text-5xl">
                        Crafting digital experiences through code, community, and creativity.
                    </h1>

                    <p
                        x-show="show"
                        x-transition:enter="transition ease-out duration-1000 delay-300"
                        x-transition:enter-start="opacity-0 transform translate-x-4"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        class="mt-6 text-base text-zinc-600 dark:text-zinc-400">
                        I'm Karl, a passionate full-stack software engineer at
                        <a href="https://www.nasa.gov/goddard/" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition duration-300">
                            NASA Goddard Space Flight Center
                        </a>
                        where I architect and develop mission-critical web applications that help share NASA's groundbreaking research with the world. When I'm not coding, I dedicate my time to the arts as a volunteer at
                        <a href="https://americanart.si.edu/visit/renwick" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition duration-300">
                            The Renwick Gallery
                        </a>
                        and the
                        <a href="https://americanart.si.edu/" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition duration-300">
                            Smithsonian American Art Museum
                        </a>.
                        My diverse interests extend into music, where I find creative expression through performance and composition.
                    </p>

                    <div
                        x-show="show"
                        x-transition:enter="transition ease-out duration-1000 delay-500"
                        x-transition:enter-start="opacity-0 transform translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        class="mt-6 flex gap-6">
                        <x-social-link href="https://twitter.com/karl_hill" label="Follow on Twitter" icon="twitter" class="hover:scale-110 transition duration-300" />
                        <x-social-link href="https://github.com/karlhillx" label="Follow on GitHub" icon="github" class="hover:scale-110 transition duration-300" />
                        <x-social-link href="https://www.linkedin.com/in/khill/" label="Follow on LinkedIn" icon="linkedin" class="hover:scale-110 transition duration-300" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
