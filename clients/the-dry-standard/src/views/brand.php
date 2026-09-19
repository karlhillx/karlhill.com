    <header class="page-header">
      <div class="shell">
        <?= $breadcrumbs ?>
        <p class="kicker">Brand</p>
        <h1><?= $view->e($name) ?></h1>
        <p class="lede"><?= $view->e($description) ?></p>
        <?php if (! empty($meta)) { ?>
        <p class="page-meta"><?= $view->e($meta) ?></p>
        <?php } ?>
      </div>
    </header>
    <section class="section">
      <div class="shell">
        <div class="card-grid"><?= $cards ?></div>
      </div>
    </section>
