<?php if (empty($src)) { ?>
        <div class="<?= $view->e($class) ?> product-figure--empty" aria-hidden="true"></div>
<?php } else { ?>
        <figure class="<?= $view->e($class) ?>">
          <picture>
            <?php if (! empty($webp)) { ?>
            <source type="image/webp" srcset="<?= $view->e($webp) ?>">
            <?php } ?>
            <img src="<?= $view->e($src) ?>" alt="<?= $view->e($alt) ?>" width="<?= $view->e((string) ($width ?? 720)) ?>" height="<?= $view->e((string) ($height ?? 960)) ?>" loading="<?= $view->e($loading ?? 'lazy') ?>" decoding="async"<?= ! empty($priority) ? ' fetchpriority="high"' : '' ?>>
          </picture>
          <?= $credit ?? '' ?>
        </figure>
<?php } ?>
