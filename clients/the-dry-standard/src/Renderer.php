<?php

namespace DryStandard;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

final class Renderer
{
    public function __construct(private readonly SiteConfig $config) {}

    /**
     * @param  array<string, mixed>  $options
     */
    public function document(string $title, string $description, string $path, string $body, array $options = []): string
    {
        $canonical = $this->config->canonicalUrl($path);
        $siteName = Str::e($this->config->name());
        $fullTitle = $path === '' ? $title : $title.' — '.$this->config->name();
        $ogType = Str::e((string) ($options['og_type'] ?? 'website'));
        $jsonLd = (string) ($options['json_ld'] ?? '');
        $extraHead = (string) ($options['head'] ?? '');
        $current = $this->navKey((string) ($options['nav'] ?? $path));
        $image = (string) ($options['image'] ?? '');
        $ogImage = '';

        if ($image !== '') {
            $imageUrl = $this->e($this->config->canonicalUrl($image));
            $ogImage = <<<HTML
  <meta property="og:image" content="{$imageUrl}">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:image" content="{$imageUrl}">
HTML;
        } else {
            $ogImage = '  <meta name="twitter:card" content="summary">';
        }

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{$this->e($fullTitle)}</title>
  <meta name="description" content="{$this->e($description)}">
  <link rel="canonical" href="{$this->e($canonical)}">
  <meta property="og:site_name" content="{$siteName}">
  <meta property="og:title" content="{$this->e($title)}">
  <meta property="og:description" content="{$this->e($description)}">
  <meta property="og:type" content="{$ogType}">
  <meta property="og:url" content="{$this->e($canonical)}">
  {$ogImage}
  <meta name="twitter:title" content="{$this->e($title)}">
  <meta name="twitter:description" content="{$this->e($description)}">
  <link rel="alternate" type="application/atom+xml" title="{$siteName} reviews" href="{$this->url('feed.xml')}">
  <link rel="icon" href="{$this->url('mark.svg')}" type="image/svg+xml">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,500&family=IBM+Plex+Sans:wght@400;500&family=Source+Serif+4:ital,opsz,wght@0,8..60,400;0,8..60,600;1,8..60,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{$this->url('styles.css')}?v=6">
  {$extraHead}
  {$jsonLd}
</head>
<body>
  <a class="skip-link" href="#main">Skip to content</a>
  {$this->header($current)}
  <main id="main">
    {$body}
  </main>
  {$this->footer()}
  <script src="{$this->url('script.js')}?v=3" defer></script>
</body>
</html>
HTML;
    }

