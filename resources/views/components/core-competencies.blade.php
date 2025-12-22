@php
    $competencies = [
        'left' => [
            [
                'text' => '25+ years engineering secure, mission-critical systems with proven leadership track record',
                'icon' => 'fa-solid fa-shield-halved'
            ],
            [
                'text' => 'Driving DevSecOps strategy and secure CI/CD workflows that accelerate capability delivery',
                'icon' => 'fa-solid fa-cogs'
            ],
            [
                'text' => 'Architecting cloud-native platforms that enhance operational readiness and decision advantage',
                'icon' => 'fa-solid fa-cloud'
            ],
            [
                'text' => 'Leading engineering standards and technical governance to ensure reliability, reproducibility, and compliance',
                'icon' => 'fa-solid fa-gavel'
            ],
            [
                'text' => 'Designing distributed data systems optimized for performance under real-world mission demands',
                'icon' => 'fa-solid fa-database'
            ]
        ],
        'right' => [
            [
                'text' => 'Containerizing and hardening workloads with Kubernetes-orchestrated, zero-downtime deployment patterns',
                'icon' => 'fa-brands fa-docker'
            ],
            [
                'text' => 'Collaborating with operators, analysts, and mission stakeholders to translate operational needs into deployable software capability',
                'icon' => 'fa-solid fa-users'
            ],
            [
                'text' => 'Mentoring and elevating teams through Agile delivery, code quality practices, and modern software craftsmanship',
                'icon' => 'fa-solid fa-chalkboard-teacher'
            ],
            [
                'text' => 'Instrumenting observability, validation, and automation to proactively ensure operational integrity',
                'icon' => 'fa-solid fa-binoculars'
            ],
            [
                'text' => 'Creating operator interfaces and mission workflows that improve situational awareness and execution speed',
                'icon' => 'fa-solid fa-desktop'
            ]
        ]
    ];
@endphp
<section id="core-competencies"
         class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 px-0 py-16 sm:py-20 md:py-24">
    <div
        class="absolute inset-0 bg-[url('/img/grid.svg')] bg-center [mask-image:linear-gradient(180deg,white,rgba(255,255,255,0))]"></div>
    <div
        class="absolute -top-24 right-0 h-96 w-96 animate-blob rounded-full bg-purple-500 opacity-20 mix-blend-multiply blur-xl filter"></div>
    <div
        class="absolute -bottom-8 left-0 h-96 w-96 animate-blob rounded-full bg-yellow-500 opacity-20 mix-blend-multiply blur-xl filter animation-delay-2000"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-white md:text-5xl mb-10 sm:mb-16">
            <span class="inline-block text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400 animate-fade-in">
                Core Competencies
            </span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 md:gap-8">
            @foreach(['left', 'right'] as $column)
                <div class="space-y-3 sm:space-y-4">
                    @foreach($competencies[$column] as $index => $competency)
                        @php
                            // Map icons to animation classes
                            $iconAnimations = [
                                'fa-shield-halved' => 'competency-icon-shield',
                                'fa-cogs' => 'competency-icon-cogs',
                                'fa-cloud' => 'competency-icon-cloud',
                                'fa-gavel' => 'competency-icon-gavel',
                                'fa-database' => 'competency-icon-database',
                                'fa-docker' => 'competency-icon-docker',
                                'fa-users' => 'competency-icon-users',
                                'fa-chalkboard-teacher' => 'competency-icon-teacher',
                                'fa-binoculars' => 'competency-icon-binoculars',
                                'fa-desktop' => 'competency-icon-desktop',
                            ];
                            $iconClass = '';
                            foreach ($iconAnimations as $iconKey => $animationClass) {
                                if (str_contains($competency['icon'], $iconKey)) {
                                    $iconClass = $animationClass;
                                    break;
                                }
                            }
                        @endphp
                        <div
                            class="competency-card group bg-white/5 backdrop-blur-lg rounded-xl p-4 sm:p-5 md:p-6 border border-white/10 transition-all duration-300 hover:bg-white/10 hover:scale-[1.02] hover:shadow-2xl">
                            <div class="flex items-center space-x-3 sm:space-x-4">
                                <span
                                    class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center rounded-lg bg-gradient-to-r from-cyan-400 to-indigo-400 {{ $iconClass }}">
                                    <i class="{{ $competency['icon'] }} text-base sm:text-xl text-white"></i>
                                </span>
                                <p class="text-base sm:text-lg text-white/90 font-medium">{{ $competency['text'] }}</p>
                            </div>
                            <div class="w-full bg-white/10 rounded-full h-1 mt-3 sm:mt-4 overflow-hidden opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                <div class="bg-gradient-to-r from-cyan-400 to-indigo-400 h-1 rounded-full animate-skillPulse" style="width: 100%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</section>
