<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

/**
 * C2PA-shaped content credentials sidecars (hash + authorship).
 * Full JUMBF embedding is attempted when `c2patool` is on PATH.
 */
final class ContentCredentials
{
    /**
     * @return array<string, mixed>
     */
    public static function forFile(string $absolutePath, string $url): array
    {
        $hash = is_file($absolutePath)
            ? hash_file('sha256', $absolutePath)
            : null;

        return [
            '@context' => 'https://c2pa.org',
            'instance_id' => 'xmp:iid:'.($hash ?? bin2hex(random_bytes(8))),
            'claim_generator' => 'karlhill.com/credentials',
            'alg' => 'sha256',
            'hash' => $hash,
            'bytes' => is_file($absolutePath) ? filesize($absolutePath) : null,
            'url' => $url,
            'author' => [
                'name' => config('site.person.name'),
                'url' => PageMeta::siteUrl(),
                'email' => config('site.person.email'),
            ],
            'assertions' => [
                [
                    'label' => 'c2pa.hash.data',
                    'data' => [
                        'alg' => 'sha256',
                        'hash' => $hash,
                        'pad' => '',
                    ],
                ],
                [
                    'label' => 'stds.schema-org.CreativeWork',
                    'data' => [
                        '@type' => 'CreativeWork',
                        'author' => config('site.person.name'),
                        'copyrightNotice' => '© '.date('Y').' '.config('site.person.name'),
                    ],
                ],
            ],
            'generated_at' => now()->toIso8601String(),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function catalog(): array
    {
        $base = PageMeta::siteUrl();
        $items = [];

        $resumeRel = ltrim((string) config('site.footer.resume'), '/');
        $resumeAbs = public_path($resumeRel);
        $items[] = self::forFile($resumeAbs, $base.'/'.$resumeRel);

        $homeOg = public_path('img/og-home.jpg');
        $items[] = self::forFile($homeOg, $base.'/img/og-home.jpg');

        foreach (app(BlogPostRepository::class)->all() as $post) {
            $og = public_path('img/og/blog/'.$post->slug.'.jpg');
            if (is_file($og)) {
                $items[] = self::forFile($og, $base.'/img/og/blog/'.$post->slug.'.jpg');
            }
        }

        return $items;
    }

    /**
     * @return array<string, mixed>
     */
    public static function document(): array
    {
        return [
            'version' => 1,
            'claim_generator' => 'karlhill.com/credentials',
            'author' => config('site.person.name'),
            'assets' => self::catalog(),
        ];
    }

    public static function persist(): string
    {
        $path = public_path('files/content-credentials.json');
        File::ensureDirectoryExists(dirname($path));
        File::put(
            $path,
            json_encode(self::document(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL,
        );

        $resumeRel = ltrim((string) config('site.footer.resume'), '/');
        $sidecar = public_path($resumeRel.'.c2pa.json');
        $resumeAbs = public_path($resumeRel);
        File::put(
            $sidecar,
            json_encode(self::forFile($resumeAbs, PageMeta::siteUrl().'/'.$resumeRel), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL,
        );

        return $path;
    }

    public static function c2patoolAvailable(): bool
    {
        $which = trim((string) shell_exec('command -v c2patool 2>/dev/null'));

        return $which !== '';
    }
}
