      <article class="review-card<?= $compact ? ' review-card--compact' : '' ?>" <?= $attrs ?>>
        <a class="review-card-media" href="<?= $view->e($href) ?>" tabindex="-1" aria-hidden="true">
          <?= $thumb ?>
        </a>
        <div class="review-card-body">
          <?php if (! empty($relation)) { ?>
          <p class="card-relation"><?= $view->e($relation) ?></p>
          <?php } ?>
          <div class="card-top">
            <p class="card-brand"><?= $brand ?></p>
            <?= $score ?>
          </div>
          <h3><a href="<?= $view->e($href) ?>" data-analytics-event="review_opened"><?= $view->e($title) ?></a></h3>
          <p class="card-meta"><?= $view->e($meta) ?></p>
          <?php if (! empty($descriptors)) { ?>
          <ul class="card-descriptors tasting-chips" role="list">
            <?php foreach ($descriptors as $descriptor) { ?>
            <li><?= $view->e($descriptor) ?></li>
            <?php } ?>
          </ul>
          <?php } ?>
          <?php if (empty($compact) && ! empty($summary)) { ?>
          <p class="card-summary"><?= $view->e($summary) ?></p>
          <?php } ?>
          <div class="card-foot">
            <?= $badge ?>
            <?php if (! empty($price)) { ?>
            <span class="card-price"><?= $view->e($price) ?></span>
            <?php } ?>
            <?php if (! empty($compareSlug) || ! empty($saveSlug)) { ?>
            <div class="card-actions">
              <?php if (! empty($compareSlug)) { ?>
              <label class="compare-toggle">
                <input type="checkbox" data-compare-toggle value="<?= $view->e($compareSlug) ?>" data-compare-title="<?= $view->e($title) ?>">
                <span>Compare</span>
              </label>
              <?php } ?>
              <?php if (! empty($saveSlug)) { ?>
              <button type="button" class="save-toggle" data-save-toggle value="<?= $view->e($saveSlug) ?>" data-save-title="<?= $view->e($title) ?>" aria-pressed="false">Save</button>
              <?php } ?>
            </div>
            <?php } ?>
          </div>
        </div>
      </article>
