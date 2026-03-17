<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karl Hill — Staff Software Engineer</title>
    <meta name="description" content="Karl Hill is a Staff Software Engineer with 25+ years of experience. Built NASA Goddard's Earth Observatory, flood mapping, and direct readout satellite platforms. Now leading aerospace software at Jacobs.">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://karlhill.com">
    <meta property="og:title" content="Karl Hill — Staff Software Engineer">
    <meta property="og:description" content="25+ years shipping systems under pressure — from NASA Goddard's Earth science and flood mapping platforms to mission-critical aerospace software at Jacobs/BlackLynx.">
    <meta property="og:image" content="https://karlhill.com/img/profile.jpg">
    <meta property="og:image:width" content="800">
    <meta property="og:image:height" content="800">

    {{-- Twitter / X --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@karl_hill">
    <meta name="twitter:creator" content="@karl_hill">
    <meta name="twitter:title" content="Karl Hill — Staff Software Engineer">
    <meta name="twitter:description" content="25+ years shipping systems under pressure — from NASA Goddard's Earth science and flood mapping platforms to mission-critical aerospace software at Jacobs/BlackLynx.">
    <meta name="twitter:image" content="https://karlhill.com/img/profile.jpg">

    <meta name="theme-color" content="#080808">

    <link rel="canonical" href="https://karlhill.com">

    {{-- Favicons --}}
    <link rel="icon" type="image/svg+xml" href="/img/favicon.svg">
    <link rel="icon" type="image/png" sizes="96x96" href="/img/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">

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
        "worksFor": {
            "@type": "Organization",
            "name": "Jacobs"
        },
        "sameAs": [
            "https://www.linkedin.com/in/khill/",
            "https://github.com/karlhillx",
            "https://twitter.com/karl_hill",
            "https://orcid.org/0009-0002-6847-3368",
            "https://www.discogs.com/artist/1286669-Karl-Hill"
        ]
    }
    </script>
    @endverbatim
</head>
<body class="bg-[#080808] text-neutral-100 antialiased">

    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[100] focus:px-4 focus:py-2 focus:bg-orange-500 focus:text-black focus:font-semibold focus:text-xs focus:uppercase focus:tracking-widest">
        Skip to content
    </a>

    {{-- Nav --}}
    <nav aria-label="Primary" class="fixed top-0 left-0 right-0 z-50 border-b border-neutral-800/60 bg-[#080808]/90 backdrop-blur-sm nav-enter">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <span class="font-display text-2xl tracking-wider text-orange-500">KARL HILL</span>
            <div class="hidden md:flex items-center gap-8 font-mono text-xs text-neutral-500 uppercase tracking-widest">
                <a href="#experience" class="hover:text-orange-500 transition-colors duration-200">Experience</a>
                <a href="#work" class="hover:text-orange-500 transition-colors duration-200">Work</a>
                <a href="#stack" class="hover:text-orange-500 transition-colors duration-200">Stack</a>
                <a href="#contact" class="hover:text-orange-500 transition-colors duration-200">Contact</a>
            </div>
            <div class="flex items-center gap-3">
                <a href="mailto:karlhillx@gmail.com"
                   class="text-xs font-semibold text-neutral-300 border border-neutral-700 px-5 py-2.5 uppercase tracking-widest hover:border-orange-500 hover:text-orange-500 transition-colors duration-200">
                    Get in Touch
                </a>
                {{-- Mobile hamburger --}}
                <button id="nav-toggle" aria-controls="mobile-menu" aria-expanded="false" aria-label="Toggle navigation"
                        class="md:hidden flex flex-col justify-center items-center w-9 h-9 gap-1.5 border border-neutral-700 hover:border-orange-500 transition-colors shrink-0">
                    <span class="block w-4 h-px bg-current" aria-hidden="true"></span>
                    <span class="block w-4 h-px bg-current" aria-hidden="true"></span>
                    <span class="block w-4 h-px bg-current" aria-hidden="true"></span>
                </button>
            </div>
        </div>
        {{-- Mobile menu --}}
        <div id="mobile-menu" hidden class="md:hidden border-t border-neutral-800 bg-[#080808]/98 backdrop-blur-sm">
            <div class="max-w-6xl mx-auto px-6 py-4 flex flex-col gap-1 font-mono text-xs text-neutral-500 uppercase tracking-widest">
                <a href="#experience" class="py-3 border-b border-neutral-800/50 hover:text-orange-500 transition-colors">Experience</a>
                <a href="#work"       class="py-3 border-b border-neutral-800/50 hover:text-orange-500 transition-colors">Work</a>
                <a href="#stack"      class="py-3 border-b border-neutral-800/50 hover:text-orange-500 transition-colors">Stack</a>
                <a href="#contact"    class="py-3 hover:text-orange-500 transition-colors">Contact</a>
            </div>
        </div>
    </nav>

    <main id="main-content">

    {{-- Hero --}}
    <section id="hero" class="relative min-h-screen flex flex-col justify-end pt-24 pb-16 px-6 overflow-hidden">

        {{-- Dot-grid scrolling overlay --}}
        <div class="hero-dot-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>

        {{-- Ambient glow orbs --}}
        <div class="glow-orb-1 pointer-events-none absolute -bottom-32 -left-32 w-[600px] h-[600px] rounded-full"
             style="background: radial-gradient(ellipse, rgba(249,115,22,0.14) 0%, transparent 65%);"
             aria-hidden="true"></div>
        <div class="glow-orb-2 pointer-events-none absolute -top-48 -right-48 w-[500px] h-[500px] rounded-full"
             style="background: radial-gradient(ellipse, rgba(249,115,22,0.09) 0%, transparent 65%);"
             aria-hidden="true"></div>

        <div class="relative z-10 max-w-6xl mx-auto w-full">
            <div class="pt-12">
                <div class="flex items-center gap-4 mb-8 hero-enter" style="animation-delay:100ms">
                    <img src="/img/profile.jpg" alt="Karl Hill"
                         width="48" height="48"
                         class="w-12 h-12 rounded-full object-cover ring-2 ring-orange-500/30 shrink-0">
                    <p class="font-mono text-orange-500 text-xs tracking-widest uppercase">
                        Staff Software Engineer &nbsp;·&nbsp; {{ date('Y') - 1999 }}+ Years
                    </p>
                </div>
                <h1 class="font-display text-[clamp(5rem,20vw,15rem)] leading-none tracking-wide text-white mb-6 hero-enter" style="animation-delay:220ms">
                    Karl Hill
                </h1>
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8 mb-12">
                    <p class="font-display text-[clamp(1.4rem,3.5vw,2.5rem)] text-neutral-500 tracking-widest uppercase hero-enter" style="animation-delay:360ms">
                        Cloud · Platforms · Engineering Leadership
                    </p>
                    <p class="text-neutral-400 text-base leading-relaxed max-w-md lg:text-right hero-enter" style="animation-delay:440ms">
                        I architect systems, lead teams, and ship software that matters —
                        from disaster-response platforms at NASA to mission-critical aerospace systems at Jacobs/BlackLynx.
                    </p>
                </div>
                <div class="flex flex-wrap gap-4 hero-enter" style="animation-delay:560ms">
                    <a href="https://www.linkedin.com/in/khill/" target="_blank" rel="noopener noreferrer"
                       class="bg-orange-500 text-black font-bold px-8 py-3.5 text-xs uppercase tracking-widest hover:bg-orange-400 transition-colors duration-200">
                        LinkedIn
                    </a>
                    <a href="mailto:karlhillx@gmail.com"
                       class="border border-neutral-700 text-neutral-300 font-semibold px-8 py-3.5 text-xs uppercase tracking-widest hover:border-orange-500 hover:text-orange-500 transition-colors duration-200">
                        karlhillx@gmail.com
                    </a>
                    <a href="https://github.com/karlhillx" target="_blank" rel="noopener noreferrer"
                       class="border border-neutral-700 text-neutral-300 font-semibold px-8 py-3.5 text-xs uppercase tracking-widest hover:border-orange-500 hover:text-orange-500 transition-colors duration-200">
                        GitHub
                    </a>
                </div>
                <div class="flex items-center gap-2.5 mt-6 hero-enter" style="animation-delay:640ms">
                    <span class="w-2 h-2 rounded-full bg-green-500 availability-pulse" aria-hidden="true"></span>
                    <span class="font-mono text-xs text-neutral-500 uppercase tracking-widest">Available for select consulting</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Why Hire Me --}}

    <section id="why" class="py-28 px-6 border-t border-neutral-800">
        <div class="max-w-6xl mx-auto">
            <h2 class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-16" data-reveal>01 — Why Me</h2>
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
            [date('Y') - 1999 . '+',   'Years of Experience',              date('Y') - 1999,  '',  '+'],
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
               data-final="{{ $stat }}"
               aria-label="{{ $stat }} {{ $label }}">{{ $stat }}</p>
            <p class="font-mono text-xs text-neutral-500 mt-2 uppercase tracking-wide leading-snug">{{ $label }}</p>
        </div>
        @endforeach
        </div>
    </section>

    {{-- Experience --}}
    <section id="experience" class="py-28 px-6 border-t border-neutral-800">
        <div class="max-w-6xl mx-auto">
            <h2 class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-16" data-reveal>02 — Experience</h2>

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
                        <span class="text-orange-500 shrink-0 mt-0.5 arrow-nudge" aria-hidden="true">→</span>
                        Lead delivery for cloud-native mission-simulation and telemetry services. Own planning, refinement, demos, and stakeholder alignment end-to-end.
                    </li>
                    <li class="flex gap-4">
                        <span class="text-orange-500 shrink-0 mt-0.5 arrow-nudge" aria-hidden="true">→</span>
                        Own team health and execution — coaching, 1:1s, onboarding, and delivery discipline across multi-repo, multi-environment systems.
                    </li>
                    <li class="flex gap-4">
                        <span class="text-orange-500 shrink-0 mt-0.5 arrow-nudge" aria-hidden="true">→</span>
                        Set and enforce engineering standards: repo/branch governance, PR reviews, Definition of Done, and documentation to reduce integration risk.
                    </li>
                    <li class="flex gap-4">
                        <span class="text-orange-500 shrink-0 mt-0.5 arrow-nudge" aria-hidden="true">→</span>
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
                            <span class="text-orange-500 shrink-0 mt-0.5 arrow-nudge" aria-hidden="true">→</span>
                            Architected NASA's cloud-based Flood Mapping System on AWS, delivering near real-time satellite-derived flood products to support global disaster response.
                        </li>
                        <li class="flex gap-4">
                            <span class="text-orange-500 shrink-0 mt-0.5 arrow-nudge" aria-hidden="true">→</span>
                            Rebuilt the NASA Earth Observatory — a platform serving <strong class="text-white font-semibold">~1.5M monthly visitors</strong> — improving performance, UX, and SEO.
                        </li>
                        <li class="flex gap-4">
                            <span class="text-orange-500 shrink-0 mt-0.5 arrow-nudge" aria-hidden="true">→</span>
                            Built a high-performance Ceph-based file + metadata platform, improving large dataset discovery and access for researchers.
                        </li>
                        <li class="flex gap-4">
                            <span class="text-orange-500 shrink-0 mt-0.5 arrow-nudge" aria-hidden="true">→</span>
                            Delivered an automated content registry workflow that boosted data collection efficiency by <strong class="text-white font-semibold">~60%</strong> and accelerated researcher access.
                        </li>
                        <li class="flex gap-4">
                            <span class="text-orange-500 shrink-0 mt-0.5 arrow-nudge" aria-hidden="true">→</span>
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
                            <span class="text-orange-500 shrink-0 mt-0.5 arrow-nudge" aria-hidden="true">→</span>
                            Architected a Laravel-based case management platform reducing operational costs by <strong class="text-white font-semibold">$30K/year</strong>.
                        </li>
                        <li class="flex gap-4">
                            <span class="text-orange-500 shrink-0 mt-0.5 arrow-nudge" aria-hidden="true">→</span>
                            Led CRM enhancements improving client retention and contributing ~15% revenue growth through better lifecycle workflows and reporting.
                        </li>
                        <li class="flex gap-4">
                            <span class="text-orange-500 shrink-0 mt-0.5 arrow-nudge" aria-hidden="true">→</span>
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
            <h2 class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-16" data-reveal>03 — Selected Work</h2>
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
                        ['Python', 'Docker', 'AWS'],
                        ['/img/logo-nasa.svg', null, 'h-8'],
                    ],
                    [
                        'Direct Readout Laboratory',
                        'NASA &nbsp;·&nbsp; 2017–2025',
                        'Scientific data processing hub ingesting multi-instrument sensor streams from polar-orbiting satellites in near real-time. Reformats and distributes Level-0 through Level-2 geophysical products to operational centers and research institutions across a global network of registered direct broadcast ground stations.',
                        '/img/ss-direct-readout2.png',
                        null,
                        ['PHP', 'Linux', 'NGINX'],
                        ['/img/logo-nasa.svg', null, 'h-8'],
                    ],
                    [
                        'ESSCOR',
                        'NASA &nbsp;·&nbsp; 2017–2025',
                        'Earth science data discovery portal unifying archival and near real-time remote sensing holdings into a searchable, standards-compliant catalog. Implemented granule-level access controls and standardized metadata schemas to streamline data ordering and delivery for researchers across government agencies and partner institutions.',
                        '/img/ss-esccor.png',
                        null,
                        ['PHP', 'MySQL', 'ElasticSearch'],
                        ['/img/logo-nasa.svg', null, 'h-8'],
                    ],
                    [
                        'InformedDNA Platform',
                        'InformedDNA &nbsp;·&nbsp; 2016–2017',
                        'Clinical genomics workflow platform coordinating case management, genetic counseling routing, and billing reconciliation across distributed care teams. Unified fragmented operational processes into a governed system with role-based access, full audit trails, and automated documentation pipelines that cut per-case overhead by $30K annually.',
                        '/img/ss-informeddna.png',
                        null,
                        ['Laravel', 'MySQL', 'RESTful APIs'],
                        ['/img/logo-informeddna.png', 'brightness(0) invert(1)', 'h-6'],
                    ],
                    [
                        'Finium',
                        'Verizon Business &nbsp;·&nbsp; 1999–2005',
                        'Enterprise managed security services platform unifying multi-tenant client operations across a national carrier network for a Fortune 500 provider. Automated provisioning, monitoring, and incident response orchestration drove a 10× growth in client engagements and contributed directly to a $105M acquisition.',
                        '/img/ss-mci-verizon.png',
                        null,
                        ['Java', 'SQL Server', 'Security'],
                        ['/img/logo-verizon.svg', null, 'h-5'],
                    ],
                ] as [$title, $meta, $desc, $img, $url, $tags, $logo])
                <div class="bg-[#080808] group relative overflow-hidden h-80 lg:h-96 rounded-2xl ring-1 ring-white/[0.06] hover:ring-white/[0.12] focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500/60 transition-shadow duration-300" tabindex="0" data-reveal>

                    {{-- Full-bleed image --}}
                    <img src="{{ $img }}" alt="{{ $title }}"
                         loading="lazy" decoding="async"
                         class="absolute inset-0 w-full h-full object-cover object-top opacity-50 group-hover:opacity-70 group-hover:scale-[1.03] transition-[opacity,transform] duration-700 ease-out">

                    {{-- Top scrim so tags are readable --}}
                    <div class="absolute inset-x-0 top-0 h-20 bg-gradient-to-b from-black/60 to-transparent"></div>

                    {{-- Logo: top-right --}}
                    @if($logo)
                    <div class="absolute top-4 right-4">
                        <img src="{{ $logo[0] }}" alt="" loading="lazy" decoding="async" aria-hidden="true"
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
                        <div class="max-h-0 group-hover:max-h-52 group-focus:max-h-52 overflow-hidden transition-[max-height] duration-500 ease-out">
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
            <h2 class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-16" data-reveal>04 — Technical Stack</h2>
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
                <h2 class="font-mono text-orange-500 text-xs tracking-widest uppercase">05 — Open Source</h2>
                <a href="https://github.com/karlhillx" target="_blank" rel="noopener noreferrer"
                   class="font-mono text-xs text-neutral-600 hover:text-orange-500 transition-colors">
                    github.com/karlhillx ↗
                </a>
            </div>
            <div id="github-repos" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-px bg-neutral-800" aria-busy="true" aria-label="Loading repositories">
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
            <h2 class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-16" data-reveal>06 — Certifications & Education</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-px bg-neutral-800">
                @foreach([
                    ['PSM II',  'Professional Scrum Master™ II',       'Scrum.org',     'https://www.credly.com/badges/1874ba29-99d7-4dae-8335-1a915795d956'],
                    ['PSD I',   'Professional Scrum Developer™ I',      'Scrum.org',     'https://www.credly.com/badges/937b37cf-6fa7-49dd-8c70-e43378feda5b'],
                    ['PSPO I',  'Professional Scrum Product Owner™ I',  'Scrum.org',     'https://www.credly.com/badges/da27e50e-ef55-41f0-bc14-ca26d9e3e0ff'],
                    ['CSM',     'Certified ScrumMaster®',               'Scrum Alliance', 'https://certification.scrumalliance.org/accounts/1484321-karl-hill/certifications/1735632-csm'],
                ] as [$abbr, $name, $issuer, $verifyUrl])
                <a href="{{ $verifyUrl }}" target="_blank" rel="noopener noreferrer"
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

    </main>

    {{-- Contact / Footer --}}
    <footer id="contact" class="border-t border-neutral-800 py-24 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-12">
                <div class="max-w-xl" data-reveal>
                    <h2 class="font-mono text-orange-500 text-xs tracking-widest uppercase mb-6">07 — Contact</h2>
                    <p class="font-display text-[clamp(3rem,8vw,6rem)] leading-none tracking-wide mb-6">
                        Let's Work<br>Together
                    </p>
                    <p class="text-neutral-500 text-sm leading-relaxed max-w-sm">
                        Building something important and need an engineer who can lead, architect, and deliver?
                        I'd like to hear about it.
                    </p>
                </div>
                <div class="flex flex-col gap-4 lg:pt-16 shrink-0" data-reveal>
                    <a href="mailto:karlhillx@gmail.com"
                       class="flex items-center gap-4 font-mono text-sm text-neutral-400 hover:text-orange-500 transition-colors group">
                        <span class="text-orange-500 text-base arrow-nudge" aria-hidden="true">→</span>
                        karlhillx@gmail.com
                    </a>
                    <a href="/files/karlhill-resume.pdf" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-3 border border-neutral-700 text-neutral-300 font-semibold px-6 py-3 text-xs uppercase tracking-widest hover:border-orange-500 hover:text-orange-500 transition-colors duration-200 w-fit">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download Resume
                    </a>

                    {{-- Social icon links --}}
                    <div class="flex items-center gap-5 pt-2">
                        <a href="https://www.linkedin.com/in/khill/" target="_blank" rel="noopener noreferrer"
                           aria-label="LinkedIn" title="linkedin.com/in/khill"
                           class="text-neutral-500 hover:text-orange-500 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                        <a href="https://github.com/karlhillx" target="_blank" rel="noopener noreferrer"
                           aria-label="GitHub" title="github.com/karlhillx"
                           class="text-neutral-500 hover:text-orange-500 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com/karl_hill/" target="_blank" rel="noopener noreferrer"
                           aria-label="X / Twitter" title="@karl_hill"
                           class="text-neutral-500 hover:text-orange-500 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        <a href="https://orcid.org/0009-0002-6847-3368" target="_blank" rel="noopener noreferrer"
                           aria-label="ORCID" title="orcid.org/0009-0002-6847-3368"
                           class="text-neutral-500 hover:text-orange-500 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 0C5.372 0 0 5.372 0 12s5.372 12 12 12 12-5.372 12-12S18.628 0 12 0zM7.369 4.378c.525 0 .947.431.947.947s-.422.947-.947.947a.95.95 0 01-.947-.947c0-.525.422-.947.947-.947zm-.722 3.038h1.444v10.041H6.647V7.416zm3.562 0h3.9c3.712 0 5.344 2.653 5.344 5.025 0 2.578-2.016 5.025-5.325 5.025h-3.919V7.416zm1.444 1.303v7.444h2.297c3.272 0 4.022-2.484 4.022-3.722 0-2.016-1.284-3.722-4.097-3.722h-2.222z"/>
                            </svg>
                        </a>
                        <a href="https://www.discogs.com/artist/1286669-Karl-Hill" target="_blank" rel="noopener noreferrer"
                           aria-label="Discogs" title="discogs.com/artist/Karl-Hill"
                           class="text-neutral-500 hover:text-orange-500 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 6.207a5.793 5.793 0 110 11.586A5.793 5.793 0 0112 6.207zm0 3.585a2.208 2.208 0 100 4.416 2.208 2.208 0 000-4.416zM3.585 11.1H1.097a10.92 10.92 0 000 1.8H3.585a8.534 8.534 0 010-1.8zm19.318 0h-2.488a8.535 8.535 0 010 1.8h2.488a10.921 10.921 0 000-1.8zM11.1 3.585V1.097a10.919 10.919 0 00-1.8 0V3.585a8.535 8.535 0 011.8 0zm1.8 16.83v-2.488a8.535 8.535 0 01-1.8 0v2.488a10.921 10.921 0 001.8 0zM5.967 4.381L4.21 2.625a10.94 10.94 0 00-1.273 1.273l1.757 1.757a8.538 8.538 0 011.273-1.274zm12.097 12.097l1.757 1.757a10.94 10.94 0 001.273-1.273l-1.757-1.757a8.538 8.538 0 01-1.273 1.273zM4.381 18.033L2.625 19.79a10.942 10.942 0 001.273 1.273l1.757-1.757a8.538 8.538 0 01-1.274-1.273zm13.37-12.097l1.757-1.757A10.94 10.94 0 0018.235 2.9l-1.757 1.757a8.538 8.538 0 011.273 1.279z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-20 pt-8 border-t border-neutral-800/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <p class="font-display text-3xl tracking-widest text-neutral-600">Karl Hill</p>
                <p class="font-mono text-xs text-neutral-500">Washington, DC &nbsp;·&nbsp; Staff Software Engineer &nbsp;·&nbsp; {{ date('Y') - 1999 }}+ Years</p>
                <p class="font-mono text-xs text-neutral-700">Laravel {{ app()->version() }}</p>
            </div>
        </div>
    </footer>

    <script>
        const navToggle = document.getElementById('nav-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        navToggle?.addEventListener('click', () => {
            const expanded = navToggle.getAttribute('aria-expanded') === 'true';
            navToggle.setAttribute('aria-expanded', String(!expanded));
            mobileMenu.hidden = expanded;
        });
        mobileMenu?.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', () => {
                mobileMenu.hidden = true;
                navToggle.setAttribute('aria-expanded', 'false');
            });
        });
    </script>
</body>
</html>
