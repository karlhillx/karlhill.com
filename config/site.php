<?php

use App\Support\Booking;

/**
 * Site content aggregator.
 *
 * Domain content lives in config/site/*.php. Environment-sensitive flags and
 * derived values (sameAs, analytics primary) stay here so fragments stay pure.
 *
 * Page roles (hire path first — avoid parallel pitch surfaces):
 * - /        identity + proof + next-role line → selected work → delivery diagram → contact
 * - /work    Jacobs chapter + public NASA/older proof
 * - /kit     leave-behind: PDF + bio + links (primary recruiter packet)
 * - /now     booking; living status stays in the hero. Book CTAs land on #book
 * - /blog    writing
 * - /about   who I am: leadership, delivery, career, numbers, research (secondary)
 * - /delivery written bar for reviews, integration, and release
 * - /resume  HTML CV evidence (secondary; PDF from kit)
 * - /privacy contact, booking, analytics (footer credit strip)
 * - /lead    301 → /delivery (legacy)
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

// Person item for this Karl Hill. Do not sameAs enwiki "Karl Hill (musician)":
// that title redirects to the Government Issue article (the band). Membership
// is Person.memberOf → that MusicGroup, which sameAs the band's own page.
$sameAs[] = 'https://www.wikidata.org/wiki/Q139902938';
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
    'facts' => require __DIR__.'/site/facts.php',
    'hero' => require __DIR__.'/site/hero.php',
    'experience' => require __DIR__.'/site/experience.php',
    'projects' => require __DIR__.'/site/projects.php',
    'work' => require __DIR__.'/site/work.php',
    'research' => require __DIR__.'/site/research.php',
    'stack' => require __DIR__.'/site/stack.php',
    'skills' => require __DIR__.'/site/skills.php',
    'certifications' => require __DIR__.'/site/certifications.php',
    'education' => require __DIR__.'/site/education.php',
    'footer' => require __DIR__.'/site/footer.php',
    'about' => require __DIR__.'/site/about.php',
    'now' => require __DIR__.'/site/now.php',
    'privacy' => require __DIR__.'/site/privacy.php',
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

    // FrankenPHP-safe: middleware only flushes 103 when the SAPI supports it
    // (or EARLY_HINTS_FORCE=true). Link preload headers still emit either way.
    'early_hints' => filter_var(env('EARLY_HINTS', true), FILTER_VALIDATE_BOOLEAN),

    // Browser reports (/report) are also written to the log at this level so
    // they reach whatever sink LOG_STACK points at. Set to "none" to disable.
    'reporting_log_level' => env('REPORTING_LOG_LEVEL', 'warning'),

    // Integrity-Policy: report-only | enforce | auto (enforce when Vite SRI
    // hashes exist and retained integrity reports are clean).
    'integrity_policy' => env('INTEGRITY_POLICY', 'auto'),

    'features' => [
        'webmention' => filter_var(env('WEBMENTION_ENABLED', false), FILTER_VALIDATE_BOOLEAN),
        'reporting' => filter_var(env('REPORTING_ENABLED', false), FILTER_VALIDATE_BOOLEAN),
        'compression_dictionary' => filter_var(env('COMPRESSION_DICTIONARY', false), FILTER_VALIDATE_BOOLEAN),
        'content_credentials' => filter_var(env('CONTENT_CREDENTIALS', true), FILTER_VALIDATE_BOOLEAN),
        'webgpu' => filter_var(env('WEBGPU_FLOOD', true), FILTER_VALIDATE_BOOLEAN),
    ],

];
