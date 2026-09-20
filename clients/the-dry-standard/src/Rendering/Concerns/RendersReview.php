<?php

namespace DryStandard\Rendering\Concerns;

use DryStandard\Markdown;
use DryStandard\Rendering\StructuredData;
use DryStandard\Review;
use DryStandard\Sensory;
use DryStandard\Str;
use Illuminate\Support\Collection;

trait RendersReview
{
    public function review(Review $review, array $crumbs, ?Collection $relatedReviews = null): string
    {
        $essayMarkdown = $this->essayBodyMarkdown($review);
        $bodyHtml = Markdown::toHtml($essayMarkdown);
        $hasGlance = $review->hasGlancePanel();
        $hasServe = ($review->serve !== null && $review->serve !== '')
            || (! $hasGlance && $review->bestFor !== null && $review->bestFor !== '');
        $related = $relatedReviews?->isNotEmpty()
            ? $this->reviewCards($relatedReviews, compact: true, relationBase: $review)
            : '';
        $relatedHeading = 'More from the cellar';
        $relatedHref = $this->config->publicUrl('reviews/');
        $relatedLinkLabel = 'All reviews';
        $compareSlugs = [$review->slug];
        if ($relatedReviews?->isNotEmpty()) {
            $sameStyle = $review->hasComparableStyle()
                && $relatedReviews->every(fn (Review $other): bool => $other->styleSlug() === $review->styleSlug());
            if ($sameStyle) {
                $relatedHeading = 'Other '.$review->styleLabel();
                $relatedHref = $this->config->publicUrl('styles/'.$review->styleSlug().'/');
                $relatedLinkLabel = 'All '.$review->styleLabel();
                foreach ($relatedReviews->take(3) as $other) {
                    $compareSlugs[] = $other->slug;
                }
            } elseif ($relatedReviews->contains(fn (Review $other): bool => $other->brandSlug() === $review->brandSlug())) {
                $relatedHeading = 'More from '.$review->brandDisplayName();
                $relatedHref = $this->config->publicUrl('brands/'.$review->brandSlug().'/');
                $relatedLinkLabel = $review->brandDisplayName();
            }
        }
        $compareSlugs = array_values(array_unique(array_slice($compareSlugs, 0, 4)));
        $compareHref = $this->config->publicUrl('compare/').'?slugs='.rawurlencode(implode(',', $compareSlugs));

        $badge = $this->view->render('partials/production-badge', [
            'type' => $review->productionType,
            'label' => $review->productionTypeShortLabel(),
        ]);

        $methodologyUrl = $this->config->publicUrl('methodology/');
        $body = $this->view->render('review', [
            'title' => $review->title,
            'summary' => $review->summary,
            'categoryLabel' => $this->config->categoryLabel($review->category),
            'breadcrumbs' => $this->breadcrumbs($crumbs),
            'figure' => $this->productFigure($review, 'product-figure product-figure--hero', hero: true),
            'metaLine' => $this->reviewMetaLine($review),
            'byline' => 'Reviewed by '.$this->e($this->config->editorName()).', '.strtolower($this->config->editorRole()),
            'identity' => $this->view->render('partials/identity', [
                'factsPeek' => $this->factsPeek($review),
                'badge' => $badge,
                'pageUrl' => $this->config->publicUrl($review->path()),
            ]),
            'score' => $this->view->render('partials/score-badge', [
                'rating' => $review->rating,
                'band' => null,
                'scoreKind' => $review->scoreKindLabel(),
                'methodologyUrl' => $methodologyUrl,
            ]),
            'statusLabel' => $review->productionTypeLabel(),
            'verifiedLabel' => $review->verifiedLabel(),
            'disclosureStanceLabel' => $review->disclosureLabel(),
            'methodBlock' => $this->methodBlock($review),
            'discrepancies' => $this->discrepancies($review),
            'overview' => $bodyHtml !== ''
                ? '<section class="prose"><h2>'.$this->e($review->essayHeading()).'</h2>'.$bodyHtml.'</section>'
                : '',
            'tasting' => $this->tasting($review),
            'provenance' => $this->provenancePanel($review),
            'hasServe' => $hasServe,
            'serveBlock' => $this->optionalBlock($review->serve),
            'bestForBlock' => $hasGlance ? '' : $this->optionalBlock($review->bestFor, 'Best for: '),
            'verdict' => $review->verdict,
            'sources' => $this->sources($review),
            'facts' => $this->facts($review),
            'links' => $this->purchaseLinks($review),
            'related' => $related,
            'relatedHeading' => $relatedHeading,
            'relatedHref' => $relatedHref,
            'relatedLinkLabel' => $relatedLinkLabel,
            'reviewsUrl' => $this->config->publicUrl('reviews/'),
            'pageUrl' => $this->config->publicUrl($review->path()),
            'compareHref' => $compareHref,
            'disclosure' => $this->disclosure($review),
            'industryUrl' => $this->config->publicUrl('industry/'),
            'methodologyUrl' => $methodologyUrl,
        ]);

        return $this->document(
            $review->title,
            $review->summary,
            $review->path(),
            $body,
            [
                'nav' => 'reviews',
                'og_type' => 'article',
                'image' => $review->imageSrc() ?? '',
                'json_ld' => $this->jsonLd([
                    $this->websiteGraph(),
                    (new StructuredData($this->config))->organization(),
                    $this->breadcrumbGraph($crumbs),
                    $this->articleGraph($review),
                    $this->productGraph($review),
                ]),
            ],
        );
    }

