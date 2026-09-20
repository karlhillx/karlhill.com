<?php

namespace DryStandard\Rendering\Concerns;

use DryStandard\Collections;
use DryStandard\PageDocument;
use DryStandard\Paths;
use DryStandard\Rendering\StructuredData;
use DryStandard\Review;
use DryStandard\Routing\RouteTable;
use DryStandard\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Vite;

trait BuildsDocuments
{
    private function assetVersion(string $relative): string
    {
        $absolute = Paths::default()->path($relative);

        return is_file($absolute) ? (string) filemtime($absolute) : '1';
    }

    /**
     * Prefer hashed Vite build assets; fall back to client-folder files for local without npm build.
     *
     * @return array{href: string, module: bool}
     */
    private function frontAsset(string $viteEntry, string $fallbackRelative): array
    {
        if (is_file(public_path('build/manifest.json'))) {
            try {
                return [
                    'href' => (string) Vite::asset($viteEntry),
                    'module' => str_ends_with($viteEntry, '.js'),
                ];
            } catch (\Throwable) {
                // Fall through to the client-folder asset.
            }
        }

        return [
            'href' => $this->config->publicUrl($fallbackRelative).'?v='.$this->assetVersion($fallbackRelative),
            'module' => false,
        ];
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function document(string $title, string $description, string $path, string $body, array $options = []): string
    {
        $canonical = $this->config->canonicalUrl($path);
        $ogType = (string) ($options['og_type'] ?? 'website');
        $image = (string) ($options['image'] ?? $this->defaultShareImage());
        $ogImage = $image === ''
            ? '  <meta name="twitter:card" content="summary">'
            : <<<HTML
  <meta property="og:image" content="{$this->e($this->config->canonicalUrl($image))}">
  <meta property="og:locale" content="en_US">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:image" content="{$this->e($this->config->canonicalUrl($image))}">
HTML;

        $stylesheet = $this->frontAsset(
            'clients/the-dry-standard/assets/css/site.css',
            'styles.css',
        );
        $script = $this->frontAsset(
            'clients/the-dry-standard/assets/js/site.js',
            'script.js',
        );

        return $this->view->render('layout', [
            'title' => $title,
            'fullTitle' => $path === '' ? $title : $title.' — '.$this->config->name(),
            'description' => $description,
            'canonical' => $canonical,
            'siteName' => $this->config->name(),
            'ogType' => $ogType,
            'ogImage' => $ogImage,
            'feedUrl' => $this->config->publicUrl('feed.xml'),
            'iconUrl' => $this->config->publicUrl('mark.svg'),
            'fontDisplay' => $this->config->publicUrl('fonts/fraunces.woff2'),
            'fontSans' => $this->config->publicUrl('fonts/figtree.woff2'),
            'stylesheet' => $stylesheet['href'],
            'script' => $script['href'],
            'scriptModule' => $script['module'],
            'baseHref' => $this->config->basePath() === '' ? '' : $this->config->basePath().'/',
            'pageUrl' => $this->config->publicUrl($path),
            'analyticsEnabled' => $this->config->bool('analytics.enabled', true),
            'extraHead' => (string) ($options['head'] ?? ''),
            'jsonLd' => (string) ($options['json_ld'] ?? ''),
            'bodyClass' => (string) ($options['body_class'] ?? ''),
            'bodyAttrs' => (string) ($options['body_attrs'] ?? ''),
            'header' => $this->header($this->navKey((string) ($options['nav'] ?? $path))),
            'footer' => $this->footer(),
            'body' => $body,
        ]);
    }

    public function notFound(): string
    {
        $items = [];
        foreach ($this->config->categories() as $category) {
            $items[] = [
                'href' => $this->config->publicUrl('reviews/'.$category.'/'),
                'label' => $this->config->categoryLabel($category),
            ];
        }

        $body = $this->view->render('not-found', [
            'reviewsUrl' => $this->config->publicUrl('reviews/'),
            'categoryRail' => $this->view->render('partials/category-rail', [
                'label' => 'Browse by category',
                'items' => $items,
            ]),
        ]);

        return $this->document(
            'Page not found',
            'That address is not a published review, guide, or method on The Dry Standard.',
            '404/',
            $body,
            ['nav' => 'home'],
        );
    }

    /**
     * @param  Collection<int, PageDocument>  $methods
     */
    private function header(string $current): string
    {
        // Primary chrome only — Compare stays tray/URL; Brands/Styles/Methods/Collections live in cellar + footer.
        $links = [];
        foreach (RouteTable::primaryNav() as $item) {
            $links[$item['key']] = [$item['label'], $item['path']];
        }

        $items = '';
        foreach ($links as $key => [$label, $path]) {
            $currentAttr = $current === $key || ($key === 'guides' && in_array($current, ['learn', 'methods'], true))
                ? ' aria-current="page"'
                : '';
            $items .= '<a href="'.$this->url($path).'"'.$currentAttr.'>'.Str::e($label).'</a>';
        }

        return $this->view->render('partials/header', [
            'homeUrl' => $this->config->publicUrl(),
            'homeCurrent' => $current === 'home',
            'markUrl' => $this->config->publicUrl('mark.svg'),
            'searchUrl' => $this->config->publicUrl('reviews/'),
            'items' => $items,
            'isBeta' => $this->config->isBeta(),
            'betaNote' => $this->config->betaNote(),
        ]);
    }

    private function footer(): string
    {
        $categories = [];
        foreach ($this->config->categories() as $category) {
            $categories[] = [
                'href' => $this->config->publicUrl('reviews/'.$category.'/'),
                'label' => $this->config->categoryLabel($category),
            ];
        }

        return $this->view->render('partials/footer', [
            'reviewsUrl' => $this->config->publicUrl('reviews/'),
            'guidesUrl' => $this->config->publicUrl('learn/'),
            'methodsUrl' => $this->config->publicUrl('methods/'),
            'brandsUrl' => $this->config->publicUrl('brands/'),
            'aboutUrl' => $this->config->publicUrl('about/'),
            'methodologyUrl' => $this->config->publicUrl('methodology/'),
            'collectionsUrl' => $this->config->publicUrl('collections/'),
            'privacyUrl' => $this->config->publicUrl('privacy/'),
            'feedUrl' => $this->config->publicUrl('feed.xml'),
            'bestUrl' => $this->config->publicUrl('best/'),
            'compareUrl' => $this->config->publicUrl('compare/'),
            'stylesUrl' => $this->config->publicUrl('styles/'),
            'industryUrl' => $this->config->publicUrl('industry/'),
            'submitUrl' => $this->config->publicUrl('industry/submit/'),
            'partnershipsUrl' => $this->config->publicUrl('industry/partnerships/'),
            'year' => (string) now()->year,
            'categories' => $categories,
            'editorEmail' => $this->config->editorEmail(),
            'editorMailto' => $this->config->editorMailto(),
            'isBeta' => $this->config->isBeta(),
            'betaNote' => $this->config->betaNote(),
        ]);
    }

    /**
     * @param  Collection<int, Review>  $reviews
     */
    private function navKey(string $path): string
    {
        $path = trim($path, '/');

        if ($path === '' || $path === 'home') {
            return 'home';
        }

        return explode('/', $path)[0];
    }

    /**
     * @param  array{csrf: string, errors: array<string, string>, old: array<string, mixed>, sent: bool, failed: bool}  $form
     */
    public function crumbs(array $items): array
    {
        $crumbs = [['label' => 'Home', 'url' => '']];

        foreach ($items as $label => $url) {
            $crumbs[] = ['label' => $label, 'url' => $url];
        }

        return $crumbs;
    }

    /**
     * @param  array<int, array{label: string, url: string}>  $crumbs
     */
    private function breadcrumbs(array $crumbs): string
    {
        $items = '';
        $last = array_key_last($crumbs);

        foreach ($crumbs as $index => $crumb) {
            if ($index === $last) {
                $items .= '<li><span aria-current="page">'.Str::e($crumb['label']).'</span></li>';
            } else {
                $items .= '<li><a href="'.Str::e($this->config->publicUrl(ltrim((string) ($crumb['url'] ?? ''), '/'))).'">'.Str::e($crumb['label']).'</a></li>';
            }
        }

        return $this->view->render('partials/breadcrumbs', ['items' => $items]);
    }

    /**
     * @param  array<int, array<string, mixed>>  $graph
     */
    private function jsonLd(array $graph): string
    {
        return (new StructuredData($this->config))->script($graph);
    }

    /**
     * @return array<string, mixed>
     */
    private function websiteGraph(): array
    {
        return (new StructuredData($this->config))->website();
    }

    /**
     * @param  array<int, array{label: string, url: string}>  $crumbs
     * @return array<string, mixed>
     */
    private function breadcrumbGraph(array $crumbs): array
    {
        return (new StructuredData($this->config))->breadcrumbs($crumbs);
    }

    /**
     * @return array<string, mixed>
     */
    private function personGraph(): array
    {
        return (new StructuredData($this->config))->person();
    }

    /**
     * @return array{editorName: string, editorEmail: string, editorMailto: string}
     */
    private function editorViewData(): array
    {
        return [
            'editorName' => $this->config->editorName(),
            'editorEmail' => $this->config->editorEmail(),
            'editorMailto' => $this->config->editorMailto(),
        ];
    }

    private function editorDesk(): string
    {
        return $this->view->render('partials/editor-desk', $this->editorViewData() + [
            'name' => $this->config->editorName(),
            'role' => $this->config->editorRole(),
            'email' => $this->config->editorEmail(),
            'mailto' => $this->config->editorMailto(),
            'location' => $this->config->editorLocation(),
        ]);
    }

    private function defaultShareImage(): string
    {
        foreach (['media/reviews/guinness-0-0.jpg', 'mark.svg'] as $relative) {
            $absolute = Paths::default()->path().DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relative);
            if (is_file($absolute)) {
                return $relative;
            }
        }

        return '';
    }

