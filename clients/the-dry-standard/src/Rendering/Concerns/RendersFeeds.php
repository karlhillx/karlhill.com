<?php

namespace DryStandard\Rendering\Concerns;

use Carbon\CarbonImmutable;
use DryStandard\Collections;
use DryStandard\Review;
use DryStandard\Str;
use Illuminate\Support\Collection;

trait RendersFeeds
{
    public function sitemap(Collection $reviews, Collection $guides, Collection $methods, Collection $brands, Collection $styles = new Collection): string
    {
        $urls = [
            $this->sitemapUrl('', 'weekly', '1.0'),
            $this->sitemapUrl('reviews/', 'weekly', '0.8'),
            $this->sitemapUrl('learn/', 'monthly', '0.6'),
            $this->sitemapUrl('brands/', 'weekly', '0.6'),
            $this->sitemapUrl('methods/', 'monthly', '0.6'),
            $this->sitemapUrl('styles/', 'weekly', '0.6'),
            $this->sitemapUrl('about/', 'monthly', '0.5'),
            $this->sitemapUrl('methodology/', 'monthly', '0.6'),
            $this->sitemapUrl('collections/', 'weekly', '0.7'),
            $this->sitemapUrl('privacy/', 'yearly', '0.2'),
            $this->sitemapUrl('best/', 'weekly', '0.7'),
            $this->sitemapUrl('compare/', 'weekly', '0.6'),
            $this->sitemapUrl('industry/', 'monthly', '0.4'),
            $this->sitemapUrl('industry/submit/', 'monthly', '0.4'),
            $this->sitemapUrl('industry/samples/', 'monthly', '0.3'),
            $this->sitemapUrl('industry/partnerships/', 'monthly', '0.4'),
        ];

        foreach ($this->config->categories() as $category) {
            $urls[] = $this->sitemapUrl('reviews/'.$category.'/', 'weekly', '0.7');
            $urls[] = $this->sitemapUrl('best/'.$category.'/', 'weekly', '0.6');
        }

        foreach ($reviews as $review) {
            $urls[] = $this->sitemapUrl($review->path(), 'monthly', '0.8', $review->modifiedAt()->toDateString());
        }

        foreach ($guides as $guide) {
            $urls[] = $this->sitemapUrl('learn/'.$guide->slug.'/', 'monthly', '0.6');
        }

        foreach ($methods as $method) {
            $urls[] = $this->sitemapUrl('methods/'.$method->slug.'/', 'monthly', '0.6');
        }

        foreach ($brands as $brand) {
            $urls[] = $this->sitemapUrl('brands/'.$brand['slug'].'/', 'weekly', '0.5');
        }

        foreach ($styles as $style) {
            $urls[] = $this->sitemapUrl('styles/'.$style['slug'].'/', 'weekly', '0.6');
        }

        foreach (Collections::definitions() as $def) {
            $urls[] = $this->sitemapUrl('collections/'.$def['slug'].'/', 'weekly', '0.6');
        }

        $body = implode("\n", $urls);

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{$body}
</urlset>
XML;
    }

    /**
     * @param  Collection<int, Review>  $reviews
     */
    public function feed(Collection $reviews): string
    {
        $updated = $reviews->map(fn (Review $review): string => $review->modifiedAt()->toIso8601String())->first()
            ?? CarbonImmutable::now()->toIso8601String();
        $entries = $reviews->map(function (Review $review): string {
            $url = $this->config->canonicalUrl($review->path());
            $title = Str::xml($review->title);
            $summary = Str::xml($review->summary);
            $updated = $review->modifiedAt()->toIso8601String();
            $published = $review->reviewDate->toIso8601String();

            return <<<XML
  <entry>
    <id>{$url}</id>
    <title>{$title}</title>
    <link rel="alternate" type="text/html" href="{$url}"/>
    <updated>{$updated}</updated>
    <published>{$published}</published>
    <author><name>{$this->xml($this->config->editorName())}</name></author>
    <category term="{$review->category}"/>
    <summary>{$summary}</summary>
  </entry>
XML;
        })->implode("\n");

        $home = $this->config->canonicalUrl();
        $feed = $this->config->canonicalUrl('feed.xml');
        $name = Str::xml($this->config->name());
        $editor = $this->xml($this->config->editorName());

        return <<<XML
<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title>{$name}</title>
  <subtitle>{$this->xml($this->config->string('site.description'))}</subtitle>
  <link rel="alternate" type="text/html" href="{$home}"/>
  <link rel="self" type="application/atom+xml" href="{$feed}"/>
  <id>{$feed}</id>
  <updated>{$updated}</updated>
  <author><name>{$editor}</name></author>
{$entries}
</feed>
XML;
    }

    /**
     * @param  array<string, string>  $items
     * @return array<int, array{label: string, url: string}>
     */
}