    /**
     * @param  Collection<int, Review>  $reviews
     * @param  Collection<int, PageDocument>  $guides
     * @param  Collection<int, PageDocument>  $methods
     */
    public function home(Collection $reviews, Collection $guides, Collection $methods): string
    {
        $latest = $reviews->take(4);
        $highlyRated = $reviews->filter(fn (Review $review): bool => ($review->rating ?? 0) >= 85)
            ->sortByDesc(fn (Review $review): int => $review->rating ?? 0)
            ->take(3);
        $byCategory = [];

        foreach ($this->config->categories() as $category) {
            $byCategory[$category] = $reviews
                ->filter(fn (Review $review): bool => $review->category === $category)
                ->take(3);
        }

        $latestCards = $this->reviewCards($latest, ledger: true);
        $ratedCards = $this->reviewCards($highlyRated, ledger: true);
        $categoryBlocks = '';

        foreach ($byCategory as $category => $items) {
            if ($items->isEmpty()) {
                continue;
            }

            $label = Str::e($this->config->categoryLabel($category));
            $href = $this->url('reviews/'.$category.'/');
            $cards = $this->reviewCards($items, compact: true);
            $categoryBlocks .= <<<HTML
        <section class="stack">
          <div class="section-head">
            <h3>{$label}</h3>
            <a class="text-link" href="{$href}">All {$label}</a>
          </div>
          <div class="card-grid card-grid--compact">{$cards}</div>
        </section>
HTML;
        }

        $guideCards = $guides->take(3)->map(function (PageDocument $guide): string {
            return <<<HTML
        <article class="text-card">
          <p class="kicker">Guide</p>
          <h3><a href="{$this->url('guides/'.$guide->slug.'/')}">{$this->e($guide->title)}</a></h3>
          <p>{$this->e($guide->summary)}</p>
        </article>
HTML;
        })->implode('');

        $methodCards = $methods->take(4)->map(function (PageDocument $method): string {
            return <<<HTML
        <article class="text-card">
          <p class="kicker">Method</p>
          <h3><a href="{$this->url('methods/'.$method->slug.'/')}">{$this->e($method->title)}</a></h3>
          <p>{$this->e($method->summary)}</p>
        </article>
HTML;
        })->implode('');

        $mission = Str::e($this->config->string('site.mission'));
        $tagline = Str::e($this->config->tagline());

        $body = <<<HTML
    <section class="hero">
      <div class="shell hero-inner">
        <p class="kicker">Independent reviews</p>
        <h1>The standard for what remains after the alcohol is gone.</h1>
        <p class="lede">{$tagline} We review beverages at 0.5% ABV or less, and we separate products that were actually dealcoholized from those formulated to imitate a drink.</p>
        <div class="hero-actions">
          <a class="btn" href="{$this->url('reviews/')}">Read the reviews</a>
          <a class="btn btn--ghost" href="{$this->url('about/')}">Editorial method</a>
        </div>
      </div>
    </section>
    <section class="band">
      <div class="shell">
        <p class="mission">{$mission}</p>
      </div>
    </section>
    <section class="section">
      <div class="shell stack">
        <div class="section-head">
          <div>
            <p class="kicker">Latest Reviews</p>
            <h2>Recently reviewed</h2>
          </div>
          <a class="text-link" href="{$this->url('reviews/')}">All reviews</a>
        </div>
        <div class="ledger">{$latestCards}</div>
      </div>
    </section>
    <section class="section section--paper">
      <div class="shell stack-lg">
        <div class="section-head">
          <div>
            <p class="kicker">By category</p>
            <h2>Wine, beer, spirits, and the rest of the cellar</h2>
          </div>
        </div>
        {$categoryBlocks}
      </div>
    </section>
    <section class="section">
      <div class="shell stack">
        <div class="section-head">
          <div>
            <p class="kicker">Highly Rated</p>
            <h2>What holds up in the glass</h2>
          </div>
        </div>
        <div class="ledger">{$ratedCards}</div>
      </div>
    </section>
    <section class="section section--ink">
      <div class="shell stack">
        <div class="section-head">
          <div>
            <p class="kicker">How it's made</p>
            <h2>The techniques that remove alcohol — and the ones that never put it in</h2>
          </div>
          <a class="text-link" href="{$this->url('methods/')}">Method index</a>
        </div>
        <div class="card-grid card-grid--compact">{$methodCards}</div>
      </div>
    </section>
    <section class="section">
      <div class="shell stack">
        <div class="section-head">
          <div>
            <p class="kicker">Guides</p>
            <h2>How to read a bottle before you buy it</h2>
          </div>
          <a class="text-link" href="{$this->url('guides/')}">All guides</a>
        </div>
        <div class="card-grid card-grid--compact">{$guideCards}</div>
      </div>
    </section>
HTML;

        return $this->document(
            $this->config->name(),
            $this->config->string('site.description'),
            '',
            $body,
            [
                'nav' => 'home',
                'json_ld' => $this->jsonLd([
                    $this->websiteGraph(),
                    [
                        '@type' => 'WebPage',
                        '@id' => $this->config->canonicalUrl().'#webpage',
                        'url' => $this->config->canonicalUrl(),
                        'name' => $this->config->name(),
                        'description' => $this->config->string('site.description'),
                        'isPartOf' => ['@id' => $this->config->canonicalUrl().'#website'],
                    ],
                ]),
            ],
        );
    }

