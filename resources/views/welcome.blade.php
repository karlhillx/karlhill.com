<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karl Hill — Staff Software Engineer</title>
    <meta name="description" content="Karl Hill is a Staff Software Engineer with 25+ years building cloud-native systems, leading high-performing engineering teams, and shipping reliable software at scale.">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://karlhill.com">
    <meta property="og:title" content="Karl Hill — Staff Software Engineer">
    <meta property="og:description" content="25+ years building systems that perform under pressure — from NASA's Earth Observatory to mission-critical aerospace platforms.">
    <meta property="og:image" content="https://karlhill.com/img/profile.jpg">

    {{-- Twitter / X --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Karl Hill — Staff Software Engineer">
    <meta name="twitter:description" content="25+ years building systems that perform under pressure — from NASA's Earth Observatory to mission-critical aerospace platforms.">
    <meta name="twitter:image" content="https://karlhill.com/img/profile.jpg">

    <link rel="canonical" href="https://karlhill.com">

    {{-- Favicons --}}
    <link rel="icon" type="image/svg+xml" href="/img/favicon.svg">
    <link rel="icon" type="image/png" sizes="96x96" href="/img/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
    <link rel="manifest" href="/img/site.webmanifest">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:opsz,wght@14..32,300..700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Structured Data --}}
    @verbatim
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Person",
        "name": "Karl Hill",
        "jobTitle": "Staff Software Engineer",
        "url": "https://karlhill.com",
        "image": "https://karlhill.com/img/profile.jpg",
        "email": "karlhillx@gmail.com",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Washington",
            "addressRegion": "DC",
            "addressCountry": "US"
        },
        "sameAs": [
            "https://www.linkedin.com/in/khill/",
            "https://github.com/karlhillx"
        ]
    }
    </script>
    @endverbatim
