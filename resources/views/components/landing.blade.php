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
                x-data="{ show: false, resumeModalOpen: false }"
                x-init="setTimeout(() => show = true, 800); $watch('resumeModalOpen', value => { if(value) { document.body.classList.add('resume-modal-open'); } else { document.body.classList.remove('resume-modal-open'); } })"
                x-show="show"
                x-transition:enter="transition ease-out duration-800"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="flex gap-2 flex-wrap mt-6"
            >
                <button @click="resumeModalOpen = true" class="btn-secondary inline-flex items-center px-6 py-3 text-sm font-semibold sm:text-base mb-2 group">
                    <svg class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    View Resume
                </button>

                <!-- Resume Modal -->
                <div x-show="resumeModalOpen" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click.away="resumeModalOpen = false"
                     @keydown.escape.window="resumeModalOpen = false"
                     class="resume-modal-overlay fixed inset-0 flex items-center justify-center p-4 pt-20 sm:pt-24 bg-black/80 backdrop-blur-sm"
                     style="display: none; z-index: 999999;"
                     x-cloak>
                    <div class="relative w-full max-w-5xl max-h-[85vh] bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl overflow-hidden flex flex-col">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between p-4 sm:p-6 border-b border-zinc-200 dark:border-zinc-700 bg-gradient-to-r from-zinc-50 to-white dark:from-zinc-800 dark:to-zinc-900">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Karl Hill - Resume</h3>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Staff Software Engineer</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ asset('files/karlhill-resume.pdf') }}" 
                                   download
                                   class="p-2 text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-colors"
                                   title="Download Resume">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                </a>
                                <button @click="resumeModalOpen = false" 
                                        class="p-2 text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-colors"
                                        aria-label="Close modal">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- PDF Viewer -->
                        <div class="flex-1 overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                            <iframe src="{{ asset('files/karlhill-resume.pdf') }}#toolbar=1&navpanes=1&scrollbar=1" 
                                    class="w-full h-full min-h-[500px] border-0"
                                    title="Resume PDF Viewer">
                            </iframe>
                        </div>
                        
                        <!-- Modal Footer -->
                        <div class="flex items-center justify-between p-4 sm:p-6 border-t border-zinc-200 dark:border-zinc-700 bg-gradient-to-r from-zinc-50 to-white dark:from-zinc-800 dark:to-zinc-900">
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                <span class="hidden sm:inline">Tip: Use the controls above to navigate, zoom, or </span>
                                <a href="{{ asset('files/karlhill-resume.pdf') }}" 
                                   download
                                   class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                    download the PDF
                                </a>
                            </p>
                            <button @click="resumeModalOpen = false" 
                                    class="px-4 py-2 bg-zinc-900 dark:bg-zinc-700 text-white rounded-lg hover:bg-zinc-800 dark:hover:bg-zinc-600 transition-colors text-sm font-medium">
                                Close
                            </button>
                        </div>
                    </div>
                </div>

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
