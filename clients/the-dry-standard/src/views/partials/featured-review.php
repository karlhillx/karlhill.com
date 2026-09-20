      <article class="featured">
        <a class="featured-media" href="<?= $view->e($href) ?>" tabindex="-1" aria-hidden="true">
          <?= $figure ?>
          <?php if (! empty($score)) { ?>
          <?= $score ?>
          <?php } ?>
        </a>
        <div class="featured-body">
          <p class="kicker">Start here</p>
          <p class="featured-brand"><?= $brand ?></p>
          <h2><a href="<?= $view->e($href) ?>" data-analytics-event="review_opened"><?= $view->e($title) ?></a></h2>
          <p><?= $view->e($summary) ?></p>
          <div class="featured-meta">
            <?= $badge ?>
            <a class="text-link" href="<?= $view->e($href) ?>" data-analytics-event="review_opened">Read the review</a>
          </div>
        </div>
      </article>