    /**
     * Essay markdown without a leading category H2 — the review template prints essayHeading().
     */
    private function essayBodyMarkdown(Review $review): string
    {
        $body = trim($review->bodyMarkdown);
        $heading = preg_quote($review->essayHeading(), '/');
        $body = preg_replace('/^##\s+'.$heading.'\s*$/mi', '', $body) ?? $body;

        return trim($body);
    }

    /**
     * @param  Collection<int, Review>  $reviews
     * @param  Collection<int, array{slug: string, name: string, reviews: Collection<int, Review>}>  $brands
     * @param  Collection<int, array{slug: string, label: string, reviews: Collection<int, Review>}>  $styles
     */
    private function reviewMetaLine(Review $review): string
    {
        $parts = [
            '<a href="'.$this->url('brands/'.$review->brandSlug().'/').'">'.$this->e($review->brandDisplayName()).'</a>',
            '<a href="'.$this->url('reviews/'.$review->category.'/').'">'.$this->e($this->config->categoryLabel($review->category)).'</a>',
        ];

        if ($review->hasComparableStyle()) {
            $parts[] = '<a href="'.$this->url('styles/'.$review->styleSlug().'/').'">'.$this->e($review->styleLabel()).'</a>';
        }

        $methodKey = $review->methodKey();
        if ($methodKey !== null && in_array($methodKey, Review::METHOD_FACETS, true)) {
            $parts[] = '<a href="'.$this->url('methods/'.$methodKey.'/').'">'.$this->e($review->methodCardLabel()).'</a>';
        } elseif ($review->dealcoholizationMethod) {
            $parts[] = $this->e($review->methodCardLabel());
        }

        return '<p class="review-meta review-taxonomy">'.implode('<span class="review-meta-sep" aria-hidden="true"> · </span>', $parts).'</p>';
    }

    private function methodBlock(Review $review): string
    {
        if ($review->dealcoholizationMethod === null || $review->dealcoholizationMethod === '') {
            return '';
        }

        $methodKey = $review->methodKey();
        if ($methodKey !== null && in_array($methodKey, Review::METHOD_FACETS, true)) {
            return '<p>Method: <a href="'.$this->url('methods/'.$methodKey.'/').'">'.$this->e($review->dealcoholizationMethod).'</a></p>';
        }

        return $this->optionalBlock($review->dealcoholizationMethod, 'Method: ');
    }

    private function facts(Review $review): string
    {
        $rows = [
            'ABV' => $review->abv,
            'Origin' => $review->originLabel(),
            'Category' => $this->config->categoryLabel($review->category).($review->subcategory ? ' / '.$review->subcategory : ''),
            'Style' => $review->style,
            'Producer' => $review->producer,
            'Base beverage' => $review->baseBeverage,
            'Bottle / can' => $review->volume,
            'Typical price' => $review->price,
            'Ingredients' => $review->ingredients,
            'Calories' => $review->calories,
            'Sugar' => $review->sugar,
        ];

        $html = '';
        foreach ($rows as $label => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            $html .= '<div><dt>'.Str::e($label).'</dt><dd>'.Str::e($value).'</dd></div>';
        }

        return $this->view->render('partials/facts', ['rows' => $html]);
    }

