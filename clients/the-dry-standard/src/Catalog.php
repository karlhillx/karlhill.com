<?php

namespace DryStandard;

use Illuminate\Support\Collection;
use PDO;

final class Catalog
{
    private function __construct(private readonly PDO $pdo) {}

    public static function open(Paths $paths): self
    {
        $file = $paths->data('catalog.sqlite');
        $directory = dirname($file);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $pdo = new PDO('sqlite:'.$file, options: [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $pdo->exec('PRAGMA foreign_keys = ON');
        $catalog = new self($pdo);
        $catalog->migrate();

        return $catalog;
    }

    public function isEmpty(): bool
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM products')->fetchColumn() === 0;
    }

    /**
     * @return Collection<int, Review>
     */
    public function reviews(): Collection
    {
        $rows = $this->pdo->query(
            "SELECT * FROM products WHERE slug IS NOT NULL AND slug != '' AND category IS NOT NULL AND category != '' AND TRIM(COALESCE(body_markdown, '')) != '' ORDER BY review_date DESC, slug ASC"
        )->fetchAll();

        return collect($rows)
            ->map(fn (array $row): Review => Review::fromRecord($row))
            ->values();
    }

    /**
     * @return Collection<int, Review>
     */
    public function published(): Collection
    {
        $rows = $this->pdo->query(
            "SELECT * FROM products WHERE status = 'published' AND slug IS NOT NULL AND slug != '' AND category IS NOT NULL AND category != '' AND TRIM(COALESCE(body_markdown, '')) != '' AND rating IS NOT NULL ORDER BY review_date DESC, slug ASC"
        )->fetchAll();

        return collect($rows)
            ->map(fn (array $row): Review => Review::fromRecord($row))
            ->filter(fn (Review $review): bool => $review->isPublic())
            ->values();
    }

    /**
     * Published reviews without body markdown — for listings, facets, and cards.
     *
     * @return Collection<int, Review>
     */
    public function publishedListing(): Collection
    {
        $columns = array_values(array_filter(
            $this->columns(),
            fn (string $column): bool => $column !== 'body_markdown',
        ));

        $rows = $this->pdo->query(
            'SELECT '.implode(', ', $columns)
            ." FROM products WHERE status = 'published' AND slug IS NOT NULL AND slug != '' AND category IS NOT NULL AND category != '' AND TRIM(COALESCE(body_markdown, '')) != '' AND rating IS NOT NULL AND INSTR(lower(title || ' ' || product), 'bundle') = 0 ORDER BY review_date DESC, slug ASC"
        )->fetchAll();

        return collect($rows)
            ->map(function (array $row): Review {
                $row['body_markdown'] = ' ';

                return Review::fromRecord($row);
            })
            ->filter(fn (Review $review): bool => $review->isListed())
            ->values();
    }

    /**
     * @param  array<string, int>  $publishOrder
     * @return Collection<int, Review>
     */
    public function publishedMatching(ArchiveQuery $query, array $publishOrder = []): Collection
    {
        [$where, $params] = $query->sqlWhere();
        $columns = array_values(array_filter(
            $this->columns(),
            fn (string $column): bool => $column !== 'body_markdown',
        ));
        $statement = $this->pdo->prepare(
            'SELECT '.implode(', ', $columns).' FROM products WHERE '.$where
        );
        $statement->execute($params);
        $rows = $statement->fetchAll();

        $reviews = collect($rows)
            ->map(function (array $row): Review {
                $row['body_markdown'] = ' ';

                return Review::fromRecord($row);
            })
            ->filter(fn (Review $review): bool => $review->isListed())
            ->values();

        return $query->apply($reviews, $publishOrder);
    }

    public function find(string $slug): ?Review
    {
        $statement = $this->pdo->prepare('SELECT * FROM products WHERE slug = :slug LIMIT 1');
        $statement->execute(['slug' => $slug]);
        $row = $statement->fetch();

        return $row === false ? null : Review::fromRecord($row);
    }

    public function findById(string $id): ?Review
    {
        if ($id === '') {
            return null;
        }

        $statement = $this->pdo->prepare(
            "SELECT * FROM products WHERE id = :id ORDER BY (TRIM(COALESCE(body_markdown, '')) = '') ASC, slug ASC LIMIT 1"
        );
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        return $row === false ? null : Review::fromRecord($row);
    }

    public function findByProduct(string $product, string $brand = ''): ?Review
    {
        $sql = 'SELECT * FROM products WHERE lower(product) = lower(:product)';
        $params = ['product' => $product];

        if ($brand !== '') {
            $sql .= ' AND lower(brand) = lower(:brand)';
            $params['brand'] = $brand;
        }

        $sql .= " ORDER BY (TRIM(COALESCE(body_markdown, '')) = '') ASC, slug ASC LIMIT 1";
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        $row = $statement->fetch();

        return $row === false ? null : Review::fromRecord($row);
    }

    public function findByEan(string $ean): ?Review
    {
        if ($ean === '') {
            return null;
        }

        $statement = $this->pdo->prepare(
            "SELECT * FROM products WHERE ean = :ean ORDER BY (TRIM(COALESCE(body_markdown, '')) = '') ASC, slug ASC LIMIT 1"
        );
        $statement->execute(['ean' => $ean]);
        $row = $statement->fetch();

        return $row === false ? null : Review::fromRecord($row);
    }

    public function findIdentity(?string $id, string $product, string $brand = '', ?string $ean = null): ?Review
    {
        if ($id !== null && $id !== '') {
            $match = $this->findById($id);
            if ($match instanceof Review) {
                return $match;
            }

            $statement = $this->pdo->prepare(
                "SELECT * FROM products WHERE product_id = :id ORDER BY (TRIM(COALESCE(body_markdown, '')) = '') ASC, slug ASC LIMIT 1"
            );
            $statement->execute(['id' => $id]);
            $row = $statement->fetch();
            if ($row !== false) {
                return Review::fromRecord($row);
            }
        }

        if ($ean !== null && $ean !== '') {
            $match = $this->findByEan($ean);
            if ($match instanceof Review) {
                return $match;
            }
        }

        if ($product === '') {
            return null;
        }

        return $this->findByProduct($product, $brand);
    }

    public function deleteSlug(string $slug): void
    {
        $statement = $this->pdo->prepare('DELETE FROM products WHERE slug = :slug');
        $statement->execute(['slug' => $slug]);
    }

    public function pruneGhosts(): int
    {
        $deleted = 0;

        foreach ([
            "DELETE FROM products WHERE slug IN (
                SELECT slug FROM (
                    SELECT ghost.slug
                    FROM products AS ghost
                    WHERE ghost.id IS NOT NULL AND ghost.id != ''
                      AND EXISTS (
                          SELECT 1 FROM products AS keep
                          WHERE keep.id = ghost.id
                            AND keep.slug != ghost.slug
                            AND (
                                (TRIM(COALESCE(keep.body_markdown, '')) != '' AND TRIM(COALESCE(ghost.body_markdown, '')) = '')
                                OR (
                                    (TRIM(COALESCE(keep.body_markdown, '')) != '') = (TRIM(COALESCE(ghost.body_markdown, '')) != '')
                                    AND (
                                        (keep.status = 'published' AND ghost.status != 'published')
                                        OR (
                                            (keep.status = 'published') = (ghost.status = 'published')
                                            AND keep.slug < ghost.slug
                                        )
                                    )
                                )
                            )
                      )
                )
            )",
            "DELETE FROM products WHERE slug IN (
                SELECT slug FROM (
                    SELECT ghost.slug
                    FROM products AS ghost
                    WHERE ghost.ean IS NOT NULL AND ghost.ean != ''
                      AND EXISTS (
                          SELECT 1 FROM products AS keep
                          WHERE keep.ean = ghost.ean
                            AND keep.slug != ghost.slug
                            AND TRIM(COALESCE(keep.body_markdown, '')) != ''
                            AND TRIM(COALESCE(ghost.body_markdown, '')) = ''
                      )
                )
            )",
        ] as $sql) {
            $count = $this->pdo->exec($sql);
            $deleted += is_int($count) ? $count : 0;
        }

