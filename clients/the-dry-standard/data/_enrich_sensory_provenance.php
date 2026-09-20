<?php

/**
 * One-shot enricher: provenance, identifiers, sensory, structure_scales.
 * Run: php clients/the-dry-standard/data/_enrich_sensory_provenance.php
 */

declare(strict_types=1);

use DryStandard\Paths;
use DryStandard\Registry;
use DryStandard\Review;
use DryStandard\ReviewRepository;
use DryStandard\Sensory;
use Symfony\Component\Yaml\Yaml;

require dirname(__DIR__, 3).'/vendor/autoload.php';

$paths = Paths::default();
$repo = new ReviewRepository($paths);
$changed = 0;

foreach ($repo->fromDisk() as $review) {
    $path = $review->sourcePath;
    if (! is_file($path)) {
        continue;
    }

    $raw = file_get_contents($path);
    if ($raw === false || ! str_starts_with($raw, '---')) {
        continue;
    }

    $parts = explode('---', $raw, 3);
    if (count($parts) < 3) {
        continue;
    }

    try {
        $matter = Yaml::parse($parts[1]);
    } catch (\Throwable $e) {
        echo 'skip '.$review->slug.': '.$e->getMessage().PHP_EOL;
        continue;
    }
    if (! is_array($matter)) {
        continue;
    }

    $original = $matter;
    $matter = enrichMatter($matter, $review);

    if ($matter === $original) {
        continue;
    }

    try {
        $yaml = Yaml::dump($matter, 6, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK | Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE);
        // Round-trip check before writing — refuse dumps that won't re-parse.
        Yaml::parse($yaml);
    } catch (\Throwable $e) {
        echo 'skip write '.$review->slug.': '.$e->getMessage().PHP_EOL;
        continue;
    }

    $body = $parts[2];
    if (! str_starts_with($body, "\n")) {
        $body = "\n".$body;
    }
    file_put_contents($path, "---\n".$yaml."---".$body);
    $changed++;
    echo 'updated '.$review->slug.PHP_EOL;
}

echo "done: {$changed} files".PHP_EOL;

/**
 * @param  array<string, mixed>  $matter
 * @return array<string, mixed>
 */
