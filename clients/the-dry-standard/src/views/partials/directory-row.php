      <article class="directory-row" data-search="<?= $view->e($search) ?>">
        <div>
          <h3><a href="<?= $view->e($href) ?>"><?= $view->e($title) ?></a></h3>
          <?php if (! empty($summary)): ?>
          <p><?= $view->e($summary) ?></p>
          <?php endif; ?>
        </div>
        <?php if (! empty($meta)): ?>
        <p class="directory-meta"><?= $view->e($meta) ?></p>
        <?php endif; ?>
      </article>
