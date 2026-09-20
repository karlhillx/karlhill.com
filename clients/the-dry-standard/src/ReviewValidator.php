<?php

namespace DryStandard;

final class ReviewValidator
{
    /**
     * @return array<int, string>
     */
    public function errors(Review $review, SiteConfig $config, bool $forPublish = false): array
    {
        $errors = [];

        if ($review->title === '') {
            $errors[] = 'title is required';
        }

        if ($review->slug === '' || ! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $review->slug)) {
            $errors[] = 'slug must be lowercase kebab-case without dates';
        }

        if (preg_match('/\d{4}/', $review->slug) === 1) {
            $errors[] = 'slug must not include dates';
        }

        if ($review->brand === '' || $review->product === '') {
            $errors[] = 'brand and product are required';
        }

        if (! in_array($review->category, $config->categories(), true)) {
            $errors[] = 'category must be one of: '.implode(', ', $config->categories());
        }

        if (! array_key_exists($review->productionType, Review::PRODUCTION_TYPES)) {
            $errors[] = 'production_type must be dealcoholized, alternative, naturally-low-alcohol, hybrid, or not-verified';
        }

        if (! in_array($review->verified, ['yes', 'no'], true)) {
            $errors[] = 'verified must be yes or no';
        }

        if (! Registry::isAllowedStatus($review->status)) {
            $errors[] = 'status is not a recognized review state';
        }

        if ($review->sources === []) {
            $errors[] = 'at least one source with a title and URL is required';
        }

        foreach ($review->sources as $index => $source) {
            if (! filter_var($source['url'], FILTER_VALIDATE_URL)) {
                $errors[] = 'source '.($index + 1).' has an invalid URL';
            }
        }

        $claims = $review->sourcedClaims();

        foreach (Review::FACT_FIELDS as $field) {
            $value = $review->fact($field);

            if ($value === null || $value === '' || ($field === 'abv' && $value === 'Not published')) {
                continue;
            }

            $aliases = $this->claimAliases($field);

            if (array_intersect($aliases, $claims) === []) {
                $errors[] = "factual field [{$field}] is set but no source claims it; omit the field or cite a source";
            }
        }

        if ($review->productionType !== 'not-verified') {
            if (array_intersect(['production_type', 'dealcoholized', 'method'], $claims) === []) {
                $errors[] = 'a classified production type requires a source claiming production_type, dealcoholized, or method';
            }
        }

        if ($review->abv !== null && $review->abv !== 'Not published' && ! in_array('abv', $claims, true)) {
            $errors[] = 'ABV is set but no source claims abv';
        }

        if ($review->abvNumeric !== null && $review->abvNumeric > 0.5) {
            $errors[] = 'abv_numeric exceeds the 0.5% editorial ceiling; do not publish over-limit products';
        }

        if ($review->rating !== null && ($review->rating < 0 || $review->rating > 100)) {
            $errors[] = 'rating must be between 0 and 100';
        }

        if ($review->acquisition !== null && ! array_key_exists($review->acquisition, Review::ACQUISITIONS)) {
            $errors[] = 'acquisition must be purchased, manufacturer-sample, distributor-sample, or unknown';
        }

        if (! in_array($review->sponsored, ['yes', 'no'], true)) {
            $errors[] = 'sponsored must be yes or no';
        }

        if (! in_array($review->affiliateRelationship, ['none', 'present'], true)) {
            $errors[] = 'affiliate_relationship must be none or present';
        }

        if (! in_array($review->advertisingRelationship, ['none', 'present'], true)) {
            $errors[] = 'advertising_relationship must be none or present';
        }

        if (! in_array($review->commercialRelationship, Review::COMMERCIAL_RELATIONSHIPS, true)) {
            $errors[] = 'commercial_relationship must be none, brand, retailer, distributor, or other';
        }

        foreach ($review->identifiersRecord() as $identifier) {
            if (! in_array($identifier['type'], Review::IDENTIFIER_TYPES, true)) {
                $errors[] = 'identifier type is not recognized: '.$identifier['type'];
            }
            if (in_array($identifier['type'], ['gtin', 'ean', 'upc'], true)) {
                $digits = preg_replace('/\D+/', '', $identifier['value']) ?: '';
                if (! in_array(strlen($digits), [8, 12, 13, 14], true)) {
                    $errors[] = 'identifier '.$identifier['type'].' must be 8, 12, 13, or 14 digits';
                }
            }
        }

        foreach ($review->provenanceRecord() as $field => $entry) {
            $kind = is_array($entry) ? (string) ($entry['kind'] ?? '') : '';
            if ($kind !== '' && ! in_array($kind, Review::PROVENANCE_KINDS, true)) {
                $errors[] = "provenance.{$field} has an unrecognized source kind";
            }
            $confidence = is_array($entry) ? (string) ($entry['confidence'] ?? '') : '';
            if ($confidence !== '' && ! Sensory::isAllowedConfidence($confidence)) {
                $errors[] = "provenance.{$field} has an unrecognized confidence";
            }
        }

        foreach ($review->resolvedSensory() as $index => $row) {
            $id = (string) ($row['descriptor'] ?? '');
            if ($id !== '' && ! Sensory::hasDescriptor($id)) {
                $errors[] = 'sensory '.($index + 1).' uses an unknown descriptor: '.$id;
            }
        }

        foreach ($review->tastes as $taste) {
            if (Sensory::isNoiseTaste($taste)) {
                $errors[] = 'tastes contains prose or noise rather than a flavor chip: '.$taste;
            }
        }

