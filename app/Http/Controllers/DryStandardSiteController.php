<?php

namespace App\Http\Controllers;

use DryStandard\StillPipeline;
use DryStandard\Workspace;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DryStandardSiteController extends Controller
{
    public function show(?string $path = null): BinaryFileResponse|Response
    {
        $relative = $this->normalizePath($path);
        $workspace = Workspace::default();
        $site = $workspace->site();

        if ($relative === 'feed.xml') {
            return $this->payload($site->feed(), 'application/atom+xml; charset=UTF-8');
        }

        if ($relative === 'sitemap.xml') {
            return $this->payload($site->sitemap(), 'application/xml; charset=UTF-8');
        }

        if ($relative === 'catalog.json') {
            return $this->payload($site->catalogJson(), 'application/json; charset=UTF-8');
        }

        if ($relative === 'robots.txt') {
            return $this->payload($site->robots(), 'text/plain; charset=UTF-8');
        }

        $html = $site->html($relative, request()->query());

        if ($html !== null) {
            $isFragment = (string) request()->query('fragment', '') === 'archive';

            return $this->payload(
                $isFragment ? $html : $this->withBaseHref($html),
                'text/html; charset=UTF-8',
            );
        }

        if (str_starts_with($relative, 'media/reviews/')) {
            $served = (new StillPipeline($workspace->paths))->serve(substr($relative, strlen('media/reviews/')));
            if ($served !== null) {
                return response()->file($served, [
                    'X-Robots-Tag' => 'noindex, nofollow',
                    'Cache-Control' => 'public, max-age=86400, s-maxage=604800',
                    'Content-Type' => str_ends_with($served, '.webp') ? 'image/webp' : 'image/jpeg',
                ]);
            }
        }

        if ($this->isDocumentPath($relative)) {
            return $this->payload($this->withBaseHref($site->notFound()), 'text/html; charset=UTF-8', 404);
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

    private function isDocumentPath(string $relative): bool
    {
        if ($relative === '') {
            return true;
        }

        $base = basename($relative);

        return ! str_contains($base, '.') || str_ends_with($base, '.html') || str_ends_with($base, '.htm');
    }

    private function payload(string $contents, string $contentType, int $status = 200): Response
    {
        return response($contents, $status, [
            'X-Robots-Tag' => 'noindex, nofollow',
            'Cache-Control' => 'public, max-age=300, s-maxage=600, stale-while-revalidate=120',
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
