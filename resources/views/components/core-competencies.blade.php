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
                        <div
                            class="group bg-white/5 backdrop-blur-lg rounded-xl p-4 sm:p-5 md:p-6 border border-white/10 transition-all duration-300 hover:bg-white/10 hover:scale-[1.02] hover:shadow-2xl skill-item"
                            style="animation-delay: {{ $index * 200 }}ms">
                            <div class="flex items-center space-x-3 sm:space-x-4">
                                <span
                                    class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center rounded-lg bg-gradient-to-r from-cyan-400 to-indigo-400 animate-skillPulse">
                                    <i class="{{ $competency['icon'] }} text-base sm:text-xl text-white"></i>
                                </span>
                                <p class="text-base sm:text-lg text-white/90 font-medium animate-fade-in-up">{{ $competency['text'] }}</p>
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
