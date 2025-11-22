@php
    $career = [
        [
            'year' => 'Sep 2025 - Present',
            'role' => 'Staff Aerospace Software Engineer',
            'company' => 'Jacobs',
            'company_url' => 'https://www.jacobs.com/',
            'logo' => '/img/logo-jacobs.png',
            'highlights' => [
                'Deliver mission-simulation and algorithm services aligned to operational needs',
                'Drive Agile execution and stakeholder updates for predictable delivery',
                'Define repo/branching/PR standards to reduce integration risk',
                'Build CI/CD with tests and security scans for safe, fast releases',
                'Use containers and IaC for reproducible environments across deployments',
                'Mentor engineers on DevSecOps and platform-first practices'
            ],
            'level' => 'staff'
        ],
        [
            'year' => 'Dec 2017 - Sep 2025',
            'role' => 'Lead Software Engineer',
            'company' => 'NASA/SSAI',
            'company_url' => 'https://www.nasa.gov/',
            'logo' => '/img/logo-nasa.png',
            'highlights' => [
                'Leading DevSecOps transformation for mission-critical ground systems',
                'Architecting cloud-native platforms for space operations',
                'Mentoring engineering teams in secure Agile delivery practices',
                'Designing high-assurance systems for national security environments'
            ],
            'level' => 'lead'
        ],
        [
            'year' => '2016 - 2017',
            'role' => 'Senior Software Engineer',
            'company' => 'InformedDNA',
            'company_url' => 'https://informeddna.com/',
            'logo' => '/img/logo-informeddna.png',
            'highlights' => [
                'Built precision medicine platform handling genomic data',
                'Led Laravel-based application architecture',
                'Delivered healthcare solutions with clinical impact'
            ],
            'level' => 'senior'
        ],
        [
            'year' => '2012 - 2015',
            'role' => 'Senior Software Engineer',
            'company' => 'Ticomix',
            'company_url' => 'https://www.ticomix.com/',
            'logo' => '/img/logo-ticomix-small.png',
            'highlights' => [
                'Developed enterprise CRM solutions',
                'Led technical initiatives for client implementations'
            ],
            'level' => 'senior'
        ],
        [
            'year' => '2010 - 2012',
            'role' => 'Software Engineer',
            'company' => 'Sabre Corporation',
            'company_url' => 'https://www.sabre.com/',
            'logo' => '/img/logo-sabre-small.png',
            'highlights' => [
                'Built travel technology solutions',
                'Contributed to enterprise-scale systems'
            ],
            'level' => 'mid'
        ],
        [
            'year' => '2007 - 2010',
            'role' => 'Software Engineer',
            'company' => 'Dante Inc.',
            'company_url' => 'https://www.danteinc.com/',
            'logo' => '/img/logo-dante-small.png',
            'highlights' => [
                'Developed UML modeling tools and enterprise applications',
                'Worked on model-driven architecture platforms'
            ],
            'level' => 'mid'
        ],
        [
            'year' => '1999 - 2005',
            'role' => 'Software Developer',
            'company' => 'Verizon Business',
            'company_url' => 'https://www.verizon.com/business/',
            'logo' => '/img/logo-verizon-small.png',
            'highlights' => [
                'Built cybersecurity platforms and enterprise solutions',
                'Developed foundational software engineering skills'
            ],
            'level' => 'junior'
        ]
    ];
@endphp

<section id="career-trajectory" class="relative overflow-hidden bg-gradient-to-b from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 px-0 py-16 sm:py-20 md:py-24">
    <div class="absolute inset-0 bg-[url('/img/grid-pattern.svg')] bg-center opacity-5"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-gray-900 dark:text-white md:text-5xl mb-4">
                <span class="inline-block text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-cyan-600 dark:from-indigo-400 dark:to-cyan-400">
                    Career Trajectory
                </span>
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                25+ years of progressive engineering leadership, from building foundational systems to leading mission-critical platforms
            </p>
        </div>

        <div class="relative">
            <!-- Timeline line -->
            <div class="absolute left-8 md:left-1/2 top-0 bottom-0 w-0.5 bg-gradient-to-b from-indigo-500 via-cyan-500 to-indigo-500 transform md:-translate-x-1/2"></div>

            <div class="space-y-12">
                @foreach($career as $index => $position)
                    <div class="relative flex items-start md:items-center" 
                         x-data="{ inView: false }"
                         x-intersect="inView = true">
                        <!-- Timeline dot -->
                        <div class="absolute left-6 md:left-1/2 w-4 h-4 rounded-full bg-gradient-to-r from-indigo-500 to-cyan-500 border-4 border-white dark:border-gray-900 transform md:-translate-x-1/2 z-10 shadow-lg"
                             :class="{ 'scale-125': inView }"
                             style="transition: transform 0.3s ease;"></div>

                        <!-- Content card -->
                        <div class="ml-16 md:ml-0 md:w-1/2 {{ $index % 2 === 0 ? 'md:mr-auto md:pr-8' : 'md:ml-auto md:pl-8' }}"
                             x-show="inView"
                             x-transition:enter="transition ease-out duration-700"
                             x-transition:enter-start="opacity-0 {{ $index % 2 === 0 ? 'transform translate-x-8' : 'transform -translate-x-8' }}"
                             x-transition:enter-end="opacity-100 transform translate-x-0">
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700">
                                <div class="flex items-start gap-4 mb-4">
                                    <img src="{{ $position['logo'] }}" 
                                         alt="{{ $position['company'] }}" 
                                         class="w-12 h-12 rounded-lg object-contain flex-shrink-0">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                                {{ $position['role'] }}
                                            </h3>
                                            <span class="text-xs font-semibold px-2 py-1 rounded-full
                                                @if($position['level'] === 'staff') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300
                                                @elseif($position['level'] === 'lead') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300
                                                @elseif($position['level'] === 'senior') bg-cyan-100 text-cyan-800 dark:bg-cyan-900/30 dark:text-cyan-300
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                                @endif">
                                                {{ strtoupper($position['level']) }}
                                            </span>
                                        </div>
                                        <a href="{{ $position['company_url'] }}" 
                                           target="_blank"
                                           class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium text-sm">
                                            {{ $position['company'] }}
                                        </a>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $position['year'] }}</p>
                                    </div>
                                </div>
                                <ul class="space-y-2 mt-4">
                                    @foreach($position['highlights'] as $highlight)
                                        <li class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-300">
                                            <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>{{ $highlight }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


