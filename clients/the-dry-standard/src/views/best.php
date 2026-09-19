    <article class="best">
      <?= $view->render('partials/page-header', [
          'breadcrumbs' => $breadcrumbs,
          'kicker' => 'The cellar',
          'title' => 'What holds up in the glass',
          'lede' => 'Scores of 85 and up, then the strongest bottles in each category. Quality, not how closely a drink impersonates ethanol.',
      ]) ?>
      <section class="section">
        <div class="shell stack">
          <?= $view->render('partials/section-head', [
              'kicker' => 'Exceptional',
              'title' => '85 and above',
          ]) ?>
          <div class="card-grid"><?= $topCards ?></div>
        </div>
      </section>
      <section class="section section--paper">
        <div class="shell stack">
          <?= $sections ?>
        </div>
      </section>
    </article>
