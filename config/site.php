<?php

use App\Support\Booking;

/**
 * Site content aggregator.
 *
 * Domain content lives in config/site/*.php. Environment-sensitive flags and
 * derived values (sameAs, analytics primary) stay here so fragments stay pure.
 *
 * Page roles (hire path first — avoid parallel pitch surfaces):
 * - /        hire funnel: spoken ask + proof → selected work → delivery diagram → next steps → contact
 * - /work    Jacobs chapter + public NASA/older proof
 * - /kit     leave-behind: PDF + bio + links (primary recruiter packet)
 * - /now     booking (+ living status); Book CTAs land on #book
 * - /blog    writing
 * - /about   people craft + delivery OS + experience + credentials (secondary)
 * - /resume  HTML CV evidence (secondary; PDF from kit)
 * - /lead    301 → /about#delivery (legacy)
 * - footer   home = contact form; other pages = Book + email
 */
$social = require __DIR__.'/site/social.php';

// Structured-data sameAs: public identities for this person. Skip schema:false
// entries; prefer an explicit same_as URL when the href is a filtered view.
$sameAs = array_values(array_unique(array_map(
    static fn (array $link): string => rtrim((string) ($link['same_as'] ?? $link['url']), '/'),
    array_values(array_filter(
        $social,
        static fn (array $link): bool => ($link['schema'] ?? true) !== false
    ))
)));

// Wikipedia lists this Karl as the musician (dab → Karl Hill (musician) → GI).
$sameAs[] = 'https://en.wikipedia.org/wiki/Karl_Hill_(musician)';
$sameAs = array_values(array_unique($sameAs));

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

    'seo' => array_merge(require __DIR__.'/site/seo.php', [
        // HTML meta for Search Console URL-prefix verification (optional).
        'google_site_verification' => env('GOOGLE_SITE_VERIFICATION'),
    ]),
    'social' => $social,
    'same_as' => $sameAs,
    'hero' => require __DIR__.'/site/hero.php',
    'pillars' => require __DIR__.'/site/pillars.php',
    'stats' => require __DIR__.'/site/stats.php',
    'experience' => require __DIR__.'/site/experience.php',
    'projects' => require __DIR__.'/site/projects.php',
    'research' => require __DIR__.'/site/research.php',
    'stack' => require __DIR__.'/site/stack.php',
    'skills' => require __DIR__.'/site/skills.php',
    'certifications' => require __DIR__.'/site/certifications.php',
    'education' => require __DIR__.'/site/education.php',
    'footer' => require __DIR__.'/site/footer.php',
    'about' => require __DIR__.'/site/about.php',
    'now' => require __DIR__.'/site/now.php',
    'kit' => require __DIR__.'/site/kit.php',
    'lead' => require __DIR__.'/site/lead.php',
    'system' => require __DIR__.'/site/system.php',
    'github' => require __DIR__.'/site/github.php',
    'resume' => require __DIR__.'/site/resume.php',

    'push' => [
        'public_key' => env('VAPID_PUBLIC_KEY'),
        'private_key' => env('VAPID_PRIVATE_KEY'),
        'subject' => env('VAPID_SUBJECT', 'mailto:karlhillx@gmail.com'),
    ],

    'early_hints' => filter_var(env('EARLY_HINTS', false), FILTER_VALIDATE_BOOLEAN),

    // Browser reports (/report) are also written to the log at this level so
    // they reach whatever sink LOG_STACK points at. Set to "none" to disable.
    'reporting_log_level' => env('REPORTING_LOG_LEVEL', 'warning'),

    'features' => [
        'webmention' => filter_var(env('WEBMENTION_ENABLED', false), FILTER_VALIDATE_BOOLEAN),
        'reporting' => filter_var(env('REPORTING_ENABLED', false), FILTER_VALIDATE_BOOLEAN),
        'compression_dictionary' => filter_var(env('COMPRESSION_DICTIONARY', false), FILTER_VALIDATE_BOOLEAN),
        'content_credentials' => filter_var(env('CONTENT_CREDENTIALS', false), FILTER_VALIDATE_BOOLEAN),
        'webgpu' => filter_var(env('WEBGPU_FLOOD', false), FILTER_VALIDATE_BOOLEAN),
    ],

];