        $this->pdo->exec(
            "UPDATE products SET status = 'queued' WHERE status = 'published' AND TRIM(COALESCE(body_markdown, '')) = ''"
        );
        $this->pdo->exec("UPDATE products SET id = NULL WHERE id = ''");
        $this->pdo->exec("UPDATE products SET ean = NULL WHERE ean = ''");

        return $deleted;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function rowBySlug(string $slug): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM products WHERE slug = :slug LIMIT 1');
        $statement->execute(['slug' => $slug]);
        $row = $statement->fetch();

        return $row === false ? null : $row;
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function rows(): Collection
    {
        return collect($this->pdo->query('SELECT * FROM products ORDER BY product ASC')->fetchAll());
    }

    public function exists(string $slug): bool
    {
        return $this->find($slug) !== null;
    }

    /**
     * @param  array<string, mixed>  $extra
     */
    public function upsert(Review $review, array $extra = []): void
    {
        $record = [...$review->toRecord(), ...$extra];
        $columns = $this->columns();
        $record = array_intersect_key($record, array_flip($columns));

        $names = array_keys($record);
        $placeholders = implode(', ', array_map(fn (string $name): string => ':'.$name, $names));
        $updates = implode(', ', array_map(
            fn (string $name): string => $name.' = excluded.'.$name,
            array_values(array_filter($names, fn (string $name): bool => $name !== 'rowid')),
        ));

        $sql = 'INSERT INTO products ('.implode(', ', $names).') VALUES ('.$placeholders.')'
            .' ON CONFLICT(slug) DO UPDATE SET '.$updates;

        $statement = $this->pdo->prepare($sql);
        foreach ($record as $name => $value) {
            $statement->bindValue(':'.$name, $value);
        }
        $statement->execute();

        $id = trim((string) ($record['id'] ?? ''));
        $slug = trim((string) ($record['slug'] ?? ''));
        if ($id !== '' && $slug !== '') {
            $retire = $this->pdo->prepare('DELETE FROM products WHERE id = :id AND slug != :slug');
            $retire->execute([
                'id' => $id,
                'slug' => $slug,
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $row
     */
    public function upsertRow(array $row): void
    {
        if (($row['slug'] ?? '') === '') {
            throw new \InvalidArgumentException('Catalog rows require a slug.');
        }

        $existing = $this->find((string) $row['slug']);
        $base = $existing?->toRecord() ?? [
            'slug' => $row['slug'],
            'title' => $row['title'] ?? $row['product'] ?? $row['slug'],
            'brand' => $row['brand'] ?? '',
            'product' => $row['product'] ?? '',
            'category' => $row['category'] ?? 'wine',
            'dealcoholized' => $row['dealcoholized'] ?? 'not-verified',
            'production_type' => $row['production_type'] ?? null,
            'verified' => $row['verified'] ?? null,
            'purchase_links' => '[]',
            'sources' => '[]',
            'discrepancies' => '[]',
            'verdict' => '',
            'summary' => '',
            'body_markdown' => '',
            'status' => $row['status'] ?? 'queued',
            'review_date' => $row['review_date'] ?? now()->toDateString(),
        ];

        $merged = [...$base, ...array_filter($row, fn (mixed $value): bool => $value !== null && $value !== '')];
        $review = Review::fromRecord($merged);
        $extra = array_intersect_key($merged, array_flip([
            'priority',
            'notes',
            'retailers',
        ]));

        $this->upsert($review, $extra);
    }

    public function markStatus(string $slug, string $status): void
    {
        $statement = $this->pdo->prepare('UPDATE products SET status = :status, updated_date = :updated WHERE slug = :slug');
        $statement->execute([
            'status' => $status,
            'updated' => now()->toDateString(),
            'slug' => $slug,
        ]);
    }

    public function containsProduct(string $product, string $brand = ''): bool
    {
        $sql = 'SELECT COUNT(*) FROM products WHERE lower(product) = lower(:product)';
        $params = ['product' => $product];

        if ($brand !== '') {
            $sql .= ' AND lower(brand) = lower(:brand)';
            $params['brand'] = $brand;
        }

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return (int) $statement->fetchColumn() > 0;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function queueItems(): array
    {
        $rows = $this->pdo->query(
            'SELECT product, brand, category, priority, status, notes, id, ean FROM products ORDER BY status, product'
        )->fetchAll();

        return array_map(function (array $row): array {
            $item = [
                'product' => (string) ($row['product'] ?? ''),
                'brand' => (string) ($row['brand'] ?? ''),
                'category' => (string) ($row['category'] ?? ''),
                'priority' => (string) ($row['priority'] ?? 'normal'),
                'status' => (string) ($row['status'] ?? 'queued'),
                'notes' => (string) ($row['notes'] ?? ''),
            ];

            if (($row['id'] ?? '') !== '') {
                $item = ['id' => $row['id'], ...$item];
            }
            if (($row['ean'] ?? '') !== '') {
                $item['ean'] = $row['ean'];
            }

            return $item;
        }, $rows);
    }

    /**
     * @return list<string>
     */
    private function columns(): array
    {
        return [
            'id',
            'ean',
            'slug',
            'title',
            'brand',
            'product',
            'category',
            'subcategory',
            'country',
            'region',
            'style',
            'abv',
            'abv_numeric',
            'dealcoholized',
            'dealcoholized_note',
            'production_type',
            'verified',
            'dealcoholization_method',
            'base_beverage',
            'producer',
            'price',
            'volume',
            'ingredients',
            'calories',
            'sugar',
            'availability',
            'purchase_links',
            'review_date',
            'updated_date',
            'rating',
            'verdict',
            'summary',
            'nose',
            'palate',
            'finish',
            'structure',
            'tastes',
            'profile',
            'mouthfeel',
            'highlight',
            'likeness',
            'drink_if_you_like',
            'best_for',
            'serve',
            'sources',
            'discrepancies',
            'body_markdown',
            'image',
            'image_alt',
            'image_credit',
            'image_source',
            'image_source_url',
            'image_sku_confirmed',
            'status',
            'priority',
            'notes',
            'retailers',
            'brand_slug',
            'style_slug',
            'method_facet',
            'abv_bucket',
            'country_slug',
            'search_text',
            'product_id',
            'identifiers',
            'producer_slug',
            'acquisition',
            'sponsored',
            'affiliate_relationship',
            'advertising_relationship',
            'commercial_relationship',
            'disclosure_note',
            'provenance',
        ];
    }

    private function migrate(): void
    {
        $this->pdo->exec(<<<'SQL'
CREATE TABLE IF NOT EXISTS products (
    slug TEXT PRIMARY KEY,
    id TEXT,
    ean TEXT,
    title TEXT NOT NULL,
    brand TEXT NOT NULL DEFAULT '',
    product TEXT NOT NULL DEFAULT '',
    category TEXT NOT NULL DEFAULT 'wine',
    subcategory TEXT,
    country TEXT,
    region TEXT,
    style TEXT,
    abv TEXT,
    abv_numeric REAL,
    dealcoholized TEXT NOT NULL DEFAULT 'not-verified',
    dealcoholized_note TEXT,
    dealcoholization_method TEXT,
    base_beverage TEXT,
    producer TEXT,
    price TEXT,
    volume TEXT,
    ingredients TEXT,
    calories TEXT,
    sugar TEXT,
    availability TEXT,
    purchase_links TEXT NOT NULL DEFAULT '[]',
    review_date TEXT,
    updated_date TEXT,
    rating INTEGER,
    verdict TEXT NOT NULL DEFAULT '',
    summary TEXT NOT NULL DEFAULT '',
    nose TEXT,
    palate TEXT,
    finish TEXT,
    best_for TEXT,
    serve TEXT,
    sources TEXT NOT NULL DEFAULT '[]',
    discrepancies TEXT NOT NULL DEFAULT '[]',
    body_markdown TEXT NOT NULL DEFAULT '',
    image TEXT,
    image_alt TEXT,
    image_credit TEXT,
    status TEXT NOT NULL DEFAULT 'queued',
    priority TEXT DEFAULT 'normal',
    notes TEXT,
    retailers TEXT,
    production_type TEXT NOT NULL DEFAULT 'not-verified',
    verified TEXT NOT NULL DEFAULT 'no'
);
CREATE INDEX IF NOT EXISTS products_status_idx ON products(status);
CREATE INDEX IF NOT EXISTS products_category_idx ON products(category);
CREATE INDEX IF NOT EXISTS products_brand_idx ON products(brand);
CREATE INDEX IF NOT EXISTS products_id_idx ON products(id);
CREATE INDEX IF NOT EXISTS products_style_idx ON products(style);
SQL);

        $this->ensureColumn('production_type', "production_type TEXT NOT NULL DEFAULT 'not-verified'");
        $this->ensureColumn('verified', "verified TEXT NOT NULL DEFAULT 'no'");
        $this->ensureColumn('image_source', 'image_source TEXT');
        $this->ensureColumn('image_source_url', 'image_source_url TEXT');
        $this->ensureColumn('image_sku_confirmed', 'image_sku_confirmed TEXT');
        $this->ensureColumn('brand_slug', 'brand_slug TEXT');
        $this->ensureColumn('style_slug', 'style_slug TEXT');
        $this->ensureColumn('method_facet', 'method_facet TEXT');
        $this->ensureColumn('abv_bucket', 'abv_bucket TEXT');
        $this->ensureColumn('country_slug', 'country_slug TEXT');
        $this->ensureColumn('search_text', 'search_text TEXT');
        $this->ensureColumn('product_id', 'product_id TEXT');
        $this->ensureColumn('identifiers', "identifiers TEXT NOT NULL DEFAULT '[]'");
        $this->ensureColumn('producer_slug', 'producer_slug TEXT');
        $this->ensureColumn('acquisition', 'acquisition TEXT');
        $this->ensureColumn('sponsored', "sponsored TEXT NOT NULL DEFAULT 'no'");
        $this->ensureColumn('affiliate_relationship', "affiliate_relationship TEXT NOT NULL DEFAULT 'none'");
        $this->ensureColumn('advertising_relationship', "advertising_relationship TEXT NOT NULL DEFAULT 'none'");
        $this->ensureColumn('commercial_relationship', "commercial_relationship TEXT NOT NULL DEFAULT 'none'");
        $this->ensureColumn('disclosure_note', 'disclosure_note TEXT');
        $this->ensureColumn('provenance', "provenance TEXT NOT NULL DEFAULT '{}'");
        $this->ensureColumn('structure', 'structure TEXT');
        $this->ensureColumn('tastes', "tastes TEXT NOT NULL DEFAULT '[]'");
        $this->ensureColumn('profile', "profile TEXT NOT NULL DEFAULT '[]'");
        $this->ensureColumn('mouthfeel', 'mouthfeel TEXT');
        $this->ensureColumn('highlight', 'highlight TEXT');
        $this->ensureColumn('likeness', 'likeness TEXT');
        $this->ensureColumn('drink_if_you_like', "drink_if_you_like TEXT NOT NULL DEFAULT '[]'");
        $this->pdo->exec('CREATE INDEX IF NOT EXISTS products_production_type_idx ON products(production_type)');
        $this->pdo->exec('CREATE INDEX IF NOT EXISTS products_style_idx ON products(style)');
        $this->pdo->exec('CREATE INDEX IF NOT EXISTS products_brand_slug_idx ON products(brand_slug)');
        $this->pdo->exec('CREATE INDEX IF NOT EXISTS products_style_slug_idx ON products(style_slug)');
        $this->pdo->exec('CREATE INDEX IF NOT EXISTS products_method_facet_idx ON products(method_facet)');
        $this->pdo->exec('CREATE INDEX IF NOT EXISTS products_country_slug_idx ON products(country_slug)');
        $this->pdo->exec('CREATE INDEX IF NOT EXISTS products_abv_bucket_idx ON products(abv_bucket)');
        $this->pdo->exec('CREATE INDEX IF NOT EXISTS products_product_id_idx ON products(product_id)');
        $this->dropColumn('times_purchased');
        $this->dropColumn('first_purchase');
        $this->dropColumn('most_recent_purchase');
    }

    private function ensureColumn(string $name, string $definition): void
    {
        $columns = $this->pdo->query('PRAGMA table_info(products)')->fetchAll();
        $existing = array_column($columns, 'name');

        if (! in_array($name, $existing, true)) {
            $this->pdo->exec('ALTER TABLE products ADD COLUMN '.$definition);
        }
    }

    private function dropColumn(string $name): void
    {
        $columns = $this->pdo->query('PRAGMA table_info(products)')->fetchAll();
        $existing = array_column($columns, 'name');

        if (in_array($name, $existing, true)) {
            $this->pdo->exec('ALTER TABLE products DROP COLUMN '.$name);
        }
    }
}
