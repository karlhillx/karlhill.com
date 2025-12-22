<section id="map" class="relative overflow-hidden bg-gradient-to-br from-gray-200 via-gray-100 to-gray-200 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 border-t border-yellow-400 py-8 sm:py-12">
    <!-- Animated background gradient orbs -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-400/20 dark:bg-blue-500/10 rounded-full blur-3xl animate-map-orb-1"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-yellow-400/20 dark:bg-yellow-500/10 rounded-full blur-3xl animate-map-orb-2"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-indigo-400/10 dark:bg-indigo-500/5 rounded-full blur-3xl animate-map-orb-3"></div>
    </div>

    <!-- Animated grid pattern overlay -->
    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05] pointer-events-none">
        <div class="absolute inset-0" style="background-image: linear-gradient(rgba(0,0,0,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(0,0,0,0.1) 1px, transparent 1px); background-size: 50px 50px; animation: map-grid-shift 20s linear infinite;"></div>
    </div>

    <div class="relative max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Location info overlay -->
        <div class="map-location-info mb-6 text-center">
            <div class="inline-flex items-center gap-3 px-6 py-3 bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg rounded-full shadow-lg border border-gray-200/50 dark:border-gray-700/50">
                <i class="fa-solid fa-location-dot text-yellow-500 text-xl animate-map-pin-bounce"></i>
                <span class="text-gray-800 dark:text-gray-200 font-semibold text-sm sm:text-base">Brightwood Park, Washington, DC</span>
                <i class="fa-solid fa-map text-blue-500 text-lg animate-map-compass"></i>
            </div>
        </div>

        <!-- Map container with animated border -->
        <div class="relative map-container">
            <!-- Animated border glow -->
            <div class="absolute -inset-1 bg-gradient-to-r from-yellow-400 via-blue-400 to-indigo-400 rounded-lg opacity-75 dark:opacity-50 blur-sm animate-map-border-glow"></div>
            
            <!-- Map iframe -->
            <div class="relative rounded-lg overflow-hidden shadow-2xl border-2 border-white/50 dark:border-gray-700/50 map-iframe-wrapper">
                <iframe
                    class="w-full transition-all duration-500 hover:opacity-95 filter saturate-[0.85] hover:saturate-100 map-iframe"
                    style="height: 50vh"
                    loading="lazy"
                    src="https://www.google.com/maps?q=Brightwood+Park,+Washington,+DC+20011&z=14&output=embed"
                    allowfullscreen
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Google Maps - Brightwood Park Location">
                </iframe>
            </div>

            <!-- Animated location pin overlay -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none map-pin-overlay">
                <div class="relative">
                    <!-- Pulsing circles -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-16 h-16 bg-yellow-400/30 rounded-full animate-map-pulse-1"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-12 h-12 bg-yellow-400/40 rounded-full animate-map-pulse-2"></div>
                    <!-- Pin icon -->
                    <div class="relative flex items-center justify-center">
                        <i class="fa-solid fa-location-dot text-yellow-500 text-3xl drop-shadow-lg animate-map-pin-float"></i>
                    </div>
                </div>
            </div>

            <!-- Animated corner decorations -->
            <div class="absolute top-2 left-2 w-3 h-3 border-t-2 border-l-2 border-yellow-400 animate-map-corner-1"></div>
            <div class="absolute top-2 right-2 w-3 h-3 border-t-2 border-r-2 border-blue-400 animate-map-corner-2"></div>
            <div class="absolute bottom-2 left-2 w-3 h-3 border-b-2 border-l-2 border-indigo-400 animate-map-corner-3"></div>
            <div class="absolute bottom-2 right-2 w-3 h-3 border-b-2 border-r-2 border-yellow-400 animate-map-corner-4"></div>
        </div>

        <!-- Animated coordinates display -->
        <div class="map-coordinates mt-6 text-center opacity-0">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/60 dark:bg-gray-800/60 backdrop-blur-md rounded-lg text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                <i class="fa-solid fa-globe text-blue-500"></i>
                <span>38.9546° N, 77.0234° W</span>
            </div>
        </div>
    </div>
</section>
