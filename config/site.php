<?php

$discogsCreditsUrl = 'https://www.discogs.com/artist/1286669-Karl-Hill?superFilter=Credits&sort=year,desc';

return [

    'person' => [
        'name' => 'Karl Hill',
        'job_title' => 'Staff Software Engineer',
        'email' => 'karlhillx@gmail.com',
        'location' => 'Washington, DC',
        'tagline' => 'Staff Software Engineer · Engineering Leadership · 25+ Years',
        'availability' => 'Open to Engineering Manager & Staff+ leadership roles',
        'employer' => 'Jacobs',
        'twitter_handle' => '@karl_hill',
    ],

    'analytics' => [
        'google' => [
            'enabled' => env('GOOGLE_ANALYTICS_ENABLED', false),
            'id' => env('GOOGLE_ANALYTICS_MEASUREMENT_ID'),
        ],
        'plausible' => [
            'enabled' => env('PLAUSIBLE_ENABLED', false),
            'domain' => env('PLAUSIBLE_DOMAIN', 'karlhill.com'),
        ],
    ],

    // Cal.com (or Calendly) scheduling link. When set, "Book a conversation"
    // CTAs appear on /now, the homepage availability line, and the contact footer.
    'booking' => [
        'url' => env('BOOKING_URL', 'https://calendly.com/karlhill'),
        'label' => env('BOOKING_LABEL', 'Book a conversation'),
    ],

    // Ordered essay series (slug lists). Used for series nav + writing index cards.
    'series' => [
        'em-craft' => [
            'title' => 'Engineering Manager craft',
            'description' => 'The Staff→EM bridge: first 90 days, saying no under roadmap pressure, and feedback without politics.',
            'slugs' => [
                'staff-to-em-first-90-days',
                'saying-no-roadmap-pressure',
                'performance-feedback-without-politics',
            ],
        ],
    ],

    // CI-only accessibility fixtures (never enable in production).
    'a11y_fixtures' => filter_var(env('A11Y_FIXTURES', false), FILTER_VALIDATE_BOOLEAN),

    'seo' => [
        'home' => [
            'title' => 'Karl Hill — Staff Engineer · Engineering Leadership',
            'description' => 'Staff Software Engineer moving toward Engineering Manager roles — 25+ years leading platform delivery, coaching engineers, and shipping NASA and aerospace systems people depend on.',
            'og_description' => 'Platforms, delivery discipline, and engineering leadership — NASA science ops to aerospace mission software at Jacobs.',
        ],
        'blog_index' => [
            'title' => 'Writing — Karl Hill',
            'description' => 'Essays on engineering leadership, team systems, release governance, and mission software — by Karl Hill.',
            'og_description' => 'Writing on engineering leadership, team systems, and the work that turns code into something people depend on.',
        ],
        'work' => [
            'title' => 'Work — Karl Hill',
            'description' => 'Selected projects — NASA Earth science platforms, flood mapping systems, clinical genomics workflows, and enterprise security software.',
            'og_description' => 'Mission-critical platforms built across NASA, aerospace, healthcare, and enterprise security.',
        ],
        'about' => [
            'title' => 'About — Karl Hill',
            'description' => 'Staff engineer building Engineering Manager muscles — how I lead, coach, and deliver through NASA science operations and aerospace mission software.',
            'og_description' => 'How I lead, the experience behind the work, and the path from Staff IC toward Engineering Manager.',
        ],
        'now' => [
            'title' => 'Now — Karl Hill',
            'description' => 'What Karl Hill is focused on now — Engineering Manager trajectory, aerospace platform delivery, and the leadership craft behind reliable teams.',
            'og_description' => 'Current focus: shipping aerospace platforms while building Engineering Manager muscles.',
        ],
    ],

    'social' => [
        ['label' => 'LinkedIn', 'url' => 'https://www.linkedin.com/in/khill/', 'icon' => 'linkedin'],
        ['label' => 'GitHub', 'url' => 'https://github.com/karlhillx', 'icon' => 'github'],
        ['label' => 'X / Twitter', 'url' => 'https://twitter.com/karl_hill/', 'icon' => 'twitter'],
        ['label' => 'ORCID', 'url' => 'https://orcid.org/0009-0002-6847-3368', 'icon' => 'orcid'],
        ['label' => 'ResearchGate', 'url' => 'https://www.researchgate.net/profile/Karl-Hill-2', 'icon' => 'researchgate'],
        ['label' => 'Google Scholar', 'url' => 'https://scholar.google.com/citations?user=ykw3hstDPLcC', 'icon' => 'scholar'],
        ['label' => 'Discogs', 'url' => $discogsCreditsUrl, 'icon' => 'discogs'],
    ],

    'same_as' => [
        'https://www.linkedin.com/in/khill/',
        'https://github.com/karlhillx',
        'https://twitter.com/karl_hill',
        'https://orcid.org/0009-0002-6847-3368',
        'https://www.researchgate.net/profile/Karl-Hill-2',
        'https://scholar.google.com/citations?user=ykw3hstDPLcC',
        $discogsCreditsUrl,
    ],

    'hero' => [
        'headline' => 'Karl Hill',
        'subtitle' => 'Platforms · Delivery · Engineering Leadership',
        'positioning' => 'I help mission-driven teams ship reliably — through clear ownership, coaching, and operating standards that outlast any single hero.',
        'bio' => 'I architect systems, lead teams, and ship software that matters — from disaster-response platforms at NASA to mission-critical aerospace systems at Jacobs/BlackLynx.',
        // Two CTAs, clear hierarchy. Lead with the EM funnel; portfolio is one click away.
        'cta' => [
            ['label' => 'Now', 'url' => '/now', 'primary' => true],
            ['label' => 'How I Lead', 'url' => '/about#how-i-lead'],
        ],
    ],

    'pillars' => [
        [
            'title' => 'I Build',
            'body' => 'Cloud-native platforms on AWS. Containerized services with Docker and Kubernetes. High-traffic web systems. Secure CI/CD pipelines. Built to last and operate reliably at scale — not just to demo well.',
        ],
        [
            'title' => 'I Lead',
            'body' => 'People and systems together. 1:1s, onboarding, honest feedback, PR standards, and definition of done — the operating work that turns a group of engineers into a team that ships without burning out.',
        ],
        [
            'title' => 'I Deliver',
            'body' => 'Predictable execution, every sprint. I translate mission needs into sequenced plans, partner with stakeholders, remove blockers for others, and keep trust when priorities collide.',
        ],
    ],

    'stats' => [
        ['display' => '25+', 'label' => 'Years of Experience', 'to' => 25, 'prefix' => '', 'suffix' => '+'],
        ['display' => '1.5M', 'label' => 'Monthly Visitors · NASA Platforms', 'to' => 1.5, 'prefix' => '', 'suffix' => 'M'],
        ['display' => '$105M', 'label' => 'Platform Acquisition Value', 'to' => 105, 'prefix' => '$', 'suffix' => 'M'],
        ['display' => '~60%', 'label' => 'Efficiency Gained via Automation', 'to' => 60, 'prefix' => '~', 'suffix' => '%'],
    ],

    'experience' => [
        'intro' => 'Twenty-five years from managed security platforms to NASA science operations to aerospace mission software — the through-line is the same: own the hard systems, raise the team\'s operating standard, and ship work people can depend on.',
        'current' => [
            'label' => 'Current Role',
            'title' => 'Staff Aerospace Software Engineer',
            'company' => 'Jacobs',
            'location' => 'Chantilly, VA',
            'period' => 'Sept 2025 — Present',
            'highlights' => [
                'Own delivery for cloud-native mission simulation and telemetry services — planning, refinement, demos, and stakeholder alignment from concept through production.',
                'Coach ~6 engineers across multi-repo mission services through 1:1s, PR discipline, and onboarding patterns that raise the bar without making me the single point of failure.',
                'Set the engineering operating system across collaborating teams: branch strategy, Definition of Done, and release governance that turns process into predictable shipping.',
            ],
        ],
        'roles' => [
            [
                'title' => 'Lead Software Engineer',
                'company' => 'NASA / SSAI',
                'location' => 'Lanham, MD',
                'period' => 'Dec 2017 — Sept 2025',
                'highlights' => [
                    'Led architecture and delivery for NASA\'s Flood Mapping System — automated satellite-to-product pipelines for global disaster response. <a href="/work/flood-mapping-system" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">Case study</a>',
                    'Directed the Earth Observatory re-platform for editorial velocity, performance, and long-term maintainability at public scale (1.5M+ monthly visitors). <a href="/work/nasa-earth-observatory" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">Case study</a>',
                    'Mentored and coordinated ~4 engineers and content partners across Flood Mapping, Earth Observatory, Direct Readout, and ESSCOR — turning platform ownership into shared delivery discipline instead of heroics.',
                    'Stood up GitLab CI/CD with Docker and Kubernetes so releases were repeatable, reviewable, and safe for stakeholder-approved science delivery.',
                ],
            ],
            [
                'title' => 'Sr. Software Engineer',
                'company' => 'InformedDNA',
                'location' => 'St. Petersburg, FL',
                'period' => 'Jan 2016 — Dec 2017',
                'highlights' => [
                    'Architected a Laravel case-management platform that cut operational cost by <strong class="text-white font-semibold">$30K/year</strong> while hardening clinical workflow reliability.',
                    'Partnered with a 2–4 person engineering circle plus product and operations on CRM lifecycle upgrades that improved retention and contributed ~15% revenue growth.',
                ],
            ],
            [
                'title' => 'Software Developer',
                'company' => 'Verizon Business',
                'location' => 'Herndon, VA',
                'period' => '1999 — 2005',
                'highlights' => [
                    'Architected Finium, the multi-tenant managed-security platform that scaled client engagements 10× and directly enabled a <strong class="text-white font-semibold">$105M</strong> acquisition by MCI/Verizon. <a href="/work/finium" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">Case study</a>',
                    'Owned platform architecture and delivery for enterprise security customers — coaching 2–4 engineers into shared ownership when reliability and tenancy isolation were the product.',
                ],
            ],
        ],
        'earlier' => [
            'title' => 'Earlier Career',
            'period' => '1997 — 2015',
            'entries' => [
                [
                    'company' => 'Ticomix, Inc.',
                    'meta' => 'Sr. Software Engineer · Washington, D.C. · 2012–2015',
                    'detail' => 'Led CRM platform delivery for 20+ enterprise clients including VDOT and Kastle Systems, architecting custom workflow and integration solutions. Overhauled the delivery process to cut the backlog ~90%, sharpening predictability and customer satisfaction.',
                ],
                [
                    'company' => 'Sabre Corporation',
                    'meta' => 'Software Engineer · Bethesda, MD · 2010–2012',
                    'detail' => 'Engineered web applications serving global travel-industry clients at scale. Raised team engineering standards by mentoring junior developers through pair programming and rigorous code review.',
                ],
                [
                    'company' => 'Dante Inc.',
                    'meta' => 'Principal Software Engineer · Arlington, VA · 2007–2010',
                    'detail' => 'Delivered enterprise solutions for Comcast and Mastercard that improved operational efficiency ~40%. Owned end-to-end Scrum delivery as technical lead — from backlog planning through retrospectives.',
                ],
                [
                    'company' => 'Advantage Industries, Inc.',
                    'meta' => 'Sr. Software Engineer · Columbia, MD · 1997–1999',
                    'detail' => 'Designed database-driven enterprise applications that automated client workflows and eliminated manual bottlenecks. Established the foundations in production delivery and data modeling that shaped a 25-year engineering trajectory.',
                ],
            ],
        ],
    ],

    'projects' => [
        [
            'slug' => 'nasa-earth-observatory',
            'title' => 'NASA Earth Observatory',
            'meta' => 'NASA · 2017–2025',
            'description' => 'Flagship science-communication platform serving 1.5M+ monthly visitors with satellite imagery and Earth science data. Led the architectural overhaul of the publishing pipeline — re-platforming editorial workflows for distributed content teams and hardening the delivery layer for accessibility, performance, and search at scale. Set the technical direction that turned ad-hoc story production into a repeatable, self-service system built to evolve for the next decade.',
            'image' => '/img/webp/ss-earth-observatory.webp',
            'url' => 'https://earthobservatory.nasa.gov',
            'featured' => true,
            'tags' => ['Laravel', 'AWS'],
            'logo' => ['path' => '/img/logo-nasa.svg', 'filter' => null, 'class' => 'h-8'],
            'case_study' => [
                'lede' => 'A flagship NASA science communication platform serving 1.5M+ monthly visitors — rebuilt for editorial velocity, performance, and long-term maintainability.',
                'role' => 'Lead engineer — owned the platform re-architecture and publishing pipeline, and set the frontend performance and accessibility standards.',
                'leadership' => [
                    'mode' => 'Tech lead / sticky IC across platform engineering and editorial partners',
                    'team' => '~4 engineers and content partners on a flagship site serving 1.5M+ monthly visitors',
                    'unblocked' => 'Moved story production off one-off engineering work so editors could ship without waiting on custom builds.',
                    'decision' => 'Traded short-term feature velocity for a shared publishing model — fewer heroics per story, higher long-term throughput.',
                ],
                'problem' => [
                    'Editorial teams relied on brittle, one-off publishing patterns that slowed routine story production.',
                    'Performance, accessibility, and SEO debt accumulated as traffic and content volume grew.',
                    'Distributed content teams needed a shared workflow without engineering becoming the bottleneck.',
                ],
                'approach' => [
                    'Redesigned the information architecture and publishing model around repeatable story templates.',
                    'Rebuilt delivery pipelines for large imagery, metadata consistency, and non-engineer self-service.',
                    'Improved frontend performance, accessibility compliance, and search discoverability as first-class requirements.',
                ],
                'outcome' => [
                    'Unified editorial workflows across distributed teams with less custom engineering per story.',
                    'Strengthened platform performance and accessibility for a high-traffic public science audience.',
                    'Created a maintainable foundation for ongoing Earth science communication at scale.',
                ],
                'metrics' => [
                    ['value' => '1.5M+', 'label' => 'Monthly visitors'],
                    ['value' => '25+', 'label' => 'Years platform evolution'],
                ],
            ],
        ],
        [
            'slug' => 'flood-mapping-system',
            'title' => 'Flood Mapping System',
            'meta' => 'NASA · 2017–2025',
            'description' => 'Mission-critical geospatial platform generating near-real-time flood inundation maps during active global disaster events. Architected the fully automated pipeline — from raw satellite sensor ingestion through geospatial product generation, dissemination, and integration with international emergency-management networks — engineered for fault tolerance and reliability when latency is measured in hours, not sprints.',
            'image' => '/img/webp/small-flood.webp',
            'imagePosition' => 'object-left-top',
            'url' => 'https://floodmap.web.nasa.gov',
            'featured' => true,
            'tags' => ['Python', 'Docker', 'AWS'],
            'logo' => ['path' => '/img/logo-nasa.svg', 'filter' => null, 'class' => 'h-8'],
            'case_study' => [
                'lede' => 'Near real-time flood inundation mapping from satellite data — built for disaster response when latency is measured in hours, not sprints.',
                'role' => 'Architect & lead developer — designed and automated the end-to-end geospatial pipeline on AWS.',
                'leadership' => [
                    'mode' => 'Technical lead for a mission-critical geospatial pipeline',
                    'team' => '~4 engineers and science partners, plus emergency-management users from sensor acquisition through global dissemination',
                    'unblocked' => 'Removed manual handoffs that forced late-night heroics during active disaster events.',
                    'decision' => 'Prioritized automation and fault tolerance over ad-hoc speed — latency dropped because the team system was reliable under urgency.',
                ],
                'problem' => [
                    'Manual processing steps delayed flood products during active global disaster events.',
                    'End-to-end workflows from sensor acquisition to dissemination spanned multiple teams and environments.',
                    'Operational users needed trustworthy, repeatable products — not one-off engineering heroics.',
                ],
                'approach' => [
                    'Automated the pipeline from raw sensor ingestion through geospatial product generation.',
                    'Containerized processing stages for repeatable deployments across environments.',
                    'Integrated outputs with emergency management and research distribution networks.',
                ],
                'outcome' => [
                    'Delivered near real-time flood inundation maps during active disaster events worldwide.',
                    'Reduced manual handoffs that introduced latency and inconsistency under urgency.',
                    'Supported peer-reviewed research on global water and flood mapping (GeoHorizons).',
                ],
                'metrics' => [
                    ['value' => 'Near RT', 'label' => 'Product generation'],
                    ['value' => 'Global', 'label' => 'Disaster coverage'],
                ],
            ],
        ],
        [
            'slug' => 'direct-readout-laboratory',
            'title' => 'Direct Readout Laboratory',
            'meta' => 'NASA · 2017–2025',
            'description' => 'Real-time scientific data-processing hub ingesting multi-instrument sensor streams from polar-orbiting satellites. Designed the ingestion and reformatting architecture that transforms raw downlinks into Level-0 through Level-2 geophysical products, sustaining 24/7 distribution to operational centers and a global network of registered direct-broadcast ground stations.',
            'image' => '/img/webp/ss-direct-readout2.webp',
            'url' => 'https://directreadout.sci.gsfc.nasa.gov',
            'featured' => true,
            'tags' => ['PHP', 'Linux', 'NGINX'],
            'logo' => ['path' => '/img/logo-nasa.svg', 'filter' => null, 'class' => 'h-8'],
            'case_study' => [
                'lede' => 'A scientific data hub ingesting multi-instrument satellite streams and distributing geophysical products to a global network of ground stations.',
                'role' => 'Lead developer — designed the ingestion and reformatting architecture and operated the round-the-clock processing infrastructure.',
                'leadership' => [
                    'mode' => 'Lead developer / operations owner for continuous science infrastructure',
                    'team' => '~4 engineers and science operations partners, plus a global network of registered direct-broadcast ground stations',
                    'unblocked' => 'Made instrument portfolio changes operable without rewriting tribal processing knowledge.',
                    'decision' => 'Standardized product tiers and distribution paths so partner stations could trust the system instead of individual operators.',
                ],
                'problem' => [
                    'Multi-instrument sensor streams required consistent reformatting from Level-0 through Level-2 products.',
                    'Operational centers and research partners depended on predictable, near real-time delivery.',
                    'Legacy processing paths were difficult to operate and extend as instrument portfolios evolved.',
                ],
                'approach' => [
                    'Built ingestion and reformatting pipelines tuned for polar-orbiting satellite data volumes.',
                    'Standardized product tiers and distribution paths for downstream operational consumers.',
                    'Operated on Linux/NGINX infrastructure designed for continuous scientific workloads.',
                ],
                'outcome' => [
                    'Sustained near real-time distribution to registered direct broadcast ground stations.',
                    'Improved reliability for multi-instrument product generation and handoff.',
                    'Supported NASA direct readout operations across a global partner network.',
                ],
                'metrics' => [
                    ['value' => 'L0–L2', 'label' => 'Product tiers'],
                    ['value' => '24/7', 'label' => 'Operational ingest'],
                ],
            ],
        ],
        [
            'slug' => 'esscor',
            'title' => 'ESSCOR',
            'meta' => 'NASA · 2017–2025',
            'description' => 'Earth science data-discovery platform unifying archival and near-real-time remote-sensing holdings into a single searchable, standards-compliant catalog. Designed granule-level access controls and a governed metadata model that streamlined discovery, ordering, and delivery for researchers across federal agencies and partner institutions.',
            'image' => '/img/webp/ss-esccor.webp',
            'url' => '/work/esscor',
            'tags' => ['PHP', 'MySQL', 'ElasticSearch'],
            'logo' => ['path' => '/img/logo-nasa.svg', 'filter' => null, 'class' => 'h-8'],
            'case_study' => [
                'lede' => 'A discovery portal unifying archival and near real-time remote sensing holdings into a searchable, standards-compliant catalog.',
                'role' => 'Lead developer — built the discovery and search platform, the metadata model, and granule-level access controls.',
                'problem' => [
                    'Researchers struggled to discover and order data across fragmented archival and near real-time holdings.',
                    'Metadata inconsistency slowed search, access control, and downstream ordering workflows.',
                ],
                'approach' => [
                    'Implemented granule-level access controls and standardized metadata schemas.',
                    'Built search and discovery on ElasticSearch with governed ordering and delivery paths.',
                ],
                'outcome' => [
                    'Streamlined data discovery and ordering for government agencies and partner institutions.',
                    'Reduced friction between catalog search and governed data access.',
                ],
                'metrics' => [
                    ['value' => 'Granule', 'label' => 'Level access control'],
                ],
            ],
        ],
        [
            'slug' => 'informeddna-platform',
            'title' => 'InformedDNA Platform',
            'meta' => 'InformedDNA · 2016–2017',
            'description' => 'Clinical-genomics workflow platform orchestrating case management, genetic-counseling routing, and billing reconciliation across distributed care teams. Consolidated fragmented, manual operations into a single governed system — role-based access, end-to-end audit trails, and automated documentation pipelines — cutting per-case operational overhead by $30K annually.',
            'image' => '/img/webp/ss-informeddna.webp',
            'url' => 'https://www.informeddna.com',
            'tags' => ['Laravel', 'MySQL', 'RESTful APIs'],
            'logo' => ['path' => '/img/webp/logo-informeddna.webp', 'filter' => 'brightness(0) invert(1)', 'class' => 'h-6'],
            'case_study' => [
                'lede' => 'A clinical genomics workflow platform that unified case management, counseling routing, and billing across distributed care teams.',
                'role' => 'Platform architect — designed and delivered the case-management system end-to-end.',
                'leadership' => [
                    'mode' => 'Platform architect in a small delivery circle',
                    'team' => '2–4 engineers, partnering closely with product and clinical operations',
                    'unblocked' => 'Replaced fragmented manual workflows so care teams were not waiting on ad-hoc engineering for routine cases.',
                    'decision' => 'Prioritized governed case management and auditability over feature sprawl — reliability was the product.',
                ],
                'problem' => [
                    'Fragmented operational processes created manual overhead across case intake, routing, and billing.',
                    'Distributed care teams lacked a governed system with auditability and role-based access.',
                ],
                'approach' => [
                    'Architected a Laravel platform integrating case management, counseling workflows, and billing reconciliation.',
                    'Automated documentation pipelines and enforced role-based access with full audit trails.',
                ],
                'outcome' => [
                    'Cut per-case operational overhead by $30K annually.',
                    'Improved coordination across distributed genetic counseling and care teams.',
                ],
                'metrics' => [
                    ['value' => '$30K', 'label' => 'Annual savings per case type'],
                ],
            ],
        ],
        [
            'slug' => 'finium',
            'title' => 'Finium',
            'meta' => 'Verizon Business · 1999–2005',
            'description' => 'Enterprise managed-security platform running multi-tenant client operations across a national carrier network for a Fortune 500 provider. Owned the services that automated provisioning, monitoring, and incident-response orchestration — scaling operations 10× and directly enabling a $105M acquisition.',
            'image' => '/img/webp/ss-mci-verizon.webp',
            'url' => '/work/finium',
            'tags' => ['Java', 'SQL Server', 'Security'],
            'logo' => ['path' => '/img/logo-verizon.svg', 'filter' => null, 'class' => 'h-5'],
            'case_study' => [
                'lede' => 'An enterprise managed security services platform that scaled client operations across a national carrier network.',
                'role' => 'Core developer — built the multi-tenant provisioning, monitoring, and incident-response services.',
                'leadership' => [
                    'mode' => 'Core platform owner growing shared engineering ownership',
                    'team' => '2–4 platform engineers supporting multi-tenant security operations for a Fortune 500 carrier — scaled client engagements 10×',
                    'unblocked' => 'Automated provisioning and incident orchestration so growth was not gated on tribal knowledge.',
                    'decision' => 'Invested in multi-tenant platform foundations early — the trade paid off when client engagements scaled 10×.',
                ],
                'problem' => [
                    'Multi-tenant security operations required manual provisioning, monitoring, and incident coordination.',
                    'Growth was constrained by operational bottlenecks in client onboarding and response workflows.',
                ],
                'approach' => [
                    'Built Java/SQL Server services automating provisioning, monitoring, and incident response orchestration.',
                    'Unified multi-tenant client operations for a Fortune 500 carrier environment.',
                ],
                'outcome' => [
                    'Drove a 10× increase in client engagements.',
                    'Contributed to a $105M acquisition by MCI/Verizon.',
                ],
                'metrics' => [
                    ['value' => '10×', 'label' => 'Client engagement growth'],
                    ['value' => '$105M', 'label' => 'Acquisition value'],
                ],
            ],
        ],
    ],

    'research' => [
        'label' => 'Co-author',
        'publication' => 'GeoHorizons',
        'published' => 'Published online May 2026',
        'title' => 'A Web-Based High-resolution Global Water and Flood Mapping Platform',
        'summary' => 'Peer-reviewed publication describing the Global Water and Flood Mapping System, a NASA-supported platform for high-resolution surface water and flood products derived from commercial satellite data.',
        'citation' => 'F. S. Policelli, A. J. Kettner, K. Hill, and D. Maloney.',
        'journal' => 'GeoHorizons, 1(1), gh2025-7.',
        'doi' => 'https://doi.org/10.1144/gh2025-7',
        'doi_label' => 'DOI: 10.1144/gh2025-7',
    ],

    'stack' => [
        ['category' => 'Languages', 'skills' => ['Python', 'TypeScript', 'Java', 'PHP', 'Bash']],
        ['category' => 'Cloud & Infra', 'skills' => ['AWS', 'Docker', 'Kubernetes', 'Helm (OCI)', 'Ceph']],
        ['category' => 'Delivery & CI/CD', 'skills' => ['GitLab CI', 'GitHub Actions', 'Bitbucket', 'Release Automation']],
        ['category' => 'Web & UI', 'skills' => ['React', 'Node.js', 'Laravel', 'Vite', 'Tailwind', 'OpenAPI/Swagger']],
        ['category' => 'Data Platforms', 'skills' => ['PostgreSQL', 'MySQL', 'MongoDB', 'Redis', 'Elasticsearch']],
        ['category' => 'Leadership', 'skills' => ['Agile / Scrum', 'Team Coaching', 'Roadmapping', 'DevSecOps', 'Stakeholder Mgmt']],
    ],

    'certifications' => [
        ['abbr' => 'SAFe', 'name' => 'SAFe® Agilist Certification', 'issuer' => 'Scaled Agile, Inc.', 'url' => 'https://scaledagile.com/certification/safe-agilist/', 'status' => 'In Progress'],
        ['abbr' => 'PSM II', 'name' => 'Professional Scrum Master™ II', 'issuer' => 'Scrum.org', 'url' => 'https://www.credly.com/badges/1874ba29-99d7-4dae-8335-1a915795d956'],
        ['abbr' => 'PSPO I', 'name' => 'Professional Scrum Product Owner™ I', 'issuer' => 'Scrum.org', 'url' => 'https://www.credly.com/badges/da27e50e-ef55-41f0-bc14-ca26d9e3e0ff'],
        ['abbr' => 'PSD I', 'name' => 'Professional Scrum Developer™ I', 'issuer' => 'Scrum.org', 'url' => 'https://www.credly.com/badges/937b37cf-6fa7-49dd-8c70-e43378feda5b'],
    ],

    'education' => [
        ['degree' => 'B.S. Computer Science Coursework', 'school' => 'University of Maryland'],
        ['degree' => 'Associate of Arts, General Studies', 'school' => 'Howard Community College'],
        ['degree' => 'Project Management', 'school' => 'Rutgers University'],
    ],

    'footer' => [
        'headline' => "Let's Talk\nLeadership",
        'body' => 'Open to Engineering Manager and Staff+ leadership conversations — and to select consulting when the mission matters. Building a team or a platform? Reach out.',
        'resume' => '/files/karlhill-resume.pdf',
        'contact_placeholder' => 'Team leadership, EM opportunities, or a platform that needs to ship — tell me what you\'re working on.',
    ],

    'about' => [
        // About-only lede — answers why this career story matters (not a homepage repeat).
        'lede' => 'I\'m a staff engineer building Engineering Manager muscles — leading platform teams through messy, high-stakes delivery where reliability, coaching, and judgment matter as much as the architecture. This page is the arc behind the work: NASA science operations, aerospace mission software, and how I lead people through hard systems.',
        'how_i_lead' => [
            'title' => 'How I lead',
            'intro' => 'Staff influence becomes manager accountability when people outcomes are as explicit as system outcomes. This is how I work with engineers and stakeholders today.',
            'items' => [
                [
                    'title' => '1:1s that surface risk',
                    'body' => 'Career growth, feedback, and delivery risk in the same conversation — not three separate rituals. Blockers show up early enough to act.',
                ],
                [
                    'title' => 'Tradeoffs made visible',
                    'body' => 'What we ship, what we defer, and what we refuse — so the team can protect focus without politics.',
                ],
                [
                    'title' => 'Standards over heroics',
                    'body' => 'Raise the bar through coaching instead of becoming the bottleneck — PR discipline, Definition of Done, and reviews that teach.',
                ],
                [
                    'title' => 'Stakeholder trust in plain language',
                    'body' => 'Partner with product and mission partners so trust survives roadmap pressure and surprise constraints.',
                ],
                [
                    'title' => 'Team outcomes over personal touch',
                    'body' => 'Measure success by predictability and ownership — not by how much I personally touch.',
                ],
            ],
        ],
        // Human coda — kept true and specific; sits after the résumé sections.
        'beyond' => 'Away from the terminal, I\'m based in Washington, DC, where I write and release music (you\'ll find a back catalog on Discogs). I\'m happiest with a hard problem, a whiteboard, and a team worth building with — and I care as much about mentoring the next engineer as I do about shipping the next release.',
    ],

    'now' => [
        'updated' => 'August 7, 2026',
        'lede' => 'Building Engineering Manager muscles while shipping aerospace platforms — honest about the Staff title, clear about the destination.',
        'focus' => [
            [
                'title' => 'Engineering leadership trajectory',
                'body' => 'Open to Engineering Manager and Staff+ leadership roles. Practicing the manager craft now: coaching, prioritization, stakeholder trust, and team systems that outlast heroics.',
            ],
            [
                'title' => 'Aerospace platform delivery',
                'body' => 'At Jacobs, owning cloud-native mission simulation and telemetry services — planning, DevSecOps, and release governance when mission risk is high.',
            ],
            [
                'title' => 'Writing the leadership craft',
                'body' => 'Publishing the Engineering Manager craft series — Staff→EM first 90 days, saying no under roadmap pressure, and performance feedback without politics.',
            ],
        ],
        'reading' => 'Looking for conversations about team leadership, platform ownership, and roles where technical depth and people leadership reinforce each other.',
    ],

    'github' => [
        'language_colors' => [
            'JavaScript' => '#f1e05a',
            'TypeScript' => '#3178c6',
            'Python' => '#3572A5',
            'PHP' => '#4F5D95',
            'Java' => '#b07219',
            'HTML' => '#e34c26',
            'CSS' => '#563d7c',
            'Shell' => '#89e051',
            'Go' => '#00ADD8',
            'Ruby' => '#701516',
            'Blade' => '#f7523f',
            'Rust' => '#dea584',
        ],

        // Curated set shown when the live GitHub API is unavailable (rate limit,
        // outage, or no network) so the Open Source section is never empty.
        'fallback_repos' => [
            [
                'name' => 'sim-rs',
                'description' => 'High-performance satellite orbit and telemetry simulation engine. Built with Rust and Tokio for scale-testing mission control pipelines and high-throughput data sinks.',
                'url' => 'https://github.com/karlhillx/sim-rs',
                'stars' => 0,
                'language' => 'Rust',
                'topics' => ['rust', 'simulation', 'aerospace'],
            ],
            [
                'name' => 'pipeguard',
                'description' => 'Policy-as-code validator for Bitbucket Pipelines. Enforce CI/CD standards, deployment safety, and organizational consistency across repositories.',
                'url' => 'https://github.com/karlhillx/pipeguard',
                'stars' => 0,
                'language' => 'Go',
                'topics' => ['ci-cd', 'policy-as-code'],
            ],
            [
                'name' => 'bb-run',
                'description' => 'Run Bitbucket Pipelines locally — execute your bitbucket-pipelines.yml faithfully in Docker or on your host, with parallel steps, fail-fast, and artifacts.',
                'url' => 'https://github.com/karlhillx/bb-run',
                'stars' => 1,
                'language' => 'Python',
                'topics' => ['bitbucket-pipelines', 'devops'],
            ],
            [
                'name' => 'driftlens',
                'description' => 'Observability for configuration drift. Detect, classify, and score risky environment differences across .env, YAML, and JSON with policy-as-code.',
                'url' => 'https://github.com/karlhillx/driftlens',
                'stars' => 0,
                'language' => 'Python',
                'topics' => ['observability', 'configuration'],
            ],
            [
                'name' => 'drift-rs',
                'description' => 'High-performance telemetry and simulation data sink for aerospace operations. Built in Rust for memory safety, ultra-low latency, and mission-critical reliability.',
                'url' => 'https://github.com/karlhillx/drift-rs',
                'stars' => 0,
                'language' => 'Rust',
                'topics' => ['rust', 'telemetry', 'aerospace'],
            ],
        ],
    ],

];