</head>
<body class="bg-[#080808] text-neutral-100 antialiased">

    {{-- Nav --}}
    <nav class="fixed top-0 left-0 right-0 z-50 border-b border-neutral-800/60 bg-[#080808]/90 backdrop-blur-sm">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <span class="font-display text-2xl tracking-wider text-orange-500">KARL HILL</span>
            <div class="hidden md:flex items-center gap-8 font-mono text-xs text-neutral-500 uppercase tracking-widest">
                <a href="#experience" class="hover:text-orange-500 transition-colors duration-200">Experience</a>
                <a href="#work" class="hover:text-orange-500 transition-colors duration-200">Work</a>
                <a href="#stack" class="hover:text-orange-500 transition-colors duration-200">Stack</a>
                <a href="#contact" class="hover:text-orange-500 transition-colors duration-200">Contact</a>
            </div>
            <a href="mailto:karlhillx@gmail.com"
               class="text-xs font-semibold text-neutral-300 border border-neutral-700 px-5 py-2.5 uppercase tracking-widest hover:border-orange-500 hover:text-orange-500 transition-colors duration-200">
                Get in Touch
            </a>
        </div>
    </nav>

    {{-- Hero --}}
    <section id="hero" class="min-h-screen flex flex-col justify-end pt-24 pb-16 px-6">
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
                        from disaster-response platforms at NASA to mission-critical aerospace systems at Jacobs/BlackLynx.
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
    <section id="why" class="py-28 px-6 border-t border-neutral-800">
        <div class="max-w-6xl mx-auto">
            <p class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-16" data-reveal>01 — Why Me</p>
            <div class="grid md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-neutral-800">
                <div class="py-10 md:py-0 md:pr-12" data-reveal>
                    <p class="font-display text-6xl text-orange-500 mb-5">I Build</p>
                    <p class="text-neutral-400 leading-relaxed text-sm">
                        Cloud-native platforms on AWS. Containerized services with Docker and Kubernetes.
                        High-traffic web systems. Secure CI/CD pipelines. Built to last and operate
                        reliably at scale — not just to demo well.
                    </p>
                </div>
                <div class="py-10 md:py-0 md:px-12" data-reveal>
                    <p class="font-display text-6xl text-orange-500 mb-5">I Lead</p>
                    <p class="text-neutral-400 leading-relaxed text-sm">
                        Engineering teams from roadmap to release. 1:1s, onboarding, PR standards,
                        definition of done — the unglamorous work that turns a group of developers
                        into a high-performing team that ships consistently.
                    </p>
                </div>
                <div class="py-10 md:py-0 md:pl-12" data-reveal>
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
            ['25+',   'Years of Experience',              25,  '',  '+'],
            ['1.5M',  'Monthly Visitors — NASA PLATFORMS',       1.5, '',  'M'],
            ['$105M', 'Platform Acquisition Value',       105, '$', 'M'],
            ['~60%',  'Efficiency Gained via Automation', 60,  '~', '%'],
        ] as [$stat, $label, $to, $prefix, $suffix])
        <div data-reveal>
            <p class="font-display text-5xl text-orange-500 leading-none"
               data-counter
               data-to="{{ $to }}"
               data-prefix="{{ $prefix }}"
               data-suffix="{{ $suffix }}"
               data-final="{{ $stat }}">{{ $stat }}</p>
            <p class="font-mono text-xs text-neutral-500 mt-2 uppercase tracking-wide leading-snug">{{ $label }}</p>
        </div>
        @endforeach
        </div>
    </section>

    {{-- Experience --}}
    <section id="experience" class="py-28 px-6 border-t border-neutral-800">
        <div class="max-w-6xl mx-auto">
            <p class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-16" data-reveal>02 — Experience</p>

            {{-- Current Role --}}
            <div class="mb-16 p-8 md:p-10 border border-orange-500/25 bg-orange-500/[0.03]" data-reveal>
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

                <div class="grid md:grid-cols-[220px_1fr] gap-6 md:gap-12 py-14" data-reveal>
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

                <div class="grid md:grid-cols-[220px_1fr] gap-6 md:gap-12 py-14" data-reveal>
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

                <div class="grid md:grid-cols-[220px_1fr] gap-6 md:gap-12 py-14" data-reveal>
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

    {{-- Selected Work --}}
    <section id="work" class="py-28 px-6 border-t border-neutral-800">
        <div class="max-w-6xl mx-auto">
            <p class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-16" data-reveal>03 — Selected Work</p>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach([
                    [
                        'NASA Earth Observatory',
                        'NASA &nbsp;·&nbsp; 2017–2025',
                        'Flagship science communication platform serving 1.5M+ monthly visitors with satellite imagery and Earth science data. Rebuilt the publishing pipeline to unify editorial workflows across distributed content teams. Overhauled the delivery architecture for accessibility compliance and redesigned the information hierarchy for long-term scale.',
                        '/img/ss-earth-observatory.png',
                        'https://earthobservatory.nasa.gov',
                        ['Laravel', 'AWS'],
                        ['/img/logo-nasa.svg', null, 'h-8'],
                    ],
                    [
                        'Flood Mapping System',
                        'NASA &nbsp;·&nbsp; 2017–2025',
                        'Operational satellite imagery processing system generating near real-time flood inundation maps during active disaster events globally. Automated the end-to-end pipeline from raw sensor acquisition through geospatial product generation, dissemination, and integration with international emergency management networks.',
                        '/img/small-flood.png',
                        null,
                        ['AWS', 'Python'],
                        ['/img/logo-nasa.svg', null, 'h-8'],
                    ],
                    [
                        'Direct Readout Laboratory',
                        'NASA &nbsp;·&nbsp; 2017–2025',
                        'Scientific data processing hub ingesting multi-instrument sensor streams from polar-orbiting satellites in near real-time. Reformats and distributes Level-0 through Level-2 geophysical products to operational centers and research institutions across a global network of registered direct broadcast ground stations.',
                        '/img/ss-direct-readout2.png',
                        null,
                        ['PHP', 'Linux'],
                        ['/img/logo-nasa.svg', null, 'h-8'],
                    ],
                    [
                        'ESSCOR',
                        'NASA &nbsp;·&nbsp; 2017–2025',
                        'Earth science data discovery portal unifying archival and near real-time remote sensing holdings into a searchable, standards-compliant catalog. Implemented granule-level access controls and standardized metadata schemas to streamline data ordering and delivery for researchers across government agencies and partner institutions.',
                        '/img/ss-esccor.png',
                        null,
                        ['PHP', 'MySQL'],
                        ['/img/logo-nasa.svg', null, 'h-8'],
                    ],
                    [
                        'InformedDNA Platform',
                        'InformedDNA &nbsp;·&nbsp; 2016–2017',
                        'Clinical genomics workflow platform coordinating case management, genetic counseling routing, and billing reconciliation across distributed care teams. Unified fragmented operational processes into a governed system with role-based access, full audit trails, and automated documentation pipelines that cut per-case overhead by $30K annually.',
                        '/img/ss-informeddna.png',
                        null,
                        ['Laravel', 'MySQL'],
                        ['/img/logo-informeddna.png', 'brightness(0) invert(1)', 'h-6'],
                    ],
                    [
                        'Finium',
                        'Verizon Business &nbsp;·&nbsp; 1999–2005',
                        'Enterprise managed security services platform unifying multi-tenant client operations across a national carrier network for a Fortune 500 provider. Automated provisioning, monitoring, and incident response orchestration drove a 10× growth in client engagements and contributed directly to a $105M acquisition.',
                        '/img/ss-mci-verizon.png',
                        null,
                        ['Java', 'Security'],
                        ['/img/logo-verizon-v.png', null, 'h-8'],
                    ],
                ] as [$title, $meta, $desc, $img, $url, $tags, $logo])
                <div class="bg-[#080808] group relative overflow-hidden h-80 lg:h-96 rounded-2xl ring-1 ring-white/[0.06]" data-reveal>

                    {{-- Full-bleed image --}}
                    <img src="{{ $img }}" alt="{{ $title }}"
                         loading="lazy"
                         class="absolute inset-0 w-full h-full object-cover object-top opacity-50 group-hover:opacity-70 group-hover:scale-[1.03] transition-all duration-700 ease-out">

                    {{-- Top scrim so tags are readable --}}
                    <div class="absolute inset-x-0 top-0 h-20 bg-gradient-to-b from-black/60 to-transparent"></div>

                    {{-- Logo: top-right --}}
                    @if($logo)
                    <div class="absolute top-4 right-4">
                        <img src="{{ $logo[0] }}" alt="" loading="lazy" aria-hidden="true"
                             @if($logo[1]) style="filter: {{ $logo[1] }};" @endif
                             class="{{ $logo[2] }} w-auto object-contain opacity-70 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                    @endif

                    {{-- Tags: top-left --}}
                    <div class="absolute top-4 left-4 flex flex-wrap gap-1.5">
                        @foreach($tags as $tag)
                        <span class="font-mono text-[10px] px-2 py-0.5 bg-black/60 border border-neutral-700/50 text-neutral-400 backdrop-blur-sm">{{ $tag }}</span>
                        @endforeach
                    </div>

                    {{-- Frosted glass content panel --}}
                    <div class="absolute inset-x-0 bottom-0 bg-[#080808]/90 backdrop-blur-md border-t border-white/[0.06] px-5 pt-4 pb-5 rounded-b-2xl">
                        <p class="font-mono text-[10px] text-orange-500 uppercase tracking-widest mb-1.5">{!! $meta !!}</p>
                        <p class="font-display text-lg tracking-wide text-white leading-tight">{{ $title }}</p>

                        {{-- Description expands on hover --}}
                        <div class="max-h-0 group-hover:max-h-52 overflow-hidden transition-[max-height] duration-500 ease-out">
                            <p class="text-neutral-400 text-xs leading-relaxed mt-2">{{ $desc }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Tech Stack --}}
    <section id="stack" class="py-28 px-6 border-t border-neutral-800">
        <div class="max-w-6xl mx-auto">
            <p class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-16" data-reveal>04 — Technical Stack</p>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach([
                    ['Languages',        ['Python', 'TypeScript', 'Java', 'PHP', 'Bash']],
                    ['Cloud & Infra',    ['AWS', 'Docker', 'Kubernetes', 'Helm (OCI)', 'Ceph']],
                    ['Delivery & CI/CD', ['GitLab CI', 'GitHub Actions', 'Bitbucket', 'Release Automation']],
                    ['Web & UI',         ['React', 'Node.js', 'Laravel', 'Vite', 'Tailwind', 'OpenAPI/Swagger']],
                    ['Data Platforms',   ['PostgreSQL', 'MySQL', 'MongoDB', 'Redis', 'Elasticsearch']],
                    ['Leadership',       ['Agile / Scrum', 'Team Coaching', 'Roadmapping', 'DevSecOps', 'Stakeholder Mgmt']],
                ] as [$category, $skills])
                <div data-reveal>
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

    {{-- Open Source --}}
    <section id="open-source" class="py-28 px-6 border-t border-neutral-800">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-16" data-reveal>
                <p class="font-mono text-orange-500 text-xs tracking-widest uppercase">05 — Open Source</p>
                <a href="https://github.com/karlhillx" target="_blank" rel="noopener"
                   class="font-mono text-xs text-neutral-600 hover:text-orange-500 transition-colors">
                    github.com/karlhillx ↗
                </a>
            </div>
            <div id="github-repos" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-px bg-neutral-800">
                @for($i = 0; $i < 6; $i++)
                <div class="bg-[#080808] p-6 animate-pulse">
                    <div class="h-3 bg-neutral-800 rounded mb-3 w-3/4"></div>
                    <div class="h-2 bg-neutral-900 rounded mb-1.5 w-full"></div>
                    <div class="h-2 bg-neutral-900 rounded mb-4 w-2/3"></div>
                    <div class="h-2 bg-neutral-800 rounded w-1/4"></div>
                </div>
                @endfor
            </div>
        </div>
    </section>

    {{-- Certifications --}}
    <section id="certs" class="py-28 px-6 border-t border-neutral-800">
        <div class="max-w-6xl mx-auto">
            <p class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-16" data-reveal>06 — Certifications & Education</p>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-px bg-neutral-800">
                @foreach([
                    ['PSM II',  'Professional Scrum Master™ II',       'Scrum.org',     'https://www.credly.com/badges/1874ba29-99d7-4dae-8335-1a915795d956'],
                    ['PSD I',   'Professional Scrum Developer™ I',      'Scrum.org',     'https://www.credly.com/badges/937b37cf-6fa7-49dd-8c70-e43378feda5b'],
                    ['PSPO I',  'Professional Scrum Product Owner™ I',  'Scrum.org',     'https://www.credly.com/badges/da27e50e-ef55-41f0-bc14-ca26d9e3e0ff'],
                    ['CSM',     'Certified ScrumMaster®',               'Scrum Alliance', 'https://certification.scrumalliance.org/accounts/1484321-karl-hill/certifications/1735632-csm'],
                ] as [$abbr, $name, $issuer, $verifyUrl])
                <a href="{{ $verifyUrl }}" target="_blank" rel="noopener"
                   class="bg-[#080808] p-8 hover:bg-neutral-900/60 transition-colors group" data-reveal>
                    <p class="font-display text-5xl text-orange-500 mb-3 group-hover:text-orange-400 transition-colors">{{ $abbr }}</p>
                    <p class="text-sm text-neutral-300 font-medium leading-snug">{{ $name }}</p>
                    <p class="font-mono text-xs text-neutral-600 mt-3">{{ $issuer }}</p>
                    <p class="font-mono text-xs text-neutral-700 mt-2 group-hover:text-orange-600 transition-colors">Verify ↗</p>
                </a>
                @endforeach
            </div>
            <div class="mt-px bg-neutral-800">
                <div class="bg-[#080808] p-8" data-reveal>
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
    <footer id="contact" class="border-t border-neutral-800 py-24 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-12">
                <div class="max-w-xl" data-reveal>
                    <p class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-6">07 — Contact</p>
                    <h2 class="font-display text-[clamp(3rem,8vw,6rem)] leading-none tracking-wide mb-6">
                        Let's Work<br>Together
                    </h2>
                    <p class="text-neutral-500 text-sm leading-relaxed max-w-sm">
                        Building something important and need an engineer who can lead, architect, and deliver?
                        I'd like to hear about it.
                    </p>
                </div>
                <div class="flex flex-col gap-4 lg:pt-16 shrink-0" data-reveal>
                    <a href="mailto:karlhillx@gmail.com"
                       class="flex items-center gap-4 font-mono text-sm text-neutral-400 hover:text-orange-500 transition-colors group">
                        <span class="text-orange-500 text-base group-hover:translate-x-0.5 transition-transform">→</span>
                        karlhillx@gmail.com
                    </a>
                    <a href="/files/karlhill-resume.pdf" target="_blank" rel="noopener" download="Karl-Hill-Resume.pdf"
                       class="flex items-center gap-4 font-mono text-sm text-neutral-400 hover:text-orange-500 transition-colors group">
                        <span class="text-orange-500 text-base group-hover:translate-x-0.5 transition-transform">↗</span>
                        Resume
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
                </div>
            </div>
            <div class="mt-20 pt-8 border-t border-neutral-800/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <p class="font-display text-3xl tracking-widest text-neutral-600">Karl Hill</p>
                <p class="font-mono text-xs text-neutral-500">Washington, DC &nbsp;·&nbsp; Staff Software Engineer &nbsp;·&nbsp; 25+ Years</p>
                <p class="font-mono text-xs text-neutral-600 mt-1">Laravel {{ app()->version() }}</p>
            </div>
        </div>
    </footer>

</body>
</html>
