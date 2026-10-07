<?php

$facts = require __DIR__.'/facts.php';

return [
    'intro' => 'Staff Aerospace Software Engineer and technical leader with 25+ years delivering mission-critical software across national security, aerospace, NASA, and enterprise environments. Leads cross-program engineering across multiple teams, shared repositories, partner organizations, and deployment environments while remaining hands-on in Python, distributed systems, integration, and developer tooling. Builds the engineering systems - architecture, CI/CD, DevSecOps, testing, release governance, and standards - that enable teams to deliver reliable software at scale.',
    'current' => [
        'label' => 'Current Role',
        'title' => 'Staff Aerospace Software Engineer',
        'company' => $facts['employer'],
        'location' => 'Chantilly, VA',
        'period' => 'Sept 2025 — Present',
        'summary' => "Hands-on mission software and cross-program technical leadership across internal and partner teams. Core-program execution spans {$facts['team']} engineers and {$facts['repos']} Python repositories.",
        'scope' => [
            'owned' => "Hands-on implementation, technical execution, engineering standards, and engineer development. Core-program scope: {$facts['team']} engineers across {$facts['repos']} Python repositories and multiple deployment environments.",
            'influence' => 'Cross-program integration strategy, shared interfaces and engineering practices, partner-team dependencies, architecture decisions, and release readiness across aerospace mission-software efforts.',
        ],
        'highlights' => [
            'Provide cross-program technical leadership across multiple aerospace mission-software efforts, aligning internal and partner teams on engineering standards, shared interfaces, integration strategy, and release readiness.',
            "Lead technical execution for a core team of {$facts['team']} engineers across {$facts['repos']} Python repositories and multiple deployment environments; sequence work, resolve cross-team dependencies, and drive integration and delivery.",
            'Design and develop mission software, distributed integrations, shared contracts, messaging capabilities, and service orchestration spanning RabbitMQ and ActiveMQ.',
            'Build and evolve platform engineering and DevSecOps capabilities including Bitbucket Pipelines, automated testing, dependency management, repository standards, quality/security gates, and release automation.',
            'Provide technical direction through architecture and design reviews, code review, interface decisions, repository governance, and resolution of cross-team implementation and integration issues.',
            'Onboarded and coached approximately six engineers through technical feedback, development guidance, engineering standards, and structured growth plans while leading Agile execution across team boundaries.',
        ],
        'skills' => [
            'Python',
            'Technical leadership',
            'Distributed systems',
            'Messaging (RabbitMQ/ActiveMQ)',
            'CI/CD',
            'Testing',
            'Release engineering',
            'Agile / Scrum',
            'Mentoring',
            'Cross-team coordination',
        ],
    ],
    'roles' => [
        [
            'title' => 'Lead Software Engineer',
            'company' => 'SSAI / NASA Goddard Space Flight Center',
            'portfolio_group' => 'nasa',
            'location' => 'Greenbelt, MD',
            'period' => 'Dec 2017 — Sept 2025',
            'summary' => 'Earth science software other people used: flood maps, satellite-data access, and science publishing.',
            'highlights' => [
                'Architected and developed an AWS-based platform generating near-real-time flood and surface-water products from satellite and geospatial data; co-authored the peer-reviewed GeoHorizons publication describing the system.',
                'Helped rebuild NASA Earth Observatory, serving approximately 1.5 million monthly visitors; the team received a NASA Group Achievement Award.',
                'Delivered LAADS DAAC Find Data search, ordering, and near-real-time access, with GitLab CI/CD and Kubernetes-based web delivery alongside existing archive services.',
                'Led modernization of legacy scientific processing workflows into containerized services supported by automated CI/CD and Kubernetes deployment.',
                'Built an automated content-registry workflow that improved scientific data-collection efficiency by approximately 60%, and developed Ceph-based file and metadata services for large datasets.',
                'Led Agile technical delivery across engineers, scientists, operations teams, and program stakeholders while strengthening testing, code review, documentation, and production-readiness practices.',
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
            'location' => 'Washington, DC',
            'period' => 'Jan 2016 — Dec 2017',
            'summary' => 'Case-management software and CRM workflows for genetic counseling operations.',
            'highlights' => [
                'Designed and delivered a Laravel case-management platform connecting counseling workflows, documentation, and billing.',
                'Built CRM enhancements for customer lifecycle workflows and reporting, and tightened maintenance and security processes with operations.',
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
            'location' => 'Washington, DC',
            'period' => 'Jun 2012 — Mar 2015',
            'summary' => 'CRM implementation and software delivery for client organizations.',
            'highlights' => [
                'Delivered SugarCRM solutions for more than 20 clients, including the Virginia Department of Transportation and Kastle Systems.',
                'Improved backlog management and delivery practices across client projects.',
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
            'Held software engineering and principal-level roles across travel, enterprise CRM, telecommunications, and managed security.',
            'At Sabre, built new PHP applications and added features to existing ones for large-scale travel systems.',
            'Built Java and SQL Server services for Finium, a multi-tenant managed-security platform, and contributed to shared testing and code-quality practices.',
        ],
        'skills' => [
            'Managed security',
            'Multi-tenant platforms',
            'CRM',
            'Telecommunications',
            'Travel systems',
        ],
        'entries' => [
            [
                'company' => 'Sabre Corporation · Dante Inc. · Visitar Inc. · Verizon Business',
                'meta' => 'Software engineering & principal roles · 1997–2012',
                'detail' => 'Held software engineering and principal-level roles across travel, enterprise CRM, telecommunications, and managed security — including new and existing PHP applications at Sabre, and Finium, a multi-tenant managed-security platform.',
            ],
        ],
    ],
];
