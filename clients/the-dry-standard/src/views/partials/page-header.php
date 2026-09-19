    <header class="page-header">
      <div class="shell<?= ! empty($narrow) ? ' shell--narrow' : '' ?>">
        <?= $breadcrumbs ?? '' ?>
        <?php if (! empty($kicker)): ?>
        <p class="kicker"><?= $view->e($kicker) ?></p>
        <?php endif; ?>
        <?= $beforeTitle ?? '' ?>
        <h1><?= $view->e($title) ?></h1>
        <?php if (! empty($lede)): ?>
        <p class="lede"><?= $view->e($lede) ?></p>
        <?php endif; ?>
        <?= $afterLede ?? '' ?>
      </div>
    </header>