    /**
     * Consumer scan strip, ranked by importance:
     * ABV → Method → Disclosure → Style → Origin.
     *
     * @return array<int, array{key: string, label: string, value: string, href?: string, pill?: bool, tone?: string}>
     */
    private function factsPeek(Review $review): array
    {
        $peek = [];

        if ($review->abv !== null && $review->abv !== '') {
            $peek[] = [
                'key' => 'abv',
                'label' => 'ABV',
                'value' => $review->abv,
            ];
        }

        $peek[] = $this->methodPeekItem($review);

        $peek[] = [
            'key' => 'disclosure',
            'label' => 'Disclosure',
            'value' => $review->disclosureLabel(),
            'pill' => true,
            'tone' => match ($review->disclosureStance()) {
                'documented' => 'documented',
                'withheld' => 'withheld',
                default => 'undeclared',
            },
        ];

        if ($review->hasComparableStyle()) {
            $peek[] = [
                'key' => 'style',
                'label' => 'Style',
                'value' => $review->styleLabel(),
                'href' => $this->config->publicUrl('styles/'.$review->styleSlug().'/'),
            ];
        } elseif ($review->style) {
            $peek[] = [
                'key' => 'style',
                'label' => 'Style',
                'value' => $review->style,
            ];
        }

        $country = $review->countryLabel();
        if ($country !== null && $country !== '') {
            $peek[] = [
                'key' => 'origin',
                'label' => 'Origin',
                'value' => $country,
                'href' => $this->config->publicUrl('reviews/').'?country='.$review->countrySlug(),
            ];
        }

        return $peek;
    }

    /**
     * @return array{key: string, label: string, value: string, href?: string, pill?: bool, tone?: string}
     */
    private function methodPeekItem(Review $review): array
    {
        $facet = $review->methodFacetKey();
        $tone = $review->productionType === 'not-verified' ? 'not-verified' : $review->productionType;

        if ($facet === 'not-applicable') {
            return [
                'key' => 'method',
                'label' => 'Method',
                'value' => 'Formulated',
                'pill' => true,
                'tone' => $tone,
            ];
        }

        $methodKey = $review->methodKey();
        if ($methodKey !== null && in_array($methodKey, Review::METHOD_FACETS, true)
            && ! in_array($facet, ['unpublished', 'unknown'], true)) {
            return [
                'key' => 'method',
                'label' => 'Method',
                'value' => $this->peekMethodLabel($review),
                'href' => $this->config->publicUrl('methods/'.$methodKey.'/'),
                'pill' => true,
                'tone' => $tone,
            ];
        }

        return [
            'key' => 'method',
            'label' => 'Method',
            'value' => $review->productionTypeShortLabel(),
            'pill' => true,
            'tone' => $tone,
        ];
    }

    private function peekMethodLabel(Review $review): string
    {
        return match ($review->methodFacetKey()) {
            'membrane-filtration' => 'Cold filtration',
            'vacuum-distillation' => 'Vacuum distillation',
            'reverse-osmosis' => 'Reverse osmosis',
            'spinning-cone' => 'Spinning cone',
            'osmotic-distillation' => 'Osmotic distillation',
            'arrested-fermentation' => 'Arrested fermentation',
            'not-applicable' => 'Formulated',
            'other' => 'Other method',
            default => $review->methodCardLabel(),
        };
    }

    private function tasting(Review $review): string
    {
        $parts = [
            'Nose' => $review->nose,
            'Palate' => $review->palate,
            'Finish' => $review->finish,
        ];

        $notes = [];
        foreach ($parts as $label => $value) {
            if ($value === null) {
                continue;
            }

            $notes[] = [
                'label' => $label,
                'text' => $value,
            ];
        }

        $hasGlance = $review->hasGlancePanel();
        if (! $hasGlance && $notes === []) {
            return '';
        }

        return $this->view->render('partials/tasting', [
            'heading' => $hasGlance ? 'At a glance' : 'Tasting notes',
            'showGlance' => $hasGlance,
            'tastes' => $review->flavorProfileLabels(),
            'profile' => $review->structureProfileLabels(),
            'mouthfeel' => $review->mouthfeel,
            'assessments' => $this->assessmentChips($review),
            'highlight' => $review->distinctHighlight(),
            'likeness' => $review->likenessText(),
            'likenessHeading' => $review->likenessHeading(),
            'perfectFor' => $hasGlance ? $review->bestFor : null,
            'drinkIfYouLike' => $review->drinkIfYouLike,
            'productionLine' => null,
            'notes' => $notes,
            'detailTitle' => $hasGlance && $notes !== [] ? 'Tasting notes' : null,
        ]);
    }

