<?php

namespace App\Http\Controllers;

use DryStandard\Workspace;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DryStandardSiteController extends Controller
{
    public function show(?string $path = null): BinaryFileResponse|Response
    {
        $relative = $this->normalizePath($path);
        $site = Workspace::default()->site();

        if ($relative === 'feed.xml') {
            return $this->payload($site->feed(), 'application/atom+xml; charset=UTF-8');
        }

        if ($relative === 'sitemap.xml') {
            return $this->payload($site->sitemap(), 'application/xml; charset=UTF-8');
        }

        if ($relative === 'catalog.json') {
            return $this->payload($site->catalogJson(), 'application/json; charset=UTF-8');
        }

        $html = $site->html($relative);

        if ($html !== null) {
            return $this->payload($this->withBaseHref($html), 'text/html; charset=UTF-8');
        }

        return app(ClientSiteController::class)->show('the-dry-standard', $path);
    }

    private function normalizePath(?string $path): string
    {
        $relative = trim(str_replace('\\', '/', (string) $path), '/');

        if ($relative === 'index.html') {
            return '';
        }

        if (str_ends_with($relative, '/index.html')) {
            return substr($relative, 0, -11);
        }

        return $relative;
    }

    private function payload(string $contents, string $contentType): Response
    {
        return response($contents, 200, [
            'X-Robots-Tag' => 'noindex, nofollow',
            'Cache-Control' => 'private, max-age=60',
            'Content-Type' => $contentType,
        ]);
    }

    private function withBaseHref(string $html): string
    {
        if (preg_match('/<base\s/i', $html) === 1) {
            return $html;
        }

        $tag = '<base href="/clients/the-dry-standard/">';

        if (preg_match('/<head([^>]*)>/i', $html) === 1) {
            return preg_replace('/<head([^>]*)>/i', '<head$1>'.$tag, $html, 1) ?? $html;
        }

        return $tag.$html;
    }
}