    /**
     * @param  Collection<int, Review>  $reviews
     * @param  array<int, array{label: string, url: string}>  $crumbs
     */
    public function listing(
        string $title,
        string $description,
        string $path,
        Collection $reviews,
        array $crumbs,
        string $nav = 'reviews',
        bool $filterable = false,
        ?string $lockedCategory = null,
    ): string {
        $empty = '<p class="empty" data-archive-empty>No published reviews in this section yet. Products can sit in the queue until the facts are good enough to print.</p>';
        $list = $reviews->isEmpty()
            ? $empty
            : '<div class="ledger" data-review-grid>'.$this->reviewCards($reviews, ledger: true).'</div>'
                .'<p class="empty" data-archive-empty hidden>No reviews match those filters.</p>';

        $tools = $filterable && $reviews->isNotEmpty() ? $this->archiveTools($reviews, $lockedCategory) : '';

        $body = <<<HTML
    <header class="page-header">
      <div class="shell">
        {$this->breadcrumbs($crumbs)}
        <p class="kicker">The cellar</p>
        <h1>{$this->e($title)}</h1>
        <p class="lede">{$this->e($description)}</p>
        {$tools}
      </div>
    </header>
    <section class="section section--tight">
      <div class="shell">{$list}</div>
    </section>
HTML;

        return $this->document($title, $description, $path, $body, [
            'nav' => $nav,
            'json_ld' => $this->jsonLd([
                $this->breadcrumbGraph($crumbs),
                [
                    '@type' => 'CollectionPage',
                    'name' => $title,
                    'description' => $description,
                    'url' => $this->config->canonicalUrl($path),
                ],
            ]),
        ]);
    }

    /**
     * @param  array<int, array{label: string, url: string}>  $crumbs
     */
    public function articlePage(
        PageDocument $page,
        string $path,
        array $crumbs,
        string $kicker,
        string $nav,
    ): string {
        $description = $page->summary !== '' ? $page->summary : $this->config->string('site.description');

        $body = <<<HTML
    <article class="article">
      <header class="page-header">
        <div class="shell shell--narrow">
          {$this->breadcrumbs($crumbs)}
          <p class="kicker">{$this->e($kicker)}</p>
          <h1>{$this->e($page->title)}</h1>
          <p class="lede">{$this->e($page->summary)}</p>
        </div>
      </header>
      <div class="section">
        <div class="shell shell--narrow prose">
          {$page->bodyHtml}
        </div>
      </div>
    </article>
HTML;

        return $this->document($page->title, $description, $path, $body, [
            'nav' => $nav,
            'og_type' => 'article',
            'json_ld' => $this->jsonLd([
                $this->breadcrumbGraph($crumbs),
                [
                    '@type' => 'Article',
                    'headline' => $page->title,
                    'description' => $description,
                    'url' => $this->config->canonicalUrl($path),
                    'author' => [
                        '@type' => 'Organization',
                        'name' => $this->config->name(),
                    ],
                ],
            ]),
        ]);
    }

    /**
     * @param  Collection<int, Review>  $brandReviews
     * @param  array<int, array{label: string, url: string}>  $crumbs
     */
    public function brandPage(string $name, Collection $brandReviews, string $path, array $crumbs): string
    {
        $description = 'Reviews of dealcoholized and non-alcoholic products from '.$name.'.';
        $cards = $this->reviewCards($brandReviews, ledger: true);

        $body = <<<HTML
    <header class="page-header">
      <div class="shell">
        {$this->breadcrumbs($crumbs)}
        <p class="kicker">Brand</p>
        <h1>{$this->e($name)}</h1>
        <p class="lede">{$this->e($description)}</p>
      </div>
    </header>
    <section class="section">
      <div class="shell">
        <div class="ledger">{$cards}</div>
      </div>
    </section>
HTML;

        return $this->document($name, $description, $path, $body, [
            'nav' => 'brands',
            'json_ld' => $this->jsonLd([
                $this->breadcrumbGraph($crumbs),
                [
                    '@type' => 'Brand',
                    'name' => $name,
                    'url' => $this->config->canonicalUrl($path),
                ],
            ]),
        ]);
    }