    /**
     * @return array<int, string>
     */
    private function assessmentChips(Review $review): array
    {
        $chips = [];
        foreach ($review->assessments as $dimension => $level) {
            $label = Sensory::assessmentLabel($dimension, (int) $level);
            $heading = Sensory::assessments()[$dimension]['label'] ?? $dimension;
            if ($label !== null) {
                $chips[] = $heading.': '.$label;
            }
        }

        return $chips;
    }

    private function provenancePanel(Review $review): string
    {
        $provenance = $review->provenanceRecord();
        if ($provenance === []) {
            return '';
        }

        $labels = [
            'abv' => 'ABV',
            'dealcoholization_method' => 'Production method',
            'production_type' => 'Production type',
            'country' => 'Origin / country',
            'region' => 'Region',
            'producer' => 'Producer',
            'ingredients' => 'Ingredients',
            'calories' => 'Calories',
            'sugar' => 'Sugar',
            'price' => 'Price',
            'availability' => 'Availability',
            'volume' => 'Volume',
            'base_beverage' => 'Base beverage',
            'ean' => 'Barcode (EAN/GTIN)',
        ];

        $kindLabels = [
            'manufacturer' => 'Producer',
            'label' => 'Bottle / can label',
            'retailer' => 'Retail listing',
            'distributor' => 'Distributor / importer',
            'government' => 'Government record',
            'research' => 'Research / registry',
            'press' => 'Press',
            'inference' => 'Editorial inference',
            'unknown' => 'Cited source',
        ];

        $confidenceLabels = [
            'verified' => 'Independently corroborated',
            'manufacturer_verified' => 'Producer verified',
            'label_verified' => 'Bottle verified',
            'secondary' => 'Secondary source',
            'inferred' => 'Inferred',
            'unverified' => 'Unverified',
            'bottle_verified' => 'Bottle verified',
            'producer_verified' => 'Producer verified',
            'distributor_verified' => 'Distributor verified',
            'retailer_verified' => 'Retailer verified',
            'independently_corroborated' => 'Independently corroborated',
        ];

        $grouped = [];
        foreach ($provenance as $field => $entry) {
            $url = (string) ($entry['url'] ?? '');
            $kind = Review::resolveProvenanceKind(
                (string) ($entry['kind'] ?? 'unknown'),
                $url,
                (string) ($entry['note'] ?? ''),
            );
            $confidence = Sensory::normalizeConfidence((string) ($entry['confidence'] ?? (
                in_array($kind, ['manufacturer', 'label'], true) ? 'manufacturer_verified' : 'secondary'
            )));
            if ($confidence === '') {
                $confidence = 'secondary';
            }
            $key = $confidence.'|'.$kind.'|'.$url;
            if (! isset($grouped[$key])) {
                $note = isset($entry['note']) && ! str_starts_with((string) $entry['note'], 'Derived from')
                    ? (string) $entry['note']
                    : null;
                $host = $url !== '' ? (parse_url($url, PHP_URL_HOST) ?: null) : null;
                if (is_string($host) && str_starts_with($host, 'www.')) {
                    $host = substr($host, 4);
                }
                $grouped[$key] = [
                    'kind' => $kindLabels[$kind] ?? $kind,
                    'confidence' => $confidenceLabels[$confidence] ?? $confidence,
                    'confidenceClass' => preg_replace('/[^a-z0-9-]+/', '-', $confidence) ?: 'secondary',
                    'href' => $url !== '' ? $url : null,
                    'source' => $host ?? ($note ?? 'Recorded claim'),
                    'note' => $url === '' ? $note : null,
                    'fields' => [],
                ];
            }
            $grouped[$key]['fields'][] = $labels[$field] ?? ucfirst(str_replace('_', ' ', $field));
        }

        $groups = array_values($grouped);
        $fieldCount = array_sum(array_map(fn (array $group): int => count($group['fields']), $groups));
        $sourceCount = count($groups);
        $summary = $fieldCount.' '.($fieldCount === 1 ? 'fact' : 'facts')
            .' · '.$sourceCount.' '.($sourceCount === 1 ? 'source' : 'sources');

        return $this->view->render('partials/provenance', [
            'groups' => $groups,
            'summary' => $summary,
            'title' => 'Sources & verification',
        ]);
    }

    private function sources(Review $review): string
    {
        if ($review->sources === []) {
            return '';
        }

        $items = '';
        foreach ($review->sources as $source) {
            $items .= '<li><a href="'.Str::e($source['url']).'" rel="nofollow noopener">'.Str::e($source['title']).'</a></li>';
        }

        return $this->view->render('partials/sources', ['items' => $items]);
    }

