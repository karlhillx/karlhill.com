<section id="github-section" class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 px-0 py-16 sm:py-20 md:py-24">
    <!-- Animated background elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl animate-github-orb-1"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl animate-github-orb-2"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-purple-500/5 rounded-full blur-3xl animate-github-orb-3"></div>
    </div>

    <!-- Animated grid pattern -->
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none">
        <div class="absolute inset-0" style="background-image: linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 40px 40px; animation: github-grid-shift 25s linear infinite;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header with animated title -->
        <div class="github-header text-center mb-8 sm:mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-white md:text-5xl mb-4">
                <span class="inline-block text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400 animate-github-title-shimmer">
                    GitHub Activity
                </span>
            </h2>
            <a 
                href="https://github.com/karlhillx" 
                target="_blank" 
                rel="noopener noreferrer"
                class="github-profile-link inline-flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 backdrop-blur-lg rounded-full border border-white/10 hover:border-white/20 transition-all duration-300 group"
            >
                <i class="fab fa-github text-white text-lg group-hover:scale-110 transition-transform duration-300"></i>
                <span class="text-white/90 text-sm sm:text-base font-medium group-hover:text-white transition-colors">View Profile</span>
                <i class="fa-solid fa-arrow-up-right text-white/60 text-xs group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform duration-300"></i>
            </a>
        </div>

        <!-- Stats cards -->
        <div class="github-stats grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-8 sm:mb-12">
            <div class="github-stat-card bg-white/5 backdrop-blur-lg rounded-xl p-4 sm:p-6 border border-white/10 hover:bg-white/10 hover:scale-[1.02] transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm font-medium mb-1">Total Repositories</p>
                        <p class="text-2xl sm:text-3xl font-bold text-white github-stat-repos">-</p>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-indigo-500/20 flex items-center justify-center">
                        <i class="fa-solid fa-code-branch text-indigo-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="github-stat-card bg-white/5 backdrop-blur-lg rounded-xl p-4 sm:p-6 border border-white/10 hover:bg-white/10 hover:scale-[1.02] transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm font-medium mb-1">Languages</p>
                        <p class="text-2xl sm:text-3xl font-bold text-white github-stat-languages">-</p>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-cyan-500/20 flex items-center justify-center">
                        <i class="fa-solid fa-code text-cyan-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="github-stat-card bg-white/5 backdrop-blur-lg rounded-xl p-4 sm:p-6 border border-white/10 hover:bg-white/10 hover:scale-[1.02] transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm font-medium mb-1">Most Used</p>
                        <p class="text-lg sm:text-xl font-bold text-white github-stat-top">-</p>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-purple-500/20 flex items-center justify-center">
                        <i class="fa-solid fa-star text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart container with loading state -->
        <div class="github-chart-container relative">
            <!-- Loading skeleton -->
            <div class="github-loading absolute inset-0 flex items-center justify-center bg-white/5 backdrop-blur-lg rounded-2xl border border-white/10">
                <div class="text-center">
                    <div class="inline-block w-16 h-16 border-4 border-white/20 border-t-indigo-400 rounded-full animate-spin mb-4"></div>
                    <p class="text-white/60 text-sm">Loading GitHub stats...</p>
                </div>
            </div>

            <!-- Chart type toggle -->
            <div class="github-chart-controls flex justify-center gap-2 mb-6">
                <button 
                    class="github-chart-btn active px-4 py-2 bg-white/10 hover:bg-white/20 backdrop-blur-lg rounded-lg border border-white/20 text-white text-sm font-medium transition-all duration-300"
                    data-type="doughnut"
                >
                    <i class="fa-solid fa-circle-notch mr-2"></i>Doughnut
                </button>
                <button 
                    class="github-chart-btn px-4 py-2 bg-white/5 hover:bg-white/10 backdrop-blur-lg rounded-lg border border-white/10 text-white/70 hover:text-white text-sm font-medium transition-all duration-300"
                    data-type="bar"
                >
                    <i class="fa-solid fa-chart-bar mr-2"></i>Bar
                </button>
                <button 
                    class="github-chart-btn px-4 py-2 bg-white/5 hover:bg-white/10 backdrop-blur-lg rounded-lg border border-white/10 text-white/70 hover:text-white text-sm font-medium transition-all duration-300"
                    data-type="pie"
                >
                    <i class="fa-solid fa-chart-pie mr-2"></i>Pie
                </button>
            </div>

            <!-- Chart wrapper -->
            <div class="github-chart-wrapper bg-white/5 backdrop-blur-lg rounded-2xl p-4 sm:p-6 border border-white/10 shadow-2xl">
                <canvas id="github-skills-chart" class="w-full h-64 sm:h-80 md:h-96"></canvas>
            </div>

            <!-- Chart legend with hover effects -->
            <div class="github-legend mt-6 flex flex-wrap justify-center gap-3 sm:gap-4"></div>
        </div>
    </div>
</section>
