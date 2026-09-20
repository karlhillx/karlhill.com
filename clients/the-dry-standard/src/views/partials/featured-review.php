      <article class="featured featured--<?= $view->e($presentation ?? 'isolated') ?>">
        <a class="featured-hit" href="<?= $view->e($href) ?>" data-analytics-event="review_opened">
          <span class="visually-hidden">Read the review of <?= $view->e($title) ?></span>
        </a>
        <div class="featured-media product-stage product-stage--<?= $view->e($presentation ?? 'isolated') ?>" aria-hidden="true">
          <?= $figure ?>
          <?php if (! empty($score)) { ?>
          <?= $score ?>
          <?php } ?>
        </div>
        <div class="featured-body">
          <p class="kicker">Start here</p>
          <h2><?= $view->e($title) ?></h2>
          <p class="featured-brand"><?= $brand ?></p>
          <p class="featured-dek"><?= $view->e($summary) ?></p>
          <div class="featured-meta">
            <div class="featured-facts">
              <?= $badge ?>
              <?= $abv ?? '' ?>
            </div>
            <span class="featured-cta text-link">Read the review <span class="featured-cta__arrow" aria-hidden="true">→</span></span>
          </div>
        </div>
      </article>
