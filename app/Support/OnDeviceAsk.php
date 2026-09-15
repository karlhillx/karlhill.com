<?php

namespace App\Support;

/**
 * Compact hire facts for the on-device Prompt API. Print-only kit sections
 * never reach innerText, and the ask widget used to scrape itself — this
 * brief is the fallback the model can always see.
 */
final class OnDeviceAsk
{
    /**
     * @param  array<string, mixed>  $person
     * @param  array<string, mixed>  $kit
     */
    public static function kitBrief(array $person, array $kit): string
    {
        $scope = collect($kit['scope'] ?? [])
            ->filter(fn ($row): bool => is_array($row) && filled($row['label'] ?? null) && filled($row['body'] ?? null))
            ->map(fn (array $row): string => $row['label'].': '.$row['body'])
            ->implode("\n");

        $evidence = collect($kit['evidence'] ?? [])
            ->pluck('label')
            ->filter(fn ($label): bool => is_string($label) && $label !== '')
            ->implode('; ');

        $glance = collect($kit['glance'] ?? [])
            ->filter(fn ($line): bool => is_string($line) && $line !== '')
            ->implode("\n");

        return self::join([
            self::identityLine($person),
            self::prefixed('Open to', $person['availability'] ?? null),
            self::prefixed('Direction', $person['availability_long'] ?? null),
            $glance !== '' ? "At a glance:\n{$glance}" : null,
            $scope !== '' ? "Current scope:\n{$scope}" : null,
            $evidence !== '' ? 'Selected evidence: '.$evidence : null,
        ]);
    }

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
