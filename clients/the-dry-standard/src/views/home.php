    <section class="hero">
      <div class="shell hero-inner<?= $featured !== '' ? ' hero-inner--split' : '' ?>">
        <div class="hero-copy">
          <p class="kicker">Independent reviews</p>
          <h1>The standard for what remains after the alcohol is gone.</h1>
          <p class="lede"><?= $view->e($tagline) ?> We review beverages at 0.5% ABV or less, and we classify them by how they were made — not by whether they pass a dealcoholized test.</p>
          <div class="hero-actions">
            <a class="btn" href="<?= $view->e($reviewsUrl) ?>">Browse the cellar</a>
            <a class="text-link text-link--on-ink" href="<?= $view->e($aboutUrl) ?>">Editorial method</a>
          </div>
        </div>
        <?= $featured ?>
      </div>
    </section>
    <section class="section" data-reveal>
      <div class="shell stack">
        <?= $view->render('partials/section-head', [
            'kicker' => 'Production',
            'title' => 'Find a bottle by how it was made',
        ]) ?>
        <?= $processRail ?>
      </div>
    </section>
    <section class="section section--paper" data-reveal>
      <div class="shell stack">
        <?= $view->render('partials/section-head', [
            'kicker' => 'Categories',
            'title' => 'Browse by drink',
            'href' => $reviewsUrl,
            'linkLabel' => 'All reviews',
        ]) ?>
        <?= $categoryRail ?>
      </div>
    </section>
    <section class="section" data-reveal>
      <div class="shell stack">
        <?= $view->render('partials/section-head', [
            'kicker' => 'Latest Reviews',
            'title' => 'Recently reviewed',
            'href' => $reviewsUrl,
            'linkLabel' => 'All reviews',
        ]) ?>
        <div class="card-rail" data-card-rail>
          <div class="card-grid card-grid--rail"><?= $latestCards ?></div>
        </div>
      </div>
    </section>
    <section class="section section--paper" data-reveal>
      <div class="shell stack">
        <?= $view->render('partials/section-head', [
            'kicker' => 'Highly Rated',
            'title' => 'What holds up in the glass',
            'href' => $bestUrl,
            'linkLabel' => 'Best of the cellar',
        ]) ?>
        <div class="card-rail" data-card-rail>
          <div class="card-grid card-grid--rail card-grid--compact"><?= $ratedCards ?></div>
        </div>
      </div>
    </section>
    <section class="section section--ink" data-reveal>
      <div class="shell stack">
        <?= $view->render('partials/section-head', [
            'kicker' => 'Read',
            'title' => 'Methods and buying notes',
            'href' => $guidesUrl,
            'linkLabel' => 'All guides',
        ]) ?>
        <div class="card-grid card-grid--read"><?= $readCards ?></div>
      </div>
    </section>
