      <article class="card<?= $compact ? ' card--compact' : '' ?>" <?= $attrs ?>>
        <?= $thumb ?>
        <div class="card-top">
          <p class="card-meta"><?= $view->e($meta) ?></p>
          <?= $score ?>
        </div>
        <h3><a href="<?= $view->e($href) ?>"><?= $view->e($title) ?></a></h3>
        <p><?= $view->e($summary) ?></p>
        <?= $badge ?>
      </article>
