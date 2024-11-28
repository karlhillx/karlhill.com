@php
    $competencies = [
        'left' => [
            [
                'text' => '20+ years of experience delivering enterprise-level software in PHP, Python and Java',
                'icon' => 'fa-solid fa-code'
            ],
            [
                'text' => 'Proficiency with object-oriented design, data structures, algorithms, and asynchronous architectural design',
                'icon' => 'fa-solid fa-diagram-project'
            ],
            [
                'text' => 'Professional experience building REST APIs',
                'icon' => 'fa-solid fa-network-wired'
            ],
            [
                'text' => 'Professional experience with relational databases, schema design, and SQL',
                'icon' => 'fa-solid fa-database'
            ],
            [
                'text' => 'Hands-on experience with Kubernetes and related technologies',
                'icon' => 'fa-solid fa-dharmachakra'
            ]
        ],
        'right' => [
            [
                'text' => 'Strong understanding of containerization technologies like Docker',
                'icon' => 'fa-brands fa-docker'
            ],
            [
                'text' => 'Experience with modern JS frameworks like React and Vue.js',
                'icon' => 'fa-brands fa-react'
            ],
            [
                'text' => 'Experience with cloud providers like AWS',
                'icon' => 'fa-brands fa-aws'
            ],
            [
                'text' => 'Proficiency in version control systems like Git',
                'icon' => 'fa-brands fa-git-alt'
            ],
            [
                'text' => 'Passion for infrastructure and DevOps tooling',
                'icon' => 'fa-solid fa-gears'
            ]
        ]
    ];
@endphp
<section id="core-competencies"
         class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 px-0 py-24">
    <!-- Background Grid Pattern -->
    <div
        class="absolute inset-0 bg-[url('/img/grid.svg')] bg-center [mask-image:linear-gradient(180deg,white,rgba(255,255,255,0))]"></div>

    <!-- Gradient Blob Effects -->
    <div
        class="absolute -top-24 right-0 h-96 w-96 animate-blob rounded-full bg-purple-500 opacity-20 mix-blend-multiply blur-xl filter"></div>
    <div
        class="absolute -bottom-8 left-0 h-96 w-96 animate-blob rounded-full bg-yellow-500 opacity-20 mix-blend-multiply blur-xl filter animation-delay-2000"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-bold tracking-tight text-white sm:text-5xl mb-16">
            <span class="inline-block text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">
                Core Competencies
            </span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach(['left', 'right'] as $column)
                <div class="space-y-4">
                    @foreach($competencies[$column] as $competency)
                        <div
                            class="group bg-white/5 backdrop-blur-lg rounded-xl p-6 border border-white/10 transition-all duration-300 hover:bg-white/10 hover:scale-[1.02] hover:shadow-2xl">
                            <div class="flex items-center space-x-4">
                                <span
                                    class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-lg bg-gradient-to-r from-cyan-400 to-indigo-400">
                                    <i class="{{ $competency['icon'] }} text-xl text-white"></i>
                                </span>
                                <p class="text-lg text-white/90 font-medium">{{ $competency['text'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
            <div class="space-y-4">
                @foreach($competencies[$column] as $competency)
                    <div
                        class="group bg-white/5 backdrop-blur-lg rounded-xl p-6 border border-white/10 transition-all duration-300 hover:bg-white/10 hover:scale-[1.02] hover:shadow-2xl">
                        <div class="flex items-center space-x-4">
                                <span
                                    class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-lg bg-gradient-to-r from-cyan-400 to-indigo-400">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                            <p class="text-lg text-white/90 font-medium">{{ $competency['text'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</section>