    /**
     * @param  array<int, array{label: string, url: string}>  $crumbs
     */
    public function review(Review $review, array $crumbs): string
    {
        $facts = $this->facts($review);
        $tasting = $this->tasting($review);
        $sources = $this->sources($review);
        $discrepancies = $this->discrepancies($review);
        $score = $review->rating !== null
            ? '<p class="score" aria-label="Score '.$review->rating.' out of 100"><span>'.$review->rating.'</span><small>/100</small></p>'
            : '';
        $bodyHtml = Markdown::toHtml($review->bodyMarkdown);
        $overview = $bodyHtml !== '' ? '<section class="prose"><h2>Product overview</h2>'.$bodyHtml.'</section>' : '';
        $links = $this->purchaseLinks($review);
        $badgeClass = match ($review->dealcoholized) {
            'yes' => 'badge badge--yes',
            'no' => 'badge badge--no',
            default => 'badge badge--unknown',
        };
        $figure = $this->productFigure($review, 'product-figure product-figure--hero', hero: true);

        $body = <<<HTML
    <article class="review" data-review>
      <header class="page-header">
        <div class="shell">
          {$this->breadcrumbs($crumbs)}
          <p class="kicker">{$this->e($this->config->categoryLabel($review->category))}</p>
          <div class="review-hero">
            {$figure}
            <div>
              <h1>{$this->e($review->title)}</h1>
              <p class="lede">{$this->e($review->summary)}</p>
              <p class="{$badgeClass}">{$this->e($review->dealcoholizedLabel())}</p>
            </div>
            {$score}
          </div>
        </div>
      </header>
      <div class="section">
        <div class="shell review-layout">
          <div class="stack-lg">
            <section class="callout">
              <h2>Is it actually dealcoholized?</h2>
              <p class="callout-status">{$this->e($review->dealcoholizedLabel())}</p>
              {$this->optionalBlock($review->dealcoholizationMethod, 'Method: ')}
              {$this->optionalBlock($review->baseBeverage, 'Base beverage: ')}
              {$discrepancies}
            </section>
            {$overview}
            {$tasting}
            <section class="prose">
              <h2>How to drink it</h2>
              {$this->optionalBlock($review->serve)}
              {$this->optionalBlock($review->bestFor, 'Best for: ')}
            </section>
            <section class="verdict">
              <h2>Verdict</h2>
              <p>{$this->e($review->verdict)}</p>
            </section>
            {$sources}
          </div>
          <aside class="facts" aria-label="Product facts">
            <h2>Product facts</h2>
            {$facts}
            {$links}
            <p class="fine-print">Editorial tasting notes are opinion. Production facts are printed only when a source is attached.</p>
          </aside>
        </div>
      </div>
    </article>
HTML;

        return $this->document(
            $review->title,
            $review->summary,
            $review->path(),
            $body,
            [
                'nav' => 'reviews',
                'og_type' => 'article',
                'image' => $review->imageSrc() ?? '',
                'json_ld' => $this->jsonLd([
                    $this->breadcrumbGraph($crumbs),
                    $this->articleGraph($review),
                    $this->productGraph($review),
                ]),
            ],
        );
    }

