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
            ['label' => 'LinkedIn', 'url' => 'https://www.linkedin.com/in/khill/', 'primary' => true],
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
                    'detail' => 'SugarCRM solutions for 20+ clients including VDOT and Kastle Systems. Cut delivery backlog ~90%, improving predictability and customer satisfaction.',
                ],
                [
                    'company' => 'Sabre Corporation',
                    'meta' => 'Software Engineer · Bethesda, MD · 2010–2012',
                    'detail' => 'PHP applications for global travel clients. Mentored junior developers through pairing and code review.',
                ],
                [
                    'company' => 'Dante Inc.',
                    'meta' => 'Principal Software Engineer · Arlington, VA · 2007–2010',
                    'detail' => 'Java solutions for Comcast and Mastercard improving operational efficiency by ~40%. Led Scrum delivery from planning through retros.',
                ],
                [
                    'company' => 'Verizon Business',
                    'meta' => 'Software Developer · Herndon, VA · 1999–2005',
                    'detail' => 'Built Finium, the managed security-services platform that drove a 10× increase in client engagements and supported a $105M acquisition by MCI/Verizon.',
                ],
                [
                    'company' => 'Advantage Industries, Inc.',
                    'meta' => 'Sr. Software Engineer · Columbia, MD · 1997–1999',
                    'detail' => 'Built database-driven enterprise applications automating client workflows and eliminating manual bottlenecks. Foundational role in production software delivery and data modeling.',
                ],
            ],
        ],
    ],

    'projects' => [
        [
            'title' => 'NASA Earth Observatory',
            'meta' => 'NASA · 2017–2025',
            'description' => 'Flagship science communication platform serving 1.5M+ monthly visitors with satellite imagery and Earth science data. Rebuilt the publishing pipeline to unify editorial workflows across distributed content teams. Overhauled the delivery architecture for accessibility compliance and redesigned the information hierarchy for long-term scale.',
            'image' => '/img/ss-earth-observatory.png',
            'url' => 'https://earthobservatory.nasa.gov',
            'tags' => ['Laravel', 'AWS'],
            'logo' => ['path' => '/img/logo-nasa.svg', 'filter' => null, 'class' => 'h-8'],
        ],
        [
            'title' => 'Flood Mapping System',
            'meta' => 'NASA · 2017–2025',
            'description' => 'Operational satellite imagery processing system generating near real-time flood inundation maps during active disaster events globally. Automated the end-to-end pipeline from raw sensor acquisition through geospatial product generation, dissemination, and integration with international emergency management networks.',
            'image' => '/img/small-flood.png',
            'url' => null,
            'tags' => ['Python', 'Docker', 'AWS'],
            'logo' => ['path' => '/img/logo-nasa.svg', 'filter' => null, 'class' => 'h-8'],
        ],
        [
            'title' => 'Direct Readout Laboratory',
            'meta' => 'NASA · 2017–2025',
            'description' => 'Scientific data processing hub ingesting multi-instrument sensor streams from polar-orbiting satellites in near real-time. Reformats and distributes Level-0 through Level-2 geophysical products to operational centers and research institutions across a global network of registered direct broadcast ground stations.',
            'image' => '/img/ss-direct-readout2.png',
            'url' => null,
            'tags' => ['PHP', 'Linux', 'NGINX'],
            'logo' => ['path' => '/img/logo-nasa.svg', 'filter' => null, 'class' => 'h-8'],
        ],
        [
            'title' => 'ESSCOR',
            'meta' => 'NASA · 2017–2025',
            'description' => 'Earth science data discovery portal unifying archival and near real-time remote sensing holdings into a searchable, standards-compliant catalog. Implemented granule-level access controls and standardized metadata schemas to streamline data ordering and delivery for researchers across government agencies and partner institutions.',
            'image' => '/img/ss-esccor.png',
            'url' => null,
            'tags' => ['PHP', 'MySQL', 'ElasticSearch'],
            'logo' => ['path' => '/img/logo-nasa.svg', 'filter' => null, 'class' => 'h-8'],
        ],
        [
            'title' => 'InformedDNA Platform',
            'meta' => 'InformedDNA · 2016–2017',
            'description' => 'Clinical genomics workflow platform coordinating case management, genetic counseling routing, and billing reconciliation across distributed care teams. Unified fragmented operational processes into a governed system with role-based access, full audit trails, and automated documentation pipelines that cut per-case overhead by $30K annually.',
            'image' => '/img/ss-informeddna.png',
            'url' => null,
            'tags' => ['Laravel', 'MySQL', 'RESTful APIs'],
            'logo' => ['path' => '/img/logo-informeddna.png', 'filter' => 'brightness(0) invert(1)', 'class' => 'h-6'],
        ],
        [
            'title' => 'Finium',
            'meta' => 'Verizon Business · 1999–2005',
            'description' => 'Enterprise managed security services platform unifying multi-tenant client operations across a national carrier network for a Fortune 500 provider. Automated provisioning, monitoring, and incident response orchestration drove a 10× growth in client engagements and contributed directly to a $105M acquisition.',
            'image' => '/img/ss-mci-verizon.png',
            'url' => null,
            'tags' => ['Java', 'SQL Server', 'Security'],
            'logo' => ['path' => '/img/logo-verizon.svg', 'filter' => null, 'class' => 'h-5'],
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

];
