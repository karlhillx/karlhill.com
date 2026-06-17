<?php

namespace App\Http\Controllers;

use App\Support\BlogPostRepository;
use App\Support\ProjectCatalog;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __construct(
        protected readonly BlogPostRepository $posts,
    ) {}

    public function __invoke(): Response
    {
        $base = rtrim(config('app.url', 'https://karlhill.com'), '/');
        $today = now()->toDateString();

        $urls = [
            sprintf(
                "  <url>\n    <loc>%s/</loc>\n    <lastmod>%s</lastmod>\n    <changefreq>monthly</changefreq>\n    <priority>1.0</priority>\n  </url>",
                $base,
                $today,
            ),
            sprintf(
                "  <url>\n    <loc>%s/work</loc>\n    <lastmod>%s</lastmod>\n    <changefreq>monthly</changefreq>\n    <priority>0.9</priority>\n  </url>",
                $base,
                $today,
            ),
            sprintf(
                "  <url>\n    <loc>%s/about</loc>\n    <lastmod>%s</lastmod>\n    <changefreq>monthly</changefreq>\n    <priority>0.9</priority>\n  </url>",
                $base,
                $today,
            ),
            sprintf(
                "  <url>\n    <loc>%s/blog</loc>\n    <lastmod>%s</lastmod>\n    <changefreq>weekly</changefreq>\n    <priority>0.8</priority>\n  </url>",
                $base,
                $this->posts->all()->first()?->publishedAt->toDateString() ?? $today,
            ),
        ];

        foreach ($this->posts->all() as $post) {
            $urls[] = sprintf(
                "  <url>\n    <loc>%s</loc>\n    <lastmod>%s</lastmod>\n    <changefreq>yearly</changefreq>\n    <priority>0.7</priority>\n  </url>",
                $post->canonicalUrl(),
                $post->publishedAt->toDateString(),
            );
        }

        foreach (ProjectCatalog::all() as $project) {
            if (! ProjectCatalog::hasCaseStudy($project)) {
                continue;
            }

            $urls[] = sprintf(
                "  <url>\n    <loc>%s/work/%s</loc>\n    <lastmod>%s</lastmod>\n    <changefreq>yearly</changefreq>\n    <priority>0.75</priority>\n  </url>",
                $base,
                $project['slug'],
                $today,
            );
        }

        $body = implode("\n", $urls);
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{$body}
</urlset>
XML;

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
