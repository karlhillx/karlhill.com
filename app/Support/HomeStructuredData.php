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

        // Match og:image so Google Search does not pick a NASA work screenshot
        // as the result thumbnail. Face sits in the right 630×630 of this card.
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
            'publisher' => ['@id' => $personId],
            'about' => ['@id' => $personId],
        ];

        $profilePageLd = [
            '@type' => 'ProfilePage',
            '@id' => $url.'/#profile',
            'url' => $url.'/',
            'name' => $person['name'].' — Professional profile',
            'description' => $seo['description'],
            'inLanguage' => 'en-US',
            'image' => $shareImage,
            'primaryImageOfPage' => $shareImage,
            'thumbnailUrl' => "{$url}/img/og-home.jpg",
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
