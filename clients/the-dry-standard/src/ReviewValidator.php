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

        if (! in_array($review->status, $config->allowedStatuses(), true)) {
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

            if ($review->status !== 'published' && $review->status !== 'validated' && $review->status !== 'scheduled') {
                $errors[] = 'only validated, scheduled, or published reviews may be released';
            }
        }

        return $errors;
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
