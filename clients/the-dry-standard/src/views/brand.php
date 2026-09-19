    <?= $view->render('partials/page-header', [
    'breadcrumbs' => $breadcrumbs,
    'kicker' => 'Brand',
    'title' => $name,
    'lede' => $description,
    'afterLede' => $mix ?? '',
]) ?>
    <section class="section">
      <div class="shell">
        <div class="card-grid"><?= $cards ?></div>
      </div>
    </section>
