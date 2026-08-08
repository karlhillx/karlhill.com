<?php

/**
 * Site content aggregator.
 *
 * Domain content lives in config/site/*.php. Environment-sensitive flags and
 * derived values (sameAs, analytics primary) stay here so fragments stay pure.
 *
 * Page roles (avoid repeating the same pitch everywhere):
 * - /        positioning + proof hooks
 * - /now     current focus + recruiter path
 * - /about   career arc + how I lead
 * - /resume  facts (canonical HTML CV)
 * - footer   single contact CTA
 */
$social = require __DIR__.'/site/social.php';

// Structured-data sameAs: one URL list derived from social links.
$sameAs = array_values(array_unique(array_map(
    static fn (string $url): string => rtrim($url, '/'),
    array_column($social, 'url')
)));

// Analytics: GA4 is the default primary. Enabling Plausible opts into it as
// the sole provider (avoids dual tracking).
$usePlausible = filter_var(env('PLAUSIBLE_ENABLED', false), FILTER_VALIDATE_BOOLEAN);
$useGoogle = ! $usePlausible && filter_var(env('GOOGLE_ANALYTICS_ENABLED', true), FILTER_VALIDATE_BOOLEAN);

return [

    'person' => require __DIR__.'/site/person.php',

    'analytics' => [
        'provider' => $usePlausible ? 'plausible' : ($useGoogle ? 'google' : 'none'),
        'google' => [
            'enabled' => $useGoogle,
            'id' => env('GOOGLE_ANALYTICS_MEASUREMENT_ID', 'G-EZZNL8KY8P'),
        ],
        'plausible' => [
            'enabled' => $usePlausible,
            'domain' => env('PLAUSIBLE_DOMAIN', 'karlhill.com'),
        ],
    ],

    // Cal.com (or Calendly). CTAs on /now, homepage availability, footer, menu.
    'booking' => [
        'url' => env('BOOKING_URL', 'https://calendly.com/karlhill'),
        'label' => env('BOOKING_LABEL', 'Book a conversation'),
    ],

    'series' => require __DIR__.'/site/series.php',

    // CI-only accessibility fixtures (never enable in production).
    'a11y_fixtures' => filter_var(env('A11Y_FIXTURES', false), FILTER_VALIDATE_BOOLEAN),

    'seo' => require __DIR__.'/site/seo.php',
    'social' => $social,
    'same_as' => $sameAs,
    'hero' => require __DIR__.'/site/hero.php',
    'pillars' => require __DIR__.'/site/pillars.php',
    'stats' => require __DIR__.'/site/stats.php',
    'experience' => require __DIR__.'/site/experience.php',
    'projects' => require __DIR__.'/site/projects.php',
    'research' => require __DIR__.'/site/research.php',
    'stack' => require __DIR__.'/site/stack.php',
    'certifications' => require __DIR__.'/site/certifications.php',
    'education' => require __DIR__.'/site/education.php',
    'footer' => require __DIR__.'/site/footer.php',
    'about' => require __DIR__.'/site/about.php',
    'now' => require __DIR__.'/site/now.php',
    'github' => require __DIR__.'/site/github.php',
    'resume' => require __DIR__.'/site/resume.php',

];