    private function discrepancies(Review $review): string
    {
        if ($review->discrepancies === []) {
            return '';
        }

        $items = '';
        foreach ($review->discrepancies as $row) {
            $items .= '<li><strong>'.Str::e(ucfirst($row['field'])).':</strong> '.Str::e($row['note']).'</li>';
        }

        return $this->view->render('partials/discrepancies', ['items' => $items]);
    }

    private function purchaseLinks(Review $review): string
    {
        if ($review->purchaseLinks === []) {
            return $review->availability
                ? '<p><strong>Where to buy:</strong> '.Str::e($review->availability).'</p>'
                : '';
        }

        $items = '';
        foreach ($review->purchaseLinks as $link) {
            $relationship = $link['relationship'] ?? 'citation';
            $rel = $relationship === 'affiliate'
                ? 'sponsored nofollow noopener'
                : 'nofollow noopener';
            $label = $link['label'];
            if (! empty($link['region'])) {
                $label .= ' ('.$link['region'].')';
            }
            $meta = [];
            if (! empty($link['price'])) {
                $meta[] = (string) $link['price'];
            }
            if ($relationship === 'affiliate') {
                $meta[] = 'Affiliate';
            } elseif ($relationship === 'paid') {
                $meta[] = 'Paid placement';
            } elseif ($relationship === 'citation') {
                $meta[] = 'Citation';
            }
            if (! empty($link['last_verified'])) {
                $meta[] = 'Checked '.$link['last_verified'];
            }
            $metaHtml = $meta === [] ? '' : '<span class="buy-meta">'.Str::e(implode(' · ', $meta)).'</span>';
            $items .= '<li><a href="'.Str::e($link['url']).'" rel="'.$rel.'" data-analytics-event="outbound_buy">'.Str::e($label).'</a>'.$metaHtml.'</li>';
        }

        return $this->view->render('partials/purchase-links', [
            'availability' => $review->availability ? '<p>'.Str::e($review->availability).'</p>' : '',
            'items' => $items,
        ]);
    }

    private function disclosure(Review $review): string
    {
        if (! $review->hasPublicDisclosure()) {
            return '';
        }

        $items = '';
        foreach ($review->disclosureLines() as $line) {
            $items .= '<li>'.Str::e($line).'</li>';
        }

        return $this->view->render('partials/disclosure', ['items' => $items]);
    }

    private function productFigure(Review $review, string $class, bool $hero = false, bool $eager = false): string
    {
        $assets = $review->imageAssets();
        $presentation = $review->imagePresentation();
        $class = trim($class.' product-figure--'.$presentation);
        if (! empty($assets['cutout'])) {
            $class .= ' product-figure--cutout';
        }
        $loadEager = $hero || $eager;

        return $this->view->render('partials/product-figure', [
            'class' => $class,
            'src' => $assets === null ? '' : $this->config->publicUrl($assets['src']),
            'webp' => ($assets['webp'] ?? null) ? $this->config->publicUrl((string) $assets['webp']) : '',
            'webpSrcset' => $this->srcsetUrls($assets['webpSrcset'] ?? ''),
            'srcset' => $this->srcsetUrls($assets['srcset'] ?? ''),
            'sizes' => match (true) {
                str_contains($class, 'product-figure--feature') => '(max-width: 640px) 78vw, (max-width: 1024px) 42vw, 420px',
                $hero => '(max-width: 640px) 56vw, (max-width: 980px) 200px, 240px',
                default => '(max-width: 640px) 78vw, (max-width: 980px) 45vw, 274px',
            },
            'width' => $assets['width'] ?? 720,
            'height' => $assets['height'] ?? 960,
            'alt' => $review->imageAltText(),
            'loading' => $loadEager ? 'eager' : 'lazy',
            'priority' => $loadEager,
            'presentation' => $presentation,
            'credit' => $hero && $review->imageCredit !== null
                ? '<figcaption>'.$this->e($review->imageCredit).'</figcaption>'
                : '',
        ]);
    }

    private function optionalBlock(?string $value, string $prefix = ''): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return '<p>'.Str::e($prefix.$value).'</p>';
    }

    private function articleGraph(Review $review): array
    {
        return (new StructuredData($this->config))->review($review);
    }

    /**
     * @return array<string, mixed>
     */
    private function productGraph(Review $review): array
    {
        return (new StructuredData($this->config))->product($review, $this->config->categoryLabel($review->category));
    }

    /**
     * @return array<string, mixed>
     */
}