function enrichMatter(array $matter, Review $review): array
{
    $id = trim((string) ($matter['id'] ?? $matter['product_id'] ?? ''));
    if ($id !== '' && empty($matter['product_id'])) {
        $matter['product_id'] = $id;
    }

    $identifiers = [];
    $seen = [];
    if (isset($matter['identifiers']) && is_array($matter['identifiers'])) {
        foreach ($matter['identifiers'] as $row) {
            if (! is_array($row)) {
                continue;
            }
            $type = strtolower(trim((string) ($row['type'] ?? '')));
            $value = trim((string) ($row['value'] ?? ''));
            if ($type === '' || $value === '' || isset($seen[$type.':'.$value])) {
                continue;
            }
            $seen[$type.':'.$value] = true;
            $identifiers[] = $row;
        }
    }

    $ean = preg_replace('/\D+/', '', (string) ($matter['ean'] ?? '')) ?: '';
    if ($ean !== '' && ! isset($seen['ean:'.$ean]) && ! isset($seen['gtin:'.$ean])) {
        $identifiers[] = [
            'type' => 'ean',
            'value' => $ean,
            'source' => 'packaging or producer listing',
        ];
        $seen['ean:'.$ean] = true;
    }
    if ($id !== '' && ! isset($seen['tds:'.$id])) {
        $identifiers[] = ['type' => 'tds', 'value' => $id];
    }
    if ($identifiers !== []) {
        $matter['identifiers'] = $identifiers;
    }

    $tastes = [];
    foreach ($matter['tastes'] ?? [] as $taste) {
        if (! is_string($taste) || Sensory::isNoiseTaste($taste)) {
            continue;
        }
        $tastes[] = $taste;
    }
    $matter['tastes'] = $tastes;

    $sensory = Sensory::normalizeSensoryList($matter['sensory'] ?? []);
    $fromTastes = Sensory::normalizeSensoryList($tastes);
    $seen = [];
    foreach ($sensory as $row) {
        $seen[$row['descriptor']] = true;
    }
    foreach ($fromTastes as $row) {
        if (! isset($seen[$row['descriptor']])) {
            $sensory[] = $row;
            $seen[$row['descriptor']] = true;
        }
    }
    if ($sensory !== []) {
        $matter['sensory'] = $sensory;
    }

    $abv = Registry::normalizeAbv(
        isset($matter['abv']) ? (string) $matter['abv'] : null,
        isset($matter['abv_numeric']) && is_numeric($matter['abv_numeric']) ? (float) $matter['abv_numeric'] : null,
    );
    $matter['abv'] = $abv['label'];
    if ($abv['numeric'] !== null) {
        $matter['abv_numeric'] = $abv['numeric'];
    }

    $profile = [];
    foreach ($matter['profile'] ?? [] as $chip) {
        if (is_string($chip) && trim($chip) !== '') {
            $profile[] = $chip;
        }
    }
    $matter['profile'] = $profile;

    $scales = Sensory::normalizeStructureScales($matter['structure_scales'] ?? [], $profile);
    if ($scales !== []) {
        $matter['structure_scales'] = $scales;
    }

    $verdict = trim((string) ($matter['verdict'] ?? ''));
    $likeness = trim((string) ($matter['likeness'] ?? ''));
    if ($likeness !== '' && $verdict !== '' && $likeness === $verdict) {
        unset($matter['likeness']);
    }
    $highlight = trim((string) ($matter['highlight'] ?? ''));
    if ($highlight !== '' && $verdict !== '' && $highlight === $verdict) {
        unset($matter['highlight']);
    }
    $mouthfeel = trim((string) ($matter['mouthfeel'] ?? ''));
    $palate = trim((string) ($matter['palate'] ?? ''));
    if ($mouthfeel !== '' && $palate !== '' && $mouthfeel === $palate) {
        unset($matter['mouthfeel']);
    }

    $provenance = is_array($matter['provenance'] ?? null) ? $matter['provenance'] : [];
    foreach ($matter['sources'] ?? [] as $source) {
        if (! is_array($source)) {
            continue;
        }
        $url = trim((string) ($source['url'] ?? ''));
        $title = trim((string) ($source['title'] ?? ''));
        $kind = inferKind($url, $title);
        $confidence = in_array($kind, ['manufacturer', 'label'], true)
            ? 'manufacturer_verified'
            : 'secondary';
        foreach ($source['claims'] ?? [] as $claim) {
            $field = claimField((string) $claim);
            if ($field === null || isset($provenance[$field])) {
                continue;
            }
            $entry = ['kind' => $kind, 'confidence' => $confidence];
            if ($url !== '') {
                $entry['url'] = $url;
            }
            $provenance[$field] = $entry;
        }
    }
    if ($ean !== '' && ! isset($provenance['ean'])) {
        $provenance['ean'] = [
            'kind' => 'label',
            'confidence' => 'label_verified',
            'note' => 'Barcode recorded from packaging or producer listing',
        ];
    }
    if (strtolower((string) ($matter['verified'] ?? '')) === 'yes' && ! isset($provenance['production_type'])) {
        $provenance['production_type'] = [
            'kind' => 'manufacturer',
            'confidence' => 'verified',
            'note' => 'production_type marked verified in frontmatter',
        ];
    }
    if ($provenance !== []) {
        $matter['provenance'] = $provenance;
    }

    return $matter;
}

function claimField(string $claim): ?string
{
    return match (strtolower(trim($claim))) {
        'abv' => 'abv',
        'method', 'dealcoholization_method' => 'dealcoholization_method',
        'dealcoholized', 'production_type' => 'production_type',
        'origin', 'country' => 'country',
        'region' => 'region',
        'producer' => 'producer',
        'ingredients' => 'ingredients',
        'calories' => 'calories',
        'sugar' => 'sugar',
        'price' => 'price',
        'availability' => 'availability',
        'volume' => 'volume',
        'base_beverage' => 'base_beverage',
        default => null,
    };
}

function inferKind(string $url, string $title): string
{
    $hay = strtolower($url.' '.$title);
    if (str_contains($hay, 'label') || str_contains($hay, 'nutrition')) {
        return 'label';
    }
    if (preg_match('/\b(gov|fda|usda|efsa|ttb)\b/', $hay) === 1) {
        return 'government';
    }
    if (str_contains($hay, 'press') || str_contains($hay, 'prweb') || str_contains($hay, 'newsroom')) {
        return 'press';
    }
    if (preg_match('/\b(total wine|wine\.com|amazon|instacart|wegmans|retail|shop|store|cellar)\b/', $hay) === 1) {
        return 'retailer';
    }
    if (preg_match('/\b(importer|distributor|wholesale)\b/', $hay) === 1) {
        return 'distributor';
    }
    if (preg_match('/\b(weingut|winery|brewery|distill|producer|official|\/products)\b/', $hay) === 1
        || str_contains($hay, 'guinness.com')
        || str_contains($hay, 'leitz-wein')
        || str_contains($hay, 'giesen')
        || str_contains($hay, 'lyres.com')) {
        return 'manufacturer';
    }

    return 'unknown';
}
