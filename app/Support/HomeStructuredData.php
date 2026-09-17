<?php

namespace App\Support;

use Illuminate\Support\Collection;

final class HomeStructuredData
{
    /**
     * @param  Collection<int, BlogPost>  $posts
     * @return array<string, mixed>
     */
    public static function build(Collection $posts): array
    {
        $url = PageMeta::siteUrl();
        $person = config('site.person');
        $seo = config('site.seo.home');
        $personLd = PersonJsonLd::node();
        $personId = $personLd['@id'];
        $websiteId = "{$url}/#website";
        $portrait = PersonJsonLd::portraitImage();

        // og-home.jpg is the social card. Search result thumbnails are square —
        // point those at the portrait so Google does not pick a work-card logo.
        $shareImage = [
            '@type' => 'ImageObject',
            'url' => "{$url}/img/og-home.jpg",
            'contentUrl' => "{$url}/img/og-home.jpg",
            'width' => 1200,
            'height' => 630,
            'caption' => $seo['title'],
        ];

        $websiteLd = [
            '@type' => 'WebSite',
            '@id' => $websiteId,
            'url' => $url.'/',
            'name' => $person['name'],
            'alternateName' => 'karlhill.com',
            'description' => $seo['description'],
            'inLanguage' => 'en-US',
            'image' => $shareImage,
            'logo' => $portrait,
            'publisher' => ['@id' => $personId],
            'about' => ['@id' => $personId],
        ];

        $profilePageLd = [
            '@type' => 'ProfilePage',
            '@id' => $url.'/#profile',
            'url' => $url.'/',
            'name' => $person['name'],
            'description' => $seo['description'],
            'inLanguage' => 'en-US',
            'image' => $portrait,
            'primaryImageOfPage' => $portrait,
            'thumbnailUrl' => $portrait['url'],
            'mainEntity' => ['@id' => $personId],
            'isPartOf' => ['@id' => $websiteId],
        ];

        $blogPostsLd = $posts->map(fn (BlogPost $post) => [
            '@type' => 'BlogPosting',
            'headline' => $post->title,
            'url' => $post->canonicalUrl(),
            'datePublished' => $post->publishedAt->toIso8601String(),
            'description' => $post->excerpt,
            'author' => ['@id' => $personId],
        ])->values()->all();

        $blogLd = [
            '@type' => 'Blog',
            '@id' => "{$url}/blog#blog",
            'name' => 'Karl Hill — Writing',
            'url' => "{$url}/blog",
            'author' => ['@id' => $personId],
            'publisher' => ['@id' => $personId],
            'blogPost' => $blogPostsLd,
        ];

        return [
            '@context' => 'https://schema.org',
            '@graph' => [$personLd, $websiteLd, $profilePageLd, $blogLd],
        ];
    }
}
