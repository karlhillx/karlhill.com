        <div class="section-head">
          <div>
            <?php if (! empty($kicker)) { ?>
            <p class="kicker"><?= $view->e($kicker) ?></p>
            <?php } ?>
            <<?= $heading ?? 'h2' ?>><?= $view->e($title) ?></<?= $heading ?? 'h2' ?>>
          </div>
          <?php if (! empty($href)) { ?>
          <a class="text-link" href="<?= $view->e($href) ?>"><?= $view->e($linkLabel ?? 'View all') ?></a>
          <?php } ?>
        </div>
