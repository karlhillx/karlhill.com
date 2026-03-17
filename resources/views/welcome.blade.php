<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karl Hill — Staff Software Engineer</title>
    <meta name="description" content="Karl Hill is a Staff Software Engineer with 25+ years building cloud-native systems, leading high-performing engineering teams, and shipping reliable software at scale.">
    <meta property="og:title" content="Karl Hill — Staff Software Engineer">
    <meta property="og:description" content="20+ years building systems that perform under pressure — from NASA's Earth Observatory to mission-critical aerospace platforms.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:opsz,wght@14..32,300..700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#080808] text-neutral-100 antialiased">

    {{-- Nav --}}
    <nav class="fixed top-0 left-0 right-0 z-50 border-b border-neutral-800/60 bg-[#080808]/90 backdrop-blur-sm">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <span class="font-display text-2xl tracking-wider text-orange-500">KARL HILL</span>
            <a href="mailto:karlhillx@gmail.com"
               class="text-xs font-semibold text-neutral-300 border border-neutral-700 px-5 py-2.5 uppercase tracking-widest hover:border-orange-500 hover:text-orange-500 transition-colors duration-200">
                Get in Touch
            </a>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="min-h-screen flex flex-col justify-end pt-24 pb-16 px-6">
        <div class="max-w-6xl mx-auto w-full">
            <div class="border-t border-neutral-800 pt-12">
                <p class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-8">
                    Staff Software Engineer &nbsp;·&nbsp; 25+ Years
                </p>
                <h1 class="font-display text-[clamp(5rem,20vw,15rem)] leading-none tracking-wide text-white mb-6">
                    Karl Hill
                </h1>
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8 mb-12">
                    <p class="font-display text-[clamp(1.4rem,3.5vw,2.5rem)] text-neutral-500 tracking-widest uppercase">
                        Cloud · Platforms · Engineering Leadership
                    </p>
                    <p class="text-neutral-400 text-base leading-relaxed max-w-md lg:text-right">
                        I architect systems, lead teams, and ship software that matters —
                        from disaster-response platforms at NASA to mission-critical aerospace systems.
                    </p>
                </div>
                <div class="flex flex-wrap gap-4">
                    <a href="https://www.linkedin.com/in/khill/" target="_blank" rel="noopener"
                       class="bg-orange-500 text-black font-bold px-8 py-3.5 text-xs uppercase tracking-widest hover:bg-orange-400 transition-colors duration-200">
                        LinkedIn
                    </a>
                    <a href="mailto:karlhillx@gmail.com"
                       class="border border-neutral-700 text-neutral-300 font-semibold px-8 py-3.5 text-xs uppercase tracking-widest hover:border-orange-500 hover:text-orange-500 transition-colors duration-200">
                        karlhillx@gmail.com
                    </a>
                    <a href="https://github.com/karlhillx" target="_blank" rel="noopener"
                       class="border border-neutral-700 text-neutral-300 font-semibold px-8 py-3.5 text-xs uppercase tracking-widest hover:border-orange-500 hover:text-orange-500 transition-colors duration-200">
                        GitHub
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Why Hire Me --}}
    <section class="py-28 px-6 border-t border-neutral-800">
        <div class="max-w-6xl mx-auto">
            <p class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-16">01 — Why Me</p>
            <div class="grid md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-neutral-800">
                <div class="py-10 md:py-0 md:pr-12">
                    <p class="font-display text-6xl text-orange-500 mb-5">I Build</p>
                    <p class="text-neutral-400 leading-relaxed text-sm">
                        Cloud-native platforms on AWS. Containerized services with Docker and Kubernetes.
                        High-traffic web systems. Secure CI/CD pipelines. Built to last and operate
                        reliably at scale — not just to demo well.
                    </p>
                </div>
                <div class="py-10 md:py-0 md:px-12">
                    <p class="font-display text-6xl text-orange-500 mb-5">I Lead</p>
                    <p class="text-neutral-400 leading-relaxed text-sm">
                        Engineering teams from roadmap to release. 1:1s, onboarding, PR standards,
                        definition of done — the unglamorous work that turns a group of developers
                        into a high-performing team that ships consistently.
                    </p>
                </div>
                <div class="py-10 md:py-0 md:pl-12">
                    <p class="font-display text-6xl text-orange-500 mb-5">I Deliver</p>
                    <p class="text-neutral-400 leading-relaxed text-sm">
                        Predictable execution, every sprint. I translate mission needs into sequenced
                        plans, manage stakeholders across technical and non-technical audiences,
                        remove blockers, and ship.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats Bar --}}
    <section class="border-t border-b border-neutral-800 bg-neutral-900/40">
        <div class="max-w-6xl mx-auto px-6 py-10 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        @foreach([
                    ['25+', 'Years of Experience'],
                ['1.5M', 'Monthly Visitors — NASA EO'],
                ['$105M', 'Platform Acquisition Value'],
                ['~60%', 'Efficiency Gained via Automation'],
            ] as [$stat, $label])
            <div>
                <p class="font-display text-5xl text-orange-500 leading-none">{{ $stat }}</p>
                <p class="font-mono text-xs text-neutral-500 mt-2 uppercase tracking-wide leading-snug">{{ $label }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- Experience --}}
    <section class="py-28 px-6 border-t border-neutral-800">
        <div class="max-w-6xl mx-auto">
            <p class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-16">02 — Experience</p>

            {{-- Current Role - Featured --}}
            <div class="mb-16 p-8 md:p-10 border border-orange-500/25 bg-orange-500/[0.03]">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3 mb-8">
                    <div>
                        <p class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-2">Current Role</p>
                        <h3 class="font-display text-4xl tracking-wide">Staff Aerospace Software Engineer</h3>
                        <p class="text-orange-400 font-medium mt-1.5">Jacobs &nbsp;·&nbsp; Chantilly, VA</p>
                    </div>
                    <span class="font-mono text-xs text-neutral-600 uppercase tracking-widest whitespace-nowrap mt-1">Sept 2025 — Present</span>
                </div>
                <ul class="space-y-4 text-neutral-300 text-sm leading-relaxed">
                    <li class="flex gap-4">
                        <span class="text-orange-500 shrink-0 mt-0.5">→</span>
                        Lead delivery for cloud-native mission-simulation and telemetry services. Own planning, refinement, demos, and stakeholder alignment end-to-end.
                    </li>
                    <li class="flex gap-4">
                        <span class="text-orange-500 shrink-0 mt-0.5">→</span>
                        Own team health and execution — coaching, 1:1s, onboarding, and delivery discipline across multi-repo, multi-environment systems.
                    </li>
                    <li class="flex gap-4">
                        <span class="text-orange-500 shrink-0 mt-0.5">→</span>
                        Set and enforce engineering standards: repo/branch governance, PR reviews, Definition of Done, and documentation to reduce integration risk.
                    </li>
                    <li class="flex gap-4">
                        <span class="text-orange-500 shrink-0 mt-0.5">→</span>
                        Drive DevSecOps and release governance via CI/CD, automated testing, quality gates, and security checks to shorten feedback loops.
                    </li>
                </ul>
            </div>

            {{-- Past Roles --}}
            <div class="space-y-0 divide-y divide-neutral-800">

                {{-- NASA --}}
                <div class="grid md:grid-cols-[220px_1fr] gap-6 md:gap-12 py-14">
                    <div>
                        <h3 class="font-display text-2xl tracking-wide leading-tight">Lead Software Engineer</h3>
                        <p class="text-orange-500 text-sm font-medium mt-2">NASA / SSAI</p>
                        <p class="text-neutral-600 text-sm">Lanham, MD</p>
                        <span class="font-mono text-xs text-neutral-600 mt-3 block">Dec 2017 — Sept 2025</span>
                    </div>
                    <ul class="space-y-4 text-neutral-400 text-sm leading-relaxed self-start">
                        <li class="flex gap-4">
                            <span class="text-orange-500 shrink-0 mt-0.5">→</span>
                            Architected NASA's cloud-based Flood Mapping System on AWS, delivering near real-time satellite-derived flood products to support global disaster response.
                        </li>
                        <li class="flex gap-4">
                            <span class="text-orange-500 shrink-0 mt-0.5">→</span>
                            Rebuilt the NASA Earth Observatory — a platform serving <strong class="text-white font-semibold">~1.5M monthly visitors</strong> — improving performance, UX, and SEO.
                        </li>
                        <li class="flex gap-4">
                            <span class="text-orange-500 shrink-0 mt-0.5">→</span>
                            Built a high-performance Ceph-based file + metadata platform, improving large dataset discovery and access for researchers.
                        </li>
                        <li class="flex gap-4">
                            <span class="text-orange-500 shrink-0 mt-0.5">→</span>
                            Delivered an automated content registry workflow that boosted data collection efficiency by <strong class="text-white font-semibold">~60%</strong> and accelerated researcher access.
                        </li>
                        <li class="flex gap-4">
                            <span class="text-orange-500 shrink-0 mt-0.5">→</span>
                            Implemented GitLab CI/CD + Docker + Kubernetes delivery — automated deployments, repeatable releases, reliable stakeholder approvals.
                        </li>
                    </ul>
                </div>

                {{-- InformedDNA --}}
                <div class="grid md:grid-cols-[220px_1fr] gap-6 md:gap-12 py-14">
                    <div>
                        <h3 class="font-display text-2xl tracking-wide leading-tight">Sr. Software Engineer</h3>
                        <p class="text-orange-500 text-sm font-medium mt-2">InformedDNA</p>
                        <p class="text-neutral-600 text-sm">St. Petersburg, FL</p>
                        <span class="font-mono text-xs text-neutral-600 mt-3 block">Jan 2016 — Dec 2017</span>
                    </div>
                    <ul class="space-y-4 text-neutral-400 text-sm leading-relaxed self-start">
                        <li class="flex gap-4">
                            <span class="text-orange-500 shrink-0 mt-0.5">→</span>
                            Architected a Laravel-based case management platform reducing operational costs by <strong class="text-white font-semibold">$30K/year</strong>.
                        </li>
                        <li class="flex gap-4">
                            <span class="text-orange-500 shrink-0 mt-0.5">→</span>
                            Led CRM enhancements improving client retention and contributing ~15% revenue growth through better lifecycle workflows and reporting.
                        </li>
                        <li class="flex gap-4">
                            <span class="text-orange-500 shrink-0 mt-0.5">→</span>
                            Spearheaded platform upgrades and security improvements, doubling incident response efficiency and strengthening operational readiness.
                        </li>
                    </ul>
                </div>

                {{-- Earlier Roles --}}
                <div class="grid md:grid-cols-[220px_1fr] gap-6 md:gap-12 py-14">
                    <div>
                        <h3 class="font-display text-2xl tracking-wide leading-tight">Earlier Career</h3>
                        <span class="font-mono text-xs text-neutral-600 mt-3 block">1999 — 2015</span>
                    </div>
                    <div class="space-y-6">
                        @foreach([
                            [
                                'Ticomix, Inc.',
                                'Sr. Software Engineer · Washington, D.C. · 2012–2015',
                                'SugarCRM solutions for 20+ clients including VDOT and Kastle Systems. Cut delivery backlog ~90%, improving predictability and customer satisfaction.',
                            ],
                            [
                                'Sabre Corporation',
                                'Software Engineer · Bethesda, MD · 2010–2012',
                                'PHP applications for global travel clients. Mentored junior developers through pairing and code review.',
                            ],
                            [
                                'Dante Inc.',
                                'Principal Software Engineer · Arlington, VA · 2007–2010',
                                'Java solutions for Comcast and Mastercard improving operational efficiency by ~40%. Led Scrum delivery from planning through retros.',
                            ],
                            [
                                'Verizon Business',
                                'Software Developer · Herndon, VA · 1999–2005',
                                'Built Finium, the managed security-services platform that drove a 10× increase in client engagements and supported a $105M acquisition by MCI/Verizon.',
                            ],
                        ] as [$company, $meta, $detail])
                        <div class="flex gap-5">
                            <div class="pt-1.5 shrink-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-orange-500"></div>
                            </div>
                            <div>
                                <p class="font-semibold text-neutral-200 text-sm">{{ $company }}</p>
                                <p class="font-mono text-xs text-neutral-600 mt-0.5">{{ $meta }}</p>
                                <p class="text-neutral-500 text-sm mt-2 leading-relaxed">{{ $detail }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Tech Stack --}}
    <section class="py-28 px-6 border-t border-neutral-800">
        <div class="max-w-6xl mx-auto">
            <p class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-16">03 — Technical Stack</p>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach([
                    ['Languages',      ['Python', 'TypeScript', 'Java', 'PHP', 'Bash']],
                    ['Cloud & Infra',  ['AWS', 'Docker', 'Kubernetes', 'Helm (OCI)', 'Ceph']],
                    ['Delivery & CI/CD', ['GitLab CI', 'GitHub Actions', 'Bitbucket', 'Release Automation']],
                    ['Web & UI',       ['React', 'Node.js', 'Laravel', 'Vite', 'Tailwind', 'OpenAPI/Swagger']],
                    ['Data Platforms', ['PostgreSQL', 'MySQL', 'MongoDB', 'Redis', 'Elasticsearch']],
                    ['Leadership',     ['Agile / Scrum', 'Team Coaching', 'Roadmapping', 'DevSecOps', 'Stakeholder Mgmt']],
                ] as [$category, $skills])
                <div>
                    <p class="font-display text-lg text-neutral-500 tracking-widest mb-4">{{ $category }}</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($skills as $skill)
                        <span class="font-mono text-xs px-3 py-1.5 border border-neutral-800 text-neutral-400 hover:border-orange-500/60 hover:text-orange-400 transition-colors cursor-default">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Certifications --}}
    <section class="py-28 px-6 border-t border-neutral-800">
        <div class="max-w-6xl mx-auto">
            <p class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-16">04 — Certifications & Education</p>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-px bg-neutral-800">
                @foreach([
                    ['PSM II',  'Professional Scrum Master™ II',       'Scrum.org'],
                    ['PSD I',   'Professional Scrum Developer™ I',      'Scrum.org'],
                    ['PSPO I',  'Professional Scrum Product Owner™ I',  'Scrum.org'],
                    ['CSM',     'Certified ScrumMaster®',               'Scrum Alliance'],
                ] as [$abbr, $name, $issuer])
                <div class="bg-[#080808] p-8 hover:bg-neutral-900/60 transition-colors">
                    <p class="font-display text-5xl text-orange-500 mb-3">{{ $abbr }}</p>
                    <p class="text-sm text-neutral-300 font-medium leading-snug">{{ $name }}</p>
                    <p class="font-mono text-xs text-neutral-600 mt-3">{{ $issuer }}</p>
                </div>
                @endforeach
            </div>
            <div class="mt-px bg-neutral-800">
                <div class="bg-[#080808] p-8">
                    <p class="font-display text-lg text-neutral-500 tracking-widest mb-4">Education</p>
                    <div class="flex flex-col sm:flex-row gap-8">
                        <div>
                            <p class="text-sm text-neutral-300 font-medium">B.S. Computer Science Coursework</p>
                            <p class="font-mono text-xs text-neutral-600 mt-1">University of Maryland</p>
                        </div>
                        <div>
                            <p class="text-sm text-neutral-300 font-medium">Associate of Arts, General Studies</p>
                            <p class="font-mono text-xs text-neutral-600 mt-1">Howard Community College</p>
                        </div>
                        <div>
                            <p class="text-sm text-neutral-300 font-medium">Project Management</p>
                            <p class="font-mono text-xs text-neutral-600 mt-1">Rutgers University</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact / Footer --}}
    <footer class="border-t border-neutral-800 py-24 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-12">
                <div class="max-w-xl">
                    <p class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-6">05 — Contact</p>
                    <h2 class="font-display text-[clamp(3rem,8vw,6rem)] leading-none tracking-wide mb-6">
                        Let's Work<br>Together
                    </h2>
                    <p class="text-neutral-500 text-sm leading-relaxed max-w-sm">
                        Building something important and need an engineer who can lead, architect, and deliver?
                        I'd like to hear about it.
                    </p>
                </div>
                <div class="flex flex-col gap-4 lg:pt-16 shrink-0">
                    <a href="mailto:karlhillx@gmail.com"
                       class="flex items-center gap-4 font-mono text-sm text-neutral-400 hover:text-orange-500 transition-colors group">
                        <span class="text-orange-500 text-base group-hover:translate-x-0.5 transition-transform">→</span>
                        karlhillx@gmail.com
                    </a>
                    <a href="tel:+12023045556"
                       class="flex items-center gap-4 font-mono text-sm text-neutral-400 hover:text-orange-500 transition-colors group">
                        <span class="text-orange-500 text-base group-hover:translate-x-0.5 transition-transform">→</span>
                        202-304-5556
                    </a>
                    <a href="https://www.linkedin.com/in/khill/" target="_blank" rel="noopener"
                       class="flex items-center gap-4 font-mono text-sm text-neutral-400 hover:text-orange-500 transition-colors group">
                        <span class="text-orange-500 text-base group-hover:translate-x-0.5 transition-transform">↗</span>
                        linkedin.com/in/khill
                    </a>
                    <a href="https://github.com/karlhillx" target="_blank" rel="noopener"
                       class="flex items-center gap-4 font-mono text-sm text-neutral-400 hover:text-orange-500 transition-colors group">
                        <span class="text-orange-500 text-base group-hover:translate-x-0.5 transition-transform">↗</span>
                        github.com/karlhillx
                    </a>
                    <a href="https://karlhill.com" target="_blank" rel="noopener"
                       class="flex items-center gap-4 font-mono text-sm text-neutral-400 hover:text-orange-500 transition-colors group">
                        <span class="text-orange-500 text-base group-hover:translate-x-0.5 transition-transform">↗</span>
                        karlhill.com
                    </a>
                </div>
            </div>
            <div class="mt-20 pt-8 border-t border-neutral-800/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <p class="font-display text-3xl tracking-widest text-neutral-800">Karl Hill</p>
                <p class="font-mono text-xs text-neutral-700">Washington, DC &nbsp;·&nbsp; Staff Software Engineer &nbsp;·&nbsp; 25+ Years</p>
                <p class="font-mono text-xs text-neutral-800 mt-1">Laravel {{ app()->version() }}</p>
            </div>
        </div>
    </footer>

</body>
</html>
