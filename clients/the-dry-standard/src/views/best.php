    <article class="best">
      <header class="page-header">
        <div class="shell">
          <?= $breadcrumbs ?>
          <p class="kicker">The cellar</p>
          <h1>What holds up in the glass</h1>
          <p class="lede">Scores of 85 and up, then the strongest bottles in each category. Quality, not how closely a drink impersonates ethanol.</p>
        </div>
      </header>
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
