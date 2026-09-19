<?php

namespace DryStandard;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Symfony\Component\Yaml\Yaml;

final class CatalogSync
{
    public function __construct(
        private readonly Paths $paths,
        private readonly Catalog $catalog,
    ) {}

    /**
     * @return array{reviews: int, products: int, queued: int}
     */
    public function run(): array
    {
        $imported = 0;

        foreach ($this->markdownReviews() as $review) {
            $this->catalog->upsert($review);
            $imported++;
        }

        $this->mergeMasterProducts();
        $this->mergeQueue();
        $this->exportProductsCsv();

        $rows = $this->catalog->rows();

        return [
            'reviews' => $imported,
            'products' => $rows->count(),
            'queued' => $rows->where('status', 'queued')->count(),
        ];
    }

    /**
     * @return Collection<int, Review>
     */
    private function markdownReviews(): Collection
    {
        $directory = $this->paths->content('reviews');

        if (! is_dir($directory)) {
            return collect();
        }

        $files = glob($directory.DIRECTORY_SEPARATOR.'*.md') ?: [];

        return collect($files)
            ->map(function (string $file): Review {
                $document = YamlFrontMatter::parseFile($file);

                return Review::fromMatter($document->matter(), $document->body(), $file);
            })
            ->values();
    }

    private function mergeMasterProducts(): void
    {
        $file = $this->paths->data('master-products.csv');

        if (! is_file($file)) {
            return;
        }

        $handle = fopen($file, 'r');
        if ($handle === false) {
            return;
        }

        $header = fgetcsv($handle) ?: [];

        while (($values = fgetcsv($handle)) !== false) {
            if ($values === [null] || $values === false) {
                continue;
            }

            $row = array_combine($header, array_pad($values, count($header), '')) ?: [];
            $id = trim((string) ($row['ID'] ?? ''));
            $product = trim((string) ($row['Product'] ?? ''));
            $brand = trim((string) ($row['Brand'] ?? ''));

            if ($product === '') {
                continue;
            }

            $existing = $id !== '' ? $this->catalog->findById($id) : null;
            $slug = $existing?->slug ?: Str::slug($product);
            $extra = [
                'id' => $id !== '' ? $id : null,
                'ean' => trim((string) ($row['EAN'] ?? '')) ?: null,
                'retailers' => trim((string) ($row['Retailer(s)'] ?? '')) ?: null,
                'times_purchased' => is_numeric($row['Times Purchased'] ?? null) ? (int) $row['Times Purchased'] : 0,
                'first_purchase' => trim((string) ($row['First Purchase'] ?? '')) ?: null,
                'most_recent_purchase' => trim((string) ($row['Most Recent Purchase'] ?? '')) ?: null,
            ];

            if ($existing instanceof Review) {
                $this->catalog->upsert($existing, $extra);

                continue;
            }

            $this->catalog->upsertRow([
                'slug' => $slug,
                'title' => $product,
                'product' => $product,
                'brand' => $brand,
                'category' => $this->mapCategory((string) ($row['Category'] ?? '')),
                'abv' => trim((string) ($row['ABV'] ?? '')) ?: null,
                'dealcoholized' => $this->mapDealcoholized((string) ($row['Dealcoholized?'] ?? '')),
                'dealcoholization_method' => trim((string) ($row['Method'] ?? '')) ?: null,
                'status' => 'queued',
                'priority' => 'normal',
                ...$extra,
            ]);
        }

        fclose($handle);
    }

    private function mergeQueue(): void
    {
        $file = $this->paths->data('review-queue.yaml');

        if (! is_file($file)) {
            return;
        }

        $parsed = Yaml::parseFile($file);
        if (! is_array($parsed)) {
            return;
        }

        foreach ($parsed as $item) {
            if (! is_array($item)) {
                continue;
            }

            $product = trim((string) ($item['product'] ?? ''));
            $brand = trim((string) ($item['brand'] ?? ''));

            if ($product === '' || $this->catalog->containsProduct($product, $brand)) {
                continue;
            }

            $this->catalog->upsertRow([
                'slug' => Str::slug($product),
                'title' => $product,
                'product' => $product,
                'brand' => $brand,
                'category' => (string) ($item['category'] ?? 'wine'),
                'status' => (string) ($item['status'] ?? 'queued'),
                'priority' => (string) ($item['priority'] ?? 'normal'),
                'notes' => (string) ($item['notes'] ?? ''),
                'id' => trim((string) ($item['id'] ?? '')) ?: null,
                'ean' => trim((string) ($item['ean'] ?? '')) ?: null,
            ]);
        }
    }

    private function exportProductsCsv(): void
    {
        $file = $this->paths->data('products.csv');
        $handle = fopen($file, 'w');

        if ($handle === false) {
            throw new \RuntimeException('Unable to write '.$file);
        }

        $columns = [
            'id',
            'ean',
            'slug',
            'title',
            'brand',
            'product',
            'category',
            'abv',
            'abv_numeric',
            'dealcoholized',
            'dealcoholization_method',
            'status',
            'rating',
            'review_date',
            'retailers',
            'times_purchased',
        ];

        fputcsv($handle, $columns);

        foreach ($this->catalog->rows() as $row) {
            fputcsv($handle, array_map(fn (string $column): mixed => $row[$column] ?? '', $columns));
        }

        fclose($handle);
    }

    private function mapCategory(string $value): string
    {
        $value = strtolower($value);

        return match (true) {
            str_contains($value, 'beer') => 'beer',
            str_contains($value, 'spirit') => 'spirits',
            str_contains($value, 'cocktail'), str_contains($value, 'rtd') => 'cocktails',
            str_contains($value, 'cider') => 'cider',
            default => 'wine',
        };
    }

    private function mapDealcoholized(string $value): string
    {
        $value = strtolower(trim($value));

        return match (true) {
            $value === 'yes', str_starts_with($value, 'yes') => 'yes',
            $value === 'no', str_contains($value, 'formulated') => 'no',
            default => 'not-verified',
        };
    }
}
