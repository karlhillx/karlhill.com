      <article class="review-card<?= $compact ? ' review-card--compact' : '' ?>" <?= $attrs ?>>
        <a class="review-card-media" href="<?= $view->e($href) ?>" tabindex="-1" aria-hidden="true">
          <?= $thumb ?>
        </a>
        <div class="review-card-body">
          <div class="card-top">
            <p class="card-brand"><?= $brand ?></p>
            <?= $score ?>
          </div>
          <h3><a href="<?= $view->e($href) ?>"><?= $view->e($title) ?></a></h3>
          <p class="card-meta"><?= $view->e($meta) ?></p>
          <?php if (empty($compact) && ! empty($summary)) { ?>
          <p class="card-summary"><?= $view->e($summary) ?></p>
          <?php } ?>
          <?= $badge ?>
        </div>
      </article>
