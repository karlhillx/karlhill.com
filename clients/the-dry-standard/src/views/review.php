    <article class="review" data-review data-analytics-view="review_view" data-slug="<?= $view->e($pageUrl ?? '') ?>">
      <header class="page-header page-header--review">
        <div class="shell">
          <?= $breadcrumbs ?>
          <p class="kicker"><?= $view->e($categoryLabel) ?></p>
          <div class="review-hero">
            <?= $figure ?>
            <div class="review-hero-copy">
              <h1><?= $view->e($title) ?></h1>
              <?= $metaLine ?>
              <?php if (! empty($byline)) { ?>
              <p class="review-byline"><?= $byline ?></p>
              <?php } ?>
              <p class="lede"><?= $view->e($summary) ?></p>
              <?= $identity ?? '' ?>
              <?php if (! empty($verdict)) { ?>
              <section class="verdict verdict--glance" id="verdict">
                <h2 class="visually-hidden">Verdict</h2>
                <p><?= $view->e($verdict) ?></p>
              </section>
              <?php } ?>
              <p class="review-jump">
                <a href="<?= $view->e($pageUrl) ?>#tasting">At a glance</a>
                <a href="<?= $view->e($pageUrl) ?>#review-essay">Review</a>
                <?php if (! empty($hasServe)) { ?>
                <a href="<?= $view->e($pageUrl) ?>#how-to-drink">Serve</a>
                <?php } ?>
                <a href="<?= $view->e($pageUrl) ?>#how-it-was-made">How it was made</a>
                <a href="<?= $view->e($pageUrl) ?>#provenance">Sources</a>
                <a href="<?= $view->e($pageUrl) ?>#facts">Facts</a>
                <?php if (! empty($compareHref)) { ?>
                <a href="<?= $view->e($compareHref) ?>">Compare</a>
                <?php } ?>
              </p>
            </div>
            <div class="review-score-col" data-review-score>
              <?= $score ?>
            </div>
          </div>
        </div>
      </header>
      <div class="section">
        <div class="shell review-layout">
          <div class="stack-lg">
            <?= $tasting ?>
            <div id="review-essay">
              <?= $overview ?>
            </div>
            <?php if (! empty($hasServe)) { ?>
            <section class="prose" id="how-to-drink">
              <h2>How to drink it</h2>
              <?= $serveBlock ?>
              <?= $bestForBlock ?>
            </section>
            <?php } ?>
            <section class="callout" id="how-it-was-made">
              <h2>How was it made?</h2>
              <p class="callout-status"><?= $view->e($statusLabel) ?></p>
              <?= $methodBlock ?>
              <?= $discrepancies ?>
            </section>
            <?= $provenance ?? '' ?>
            <?= $sources ?>
          </div>
          <aside class="facts" id="facts" aria-label="Product facts">
            <h2>Product facts</h2>
            <?= $facts ?>
            <?= $links ?>
            <?= $disclosure ?? '' ?>
            <p class="fine-print">Editorial tasting notes are opinion. Production facts are printed only when a source is attached.</p>
            <?php if (! empty($methodologyUrl)) { ?>
            <p class="fine-print"><a href="<?= $view->e($methodologyUrl) ?>">How we score and verify</a></p>
            <?php } ?>
            <p class="fine-print"><a href="<?= $view->e($industryUrl ?? 'industry/') ?>">Brands may submit products for editorial consideration.</a></p>
          </aside>
        </div>
      </div>
      <?php if (! empty($related)) { ?>
      <section class="section section--paper">
        <div class="shell stack">
          <?= $view->render('partials/section-head', [
              'kicker' => 'Keep tasting',
              'title' => $relatedHeading ?? 'More from the cellar',
              'href' => $relatedHref ?? $reviewsUrl,
              'linkLabel' => $relatedLinkLabel ?? 'All reviews',
          ]) ?>
          <div class="card-grid"><?= $related ?></div>
        </div>
      </section>
      <?php } ?>
      <div class="review-sticky" data-review-sticky hidden>
        <div class="shell review-sticky-inner">
          <?= $score ?>
          <div class="review-sticky-actions">
            <a href="#provenance">Sources</a>
            <a href="#facts">Buy</a>
            <?php if (! empty($compareHref)) { ?>
            <a href="<?= $view->e($compareHref) ?>">Compare</a>
            <?php } ?>
          </div>
        </div>
      </div>
    </article>