    /**
     * @param  Collection<int, Review>  $reviews
     */
    public function sitemap(Collection $reviews, Collection $guides, Collection $methods, Collection $brands): string
    {
        $urls = [
            $this->sitemapUrl('', 'weekly', '1.0'),
            $this->sitemapUrl('reviews/', 'weekly', '0.8'),
            $this->sitemapUrl('guides/', 'monthly', '0.6'),
            $this->sitemapUrl('brands/', 'weekly', '0.6'),
            $this->sitemapUrl('methods/', 'monthly', '0.6'),
            $this->sitemapUrl('about/', 'monthly', '0.5'),
        ];

        foreach ($this->config->categories() as $category) {
            $urls[] = $this->sitemapUrl('reviews/'.$category.'/', 'weekly', '0.7');
        }

        foreach ($reviews as $review) {
            $urls[] = $this->sitemapUrl($review->path(), 'monthly', '0.8', $review->modifiedAt()->toDateString());
        }

        foreach ($guides as $guide) {
            $urls[] = $this->sitemapUrl('guides/'.$guide->slug.'/', 'monthly', '0.6');
        }

        foreach ($methods as $method) {
            $urls[] = $this->sitemapUrl('methods/'.$method->slug.'/', 'monthly', '0.6');
        }

        foreach ($brands as $brand) {
            $urls[] = $this->sitemapUrl('brands/'.$brand['slug'].'/', 'weekly', '0.5');
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
    <author><name>{$this->config->name()}</name></author>
    <category term="{$review->category}"/>
    <summary>{$summary}</summary>
  </entry>
XML;
        })->implode("\n");

        $home = $this->config->canonicalUrl();
        $feed = $this->config->canonicalUrl('feed.xml');
        $name = Str::xml($this->config->name());

