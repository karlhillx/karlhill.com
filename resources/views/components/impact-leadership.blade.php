@php
    $impacts = [
        [
            'title' => 'DevSecOps Transformation',
            'category' => 'Leadership & Strategy',
            'description' => 'Led organization-wide adoption of secure CI/CD practices, reducing deployment time by 60% while improving security posture through automated testing and compliance verification.',
            'metrics' => ['60% faster deployments', 'Zero security incidents', '100% automated testing'],
            'icon' => 'fa-solid fa-rocket',
            'color' => 'from-indigo-500 to-purple-600'
        ],
        [
            'title' => 'Cloud-Native Architecture',
            'category' => 'Technical Leadership',
            'description' => 'Architected and delivered Kubernetes-orchestrated platforms supporting mission-critical operations, enabling zero-downtime deployments and horizontal scalability.',
            'metrics' => ['99.9% uptime', 'Auto-scaling enabled', 'Multi-region deployment'],
            'icon' => 'fa-solid fa-cloud',
            'color' => 'from-cyan-500 to-blue-600'
        ],
        [
            'title' => 'Team Mentoring & Growth',
            'category' => 'People Leadership',
            'description' => 'Mentored 15+ engineers across multiple teams, establishing coding standards, Agile practices, and technical governance frameworks that elevated team capabilities.',
            'metrics' => ['15+ engineers mentored', '3x code quality improvement', 'Agile adoption'],
            'icon' => 'fa-solid fa-users',
            'color' => 'from-green-500 to-emerald-600'
        ],
        [
            'title' => 'Mission-Critical Systems',
            'category' => 'Impact',
            'description' => 'Designed and delivered high-assurance ground systems for space operations, ensuring reliability, traceability, and compliance with national security requirements.',
            'metrics' => ['100% mission success', 'Full compliance', 'Real-time operations'],
            'icon' => 'fa-solid fa-shield-halved',
            'color' => 'from-orange-500 to-red-600'
        ],
        [
            'title' => 'Data Platform Innovation',
            'category' => 'Technical Innovation',
            'description' => 'Built distributed data systems handling petabytes of Earth science data, improving discoverability and enabling real-time analytics for NASA missions.',
            'metrics' => ['Petabyte-scale data', 'Real-time indexing', 'Global accessibility'],
            'icon' => 'fa-solid fa-database',
            'color' => 'from-purple-500 to-pink-600'
        ],
        [
            'title' => 'Operational Excellence',
            'category' => 'Process Improvement',
            'description' => 'Established observability and automation frameworks that reduced incident response time by 75% and enabled proactive system health monitoring.',
            'metrics' => ['75% faster response', 'Proactive monitoring', 'Automated recovery'],
            'icon' => 'fa-solid fa-chart-line',
            'color' => 'from-yellow-500 to-orange-600'
        ]
    ];
@endphp

<section id="impact-leadership" class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 px-0 py-16 sm:py-20 md:py-24">
    <div class="absolute inset-0 bg-[url('/img/grid.svg')] bg-center [mask-image:linear-gradient(180deg,white,rgba(255,255,255,0))]"></div>
    <div class="absolute -top-24 right-0 h-96 w-96 animate-blob rounded-full bg-purple-500 opacity-20 mix-blend-multiply blur-xl filter"></div>
    <div class="absolute -bottom-8 left-0 h-96 w-96 animate-blob rounded-full bg-yellow-500 opacity-20 mix-blend-multiply blur-xl filter animation-delay-2000"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-white md:text-5xl mb-4">
                <span class="inline-block text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">
                    Impact & Leadership
                </span>
            </h2>
            <p class="text-lg text-gray-300 dark:text-gray-400 max-w-3xl mx-auto">
                Strategic contributions that drive technical excellence, team growth, and mission success
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach($impacts as $index => $impact)
                <div class="group bg-white/5 backdrop-blur-lg rounded-xl p-6 border border-white/10 transition-all duration-300 hover:bg-white/10 hover:scale-[1.02] hover:shadow-2xl reveal-section"
                     style="animation-delay: {{ $index * 100 }}ms">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gradient-to-r {{ $impact['color'] }} flex items-center justify-center">
                            <i class="{{ $impact['icon'] }} text-white text-xl"></i>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-indigo-400 uppercase tracking-wide">{{ $impact['category'] }}</span>
                            <h3 class="text-xl font-bold text-white mt-1">{{ $impact['title'] }}</h3>
                        </div>
                    </div>
                    
                    <p class="text-gray-300 dark:text-gray-400 mb-4 leading-relaxed">
                        {{ $impact['description'] }}
                    </p>

                    <div class="space-y-2">
                        @foreach($impact['metrics'] as $metricIndex => $metric)
                            <div class="flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-cyan-400 flex-shrink-0 impact-arrow" 
                                     style="animation-delay: {{ $metricIndex * 0.2 }}s"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                                <span class="text-gray-300 font-medium">{{ $metric }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


