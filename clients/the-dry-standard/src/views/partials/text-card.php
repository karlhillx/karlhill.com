        <article class="text-card">
          <p class="kicker"><?= $view->e($kicker) ?></p>
          <h3><a href="<?= $view->e($href) ?>"><?= $view->e($title) ?></a></h3>
          <p><?= $view->e($summary) ?></p>
          <?php if (! empty($meta)) { ?>
          <p class="card-meta"><?= $view->e($meta) ?></p>
          <?php } ?>
        </article>
