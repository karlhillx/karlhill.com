    <section class="hero">
      <div class="shell hero-inner<?= $featured !== '' ? ' hero-inner--split' : '' ?>">
        <div class="hero-copy">
          <p class="kicker">Independent reviews</p>
          <h1>Independent reviews with production provenance.</h1>
          <p class="lede">We taste beverages at 0.5% ABV or less, classify how they were made — dealcoholized, alternative, or naturally low alcohol — and mark ABV or method as Undeclared, Unclassified, or Withheld when makers won’t say.</p>
          <p class="hero-promise"><span>ABV</span><span>Classification</span><span>Method</span><span>Source confidence</span></p>
          <div class="hero-actions">
            <a class="btn" href="<?= $view->e($reviewsUrl) ?>">Find a bottle</a>
            <a class="text-link text-link--on-ink" href="<?= $view->e($dealcoholizedUrl) ?>">What “dealcoholized” means</a>
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
            'kicker' => 'Latest',
            'title' => 'Recently reviewed',
            'href' => $reviewsUrl,
            'linkLabel' => 'All reviews',
        ]) ?>
        <div class="card-rail" data-card-rail>
          <div class="card-grid card-grid--rail"><?= $latestCards ?></div>
        </div>
      </div>
    </section>
    <section class="section" data-reveal>
      <div class="shell stack">
        <?= $view->render('partials/section-head', [
            'kicker' => 'High scores',
            'title' => '85 and above',
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
            'kicker' => 'Learn',
            'title' => 'Methods and buying notes',
            'href' => $learnUrl,
            'linkLabel' => 'All of Learn',
        ]) ?>
        <div class="card-grid card-grid--read"><?= $readCards ?></div>
      </div>
    </section>
    <section class="section" data-reveal>
      <div class="shell stack industry-home-cta">
        <p class="kicker">Industry</p>
        <h2>Brands may submit products for editorial consideration.</h2>
        <p>A sample does not buy a score. We reply within three business days.</p>
        <p><a class="btn" href="<?= $view->e($submitUrl) ?>">Submit a product</a>
           <a class="text-link" href="<?= $view->e($methodologyUrl) ?>">How we score and verify</a></p>
      </div>
    </section>
