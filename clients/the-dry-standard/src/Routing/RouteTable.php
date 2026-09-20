<?php

namespace DryStandard\Routing;

/**
 * Explicit public IA for The Dry Standard.
 * Site::html remains the dispatcher; this table is the contract tests and SITE.md follow.
 *
 * @phpstan-type RouteRow array{path: string, name: string, group: string}
 */
final class RouteTable
{
    /**
     * @return list<RouteRow>
     */
    public static function entries(): array
    {
        return [
            ['path' => '/', 'name' => 'Home', 'group' => 'core'],
            ['path' => '/reviews/', 'name' => 'All reviews', 'group' => 'cellar'],
            ['path' => '/reviews/{category}/', 'name' => 'Category cellar', 'group' => 'cellar'],
            ['path' => '/reviews/{category}/{slug}/', 'name' => 'Review', 'group' => 'cellar'],
            ['path' => '/best/', 'name' => 'Best of the cellar', 'group' => 'cellar'],
            ['path' => '/best/{category}/', 'name' => 'Best by category', 'group' => 'cellar'],
            ['path' => '/compare/', 'name' => 'Compare', 'group' => 'tools'],
            ['path' => '/brands/', 'name' => 'Brands', 'group' => 'cellar'],
            ['path' => '/brands/{slug}/', 'name' => 'Brand', 'group' => 'cellar'],
            ['path' => '/styles/', 'name' => 'Styles', 'group' => 'cellar'],
            ['path' => '/styles/{slug}/', 'name' => 'Style cellar', 'group' => 'cellar'],
            ['path' => '/collections/', 'name' => 'Collections', 'group' => 'cellar'],
            ['path' => '/collections/{slug}/', 'name' => 'Collection', 'group' => 'cellar'],
            ['path' => '/learn/', 'name' => 'Learn hub', 'group' => 'learn'],
            ['path' => '/learn/{slug}/', 'name' => 'Guide', 'group' => 'learn'],
            ['path' => '/methods/', 'name' => 'Methods', 'group' => 'learn'],
            ['path' => '/methods/{slug}/', 'name' => 'Method', 'group' => 'learn'],
            ['path' => '/about/', 'name' => 'About', 'group' => 'meta'],
            ['path' => '/methodology/', 'name' => 'Methodology', 'group' => 'meta'],
            ['path' => '/privacy/', 'name' => 'Privacy', 'group' => 'meta'],
            ['path' => '/industry/', 'name' => 'Industry', 'group' => 'industry'],
            ['path' => '/industry/submit/', 'name' => 'Product submit', 'group' => 'industry'],
            ['path' => '/industry/samples/', 'name' => 'Samples', 'group' => 'industry'],
            ['path' => '/industry/partnerships/', 'name' => 'Partnerships', 'group' => 'industry'],
            ['path' => '/feed.xml', 'name' => 'Atom feed', 'group' => 'machine'],
            ['path' => '/sitemap.xml', 'name' => 'Sitemap', 'group' => 'machine'],
            ['path' => '/catalog.json', 'name' => 'Catalog JSON', 'group' => 'machine'],
        ];
    }

    /**
     * Primary chrome — keep to four items.
     *
     * @return list<array{key: string, label: string, path: string}>
     */
    public static function primaryNav(): array
    {
        return [
            ['key' => 'reviews', 'label' => 'Reviews', 'path' => 'reviews/'],
            ['key' => 'best', 'label' => 'Best', 'path' => 'best/'],
            ['key' => 'guides', 'label' => 'Learn', 'path' => 'learn/'],
            ['key' => 'about', 'label' => 'About', 'path' => 'about/'],
        ];
    }
}
