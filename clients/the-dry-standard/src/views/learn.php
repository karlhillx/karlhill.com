    <?= $view->render('partials/page-header', [
    'breadcrumbs' => $breadcrumbs,
    'kicker' => 'Learn',
    'title' => $title,
    'lede' => $description,
]) ?>
    <section class="section section--tight" data-reveal>
      <div class="shell stack">
        <?= $view->render('partials/section-head', [
            'kicker' => 'Buying guides',
            'title' => 'How to shop the cellar',
            'href' => $guidesUrl ?? null,
            'linkLabel' => null,
        ]) ?>
        <?= $guideListing ?>
      </div>
    </section>
    <section class="section section--paper" data-reveal>
      <div class="shell stack">
        <?= $view->render('partials/section-head', [
            'kicker' => 'How it’s made',
            'title' => 'Dealcoholization methods',
            'href' => $methodsUrl,
            'linkLabel' => 'All methods',
        ]) ?>
        <?= $methodListing ?>
      </div>
    </section>