        return <<<XML
<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title>{$name}</title>
  <subtitle>{$this->xml($this->config->string('site.description'))}</subtitle>
  <link rel="alternate" type="text/html" href="{$home}"/>
  <link rel="self" type="application/atom+xml" href="{$feed}"/>
  <id>{$feed}</id>
  <updated>{$updated}</updated>
  <author><name>{$name}</name></author>
{$entries}
</feed>
XML;
    }

    private function header(string $current): string
    {
        $links = [
            'reviews' => ['Reviews', 'reviews/'],
            'guides' => ['Guides', 'guides/'],
            'brands' => ['Brands', 'brands/'],
            'methods' => ['Methods', 'methods/'],
            'about' => ['About', 'about/'],
        ];

        $items = '';
        foreach ($links as $key => [$label, $path]) {
            $currentAttr = $current === $key ? ' aria-current="page"' : '';
            $items .= '<a href="'.$this->url($path).'"'.$currentAttr.'>'.Str::e($label).'</a>';
        }

        $homeCurrent = $current === 'home' ? ' aria-current="page"' : '';

        return <<<HTML
  <header class="site-header" data-header>
    <div class="header-inner">
      <a class="logo" href="{$this->url()}"{$homeCurrent}>
        <img src="{$this->url('mark.svg')}" alt="" width="18" height="18">
        <span>The Dry Standard</span>
      </a>
      <form class="header-search" action="{$this->url('reviews/')}" method="get" role="search">
        <label class="visually-hidden" for="header-q">Search reviews</label>
        <input id="header-q" type="search" name="q" placeholder="Search the cellar">
      </form>
      <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
        <span class="nav-toggle-bar"></span>
        <span class="visually-hidden">Menu</span>
      </button>
      <nav class="site-nav" id="site-nav" data-nav aria-label="Primary">{$items}</nav>
    </div>
  </header>
HTML;
    }

    private function footer(): string
    {
        return <<<HTML
  <footer class="site-footer">
    <div class="shell footer-grid">
      <div>
        <p class="logo-text">The Dry Standard</p>
        <p>Independent reviews of dealcoholized beer, wine, spirits, and cocktails at 0.5% ABV or less.</p>
      </div>
      <div>
        <p class="footer-label">Read</p>
        <a href="{$this->url('reviews/')}">Reviews</a>
        <a href="{$this->url('guides/')}">Guides</a>
        <a href="{$this->url('methods/')}">Methods</a>
      </div>
      <div>
        <p class="footer-label">The desk</p>
        <a href="{$this->url('about/')}">About &amp; methodology</a>
        <a href="{$this->url('brands/')}">Brands</a>
        <a href="{$this->url('feed.xml')}">RSS</a>
      </div>
    </div>
    <p class="copyright">© <span data-year></span> The Dry Standard. Staging preview on karlhill.com.</p>
  </footer>
HTML;
    }

    /**
     * @param  Collection<int, Review>  $reviews
     */
    private function archiveTools(Collection $reviews, ?string $lockedCategory): string
    {
        $categoryOptions = '<option value="">All categories</option>';
        foreach ($this->config->categories() as $category) {
            $selected = $lockedCategory === $category ? ' selected' : '';
            $categoryOptions .= '<option value="'.Str::e($category).'"'.$selected.'>'.Str::e($this->config->categoryLabel($category)).'</option>';
        }

        $methods = $reviews
            ->map(fn (Review $review): ?string => $review->methodKey())
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $methodOptions = '<option value="">All methods</option>';
        foreach ($methods as $method) {
            $methodOptions .= '<option value="'.Str::e((string) $method).'">'.Str::e($this->config->methodLabel((string) $method)).'</option>';
        }

        $locked = $lockedCategory !== null ? ' data-locked-category="'.Str::e($lockedCategory).'"' : '';
        $categoryDisabled = $lockedCategory !== null ? ' disabled' : '';

        return <<<HTML
        <form class="archive-tools" data-archive{$locked} role="search">
          <label class="visually-hidden" for="archive-q">Search reviews</label>
          <input id="archive-q" type="search" name="q" placeholder="Search brand, product, origin, or method" data-archive-q>
          <div class="archive-controls">
            <label>Category
              <select name="category" data-archive-category{$categoryDisabled}>{$categoryOptions}</select>
            </label>
            <label>Process
              <select name="dealcoholized" data-archive-dealcoholized>
                <option value="">All processes</option>
                <option value="yes">Dealcoholized</option>
                <option value="no">Formulated</option>
                <option value="not-verified">Not verified</option>
              </select>
            </label>
            <label>Method
              <select name="method" data-archive-method>{$methodOptions}</select>
            </label>
            <label>Sort
              <select name="sort" data-archive-sort>
                <option value="newest">Newest</option>
                <option value="rating">Highest rated</option>
                <option value="title">Name</option>
              </select>
            </label>
          </div>
          <p class="archive-count" data-archive-count></p>
        </form>
HTML;
    }

    private function reviewCards(Collection $reviews, bool $compact = false, bool $ledger = false): string
    {
        return $reviews->map(function (Review $review) use ($compact, $ledger): string {
            $score = $review->rating !== null ? '<span class="card-score">'.$review->rating.'</span>' : '';
            $meta = trim($this->config->categoryLabel($review->category).($review->originLabel() ? ' · '.$review->originLabel() : ''));
            $method = $review->dealcoholizationMethod ?? 'Method unpublished';
            $badge = '<span class="badge badge--'.Str::e($review->dealcoholized).'">'.Str::e($review->dealcoholizedLabel()).'</span>';
            $attrs = implode(' ', [
                'data-dealcoholized="'.Str::e($review->dealcoholized).'"',
                'data-category="'.Str::e($review->category).'"',
                'data-brand="'.Str::e(Str::slug($review->brand)).'"',
                'data-method="'.Str::e($review->methodKey() ?? '').'"',
                'data-rating="'.Str::e((string) ($review->rating ?? 0)).'"',
                'data-date="'.Str::e($review->reviewDate->toDateString()).'"',
                'data-search="'.Str::e($review->searchText()).'"',
            ]);
            $compactClass = $compact ? ' card--compact' : '';

            $thumb = $this->productFigure($review, 'product-figure product-figure--thumb');

            if ($ledger) {
                return <<<HTML
      <article class="ledger-row" {$attrs}>
        {$thumb}
        <div>
          <p class="ledger-brand">{$this->e($review->brand)}</p>
          <h3><a href="{$this->url($review->path())}">{$this->e($review->title)}</a></h3>
          <p class="ledger-meta">{$this->e($meta)} · {$this->e($method)}</p>
          {$badge}
        </div>
        {$score}
      </article>
HTML;
            }

            return <<<HTML
      <article class="card{$compactClass}" {$attrs}>
        {$thumb}
        <div class="card-top">
          <p class="card-meta">{$this->e($meta)}</p>
          {$score}
        </div>
        <h3><a href="{$this->url($review->path())}">{$this->e($review->title)}</a></h3>
        <p>{$this->e($review->summary)}</p>
        {$badge}
      </article>
HTML;
        })->implode('');
    }

    private function facts(Review $review): string
    {
        $rows = [
            'ABV' => $review->abv,
            'Origin' => $review->originLabel(),
            'Category' => $this->config->categoryLabel($review->category).($review->subcategory ? ' / '.$review->subcategory : ''),
            'Style' => $review->style,
            'Producer' => $review->producer,
            'Production method' => $review->dealcoholizationMethod,
            'Base beverage' => $review->baseBeverage,
            'Bottle / can' => $review->volume,
            'Typical price' => $review->price,
            'Ingredients' => $review->ingredients,
            'Calories' => $review->calories,
            'Sugar' => $review->sugar,
        ];

        $html = '<dl>';
        foreach ($rows as $label => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            $html .= '<div><dt>'.Str::e($label).'</dt><dd>'.Str::e($value).'</dd></div>';
        }

        return $html.'</dl>';
    }

    private function tasting(Review $review): string
    {
        $parts = [
            'Nose' => $review->nose,
            'Palate' => $review->palate,
            'Finish' => $review->finish,
        ];

        $items = '';
        foreach ($parts as $label => $value) {
            if ($value === null) {
                continue;
            }
            $items .= '<div><h3>'.Str::e($label).'</h3><p>'.Str::e($value).'</p></div>';
        }

        if ($items === '') {
            return '';
        }

        return '<section class="tasting"><h2>Tasting notes</h2><div class="tasting-grid">'.$items.'</div></section>';
    }

    private function sources(Review $review): string
    {
        if ($review->sources === []) {
            return '';
        }

        $items = '';
        foreach ($review->sources as $source) {
            $claims = $source['claims'] === [] ? '' : '<span class="source-claims">'.Str::e(implode(', ', $source['claims'])).'</span>';
            $items .= '<li><a href="'.Str::e($source['url']).'" rel="nofollow noopener">'.Str::e($source['title']).'</a>'.$claims.'</li>';
        }

        return '<section class="sources"><h2>Sources</h2><ol>'.$items.'</ol></section>';
    }

    private function discrepancies(Review $review): string
    {
        if ($review->discrepancies === []) {
            return '';
        }

        $items = '';
        foreach ($review->discrepancies as $row) {
            $items .= '<li><strong>'.Str::e(ucfirst($row['field'])).':</strong> '.Str::e($row['note']).'</li>';
        }

        return '<div class="discrepancies"><p>Sources disagree on the following points. We do not pick a winner.</p><ul>'.$items.'</ul></div>';
    }

    private function purchaseLinks(Review $review): string
    {
        if ($review->purchaseLinks === []) {
            return $review->availability
                ? '<p><strong>Where to buy:</strong> '.Str::e($review->availability).'</p>'
                : '';
        }

        $items = '';
        foreach ($review->purchaseLinks as $link) {
            $items .= '<li><a href="'.Str::e($link['url']).'" rel="nofollow noopener">'.Str::e($link['label']).'</a></li>';
        }

        $availability = $review->availability
            ? '<p>'.Str::e($review->availability).'</p>'
            : '';

        return '<div class="buy"><h3>Where to buy in the United States</h3>'.$availability.'<ul>'.$items.'</ul></div>';
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

        return '<nav class="breadcrumbs" aria-label="Breadcrumb"><ol>'.$items.'</ol></nav>';
    }

    /**
     * @param  array<int, array<string, mixed>>  $graph
     */
    private function jsonLd(array $graph): string
    {
        $payload = [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ];

        return '<script type="application/ld+json">'.json_encode(
            $payload,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR,
        ).'</script>';
    }

    /**
     * @return array<string, mixed>
     */
    private function websiteGraph(): array
    {
        return [
            '@type' => 'WebSite',
            '@id' => $this->config->canonicalUrl().'#website',
            'name' => $this->config->name(),
            'url' => $this->config->canonicalUrl(),
            'description' => $this->config->string('site.description'),
        ];
    }

    /**
     * @param  array<int, array{label: string, url: string}>  $crumbs
     * @return array<string, mixed>
     */
    private function breadcrumbGraph(array $crumbs): array
    {
        $items = [];

        foreach (array_values($crumbs) as $index => $crumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $crumb['label'],
                'item' => $this->config->canonicalUrl(ltrim($crumb['url'] ?? '', '/')),
            ];
        }

        return [
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function articleGraph(Review $review): array
    {
        return array_filter([
            '@type' => 'Review',
            'headline' => $review->title,
            'name' => $review->title,
            'description' => $review->summary,
            'url' => $this->config->canonicalUrl($review->path()),
            'datePublished' => $review->reviewDate->toDateString(),
            'dateModified' => $review->modifiedAt()->toDateString(),
            'author' => [
                '@type' => 'Organization',
                'name' => $this->config->name(),
            ],
            'reviewRating' => $review->rating === null ? null : [
                '@type' => 'Rating',
                'ratingValue' => $review->rating,
                'bestRating' => 100,
                'worstRating' => 0,
            ],
            'itemReviewed' => array_filter([
                '@type' => 'Product',
                'name' => $review->product,
                'brand' => [
                    '@type' => 'Brand',
                    'name' => $review->brand,
                ],
                'image' => $review->imageSrc() ? $this->config->canonicalUrl($review->imageSrc()) : null,
            ]),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function productGraph(Review $review): array
    {
        return array_filter([
            '@type' => 'Product',
            'name' => $review->title,
            'brand' => [
                '@type' => 'Brand',
                'name' => $review->brand,
            ],
            'category' => $this->config->categoryLabel($review->category),
            'description' => $review->summary,
            'image' => $review->imageSrc() ? $this->config->canonicalUrl($review->imageSrc()) : null,
            'alcoholWarning' => $review->abv,
        ], fn (mixed $value): bool => $value !== null && $value !== '');
    }

    private function productFigure(Review $review, string $class, bool $hero = false): string
    {
        $src = $review->imageSrc();

        if ($src === null) {
            return '<div class="'.Str::e($class).' product-figure--empty" aria-hidden="true"></div>';
        }

        $credit = $hero && $review->imageCredit !== null
            ? '<figcaption>'.$this->e($review->imageCredit).'</figcaption>'
            : '';
        $loading = $hero ? 'eager' : 'lazy';

        return <<<HTML
        <figure class="{$this->e($class)}">
          <img src="{$this->url($src)}" alt="{$this->e($review->imageAltText())}" width="720" height="960" loading="{$loading}">
          {$credit}
        </figure>
HTML;
    }

    private function optionalBlock(?string $value, string $prefix = ''): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return '<p>'.Str::e($prefix.$value).'</p>';
    }

    private function sitemapUrl(string $path, string $freq, string $priority, ?string $lastmod = null): string
    {
        $loc = Str::xml($this->config->canonicalUrl($path));
        $last = $lastmod ? '<lastmod>'.Str::xml($lastmod).'</lastmod>' : '';

        return "  <url><loc>{$loc}</loc>{$last}<changefreq>{$freq}</changefreq><priority>{$priority}</priority></url>";
    }

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

    private function navKey(string $path): string
    {
        $path = trim($path, '/');

        if ($path === '' || $path === 'home') {
            return 'home';
        }

        return explode('/', $path)[0];
    }
}
