    <section class="hero">
      <div class="shell hero-inner">
        <p class="kicker">Independent reviews</p>
        <h1>The standard for what remains after the alcohol is gone.</h1>
        <p class="lede"><?= $view->e($tagline) ?> We review beverages at 0.5% ABV or less, and we separate products that were actually dealcoholized from those formulated to imitate a drink.</p>
        <div class="hero-actions">
          <a class="btn" href="<?= $view->e($reviewsUrl) ?>">Read the reviews</a>
          <a class="btn btn--ghost" href="<?= $view->e($aboutUrl) ?>">Editorial method</a>
        </div>
      </div>
    </section>
    <section class="band">
      <div class="shell stats-grid">
        <?php foreach ($stats as $stat): ?>
        <?php if (! empty($stat['href'])): ?>
        <a class="stat" href="<?= $view->e($stat['href']) ?>">
          <strong><?= $view->e($stat['value']) ?></strong>
          <span><?= $view->e($stat['label']) ?></span>
        </a>
        <?php else: ?>
        <p class="stat">
          <strong><?= $view->e($stat['value']) ?></strong>
          <span><?= $view->e($stat['label']) ?></span>
        </p>
        <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </section>
    <section class="section section--tight">
      <div class="shell stack">
        <?= $categoryRail ?>
        <?= $processRail ?>
      </div>
    </section>
    <section class="section">
      <div class="shell stack">
        <?= $view->render('partials/section-head', [
            'kicker' => 'Latest Reviews',
            'title' => 'Recently reviewed',
            'href' => $reviewsUrl,
            'linkLabel' => 'All reviews',
        ]) ?>
        <div class="ledger"><?= $latestCards ?></div>
      </div>
    </section>
    <section class="section section--paper">
      <div class="shell stack">
        <?= $view->render('partials/section-head', [
            'kicker' => 'Highly Rated',
            'title' => 'What holds up in the glass',
            'href' => $reviewsUrl.'?sort=rating',
            'linkLabel' => 'Highest rated',
        ]) ?>
        <div class="ledger"><?= $ratedCards ?></div>
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
