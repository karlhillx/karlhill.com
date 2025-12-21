<section id="landing" class="bg-hero-pattern min-h-screen w-full pt-24 sm:pt-32 md:pt-48 pb-16 overflow-hidden relative">
    <!-- Animated background elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="landing-gradient-orb landing-gradient-orb-1"></div>
        <div class="landing-gradient-orb landing-gradient-orb-2"></div>
        <div class="landing-gradient-orb landing-gradient-orb-3"></div>
        
        <!-- Floating particles -->
        <div class="landing-particle landing-particle-1"></div>
        <div class="landing-particle landing-particle-2"></div>
        <div class="landing-particle landing-particle-3"></div>
        <div class="landing-particle landing-particle-4"></div>
        <div class="landing-particle landing-particle-5"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-container py-6 landing-content">
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
                <a href="{{ asset('files/karlhill-resume.pdf') }}" target="_blank" class="btn-secondary inline-flex items-center px-6 py-3 text-sm font-semibold sm:text-base mb-2 group">
                    <svg class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Resume
                </a>

                <a href="#career-trajectory" aria-label="View career trajectory" class="btn-secondary inline-flex items-center px-6 py-3 text-sm font-semibold sm:text-base mb-2 group">
                    <svg class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Career Journey
                </a>

                <a href="#feature" aria-label="Learn more about Karl Hill" class="btn-secondary inline-flex items-center px-6 py-3 text-sm font-semibold sm:text-base mb-2 group">
                    <svg class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Learn More
                </a>
            </div>
        </div>
    </div>
</section>
