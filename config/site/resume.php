<?php

return [
    // Contact details used on /resume (screen + print). Keep phone/ZIP out of the
    // public person profile so they only appear on the CV.
    'phone' => '(202) 599-1442',
    'postal' => '',
    'tagline' => 'Aerospace Mission Software | Platform Engineering | DevSecOps | Technical Leadership',
    'impact' => [
        'Lead software delivery across mission systems, coordinating engineering execution, integration readiness, and releases across internal and external teams.',
        'Standardize CI/CD, developer tooling, and release practices across mission software repositories — pipelines, quality gates, and a repeatable path from commit to a constrained environment.',
        'Drive cloud-platform and Kubernetes delivery (containers, Helm/OCI packaging, multi-environment baselines) so teams ship the same way in isolated and integrated systems.',
        'Translate mission and operational requirements into engineering roadmaps, sequenced delivery plans, and executable software work.',
    ],
    'expertise' => [
        'Technical Leadership',
        'Platform Engineering & Developer Experience',
        'Cloud Platforms & Kubernetes',
        'CI/CD & DevSecOps',
        'Mission Software Delivery',
        'Systems Integration',
        'Engineering Governance',
    ],
    'tooling' => [
        [
            'name' => 'bb-run',
            'url' => 'https://github.com/karlhillx/bb-run',
            'note' => 'Local Bitbucket Pipelines runner — CI you can execute on a laptop.',
        ],
        [
            'name' => 'pipeguard',
            'url' => 'https://github.com/karlhillx/pipeguard',
            'note' => 'Policy-as-code checks for CI/CD standards and deployment safety.',
        ],
    ],
];
