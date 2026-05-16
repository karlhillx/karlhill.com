<?php

namespace App\Http\Controllers;

use App\Support\BlogPostRepository;
use Illuminate\Http\Response;

class FeedController extends Controller
{
    public function __construct(
        protected readonly BlogPostRepository $posts,
    ) {}

    public function __invoke(): Response
    {
        $posts = $this->posts->all();
        $base = rtrim(config('app.url', 'https://karlhill.com'), '/');
        $updated = $posts->isNotEmpty() ? $posts->first()->isoDate() : now()->toIso8601String();

        $entries = $posts->map(function ($post) {
            $body = htmlspecialchars($post->bodyHtml, ENT_QUOTES | ENT_XML1, 'UTF-8');
            $title = htmlspecialchars($post->title, ENT_QUOTES | ENT_XML1, 'UTF-8');
            $summary = htmlspecialchars($post->excerpt, ENT_QUOTES | ENT_XML1, 'UTF-8');
            $url = $post->canonicalUrl();

            return <<<XML
  <entry>
    <id>{$url}</id>
    <title>{$title}</title>
    <link rel="alternate" type="text/html" href="{$url}"/>
    <updated>{$post->isoDate()}</updated>
    <published>{$post->isoDate()}</published>
    <author><name>Karl Hill</name></author>
    <summary>{$summary}</summary>
    <content type="html">{$body}</content>
  </entry>
XML;
        })->implode("\n");

        $xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title>Karl Hill — Writing</title>
  <subtitle>Notes on engineering leadership, release governance, and shipping under pressure.</subtitle>
  <link rel="alternate" type="text/html" href="{$base}/blog"/>
  <link rel="self" type="application/atom+xml" href="{$base}/feed.xml"/>
  <id>{$base}/</id>
  <updated>{$updated}</updated>
  <author><name>Karl Hill</name><uri>{$base}</uri></author>
{$entries}
</feed>
XML;

        return response($xml, 200, [
            'Content-Type'  => 'application/atom+xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=900',
        ]);
    }
}
