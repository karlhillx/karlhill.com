      <article class="ledger-row" <?= $attrs ?>>
        <?= $thumb ?>
        <div>
          <p class="ledger-brand"><?= $brand ?></p>
          <h3><a href="<?= $view->e($href) ?>"><?= $view->e($title) ?></a></h3>
          <p class="ledger-meta"><?= $view->e($meta) ?></p>
          <?= $badge ?>
        </div>
        <?= $score ?>
      </article>
