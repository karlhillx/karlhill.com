<header x-data="{ scrolled: false }" 
        @scroll.window="scrolled = window.scrollY > 20"
        :class="{ 'shadow-xl': scrolled }"
        class="py-3 sm:py-4 color-maroon-dream backdrop-blur-xl fixed w-full top-0 z-50 shadow-lg border-b border-white/10 transition-all duration-300">
    <div class="mx-auto max-w-9xl px-4 sm:px-6 lg:px-8">
        <nav x-data="{ isOpen: false }" 
             class="relative z-50 flex justify-between items-center">
            <!-- Left Side -->
            <div class="flex items-center md:gap-x-12">
                <div class="float-start" data-aos="flip-left">
                    <i class="far fa-paper-plane text-white hover:text-orange-400 transition-all transform hover:scale-110"></i>
                    <a href="mailto:karlhillx@gmail.com" target="_blank" class="text-white ml-1 hover:text-orange-400 transition-colors hidden xs:inline-block sm:inline-block md:inline-block">
                        karlhillx@gmail.com
                    </a>
                    <a href="mailto:karlhillx@gmail.com" target="_blank" class="text-white ml-1 hover:text-orange-400 transition-colors xs:hidden sm:hidden md:hidden">
                        Email
                    </a>
                </div>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-x-6">
                <a href="/" class="text-white/90 hover:text-white transition-all text-sm font-medium px-3 py-2 rounded-lg hover:bg-white/10 relative group">
                    <i class="far fa-compass mr-2 transform group-hover:rotate-45 transition-transform duration-300"></i>Home
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-orange-400 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="#career-trajectory" class="text-white/90 hover:text-white transition-all text-sm font-medium px-3 py-2 rounded-lg hover:bg-white/10 relative group">
                    <i class="fa-solid fa-microchip mr-2 group-hover:scale-110 transition-transform duration-300"></i>Career
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-orange-400 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="#impact-leadership" class="text-white/90 hover:text-white transition-all text-sm font-medium px-3 py-2 rounded-lg hover:bg-white/10 relative group">
                    <i class="far fa-star mr-2 group-hover:scale-110 transition-transform duration-300"></i>Impact
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-orange-400 group-hover:w-full transition-all duration-300"></span>
                </a>
            </div>

            <!-- Hamburger Button -->
            <button @click="isOpen = !isOpen"
                    class="md:hidden text-white p-2.5 hover:bg-white/20 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white/50 active:scale-95"
                    aria-label="Toggle navigation menu"
                    aria-expanded="false"
                    :aria-expanded="isOpen">
                <svg class="h-6 w-6 transition-transform duration-300" :class="{ 'rotate-90': isOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path x-show="!isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Mobile Menu -->
            <div x-show="isOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.away="isOpen = false" 
                 class="absolute right-0 top-full mt-2 w-64 rounded-2xl color-maroon-dream backdrop-blur-xl py-4 shadow-2xl ring-1 ring-white/20 md:hidden glass-dark">
                <a href="/" class="flex items-center px-6 py-3 text-white/90 hover:text-white hover:bg-white/20 transition-all duration-200 rounded-lg mx-2 group">
                    <i class="far fa-compass mr-3 transition-transform duration-300 group-hover:rotate-45"></i>
                    <span class="text-sm font-medium">Home</span>
                </a>
                <a href="#career-trajectory" class="flex items-center px-6 py-3 text-white/90 hover:text-white hover:bg-white/20 transition-all duration-200 rounded-lg mx-2 group">
                    <i class="fa-solid fa-microchip mr-3 transition-transform duration-300 group-hover:scale-110"></i>
                    <span class="text-sm font-medium">Career</span>
                </a>
                <a href="#impact-leadership" class="flex items-center px-6 py-3 text-white/90 hover:text-white hover:bg-white/20 transition-all duration-200 rounded-lg mx-2 group">
                    <i class="far fa-star mr-3 transition-transform duration-300 group-hover:scale-110"></i>
                    <span class="text-sm font-medium">Impact</span>
                </a>
                <a href="mailto:karlhillx@gmail.com" class="flex items-center px-6 py-3 text-white/90 hover:text-white hover:bg-white/20 transition-all duration-200 rounded-lg mx-2 group">
                    <i class="far fa-paper-plane mr-3 transition-transform duration-300 group-hover:translate-x-1"></i>
                    <span class="text-sm font-medium">Contact</span>
                </a>
            </div>
        </nav>
    </div>
</header>