        foreach ($review->purchaseLinks as $index => $link) {
            $relationship = $link['relationship'] ?? 'citation';
            if (! in_array($relationship, Review::OFFER_RELATIONSHIPS, true)) {
                $errors[] = 'purchase_links '.($index + 1).' has an unrecognized relationship';
            }
            $affiliate = $link['affiliate_url'] ?? '';
            if ($affiliate !== '' && ! filter_var($affiliate, FILTER_VALIDATE_URL)) {
                $errors[] = 'purchase_links '.($index + 1).' has an invalid affiliate URL';
            }
        }

        if ($review->image !== null) {
            $imagePath = Paths::default()->path($review->image);

            if (! is_file($imagePath)) {
                $errors[] = 'image file is missing: '.$review->image;
            }
        }

        if ($forPublish) {
            if ($review->summary === '' || $review->verdict === '') {
                $errors[] = 'published reviews require summary and verdict';
            }

            if ($review->nose === null || $review->palate === null || $review->finish === null) {
                $errors[] = 'published reviews require nose, palate, and finish tasting notes';
            }

            $publishable = ['published', 'validated', 'scheduled', 'approved'];
            if (! in_array($review->status, $publishable, true)
                && ! in_array(Registry::normalizeStatus($review->status), ['published', 'validated', 'scheduled'], true)) {
                $errors[] = 'only validated, approved, scheduled, or published reviews may be released';
            }

            if ($review->likeness !== null && $review->verdict !== '' && $review->likeness === $review->verdict) {
                $errors[] = 'likeness must not be a copy of verdict; write a likeness note or leave likeness empty';
            }

            if ($review->mouthfeel !== null && $review->palate !== null && $review->mouthfeel === $review->palate) {
                $errors[] = 'mouthfeel must not be a copy of palate';
            }
        }

        return $errors;
    }

    /**
     * Soft warnings — do not block publish by default.
     *
     * @return array<int, string>
     */
    public function warnings(Review $review): array
    {
        $warnings = [];

        if ($review->productionType === 'alternative' && $review->dealcoholizationMethod) {
            $warnings[] = 'classification warning: production_type is alternative but dealcoholization_method is set';
        }

        if ($review->productionType === 'dealcoholized'
            && ($review->dealcoholizationMethod === null || trim($review->dealcoholizationMethod) === '')) {
            $warnings[] = 'classification warning: dealcoholized without a named method (method facet unpublished)';
        }

        if ($review->abv === '0.0%' && $review->abvNumeric !== null && $review->abvNumeric > 0.05) {
            $warnings[] = 'ABV warning: label is 0.0% but abv_numeric is '.$review->abvNumeric;
        }

        if ($review->abvNumeric !== null && $review->abvNumeric > 0.0 && $review->abv === '0.0%') {
            $warnings[] = 'ABV warning: non-zero abv_numeric with a 0.0% display label';
        }

        if (! Registry::isCanonicalAbvLabel($review->abv)) {
            $warnings[] = 'ABV warning: non-canonical display label "'.$review->abv.'"; prefer 0.0%, <0.5%, or Not published';
        }

        if ($review->highlight !== null && $review->verdict !== '' && trim($review->highlight) === trim($review->verdict)) {
            $warnings[] = 'editorial warning: highlight duplicates verdict';
        }

        foreach (Sensory::editorialLengthBands() as $field => $band) {
            $text = match ($field) {
                'verdict' => $review->verdict,
                'nose' => (string) ($review->nose ?? ''),
                'palate' => (string) ($review->palate ?? ''),
                'finish' => (string) ($review->finish ?? ''),
                'summary' => $review->summary,
                default => '',
            };
            if ($text === '') {
                continue;
            }
            $len = mb_strlen($text);
            if ($len < $band['min']) {
                $warnings[] = "editorial length: {$field} is {$len} characters (recommended {$band['min']}–{$band['max']})";
            } elseif ($len > $band['max']) {
                $warnings[] = "editorial length: {$field} is {$len} characters (recommended {$band['min']}–{$band['max']})";
            }
        }

        $essay = trim($review->bodyMarkdown);
        if ($essay !== '') {
            $total = mb_strlen($review->verdict)
                + mb_strlen((string) $review->nose)
                + mb_strlen((string) $review->palate)
                + mb_strlen((string) $review->finish)
                + mb_strlen($essay);
            if ($total < 2500) {
                $warnings[] = "editorial length: review body total is {$total} characters (recommended 2500–3500 excluding metadata)";
            } elseif ($total > 4500) {
                $warnings[] = "editorial length: review body total is {$total} characters (recommended 2500–3500 excluding metadata)";
            }
        }

        return $warnings;
    }

    /**
     * Publish gate: sourced facts plus a confirmed, non-retailer still.
     *
     * @param  array<string, string>  $hashesBySlug
     * @return array<int, string>
     */
    public function errorsForPublish(Review $review, SiteConfig $config, array $hashesBySlug = []): array
    {
        return array_merge(
            $this->errors($review, $config, forPublish: true),
            (new StillAudit)->inspect($review, $hashesBySlug, forPublish: true)['errors'],
        );
    }

    public function passes(Review $review, SiteConfig $config, bool $forPublish = false): bool
    {
        return $this->errors($review, $config, $forPublish) === [];
    }

    /**
     * @return array<int, string>
     */
    private function claimAliases(string $field): array
    {
        return match ($field) {
            'dealcoholization_method' => ['method', 'dealcoholization_method'],
            'production_type' => ['production_type', 'dealcoholized', 'method'],
            'country', 'region', 'origin' => ['origin', 'country', 'region'],
            'availability' => ['availability', 'price'],
            default => [$field],
        };
    }
}
