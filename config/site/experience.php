<?php

return [
    'intro' => 'Staff Aerospace Software Engineer and technical delivery leader with experience building mission-critical software and the engineering systems that support its delivery across NASA and national security programs. Lead execution, engineer development, CI/CD and engineering standards for a ~10-engineer team spanning ~20 repositories and multiple deployment environments. Combines hands-on software engineering with team leadership, cross-team coordination, and operational delivery.',
    'current' => [
        'label' => 'Current Role',
        'title' => 'Staff Aerospace Software Engineer',
        'company' => 'Jacobs — National Security',
        'location' => 'Chantilly, VA',
        'period' => 'Sept 2025 — Present',
        'summary' => 'Hands-on Staff engineer and technical delivery leader for aerospace mission software. Program specifics stay unpublished.',
        'highlights' => [
            'Lead day-to-day technical delivery for a ~10-engineer team developing Python-based mission software across ~20 repositories and multiple deployment environments; sequence work, coordinate dependencies, and drive integration and release readiness — including holding sprint commitments when partner environments are not ready.',
            'Build and evolve shared engineering systems for CI/CD, automated testing, security gates, repository standards, dependency management, and release automation, improving consistency across independently developed services.',
            'Develop distributed application integration and messaging capabilities spanning RabbitMQ/ActiveMQ, shared interfaces, service orchestration, and multi-environment deployments.',
            'Onboarded and coached approximately six engineers through code review, technical feedback, development guidance, and structured growth plans for junior engineers.',
            'Lead Scrum/Agile execution and coordinate with program stakeholders and external engineering teams to translate mission requirements into executable engineering work and resolve cross-team dependencies.',
        ],
        'skills' => [
            'Python',
            'Technical leadership',
            'CI/CD',
            'DevSecOps',
            'Distributed systems',
            'Messaging (RabbitMQ/ActiveMQ)',
            'Release automation',
            'Agile / Scrum',
            'Engineer development',
            'Cross-team coordination',
        ],
    ],
    'roles' => [
        [
            'title' => 'Lead Software Engineer',
            'company' => 'SSAI / NASA Goddard Space Flight Center',
            'location' => 'Greenbelt, MD',
            'period' => 'Dec 2017 — Sept 2025',
            'summary' => 'Earth science platforms at operational scale — LAADS DAAC, flood mapping, and scientific data systems.',
            'highlights' => [
                'Architected NASA\'s cloud-based Flood Mapping System on AWS, delivering near real-time, satellite-derived flood products to support disaster response. <a href="/work/flood-mapping-system" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">Case study</a>',
                'Modernized NASA LAADS DAAC as Lead Software Engineer — Find Data search and order, the archive portal, and LANCE near-real-time access — and moved delivery onto GitLab CI/CD and Kubernetes. <a href="/work/laads-daac" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">Case study</a>',
                'Rebuilt NASA Earth Observatory\'s high-traffic web platform, supporting ~1.5M monthly visitors while improving performance, UX, and SEO. <a href="/work/nasa-earth-observatory" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">Case study</a>',
                'Delivered an automated content registry workflow, boosting data collection efficiency by ~60% and accelerating researcher access to new datasets. <a href="/work/esscor" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">Case study</a>',
                'Built a high-performance file and metadata platform on Ceph, improving virtual directory mapping and accelerating discovery for large scientific datasets. <a href="/work/direct-readout-laboratory" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">Case study</a>',
            ],
            'skills' => [
                'AWS',
                'Flood mapping',
                'Earth science software',
                'GitLab CI/CD',
                'Docker',
                'Kubernetes',
                'Helm',
                'Ceph',
                'High-traffic web',
                'Scientific data systems',
            ],
        ],
        [
            'title' => 'Senior Software Engineer',
            'company' => 'InformedDNA',
            'location' => 'Washington, D.C.',
            'period' => 'Jan 2016 — Dec 2017',
            'highlights' => [
                'Architected and delivered a Laravel-based case management platform, reducing operational costs by <strong class="text-white font-semibold">$30K/year</strong>. <a href="/work/informeddna-platform" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">Case study</a>',
                'Led CRM enhancements that improved retention and contributed ~15% revenue growth through better lifecycle workflows and reporting.',
                'Spearheaded platform upgrades and security process improvements, doubling incident response efficiency and strengthening operational readiness.',
            ],
            'skills' => [
                'Laravel',
                'Case management',
                'CRM',
                'Healthcare software',
                'Security operations',
            ],
        ],
        [
            'title' => 'Senior Software Engineer',
            'company' => 'Ticomix, Inc.',
            'location' => 'Washington, D.C.',
            'period' => 'Jun 2012 — Mar 2015',
            'highlights' => [
                'Delivered SugarCRM solutions for 20+ clients (including Virginia Department of Transportation, Washington Redskins, and Kastle Systems), improving sales operations and team productivity.',
                'Drove execution discipline that cut backlog ~90%, improving delivery predictability, product quality, and customer satisfaction.',
            ],
            'skills' => [
                'SugarCRM',
                'Enterprise CRM',
                'Delivery management',
            ],
        ],
    ],
    'earlier' => [
        'title' => 'Earlier Engineering Experience',
        'period' => '1997 — 2012',
        'company' => 'Sabre Corporation · Dante Inc. · Visitar Inc. · Verizon Business',
        'highlights' => [
            'Held software engineering and principal roles across travel, enterprise CRM, telecommunications, and managed security — delivering production systems for clients including Comcast, Mastercard, Verizon/MCI, and global travel customers.',
            'Shipped Finium, the multi-tenant managed-security platform that enabled a <strong class="text-white font-semibold">$105M</strong> acquisition, and helped mature engineering practices around testing, code quality, and cross-functional delivery. <a href="/work/finium" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">Case study</a>',
        ],
        'skills' => [
            'Managed security',
            'Multi-tenant platforms',
            'CRM',
            'Telecommunications',
            'Travel systems',
        ],
        // Legacy single-entry shape kept for any older consumers; prefer highlights above.
        'entries' => [
            [
                'company' => 'Sabre Corporation · Dante Inc. · Visitar Inc. · Verizon Business',
                'meta' => 'Software engineering & principal roles · 1997–2012',
                'detail' => 'Held software engineering and principal roles across travel, enterprise CRM, telecommunications, and managed security for clients including Comcast, Mastercard, Verizon/MCI, and global travel customers — including Finium, the multi-tenant managed-security platform that enabled a <strong class="text-white font-semibold">$105M</strong> acquisition. <a href="/work/finium" class="text-accent underline underline-offset-[3px] decoration-accent/35 hover:decoration-accent transition-colors">Case study</a>',
            ],
        ],
    ],
];
