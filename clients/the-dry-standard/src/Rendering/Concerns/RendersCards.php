<?php

namespace DryStandard\Rendering\Concerns;

use DryStandard\Review;
use DryStandard\Str;
use Illuminate\Support\Collection;

trait RendersCards
{
    private function featuredReview(Review $review): string
    {
        $badge = $this->view->render('partials/production-badge', [
            'type' => $review->productionType,
            'label' => $review->productionTypeShortLabel(),
        ]);

        $score = '';
        if ($review->rating !== null) {
            $score = '<p class="featured-score" aria-label="Score '.$review->rating.' out of 100">'
                .'<span class="featured-score__value">'.$review->rating.'</span>'
                .'<span class="featured-score__scale">/100</span>'
                .'<span class="featured-score__caption">Score</span>'
                .'</p>';
        }

        $abv = $review->abv !== null && $review->abv !== ''
            ? '<span class="featured-abv">'.$this->e($review->abv).' ABV</span>'
            : '';

        return $this->view->render('partials/featured-review', [
            'href' => $this->config->publicUrl($review->path()),
            'figure' => $this->productFigure($review, 'product-figure product-figure--feature', eager: true),
            'presentation' => $review->imagePresentation(),
            'brand' => $this->e($review->brandDisplayName()),
            'title' => $review->cardTitle(),
            'summary' => $review->summary,
            'score' => $score,
            'badge' => $badge,
            'abv' => $abv,
        ]);
    }

    private function reviewCards(Collection $reviews, bool $compact = false, ?Review $relationBase = null): string
    {
        return $reviews->map(function (Review $review) use ($compact, $relationBase): string {
            $score = $review->rating !== null
                ? '<span class="card-score'.($review->isResearchScore() ? ' card-score--research' : '').'"'
                    .($review->isResearchScore() ? ' title="Research score"' : '')
                    .'>'.$review->rating
                    .($review->isResearchScore() ? '<small>R</small>' : '')
                    .'</span>'
                : '';
            $meta = $review->cardMetaLine($this->config->categoryLabel($review->category));
            $badge = $this->view->render('partials/production-badge', [
                'type' => $review->productionType,
                'label' => $review->productionTypeShortLabel(),
            ]);
            $attrs = implode(' ', [
                'data-production="'.Str::e($review->productionType).'"',
                'data-verified="'.Str::e($review->verified).'"',
                'data-category="'.Str::e($review->category).'"',
                'data-brand="'.Str::e($review->brandSlug()).'"',
                'data-abv="'.Str::e($review->abvBucket()).'"',
                'data-method="'.Str::e($review->methodFacetKey()).'"',
                'data-rating="'.Str::e((string) ($review->rating ?? 0)).'"',
                'data-date="'.Str::e($review->reviewDate->toDateString()).'"',
                'data-search="'.Str::e($review->searchText()).'"',
                'data-save-slug="'.Str::e($review->slug).'"',
            ]);
            $thumb = $this->productFigure($review, 'product-figure product-figure--thumb');
            $brand = '<a href="'.$this->url('brands/'.$review->brandSlug().'/').'">'.$this->e($review->brandDisplayName()).'</a>';
            $descriptors = array_slice($review->flavorProfileLabels(), 0, 3);
            $relation = $relationBase instanceof Review ? $relationBase->relationTo($review) : null;

            return $this->view->render('partials/review-card', [
                'compact' => $compact,
                'attrs' => $attrs,
                'thumb' => $thumb,
                'brand' => $brand,
                'href' => $this->config->publicUrl($review->path()),
                'title' => $review->cardTitle(),
                'summary' => $review->summary,
                'meta' => $meta,
                'badge' => $badge,
                'score' => $score,
                'compareSlug' => $review->slug,
                'descriptors' => $descriptors,
                'price' => $review->price,
                'relation' => $relation,
                'saveSlug' => $review->slug,
            ]);
        })->implode('');
    }
}
