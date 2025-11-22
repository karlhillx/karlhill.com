<header class="py-3 sm:py-4 color-maroon-dream bg-opacity-90 backdrop-filter backdrop-blur-md fixed w-full top-0 z-50 shadow-lg">
    <div class="mx-auto max-w-9xl px-4 sm:px-6 lg:px-8">
        <nav x-data="{ isOpen: false }" class="relative z-50 flex justify-between items-center">
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
            <div class="hidden md:flex items-center gap-x-8">
                <a href="/" class="text-white hover:text-orange-400 transition-colors text-sm font-medium">
                    <i class="far fa-compass mr-2 transform hover:rotate-45 transition-transform"></i>Home
                </a>
                <a href="#career-trajectory" class="text-white hover:text-orange-400 transition-colors text-sm font-medium">
                    <i class="fa-solid fa-microchip mr-2 hover:scale-110 transition-transform"></i>Career
                </a>
                <a href="#impact-leadership" class="text-white hover:text-orange-400 transition-colors text-sm font-medium">
                    <i class="far fa-star mr-2 hover:scale-110 transition-transform"></i>Impact
                </a>
            </div>

            <!-- Hamburger Button -->
            <button @click="isOpen = !isOpen"
                    class="md:hidden text-white p-2 hover:bg-white/20 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white/50">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path x-show="!isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Mobile Menu -->
            <div x-show="isOpen" @click.away="isOpen = false" class="absolute right-0 top-full mt-2 w-56 rounded-xl color-maroon-dream backdrop-blur-xl py-3 shadow-xl ring-1 ring-white/20 md:hidden">
                <a href="/" class="flex items-center px-6 py-3 text-white hover:bg-white/20 transition-all duration-200">
                    <i class="far fa-compass mr-3 transition-transform hover:rotate-45"></i>
                    <span class="text-sm font-medium">Home</span>
                </a>
                <a href="#career-trajectory" class="flex items-center px-6 py-3 text-white hover:bg-white/20 transition-all duration-200">
                    <i class="fa-solid fa-microchip mr-3"></i>
                    <span class="text-sm font-medium">Career</span>
                </a>
                <a href="#impact-leadership" class="flex items-center px-6 py-3 text-white hover:bg-white/20 transition-all duration-200">
                    <i class="far fa-star mr-3"></i>
                    <span class="text-sm font-medium">Impact</span>
                </a>
                <a href="mailto:karlhillx@gmail.com" class="flex items-center px-6 py-3 text-white hover:bg-white/20 transition-all duration-200">
                    <i class="far fa-paper-plane mr-3"></i>
                    <span class="text-sm font-medium">Contact</span>
                </a>
            </div>
        </nav>
    </div>
</header>
