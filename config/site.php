<?php

use App\Support\Booking;

/**
 * Site content aggregator.
 *
 * Domain content lives in config/site/*.php. Environment-sensitive flags and
 * derived values (sameAs, analytics primary) stay here so fragments stay pure.
 *
 * Page roles (avoid repeating the same pitch everywhere):
 * - /        positioning + proof hooks (availability ping only)
 * - /now     current focus first; short recruiter strip; booking
 * - /kit     one-pager: PDF + bio + links
 * - /about   how I lead + arc teaser + research
 * - /resume  facts (canonical HTML CV)
 * - /work    current Jacobs chapter + public NASA/older proof
 * - footer   contact CTA (not a second hire ask)
 */
$social = require __DIR__.'/site/social.php';

// Structured-data sameAs: professional profiles only (skip schema:false entries).
$sameAs = array_values(array_unique(array_map(
    static fn (string $url): string => rtrim($url, '/'),
    array_column(
        array_values(array_filter(
            $social,
            static fn (array $link): bool => ($link['schema'] ?? true) !== false
        )),
        'url'
    )
)));

// Analytics: Plausible is the default primary. GA4 only when explicitly enabled
// and Plausible is off (avoids dual tracking).
$usePlausible = filter_var(env('PLAUSIBLE_ENABLED', true), FILTER_VALIDATE_BOOLEAN);
$useGoogle = ! $usePlausible && filter_var(env('GOOGLE_ANALYTICS_ENABLED', false), FILTER_VALIDATE_BOOLEAN);

$bookingUrl = env('BOOKING_URL', 'https://calendly.com/karlhill');

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
        'url' => $bookingUrl,
        'label' => env('BOOKING_LABEL', 'Book a conversation'),
        'embed_src' => Booking::embedSrc($bookingUrl),
    ],

    // Cloudflare Turnstile — optional; when both keys are set the contact form
    // requires a successful challenge (progressive hardening).
    'turnstile' => [
        'site_key' => env('TURNSTILE_SITE_KEY'),
        'secret_key' => env('TURNSTILE_SECRET_KEY'),
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
    'kit' => require __DIR__.'/site/kit.php',
    'github' => require __DIR__.'/site/github.php',
    'resume' => require __DIR__.'/site/resume.php',

    'push' => [
        'public_key' => env('VAPID_PUBLIC_KEY'),
        'private_key' => env('VAPID_PRIVATE_KEY'),
        'subject' => env('VAPID_SUBJECT', 'mailto:karlhillx@gmail.com'),
    ],

    'early_hints' => filter_var(env('EARLY_HINTS', false), FILTER_VALIDATE_BOOLEAN),

    'features' => [
        'webmention' => filter_var(env('WEBMENTION_ENABLED', false), FILTER_VALIDATE_BOOLEAN),
        'reporting' => filter_var(env('REPORTING_ENABLED', false), FILTER_VALIDATE_BOOLEAN),
        'compression_dictionary' => filter_var(env('COMPRESSION_DICTIONARY', false), FILTER_VALIDATE_BOOLEAN),
        'content_credentials' => filter_var(env('CONTENT_CREDENTIALS', false), FILTER_VALIDATE_BOOLEAN),
        'webgpu' => filter_var(env('WEBGPU_FLOOD', false), FILTER_VALIDATE_BOOLEAN),
    ],

];
