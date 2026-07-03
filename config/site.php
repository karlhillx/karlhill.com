<?php

return [

    'person' => [
        'name' => 'Karl Hill',
        'job_title' => 'Staff Software Engineer',
        'email' => 'karlhillx@gmail.com',
        'location' => 'Washington, DC',
        'tagline' => 'Staff Software Engineer · 25+ Years',
        'availability' => 'Available for select consulting',
        'employer' => 'Jacobs',
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

    'seo' => [
        'home' => [
            'title' => 'Karl Hill — Staff Software Engineer',
            'description' => 'Staff Software Engineer with 25+ years building NASA Earth science platforms, flood mapping systems, and mission-critical aerospace software at Jacobs.',
            'og_description' => '25+ years shipping mission-critical software — NASA Earth science, flood mapping, and aerospace platforms at Jacobs.',
        ],
        'blog_index' => [
            'title' => 'Writing — Karl Hill',
            'description' => 'Reflections on engineering leadership, mission software, and the work that turns code into something people depend on — by Karl Hill.',
            'og_description' => 'Reflections on engineering leadership, mission software, and the work that turns code into something people depend on.',
        ],
        'work' => [
            'title' => 'Work — Karl Hill',
            'description' => 'Selected projects — NASA Earth science platforms, flood mapping systems, clinical genomics workflows, and enterprise security software.',
            'og_description' => 'Mission-critical platforms built across NASA, aerospace, healthcare, and enterprise security.',
        ],
        'about' => [
            'title' => 'About — Karl Hill',
            'description' => 'Experience, research, technical stack, and credentials — 25+ years building and leading engineering teams.',
            'og_description' => 'Career history, peer-reviewed research, technical stack, and professional certifications.',
        ],
    ],

    'social' => [
        ['label' => 'LinkedIn', 'url' => 'https://www.linkedin.com/in/khill/', 'icon' => 'linkedin'],
        ['label' => 'GitHub', 'url' => 'https://github.com/karlhillx', 'icon' => 'github'],
        ['label' => 'X / Twitter', 'url' => 'https://twitter.com/karl_hill/', 'icon' => 'twitter'],
        ['label' => 'ORCID', 'url' => 'https://orcid.org/0009-0002-6847-3368', 'icon' => 'orcid'],
        ['label' => 'ResearchGate', 'url' => 'https://www.researchgate.net/profile/Karl-Hill-2', 'icon' => 'researchgate'],
        ['label' => 'Discogs', 'url' => 'https://www.discogs.com/artist/1286669-Karl-Hill', 'icon' => 'discogs'],
    ],

    'same_as' => [
        'https://www.linkedin.com/in/khill/',
        'https://github.com/karlhillx',
        'https://twitter.com/karl_hill',
        'https://orcid.org/0009-0002-6847-3368',
        'https://www.researchgate.net/profile/Karl-Hill-2',
        'https://www.discogs.com/artist/1286669-Karl-Hill',
    ],

    'hero' => [
        'headline' => 'Karl Hill',
        'subtitle' => 'Cloud · Platforms · Engineering Leadership',
        'bio' => 'I architect systems, lead teams, and ship software that matters — from disaster-response platforms at NASA to mission-critical aerospace systems at Jacobs/BlackLynx.',
        'cta' => [
            ['label' => 'View Work', 'url' => '/work', 'primary' => true],
            ['label' => 'About', 'url' => '/about'],
            ['label' => 'LinkedIn', 'url' => 'https://www.linkedin.com/in/khill/'],
            ['label' => 'karlhillx@gmail.com', 'url' => 'mailto:karlhillx@gmail.com'],
            ['label' => 'GitHub', 'url' => 'https://github.com/karlhillx'],
        ],
    ],

    'pillars' => [
        [
            'title' => 'I Build',
            'body' => 'Cloud-native platforms on AWS. Containerized services with Docker and Kubernetes. High-traffic web systems. Secure CI/CD pipelines. Built to last and operate reliably at scale — not just to demo well.',
        ],
        [
            'title' => 'I Lead',
            'body' => 'Engineering teams from roadmap to release. 1:1s, onboarding, PR standards, definition of done — the unglamorous work that turns a group of developers into a high-performing team that ships consistently.',
        ],
        [
            'title' => 'I Deliver',
            'body' => 'Predictable execution, every sprint. I translate mission needs into sequenced plans, manage stakeholders across technical and non-technical audiences, remove blockers, and ship.',
        ],
    ],

    'stats' => [
        ['display' => '25+', 'label' => 'Years of Experience', 'to' => 25, 'prefix' => '', 'suffix' => '+'],
        ['display' => '1.5M', 'label' => 'Monthly Visitors · NASA Platforms', 'to' => 1.5, 'prefix' => '', 'suffix' => 'M'],
        ['display' => '$105M', 'label' => 'Platform Acquisition Value', 'to' => 105, 'prefix' => '$', 'suffix' => 'M'],
        ['display' => '~60%', 'label' => 'Efficiency Gained via Automation', 'to' => 60, 'prefix' => '~', 'suffix' => '%'],
    ],

    'experience' => [
        'current' => [
            'label' => 'Current Role',
            'title' => 'Staff Aerospace Software Engineer',
            'company' => 'Jacobs',
            'location' => 'Chantilly, VA',
            'period' => 'Sept 2025 — Present',
            'highlights' => [
                'Lead delivery for cloud-native mission-simulation and telemetry services. Own planning, refinement, demos, and stakeholder alignment end-to-end.',
                'Own team health and execution — coaching, 1:1s, onboarding, and delivery discipline across multi-repo, multi-environment systems.',
                'Set and enforce engineering standards: repo/branch governance, PR reviews, Definition of Done, and documentation to reduce integration risk.',
                'Drive DevSecOps and release governance via CI/CD, automated testing, quality gates, and security checks to shorten feedback loops.',
            ],
        ],
        'roles' => [
            [
                'title' => 'Lead Software Engineer',
                'company' => 'NASA / SSAI',
                'location' => 'Lanham, MD',
                'period' => 'Dec 2017 — Sept 2025',
                'highlights' => [
                    'Architected NASA\'s cloud-based Flood Mapping System on AWS, delivering near real-time satellite-derived flood products to support global disaster response.',
                    'Rebuilt the NASA Earth Observatory — a platform serving <strong class="text-white font-semibold">~1.5M monthly visitors</strong> — improving performance, UX, and SEO.',
                    'Built a high-performance Ceph-based file + metadata platform, improving large dataset discovery and access for researchers.',
                    'Delivered an automated content registry workflow that boosted data collection efficiency by <strong class="text-white font-semibold">~60%</strong> and accelerated researcher access.',
                    'Implemented GitLab CI/CD + Docker + Kubernetes delivery — automated deployments, repeatable releases, reliable stakeholder approvals.',
                ],
            ],
            [
                'title' => 'Sr. Software Engineer',
                'company' => 'InformedDNA',
                'location' => 'St. Petersburg, FL',
                'period' => 'Jan 2016 — Dec 2017',
                'highlights' => [
                    'Architected a Laravel-based case management platform reducing operational costs by <strong class="text-white font-semibold">$30K/year</strong>.',
                    'Led CRM enhancements improving client retention and contributing ~15% revenue growth through better lifecycle workflows and reporting.',
                    'Spearheaded platform upgrades and security improvements, doubling incident response efficiency and strengthening operational readiness.',
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
                    'company' => 'Verizon Business',
                    'meta' => 'Software Developer · Herndon, VA · 1999–2005',
                    'detail' => 'Architected Finium, the multi-tenant managed-security platform that scaled client engagements 10× and directly enabled a $105M acquisition by MCI/Verizon.',
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
        ['abbr' => 'PSM II', 'name' => 'Professional Scrum Master™ II', 'issuer' => 'Scrum.org', 'url' => 'https://www.credly.com/badges/1874ba29-99d7-4dae-8335-1a915795d956'],
        ['abbr' => 'PSD I', 'name' => 'Professional Scrum Developer™ I', 'issuer' => 'Scrum.org', 'url' => 'https://www.credly.com/badges/937b37cf-6fa7-49dd-8c70-e43378feda5b'],
        ['abbr' => 'PSPO I', 'name' => 'Professional Scrum Product Owner™ I', 'issuer' => 'Scrum.org', 'url' => 'https://www.credly.com/badges/da27e50e-ef55-41f0-bc14-ca26d9e3e0ff'],
        ['abbr' => 'CSM', 'name' => 'Certified ScrumMaster®', 'issuer' => 'Scrum Alliance', 'url' => 'https://certification.scrumalliance.org/accounts/1484321-karl-hill/certifications/1735632-csm'],
    ],

    'education' => [
        ['degree' => 'B.S. Computer Science Coursework', 'school' => 'University of Maryland'],
        ['degree' => 'Associate of Arts, General Studies', 'school' => 'Howard Community College'],
        ['degree' => 'Project Management', 'school' => 'Rutgers University'],
    ],

    'footer' => [
        'headline' => "Let's Work\nTogether",
        'body' => 'Building something important and need an engineer who can lead, architect, and deliver? I\'d like to hear about it.',
        'resume' => '/files/karlhill-resume.pdf',
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
    ],

];
