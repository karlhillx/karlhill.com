<?php if (empty($src)) { ?>
        <div class="<?= $view->e($class) ?> product-figure--empty" aria-hidden="true"></div>
<?php } else { ?>
        <figure class="<?= $view->e($class) ?>">
          <img src="<?= $view->e($src) ?>" alt="<?= $view->e($alt) ?>" width="720" height="960" loading="<?= $view->e($loading ?? 'lazy') ?>" decoding="async"<?= ! empty($priority) ? ' fetchpriority="high"' : '' ?>>
          <?= $credit ?? '' ?>
        </figure>
<?php } ?>
