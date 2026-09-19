    <article class="review" data-review>
      <header class="page-header">
        <div class="shell">
          <?= $breadcrumbs ?>
          <p class="kicker"><?= $view->e($categoryLabel) ?></p>
          <div class="review-hero">
            <?= $figure ?>
            <div class="review-hero-copy">
              <h1><?= $view->e($title) ?></h1>
              <?= $metaLine ?>
              <p class="lede"><?= $view->e($summary) ?></p>
              <p class="<?= $view->e($badgeClass) ?>"><?= $view->e($badgeLabel) ?></p>
              <p class="review-jump"><a href="#facts">Product facts</a></p>
            </div>
            <?= $score ?>
          </div>
        </div>
      </header>
      <div class="section">
        <div class="shell review-layout">
          <div class="stack-lg">
            <section class="callout">
              <h2>Is it actually dealcoholized?</h2>
              <p class="callout-status"><?= $view->e($statusLabel) ?></p>
              <?= $methodBlock ?>
              <?= $baseBlock ?>
              <?= $discrepancies ?>
            </section>
            <?= $overview ?>
            <?= $tasting ?>
            <section class="prose">
              <h2>How to drink it</h2>
              <?= $serveBlock ?>
              <?= $bestForBlock ?>
            </section>
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
            <p class="fine-print">Editorial tasting notes are opinion. Production facts are printed only when a source is attached.</p>
          </aside>
        </div>
      </div>
      <?php if (! empty($related)) { ?>
      <section class="section section--paper">
        <div class="shell stack">
          <?= $view->render('partials/section-head', [
              'kicker' => 'Keep tasting',
              'title' => 'More from the cellar',
              'href' => $reviewsUrl,
              'linkLabel' => 'All reviews',
          ]) ?>
          <div class="card-grid"><?= $related ?></div>
        </div>
      </section>
      <?php } ?>
    </article>
