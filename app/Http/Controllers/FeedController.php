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

        $feedId = $base.'/feed.xml';

        $entries = $posts->map(function ($post) use ($base) {
            $body = htmlspecialchars($post->bodyHtml, ENT_QUOTES | ENT_XML1, 'UTF-8');
            $title = htmlspecialchars($post->title, ENT_QUOTES | ENT_XML1, 'UTF-8');
            $summary = htmlspecialchars($post->excerpt, ENT_QUOTES | ENT_XML1, 'UTF-8');
            $url = $post->canonicalUrl();
            $categories = collect($post->tags)
                ->map(fn (string $tag) => '    <category term="'
                    .htmlspecialchars($tag, ENT_QUOTES | ENT_XML1, 'UTF-8')
                    .'"/>')
                ->implode("\n");

            $categoryBlock = $categories === '' ? '' : "\n{$categories}";

            return <<<XML
  <entry>
    <id>{$url}</id>
    <title>{$title}</title>
    <link rel="alternate" type="text/html" href="{$url}"/>
    <updated>{$post->isoDate()}</updated>
    <published>{$post->isoDate()}</published>
    <author><name>Karl Hill</name><uri>{$base}</uri></author>{$categoryBlock}
    <summary>{$summary}</summary>
    <content type="html">{$body}</content>
  </entry>
XML;
        })->implode("\n");

        $xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title>Karl Hill — Writing</title>
  <subtitle>Reflections on engineering leadership, mission software, and the overlooked work that turns code into something people can depend on.</subtitle>
  <link rel="alternate" type="text/html" href="{$base}/blog"/>
  <link rel="self" type="application/atom+xml" href="{$feedId}"/>
  <id>{$feedId}</id>
  <updated>{$updated}</updated>
  <author><name>Karl Hill</name><uri>{$base}</uri></author>
{$entries}
</feed>
XML;

        return response($xml, 200, [
            'Content-Type' => 'application/atom+xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=900',
        ]);
    }
}
