<?php

namespace DryStandard;

/**
 * Partnered retailers that may be linked from availability prose.
 * Canonical registry for paid / trial retail collaborations.
 * Outbound URLs use Referral::append() for the shared site slug.
 */
final class RetailPartners
{
    /**
     * @return array<string, array{url: string, relationship: string, aliases?: list<string>}>
     */
    public static function all(): array
    {
        return [
            'Metro Wine & Spirits' => [
                'url' => 'https://www.metrowinedc.com/',
                'relationship' => 'citation',
            ],
            'Brightwood Pizza & Bottle' => [
                'url' => 'https://store.anxodc.com/',
                'relationship' => 'citation',
                'aliases' => [
                    'Brightwood Pizza',
                    'ANXO Cider',
                    'ANXO',
                ],
            ],
        ];
    }

    /**
     * @return list<string>
     */
    public static function names(): array
    {
        $names = [];
        foreach (self::all() as $primary => $partner) {
            $names[] = $primary;
            foreach ($partner['aliases'] ?? [] as $alias) {
                $names[] = $alias;
            }
        }

        usort($names, fn (string $a, string $b): int => strlen($b) <=> strlen($a));

        return $names;
    }

    public static function href(string $name): ?string
    {
        foreach (self::all() as $primary => $partner) {
            $labels = array_merge([$primary], $partner['aliases'] ?? []);
            if (! in_array($name, $labels, true)) {
                continue;
            }

            return Referral::append((string) $partner['url']);
        }

        return null;
    }

    /**
     * Escape availability prose and wrap partnered retailer names in tracked links.
     */
    public static function linkifyAvailability(string $availability): string
    {
        $escaped = Str::e($availability);
        $tokens = [];

        foreach (self::names() as $index => $name) {
            $href = self::href($name);
            if ($href === null) {
                continue;
            }

            $needle = Str::e($name);
            if (! str_contains($escaped, $needle)) {
                continue;
            }

            $token = "\u{E000}".'RP'.$index."\u{E001}";
            $escaped = str_replace($needle, $token, $escaped);
            $tokens[$token] = '<a href="'.Str::e($href).'"'.Referral::externalAttributeHtml($href, standalone: false).' data-analytics-event="outbound_retail" data-retailer="'.Str::e($name).'">'.$needle.'</a>';
        }

        return str_replace(array_keys($tokens), array_values($tokens), $escaped);
    }
}
