<section class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 px-0 py-16 sm:py-20 md:py-24">
    <!-- Background Grid Pattern -->
    <div class="absolute inset-0 bg-[url('/img/grid.svg')] bg-center [mask-image:linear-gradient(180deg,white,rgba(255,255,255,0))]"></div>

    <!-- Gradient Blob Effects -->
    <div class="absolute -top-24 right-0 h-96 w-96 animate-blob rounded-full bg-purple-500 opacity-20 mix-blend-multiply blur-xl filter"></div>
    <div class="absolute -bottom-8 left-0 h-96 w-96 animate-blob rounded-full bg-yellow-500 opacity-20 mix-blend-multiply blur-xl filter animation-delay-2000"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-white md:text-5xl mb-10 sm:mb-16">
            <span class="inline-block text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">
                Core Technologies
            </span>
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 md:gap-8">
            @foreach ([
                'Backend Development' => [
                    'PHP/Laravel' => ['level' => 95, 'icon' => 'fa-brands fa-laravel'],
                    'Node.js' => ['level' => 85, 'icon' => 'fa-brands fa-node-js'],
                    'Python' => ['level' => 80, 'icon' => 'fa-brands fa-python']
                ],
                'Frontend Engineering' => [
                    'Vue/React' => ['level' => 90, 'icon' => 'fa-brands fa-vuejs'],
                    'Tailwind/Alpine' => ['level' => 95, 'icon' => 'fa-solid fa-wind'],
                    'TypeScript' => ['level' => 85, 'icon' => 'fa-solid fa-code']
                ],
                'Cloud Architecture' => [
                    'AWS/GCP' => ['level' => 85, 'icon' => 'fa-brands fa-aws'],
                    'Docker/K8s' => ['level' => 90, 'icon' => 'fa-brands fa-docker'],
                    'CI/CD' => ['level' => 90, 'icon' => 'fa-solid fa-rotate']
                ],
                'Data Management' => [
                    'MySQL/PostgreSQL' => ['level' => 95, 'icon' => 'fa-solid fa-database'],
                    'MongoDB/Redis' => ['level' => 85, 'icon' => 'fa-solid fa-leaf'],
                    'ElasticSearch' => ['level' => 80, 'icon' => 'fa-solid fa-magnifying-glass']
                ]
            ] as $category => $skills)
                <div class="group bg-white/5 backdrop-blur-lg rounded-xl p-5 sm:p-6 md:p-8 border border-white/10 transition-all duration-300 hover:bg-white/10 hover:scale-[1.02] hover:shadow-2xl">
                    <h3 class="text-lg sm:text-xl font-bold text-white/90 mb-5 sm:mb-8">{{ $category }}</h3>
                    @foreach ($skills as $skillName => $details)
                        <div class="skill-item space-y-3 sm:space-y-4 mb-4 sm:mb-6">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <span class="flex-shrink-0 w-6 h-6 sm:w-8 sm:h-8 flex items-center justify-center rounded-lg bg-gradient-to-r from-cyan-400 to-indigo-400">
                                    <i class="{{ $details['icon'] }} text-sm sm:text-base text-white"></i>
                                </span>
                                <span class="text-sm sm:text-base text-white/90 font-medium">{{ $skillName }}</span>
                            </div>
                            <div class="w-full bg-white/10 rounded-full h-1 sm:h-1.5 overflow-hidden">
                                <div
                                    class="bg-gradient-to-r from-cyan-400 to-indigo-400 h-1 sm:h-1.5 rounded-full transition-all duration-1000"
                                    style="width: {{ $details['level'] }}%">
                                </div>
                            </div>
                            <div class="w-full bg-white/10 rounded-full h-1 sm:h-1.5 overflow-hidden">
                                <div
                                    class="bg-gradient-to-r from-cyan-400 to-indigo-400 h-1 sm:h-1.5 rounded-full transition-all duration-1000 animate-skillPulse"
                                    style="width: {{ $details['level'] }}%">
                                </div>
                            </div>
                            <div class="w-full bg-white/10 rounded-full h-1 sm:h-1.5 overflow-hidden">
                                <div
                                    class="bg-gradient-to-r from-cyan-400 to-indigo-400 h-1 sm:h-1.5 rounded-full transition-all duration-1000 animate-skillPulse"
                                    style="width: {{ $details['level'] }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</section>
