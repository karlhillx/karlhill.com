    <header class="page-header">
      <div class="shell">
        <?= $breadcrumbs ?>
        <p class="kicker">The cellar</p>
        <h1><?= $view->e($title) ?></h1>
        <p class="lede"><?= $view->e($description) ?></p>
        <?= $categoryRail ?>
      </div>
    </header>
    <section class="section section--tight">
      <?= $archive ?>
    </section>
