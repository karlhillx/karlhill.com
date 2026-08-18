<?php

namespace App\Http\Controllers;

use App\Support\SiteCatalog;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __construct(
        protected readonly SiteCatalog $catalog,
    ) {}

    public function __invoke(): Response
    {
        $urls = collect($this->catalog->sitemapUrls())
            ->map(fn (array $url) => sprintf(
                "  <url>\n    <loc>%s</loc>\n    <lastmod>%s</lastmod>\n    <changefreq>%s</changefreq>\n    <priority>%s</priority>\n  </url>",
                $url['loc'],
                $url['lastmod'],
                $url['changefreq'],
                $url['priority'],
            ))
            ->implode("\n");

        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{$urls}
</urlset>
XML;

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }
}
