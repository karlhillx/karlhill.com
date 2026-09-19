    <section class="hero">
      <div class="shell hero-inner<?= $featured !== '' ? ' hero-inner--split' : '' ?>">
        <div class="hero-copy">
          <p class="kicker">Independent reviews</p>
          <h1>The standard for what remains after the alcohol is gone.</h1>
          <p class="lede"><?= $view->e($tagline) ?> We review beverages at 0.5% ABV or less, and we classify them by how they were made — not by whether they pass a dealcoholized test.</p>
          <div class="hero-actions">
            <a class="btn" href="<?= $view->e($reviewsUrl) ?>">Browse the cellar</a>
            <a class="btn btn--ghost" href="<?= $view->e($aboutUrl) ?>">Editorial method</a>
          </div>
        </div>
        <?= $featured ?>
      </div>
    </section>
    <section class="band">
      <div class="shell stats-grid">
        <?php foreach ($stats as $stat) { ?>
        <?php if (! empty($stat['href'])) { ?>
        <a class="stat" href="<?= $view->e($stat['href']) ?>">
          <strong><?= $view->e($stat['value']) ?></strong>
          <span><?= $view->e($stat['label']) ?></span>
        </a>
        <?php } else { ?>
        <p class="stat">
          <strong><?= $view->e($stat['value']) ?></strong>
          <span><?= $view->e($stat['label']) ?></span>
        </p>
        <?php } ?>
        <?php } ?>
      </div>
    </section>
    <section class="section">
      <div class="shell stack">
        <?= $view->render('partials/section-head', [
            'kicker' => 'Browse',
            'title' => 'Find a bottle by what it is',
        ]) ?>
        <?= $categoryRail ?>
        <?= $processRail ?>
      </div>
    </section>
    <section class="section section--paper">
      <div class="shell stack">
        <?= $view->render('partials/section-head', [
            'kicker' => 'Latest Reviews',
            'title' => 'Recently reviewed',
            'href' => $reviewsUrl,
            'linkLabel' => 'All reviews',
        ]) ?>
        <div class="card-grid"><?= $latestCards ?></div>
      </div>
    </section>
    <section class="section">
      <div class="shell stack">
        <?= $view->render('partials/section-head', [
            'kicker' => 'Highly Rated',
            'title' => 'What holds up in the glass',
            'href' => $bestUrl,
            'linkLabel' => 'Best of the cellar',
        ]) ?>
        <div class="card-grid card-grid--compact"><?= $ratedCards ?></div>
      </div>
    </section>
    <section class="section section--ink">
      <div class="shell stack">
        <?= $view->render('partials/section-head', [
            'kicker' => 'How it\'s made',
            'title' => 'The techniques that remove alcohol — and the ones that never put it in',
            'href' => $methodsUrl,
            'linkLabel' => 'Method index',
        ]) ?>
        <div class="card-grid card-grid--compact"><?= $methodCards ?></div>
      </div>
    </section>
    <section class="section">
      <div class="shell stack">
        <?= $view->render('partials/section-head', [
            'kicker' => 'Guides',
            'title' => 'How to read a bottle before you buy it',
            'href' => $guidesUrl,
            'linkLabel' => 'All guides',
        ]) ?>
        <div class="card-grid card-grid--compact"><?= $guideCards ?></div>
      </div>
    </section>
