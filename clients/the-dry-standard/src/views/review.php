    <article class="review" data-review>
      <header class="page-header page-header--review">
        <div class="shell">
          <?= $breadcrumbs ?>
          <p class="kicker"><?= $view->e($categoryLabel) ?></p>
          <div class="review-hero">
            <?= $figure ?>
            <div class="review-hero-copy">
              <h1><?= $view->e($title) ?></h1>
              <?= $metaLine ?>
              <p class="lede"><?= $view->e($summary) ?></p>
              <?= $identity ?? '' ?>
              <p class="review-jump">
                <a href="<?= $view->e($pageUrl) ?>#how-it-was-made">How it was made</a>
                <a href="<?= $view->e($pageUrl) ?>#tasting">Tasting</a>
                <a href="<?= $view->e($pageUrl) ?>#facts">Facts</a>
                <?php if (! empty($hasServe)) { ?>
                <a href="<?= $view->e($pageUrl) ?>#how-to-drink">Serve</a>
                <?php } ?>
              </p>
            </div>
            <?= $score ?>
          </div>
        </div>
      </header>
      <div class="section">
        <div class="shell review-layout">
          <div class="stack-lg">
            <section class="callout" id="how-it-was-made">
              <h2>How was it made?</h2>
              <p class="callout-status"><?= $view->e($statusLabel) ?></p>
              <?= $methodBlock ?>
              <?= $discrepancies ?>
            </section>
            <?= $overview ?>
            <?= $tasting ?>
            <?php if (! empty($hasServe)) { ?>
            <section class="prose" id="how-to-drink">
              <h2>How to drink it</h2>
              <?= $serveBlock ?>
              <?= $bestForBlock ?>
            </section>
            <?php } ?>
            <section class="verdict">
              <h2>Verdict</h2>
              <p><?= $view->e($verdict) ?></p>
            </section>
            <?= $sources ?>
          </div>
          <aside class="facts" id="facts" aria-label="Product facts">
            <h2>Product facts</h2>
            <?= $facts ?>
            <?= $links ?>
            <?= $disclosure ?? '' ?>
            <p class="fine-print">Editorial tasting notes are opinion. Production facts are printed only when a source is attached.</p>
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
    </article>