    /**
     * @param  Collection<int, Review>  $reviews
     */
    private function url(string $path = ''): string
    {
        return Str::e($this->config->publicUrl($path));
    }

    private function e(string $value): string
    {
        return Str::e($value);
    }

    private function xml(string $value): string
    {
        return Str::xml($value);
    }

    /**
     * @param  Collection<int, Review>  $reviews
     */
    private function sitemapUrl(string $path, string $freq, string $priority, ?string $lastmod = null): string
    {
        $loc = Str::xml($this->config->canonicalUrl($path));
        $last = $lastmod ? '<lastmod>'.Str::xml($lastmod).'</lastmod>' : '';

        return "  <url><loc>{$loc}</loc>{$last}<changefreq>{$freq}</changefreq><priority>{$priority}</priority></url>";
    }

    private function srcsetUrls(string $srcset): string
    {
        if ($srcset === '') {
            return '';
        }

        $parts = [];
        foreach (preg_split('/\s*,\s*/', $srcset) ?: [] as $candidate) {
            if (! preg_match('/^(?<path>\S+)\s+(?<width>\d+w)$/', $candidate, $matches)) {
                continue;
            }
            $parts[] = $this->config->publicUrl($matches['path']).' '.$matches['width'];
        }

        return implode(', ', $parts);
    }
}
