<?php

namespace App\Support;

/**
 * Compact resume facts for the on-device Prompt API, independent of hidden UI.
 */
final class OnDeviceAsk
{
    /**
     * @param  array<string, mixed>  $person
     * @param  array<string, mixed>  $resume
     * @param  array<string, mixed>  $experience
     */
    public static function resumeBrief(array $person, array $resume, array $experience): string
    {
        $current = is_array($experience['current'] ?? null) ? $experience['current'] : [];
        $currentLine = collect([
            $current['title'] ?? $person['job_title'] ?? null,
            $current['company'] ?? $person['employer'] ?? null,
            $current['period'] ?? null,
        ])->filter(fn ($part): bool => is_string($part) && $part !== '')->implode(' · ');

        return self::join([
            self::identityLine($person),
            self::prefixed('Open to', $person['availability'] ?? null),
            self::prefixed('Tagline', $resume['tagline'] ?? $person['tagline'] ?? null),
            $currentLine !== '' ? 'Current role: '.$currentLine : null,
            self::prefixed('Current work', $current['summary'] ?? null),
            self::prefixed('Research', config('site.research.identity')),
        ]);
    }

    /**
     * @param  array<string, mixed>  $person
     */
    private static function identityLine(array $person): string
    {
        $employer = $person['employer_display'] ?? $person['employer'] ?? null;
        $role = collect([
            $person['job_title'] ?? null,
            is_string($employer) && $employer !== '' ? 'at '.$employer : null,
        ])->filter(fn ($part): bool => is_string($part) && $part !== '')->implode(' ');

        return collect([
            $person['name'] ?? 'Karl Hill',
            $role !== '' ? $role : null,
            $person['location'] ?? null,
        ])->filter(fn ($part): bool => is_string($part) && $part !== '')->implode(' — ');
    }

    private static function prefixed(string $label, mixed $value): ?string
    {
        if (! is_string($value) || $value === '') {
            return null;
        }

        return $label.': '.$value;
    }

    /**
     * @param  list<string|null>  $parts
     */
    private static function join(array $parts): string
    {
        return collect($parts)
            ->filter(fn ($part): bool => is_string($part) && $part !== '')
            ->implode("\n\n");
    }
}
