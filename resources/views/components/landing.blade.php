<section id="landing-hero" class="bg-hero-pattern min-h-screen w-full pt-48 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-container py-6">
            <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 200)">
                <h1
                    x-show="show"
                    x-transition:enter="transition ease-out duration-800"
                    x-transition:enter-start="opacity-0 transform -translate-y-4"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl"
                >
                    Karl Hill
                </h1>
            </div>

            <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 400)">
                <h2
                    x-show="show"
                    x-transition:enter="transition ease-out duration-800"
                    x-transition:enter-start="opacity-0 transform translate-y-4"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl mt-2 mb-3"
                >
                    Full Stack Engineer
                </h2>
            </div>

            <div
                x-data="{ show: false }"
                x-init="setTimeout(() => show = true, 600)"
                x-show="show"
                x-transition:enter="transition ease-out duration-800"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                class="text-lg sm:text-xl uppercase font-medium tracking-tight text-white mt-2 mb-3"
            >
                PHP, Laravel, Tailwind, Alpine, Vite
            </div>

            <div
                x-data="{ show: false }"
                x-init="setTimeout(() => show = true, 800)"
                x-show="show"
                x-transition:enter="transition ease-out duration-800"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="flex gap-4 flex-wrap"
            >
                <span class="relative inline-block">
                    <a href="/portfolio" class="inline-flex items-center px-3 py-2 border-2 border-white text-sm font-medium rounded-md text-white hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-base sm:px-4">
                        Portfolio
                    </a>
                    <span class="absolute -top-1 -right-1 flex h-3 w-3">
                        <span class="animate-ping absolute h-full w-full rounded-full bg-yellow-300 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-400"></span>
                    </span>
                </span>

                <a href="{{ asset('files/karlhill-resume.pdf') }}" target="_blank" class="inline-flex items-center px-3 py-2 border-2 border-white text-sm font-medium rounded-md text-white hover:bg-yellow-600 focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:text-base sm:px-4">
                    Resume
                </a>

                <a href="#feature" aria-label="Learn more about Karl Hill" class="inline-flex items-center px-3 py-2 border-2 border-white text-sm font-medium rounded-md text-white hover:bg-black focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:text-base sm:px-4">
                    Learn More
                </a>
            </div>
        </div>
    </div>
</section>
