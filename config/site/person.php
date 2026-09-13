<?php

return [
    'name' => 'Karl Hill',
    'given_name' => 'Karl',
    'family_name' => 'Hill',
    'additional_name' => 'M.',
    'job_title' => 'Staff Aerospace Software Engineer',
    'email' => 'karlhillx@gmail.com',
    'location' => 'Washington, DC',
    'tagline' => 'Software engineering, technical leadership, and delivery',
    // LinkedIn-ready headline (copy/paste); keep in sync with public positioning.
    'linkedin_headline' => 'Staff Aerospace Software Engineer at Jacobs | Mission software, technical delivery | NASA Goddard 2017–2025',
    // Desired next role — kit “Open to”, /now, llms.txt, hire packet. Not Person JSON-LD.
    'availability' => 'Principal Software Engineer, Engineering Manager, and technical leadership roles that combine strong software engineering with delivery, architecture, and developing engineers.',
    'availability_long' => 'Growing toward broader ownership of architecture, engineering strategy, delivery, and team development. Principal-level technical leadership is the primary path. Engineering management is a strong next step where the role stays technically credible and close to software delivery.',
    'trajectory' => 'Broader engineering leadership, including Principal-level technical roles and Engineering Manager.',
    'employer' => 'Jacobs',
    'employer_display' => 'Jacobs National Security',
    'twitter_handle' => '@karl_hill',
    'bio' => 'Staff Aerospace Software Engineer at Jacobs. Python mission software, shared interfaces, messaging, and CI/CD across roughly 20 repositories. Technical delivery and mentoring for a team of about 10. Lead Software Engineer at SSAI supporting NASA Goddard Earth science platforms, 2017–2025, including flood mapping, LAADS DAAC, and Earth Observatory.',
    // Schema.org Thing.disambiguatingDescription — unique facts so Google
    // separates this Person from the Scottish novelist (pen name) and the
    // 19th-century German baritone who owns the primary Wikipedia article.
    'disambiguating_description' => 'Washington, DC software engineer at Jacobs, NASA Goddard Earth science 2017–2025, research co-author, drummer in Sorry About Your Daughter and Government Issue — not the Scottish novelist.',
    // MusicGroup memberOf. Band Wikipedia/Wikidata belongs on the group, not Person.sameAs.
    // enwiki "Karl Hill (musician)" redirects to Government Issue — that URL is the band.
    'bands' => [
        [
            'name' => 'Sorry About Your Daughter',
            'same_as' => 'https://www.wikidata.org/wiki/Q30674084',
        ],
        [
            'name' => 'Government Issue',
            'same_as' => [
                'https://www.wikidata.org/wiki/Q1476234',
                'https://en.wikipedia.org/wiki/Government_Issue',
            ],
        ],
        [
            'name' => 'The Factory Incident',
            'same_as' => [
                'https://www.wikidata.org/wiki/Q23138529',
                'https://en.wikipedia.org/wiki/The_Factory_Incident',
            ],
        ],
    ],
];
