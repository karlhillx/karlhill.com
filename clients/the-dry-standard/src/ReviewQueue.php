<?php

namespace DryStandard;

use Symfony\Component\Yaml\Yaml;

final class ReviewQueue
{
    public function __construct(private readonly Paths $paths) {}

    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        $file = $this->paths->data('review-queue.yaml');

        if (! is_file($file)) {
            return [];
        }

        $parsed = Yaml::parseFile($file);

        return is_array($parsed) ? array_values($parsed) : [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function actionable(): array
    {
        return array_values(array_filter(
            $this->all(),
            fn (array $item): bool => in_array((string) ($item['status'] ?? ''), ['queued', 'validated', 'scheduled'], true),
        ));
    }

    /**
     * @param  array<string, mixed>  $item
     */
    public function add(array $item): void
    {
        $product = trim((string) ($item['product'] ?? ''));
        $brand = trim((string) ($item['brand'] ?? ''));

        if ($product === '') {
            throw new \InvalidArgumentException('Queue items require a product name.');
        }

        if ($this->contains($product, $brand)) {
            throw new \RuntimeException("Queue already contains [{$product}].");
        }

        $items = $this->all();
        $row = [
            'product' => $product,
            'brand' => $brand,
            'category' => (string) ($item['category'] ?? ''),
            'priority' => (string) ($item['priority'] ?? 'normal'),
            'status' => (string) ($item['status'] ?? 'queued'),
            'notes' => (string) ($item['notes'] ?? ''),
        ];
        $code = trim((string) ($item['id'] ?? ''));
        $ean = trim((string) ($item['ean'] ?? ''));

        if ($code !== '' || $ean !== '') {
            $identifiers = ['product' => $product];
            if ($code !== '') {
                $identifiers['id'] = $code;
            }
            if ($ean !== '') {
                $identifiers['ean'] = $ean;
            }
            $row = [...$identifiers, ...array_slice($row, 1)];
        }

        $items[] = $row;

        $this->write($items);
    }

    public function mark(string $product, string $status): void
    {
        $items = $this->all();
        $found = false;

        foreach ($items as &$item) {
            if (strcasecmp((string) ($item['product'] ?? ''), $product) === 0) {
                $item['status'] = $status;
                $found = true;
            }
        }

        unset($item);

        if (! $found) {
            throw new \RuntimeException("Queue item [{$product}] was not found.");
        }

        $this->write($items);
    }

    public function contains(string $product, string $brand = ''): bool
    {
        foreach ($this->all() as $item) {
            $sameProduct = strcasecmp((string) ($item['product'] ?? ''), $product) === 0;
            $sameBrand = $brand === '' || strcasecmp((string) ($item['brand'] ?? ''), $brand) === 0;

            if ($sameProduct && $sameBrand) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    private function write(array $items): void
    {
        $yaml = Yaml::dump($items, 4, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);
        $file = $this->paths->data('review-queue.yaml');

        if (! is_dir(dirname($file))) {
            mkdir(dirname($file), 0755, true);
        }

        file_put_contents($file, $yaml);
    }
}
