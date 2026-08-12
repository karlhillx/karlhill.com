<?php

return [
    0 => [
        'slug' => 'nasa-earth-observatory',
        'title' => 'NASA Earth Observatory',
        'meta' => 'NASA · 2017–2025',
        'description' => 'Flagship science-communication platform serving 1.5M+ monthly visitors with satellite imagery and Earth science data. Led the architectural overhaul of the publishing pipeline — re-platforming editorial workflows for distributed content teams and hardening the delivery layer for accessibility, performance, and search at scale. Set the technical direction that turned ad-hoc story production into a repeatable, self-service system built to evolve for the next decade.',
        'image' => '/img/webp/ss-earth-observatory.webp',
        'url' => 'https://earthobservatory.nasa.gov',
        'featured' => true,
        'tags' => [
            0 => 'Laravel',
            1 => 'AWS',
        ],
        'logo' => [
            'path' => '/img/logo-nasa.svg',
            'filter' => null,
            'class' => 'h-8',
        ],
        'case_study' => [
            'lede' => 'A flagship NASA science communication platform serving 1.5M+ monthly visitors — rebuilt so editors ship without waiting on engineering, and the stack stays maintainable for the next decade.',
            'role' => 'Lead engineer — owned the platform re-architecture and publishing pipeline, and set the frontend performance and accessibility standards.',
            'leadership' => [
                'mode' => 'Tech lead / sticky IC across platform engineering and editorial partners',
                'team' => '~4 engineers and content partners on a flagship site serving 1.5M+ monthly visitors',
                'unblocked' => 'Moved story production off one-off engineering work so editors could ship without waiting on custom builds.',
                'decision' => 'Traded short-term feature velocity for a shared publishing model — fewer heroics per story, higher long-term throughput.',
            ],
            'problem' => [
                0 => 'Routine stories still needed custom engineering — brittle, one-off publishing patterns made editorial velocity a queue, not a system.',
                1 => 'Traffic and imagery volume exposed performance, accessibility, and SEO debt that could not be patched story-by-story.',
                2 => 'Distributed content teams lacked a shared workflow — engineering had become the bottleneck for science communication at scale.',
            ],
            'decisions' => [
                0 => 'Standardize on repeatable story templates and a shared publishing model instead of per-story builds — accept slower net-new features to unlock editorial self-service.',
                1 => 'Treat large imagery, metadata consistency, and non-engineer workflows as first-class pipeline concerns, not afterthoughts bolted onto the CMS.',
                2 => 'Gate releases on frontend performance, accessibility, and search discoverability so public science traffic and WCAG expectations stay non-negotiable.',
            ],
            'approach' => [
                0 => 'Standardize on repeatable story templates and a shared publishing model instead of per-story builds — accept slower net-new features to unlock editorial self-service.',
                1 => 'Treat large imagery, metadata consistency, and non-engineer workflows as first-class pipeline concerns, not afterthoughts bolted onto the CMS.',
                2 => 'Gate releases on frontend performance, accessibility, and search discoverability so public science traffic and WCAG expectations stay non-negotiable.',
            ],
            'outcome' => [
                0 => 'Editors ship routine stories without waiting on custom engineering — throughput became a product of the system, not heroics.',
                1 => 'High-traffic public science audience got a stronger performance and accessibility baseline.',
                2 => 'Left a maintainable foundation for ongoing Earth science communication instead of another round of one-off platform debt.',
            ],
            'metrics' => [
                0 => [
                    'value' => '1.5M+',
                    'label' => 'Monthly visitors',
                ],
                1 => [
                    'value' => 'Self-serve',
                    'label' => 'Editorial publishing',
                ],
            ],
        ],
    ],
    1 => [
        'slug' => 'flood-mapping-system',
        'title' => 'Flood Mapping System',
        'meta' => 'NASA · 2017–2025',
        'description' => 'Mission-critical geospatial platform generating near-real-time flood inundation maps during active global disaster events. Architected the fully automated pipeline — from raw satellite sensor ingestion through geospatial product generation, dissemination, and integration with international emergency-management networks — engineered for fault tolerance and reliability when latency is measured in hours, not sprints.',
        'image' => '/img/webp/small-flood.webp',
        'imagePosition' => 'object-left-top',
        'url' => 'https://floodmap.web.nasa.gov',
        'featured' => true,
        'tags' => [
            0 => 'Python',
            1 => 'Docker',
            2 => 'AWS',
        ],
        'logo' => [
            'path' => '/img/logo-nasa.svg',
            'filter' => null,
            'class' => 'h-8',
        ],
        'case_study' => [
            'lede' => 'Near real-time flood inundation mapping from satellite data — built so disaster responders get trustworthy products in hours, without overnight engineering heroics.',
            'role' => 'Architect & lead developer — designed and automated the end-to-end geospatial pipeline on AWS.',
            'leadership' => [
                'mode' => 'Technical lead for a mission-critical geospatial pipeline',
                'team' => '~4 engineers and science partners, plus emergency-management users from sensor acquisition through global dissemination',
                'unblocked' => 'Removed manual handoffs that forced late-night heroics during active disaster events.',
                'decision' => 'Prioritized automation and fault tolerance over ad-hoc speed — latency dropped because the team system was reliable under urgency.',
            ],
            'problem' => [
                0 => 'Manual processing steps sat on the critical path — flood products lagged during active global disaster events when hours mattered.',
                1 => 'Sensor acquisition → product generation → dissemination crossed teams and environments with fragile handoffs.',
                2 => 'Emergency-management users needed repeatable, trustworthy maps — not one-off runs that only the on-call engineer could reproduce.',
            ],
            'decisions' => [
                0 => 'Automate end-to-end from raw sensor ingestion through geospatial product generation — remove humans from the latency path under urgency.',
                1 => 'Containerize processing stages so the same pipeline is deployable and recoverable across environments, not a snowflake workstation.',
                2 => 'Integrate outputs with emergency-management and research distribution networks so “done” means disseminated, not merely generated.',
            ],
            'approach' => [
                0 => 'Automate end-to-end from raw sensor ingestion through geospatial product generation — remove humans from the latency path under urgency.',
                1 => 'Containerize processing stages so the same pipeline is deployable and recoverable across environments, not a snowflake workstation.',
                2 => 'Integrate outputs with emergency-management and research distribution networks so “done” means disseminated, not merely generated.',
            ],
            'outcome' => [
                0 => 'Near real-time flood inundation maps during active disaster events worldwide — latency measured in hours, not overnight queues.',
                1 => 'Fewer manual handoffs under urgency — consistency came from the pipeline, not who was awake.',
                2 => 'Supported peer-reviewed research on global water and flood mapping (GeoHorizons).',
            ],
            'metrics' => [
                0 => [
                    'value' => 'Hours',
                    'label' => 'Not overnight latency',
                ],
                1 => [
                    'value' => 'E2E',
                    'label' => 'Automated pipeline',
                ],
            ],
        ],
    ],
    2 => [
        'slug' => 'direct-readout-laboratory',
        'title' => 'Direct Readout Laboratory',
        'meta' => 'NASA · 2017–2025',
        'description' => 'Real-time scientific data-processing hub ingesting multi-instrument sensor streams from polar-orbiting satellites. Designed the ingestion and reformatting architecture that transforms raw downlinks into Level-0 through Level-2 geophysical products, sustaining 24/7 distribution to operational centers and a global network of registered direct-broadcast ground stations.',
        'image' => '/img/webp/ss-direct-readout2.webp',
        'url' => 'https://directreadout.sci.gsfc.nasa.gov',
        'featured' => true,
        'tags' => [
            0 => 'PHP',
            1 => 'Linux',
            2 => 'NGINX',
        ],
        'logo' => [
            'path' => '/img/logo-nasa.svg',
            'filter' => null,
            'class' => 'h-8',
        ],
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
                0 => 'Multi-instrument sensor streams required consistent reformatting from Level-0 through Level-2 products.',
                1 => 'Operational centers and research partners depended on predictable, near real-time delivery.',
                2 => 'Legacy processing paths were difficult to operate and extend as instrument portfolios evolved.',
            ],
            'approach' => [
                0 => 'Built ingestion and reformatting pipelines tuned for polar-orbiting satellite data volumes.',
                1 => 'Standardized product tiers and distribution paths for downstream operational consumers.',
                2 => 'Operated on Linux/NGINX infrastructure designed for continuous scientific workloads.',
            ],
            'outcome' => [
                0 => 'Sustained near real-time distribution to registered direct broadcast ground stations.',
                1 => 'Improved reliability for multi-instrument product generation and handoff.',
                2 => 'Supported NASA direct readout operations across a global partner network.',
            ],
            'metrics' => [
                0 => [
                    'value' => 'L0–L2',
                    'label' => 'Product tiers',
                ],
                1 => [
                    'value' => '24/7',
                    'label' => 'Operational ingest',
                ],
            ],
        ],
    ],
    3 => [
        'slug' => 'esscor',
        'title' => 'ESSCOR',
        'meta' => 'NASA · 2017–2025',
        'description' => 'Earth science data-discovery platform unifying archival and near-real-time remote-sensing holdings into a single searchable, standards-compliant catalog. Designed granule-level access controls and a governed metadata model that streamlined discovery, ordering, and delivery for researchers across federal agencies and partner institutions.',
        'image' => '/img/webp/ss-esccor.webp',
        'url' => '/work/esscor',
        'tags' => [
            0 => 'PHP',
            1 => 'MySQL',
            2 => 'ElasticSearch',
        ],
        'logo' => [
            'path' => '/img/logo-nasa.svg',
            'filter' => null,
            'class' => 'h-8',
        ],
        'case_study' => [
            'lede' => 'A discovery portal unifying archival and near real-time remote sensing holdings into a searchable, standards-compliant catalog.',
            'role' => 'Lead developer — built the discovery and search platform, the metadata model, and granule-level access controls.',
            'problem' => [
                0 => 'Researchers struggled to discover and order data across fragmented archival and near real-time holdings.',
                1 => 'Metadata inconsistency slowed search, access control, and downstream ordering workflows.',
            ],
            'approach' => [
                0 => 'Implemented granule-level access controls and standardized metadata schemas.',
                1 => 'Built search and discovery on ElasticSearch with governed ordering and delivery paths.',
            ],
            'outcome' => [
                0 => 'Streamlined data discovery and ordering for government agencies and partner institutions.',
                1 => 'Reduced friction between catalog search and governed data access.',
            ],
            'metrics' => [
                0 => [
                    'value' => 'Granule',
                    'label' => 'Level access control',
                ],
            ],
        ],
    ],
    4 => [
        'slug' => 'informeddna-platform',
        'title' => 'InformedDNA Platform',
        'meta' => 'InformedDNA · 2016–2017',
        'description' => 'Clinical-genomics workflow platform orchestrating case management, genetic-counseling routing, and billing reconciliation across distributed care teams. Consolidated fragmented, manual operations into a single governed system — role-based access, end-to-end audit trails, and automated documentation pipelines — cutting per-case operational overhead by $30K annually.',
        'image' => '/img/webp/ss-informeddna.webp',
        'url' => 'https://www.informeddna.com',
        'tags' => [
            0 => 'Laravel',
            1 => 'MySQL',
            2 => 'RESTful APIs',
        ],
        'logo' => [
            'path' => '/img/webp/logo-informeddna.webp',
            'filter' => 'brightness(0) invert(1)',
            'class' => 'h-6',
        ],
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
                0 => 'Fragmented operational processes created manual overhead across case intake, routing, and billing.',
                1 => 'Distributed care teams lacked a governed system with auditability and role-based access.',
            ],
            'approach' => [
                0 => 'Architected a Laravel platform integrating case management, counseling workflows, and billing reconciliation.',
                1 => 'Automated documentation pipelines and enforced role-based access with full audit trails.',
            ],
            'outcome' => [
                0 => 'Cut per-case operational overhead by $30K annually.',
                1 => 'Improved coordination across distributed genetic counseling and care teams.',
            ],
            'metrics' => [
                0 => [
                    'value' => '$30K',
                    'label' => 'Annual savings per case type',
                ],
            ],
        ],
    ],
    5 => [
        'slug' => 'finium',
        'title' => 'Finium',
        'meta' => 'Verizon Business · 1999–2005',
        'description' => 'Enterprise managed-security platform running multi-tenant client operations across a national carrier network for a Fortune 500 provider. Owned the services that automated provisioning, monitoring, and incident-response orchestration — scaling operations 10× and directly enabling a $105M acquisition.',
        'image' => '/img/webp/ss-mci-verizon.webp',
        'url' => '/work/finium',
        'tags' => [
            0 => 'Java',
            1 => 'SQL Server',
            2 => 'Security',
        ],
        'logo' => [
            'path' => '/img/logo-verizon.svg',
            'filter' => null,
            'class' => 'h-5',
        ],
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
                0 => 'Multi-tenant security operations required manual provisioning, monitoring, and incident coordination.',
                1 => 'Growth was constrained by operational bottlenecks in client onboarding and response workflows.',
            ],
            'approach' => [
                0 => 'Built Java/SQL Server services automating provisioning, monitoring, and incident response orchestration.',
                1 => 'Unified multi-tenant client operations for a Fortune 500 carrier environment.',
            ],
            'outcome' => [
                0 => 'Drove a 10× increase in client engagements.',
                1 => 'Contributed to a $105M acquisition by MCI/Verizon.',
            ],
            'metrics' => [
                0 => [
                    'value' => '10×',
                    'label' => 'Client engagement growth',
                ],
                1 => [
                    'value' => '$105M',
                    'label' => 'Acquisition value',
                ],
            ],
        ],
    ],
];
