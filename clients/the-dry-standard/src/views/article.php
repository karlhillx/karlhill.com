    <article class="article">
      <?= $view->render('partials/page-header', [
          'breadcrumbs' => $breadcrumbs,
          'kicker' => $kicker,
          'title' => $title,
          'lede' => $summary,
          'narrow' => true,
      ]) ?>
      <div class="section">
        <div class="shell shell--narrow prose">
          <?= $bodyHtml ?>
          <?= $afterProse ?? '' ?>
        </div>
      </div>
      <?php if (! empty($siblings)) { ?>
      <section class="section">
        <div class="shell shell--narrow">
          <?= $siblings ?>
        </div>
      </section>
      <?php } ?>
      <?php if (! empty($related)) { ?>
      <section class="section section--paper">
        <div class="shell stack">
          <?= $view->render('partials/section-head', [
              'kicker' => 'From the cellar',
              'title' => $relatedHeading ?? 'Reviewed with this method',
          ]) ?>
          <div class="card-grid"><?= $related ?></div>
        </div>
      </section>
      <?php } ?>
    </article>
