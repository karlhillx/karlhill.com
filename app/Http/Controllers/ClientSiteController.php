<?php

namespace App\Http\Controllers;

use App\Support\ClientSiteCatalog;
use App\Support\PageMeta;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Mime\MimeTypes;

class ClientSiteController extends Controller
{
    public function __construct(private ClientSiteCatalog $catalog) {}

    public function index(): View
    {
        return view('clients.index', [
            'meta' => PageMeta::clients(),
            'clients' => $this->catalog->all(),
        ]);
    }

    public function show(string $client, ?string $path = null): BinaryFileResponse|Response
    {
        if (! $this->catalog->isValidSlug($client) || ! $this->catalog->exists($client)) {
            abort(404);
        }

        $relative = ($path === null || $path === '') ? 'index.html' : $path;
        $file = $this->catalog->resolveFile($client, $relative);

        if ($file === null) {
            abort(404);
        }

        $headers = [
            'X-Robots-Tag' => 'noindex, nofollow',
            'Cache-Control' => 'private, max-age=60',
            'Content-Type' => $this->mimeTypeFor($file),
        ];

        // Make relative CSS/JS/asset URLs resolve under /clients/{slug}/ even
        // when the preview is opened without a trailing slash.
        if ($this->shouldInjectBaseHref($relative, $file)) {
            $html = file_get_contents($file);
            if ($html === false) {
                abort(404);
            }

            return response($this->withBaseHref($html, $client), 200, $headers);
        }

        return response()->file($file, $headers);
    }

    protected function shouldInjectBaseHref(string $relative, string $file): bool
    {
        $relative = strtolower($relative);

        return $relative === 'index.html'
            || str_ends_with(strtolower($file), '.html')
            || str_ends_with(strtolower($file), '.htm');
    }

    protected function withBaseHref(string $html, string $client): string
    {
        if (preg_match('/<base\s/i', $html) === 1) {
            return $html;
        }

        $base = '/clients/'.$client.'/';
        $tag = '<base href="'.e($base).'">';

        if (preg_match('/<head([^>]*)>/i', $html) === 1) {
            return preg_replace('/<head([^>]*)>/i', '<head$1>'.$tag, $html, 1) ?? $html;
        }

        return $tag.$html;
    }

    protected function mimeTypeFor(string $file): string
    {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        $map = [
            'html' => 'text/html; charset=UTF-8',
            'htm' => 'text/html; charset=UTF-8',
            'css' => 'text/css; charset=UTF-8',
            'js' => 'text/javascript; charset=UTF-8',
            'mjs' => 'text/javascript; charset=UTF-8',
            'json' => 'application/json; charset=UTF-8',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'avif' => 'image/avif',
            'ico' => 'image/x-icon',
            'mp4' => 'video/mp4',
            'webm' => 'video/webm',
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'txt' => 'text/plain; charset=UTF-8',
            'xml' => 'application/xml; charset=UTF-8',
            'pdf' => 'application/pdf',
        ];

        if (isset($map[$extension])) {
            return $map[$extension];
        }

        $guessed = (new MimeTypes)->getMimeTypes($extension)[0] ?? null;

        return $guessed ?: 'application/octet-stream';
    }
}
