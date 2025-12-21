<section id="landing" class="bg-hero-pattern min-h-screen w-full pt-24 sm:pt-32 md:pt-48 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-container py-6">
            <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 200)">
                <h1
                    x-show="show"
                    x-transition:enter="transition ease-out duration-800"
                    x-transition:enter-start="opacity-0 transform -translate-y-4"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    class="text-3xl md:text-4xl font-extrabold tracking-tight text-white sm:text-5xl"
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
                    class="text-2xl md:text-4xl font-extrabold tracking-tight text-white sm:text-5xl mt-2 mb-3"
                >
                    Staff Software Engineer
                </h2>
            </div>

            <div
                x-data="{ show: false }"
                x-init="setTimeout(() => show = true, 600)"
                x-show="show"
                x-transition:enter="transition ease-out duration-800"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                class="text-base md:text-lg sm:text-xl uppercase font-medium tracking-tight text-white mt-2 mb-3"
            >
                Cloud Architecture • DevSecOps • Secure Agile Delivery
            </div>

            <div
                x-data="{ show: false }"
                x-init="setTimeout(() => show = true, 800)"
                x-show="show"
                x-transition:enter="transition ease-out duration-800"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="flex gap-2 flex-wrap mt-6"
            >
                <a href="{{ asset('files/karlhill-resume.pdf') }}" target="_blank" class="inline-flex items-center px-4 py-2 border-2 border-white text-sm font-medium rounded-md text-white hover:bg-yellow-600 focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:text-base mb-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Resume
                </a>

                <a href="#career-trajectory" aria-label="View career trajectory" class="inline-flex items-center px-4 py-2 border-2 border-white text-sm font-medium rounded-md text-white hover:bg-indigo-600 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-base mb-2">
                    Career Journey
                </a>

                <a href="#feature" aria-label="Learn more about Karl Hill" class="inline-flex items-center px-4 py-2 border-2 border-white text-sm font-medium rounded-md text-white hover:bg-black focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:text-base mb-2">
                    Learn More
                </a>
            </div>
        </div>
    </div>
</section>
