<?php

return [
    'name' => 'Karl Hill',
    'given_name' => 'Karl',
    'family_name' => 'Hill',
    'additional_name' => 'M.',
    'job_title' => 'Staff Aerospace Software Engineer',
    'email' => 'karlhillx@gmail.com',
    'location' => 'Washington, DC',
    'tagline' => 'Mission software, cross-program technical leadership, and platform engineering',
    // LinkedIn-ready headline (copy/paste); keep in sync with public positioning.
    'linkedin_headline' => 'Staff Aerospace Software Engineer at Jacobs | Cross-Program Technical Leadership | Mission Software & Platform Engineering | NASA co-author',
    // Desired next role — About, llms.txt, hire packet; not the hero or Person JSON-LD.
    'availability' => 'Interested in roles where strong hands-on software engineering and broader organizational leadership reinforce each other: mission software, architecture, platform engineering, developer experience, delivery, and engineer development.',
    'availability_long' => 'Best fit: technical leadership combining hands-on software engineering, architecture, platform engineering, developer experience, high-reliability delivery, and engineer development across team and program boundaries.',
    'trajectory' => 'Cross-program technical leadership, platform engineering, architecture, delivery, and engineer development.',
    'employer' => 'Jacobs',
    'employer_display' => 'Jacobs National Security',
    'twitter_handle' => '@karl_hill',
    'bio' => 'Staff Aerospace Software Engineer at Jacobs. Hands-on Python mission software and cross-program technical leadership across internal and partner teams, shared interfaces, messaging, platform engineering, and DevSecOps. Core-program technical execution for about 10 engineers across roughly 20 Python repositories. Lead Software Engineer at SSAI supporting NASA Goddard Earth science platforms, 2017–2025, including flood mapping (GeoHorizons co-author, 2026), LAADS DAAC, and Earth Observatory.',
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
