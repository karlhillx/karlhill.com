@php
    $career = [
        [
            'year' => 'Sep 2025 - Present · 4 mos',
            'role' => 'Staff Aerospace Software Engineer',
            'company' => 'Jacobs',
            'company_url' => 'https://www.jacobs.com/',
            'logo' => '/img/logo-jacobs.png',
            'location' => 'Chantilly, Virginia, United States',
            'description' => 'Leading design and delivery of secure, cloud-native ground systems for space and defense missions. Driving DevSecOps maturity, automation, engineering standards, and Agile execution.',
            'highlights' => [
                'Lead architecture and delivery of cloud-native mission software for modeling and operations',
                'Drive DevSecOps automation for simulation/mission platforms (CI/CD, builds, quality gates)',
                'Implement hardened pipelines enabling secure, traceable, release-ready deployments',
                'Mentor engineers and standardize code quality: reviews, testing, repo conventions, PR hygiene',
                'Align technical delivery with mission needs, schedules, and operational constraints'
            ],
            'level' => 'staff'
        ],
        [
            'year' => 'Dec 2017 - Sep 2025 · 7 yrs 10 mos',
            'role' => 'Lead Software Engineer at NASA',
            'company' => 'Science Systems and Applications, Inc (SSAI)',
            'company_url' => 'https://www.nasa.gov/',
            'logo' => '/img/logo-nasa.png',
            'location' => 'NASA Goddard Space Flight Center (GSFC), Greenbelt, Maryland',
            'description' => 'Led design and delivery of NASA Earth science data systems, including real-time hazard/event detection and major platform modernization efforts supporting large-scale public users. Served as a technical lead across cloud-native architecture, data pipelines, and DevOps, while driving Agile delivery with Jira/Confluence.',
            'highlights' => [
                'Architected and operated real-time flood detection on AWS using satellite data',
                'Modernized Earth data platforms for scale, reliability, and usability',
                'Built Ceph-backed storage services and automated science data pipelines',
                'Shipped containerized services (Docker/Kubernetes) with GitLab CI/CD',
                'Led Agile delivery (Jira/Confluence) and mentored engineers'
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
            <!-- Timeline line with animated gradient -->
            <div class="absolute left-8 md:left-1/2 top-0 bottom-0 w-0.5 bg-gradient-to-b from-indigo-500 via-cyan-500 to-indigo-500 transform md:-translate-x-1/2 career-timeline-line"></div>

            <div class="space-y-12">
                @foreach($career as $index => $position)
                    <div class="relative flex items-start md:items-center">
                        <!-- Timeline dot with pulsing glow -->
                        <div class="absolute left-6 md:left-1/2 w-4 h-4 rounded-full bg-gradient-to-r from-indigo-500 to-cyan-500 border-4 border-white dark:border-gray-900 z-10 shadow-lg career-timeline-dot"
                             style="animation-delay: {{ $index * 0.3 }}s"></div>

                        <!-- Content card -->
                        <div class="ml-16 md:ml-0 md:w-1/2 {{ $index % 2 === 0 ? 'md:mr-auto md:pr-8' : 'md:ml-auto md:pl-8' }}">
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 career-card">
                                <div class="flex items-start gap-4 mb-4">
                                    <img src="{{ $position['logo'] }}" 
                                         alt="{{ $position['company'] }}" 
                                         class="w-12 h-12 rounded-lg object-contain flex-shrink-0 career-logo"
                                         style="animation-delay: {{ $index * 0.2 }}s">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                                {{ $position['role'] }}
                                            </h3>
                                            <span class="text-xs font-semibold px-2 py-1 rounded-full career-badge
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
                                        @if(isset($position['location']))
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $position['location'] }}</p>
                                        @endif
                                    </div>
                                </div>
                                @if(isset($position['description']))
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 leading-relaxed">{{ $position['description'] }}</p>
                                @endif
                                <ul class="space-y-2 mt-4">
                                    @foreach($position['highlights'] as $highlightIndex => $highlight)
                                        <li class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-300">
                                            <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400 flex-shrink-0 mt-0.5 career-check-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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


