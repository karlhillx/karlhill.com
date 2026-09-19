<?php if (empty($src)) { ?>
        <div class="<?= $view->e($class) ?> product-figure--empty" aria-hidden="true"></div>
<?php } else { ?>
        <figure class="<?= $view->e($class) ?>">
          <picture>
            <?php if (! empty($webpSrcset) || ! empty($webp)) { ?>
            <source type="image/webp" srcset="<?= $view->e($webpSrcset !== '' ? $webpSrcset : $webp) ?>"<?= ! empty($sizes) ? ' sizes="'.$view->e($sizes).'"' : '' ?>>
            <?php } ?>
            <img src="<?= $view->e($src) ?>" alt="<?= $view->e($alt) ?>" width="<?= $view->e((string) ($width ?? 720)) ?>" height="<?= $view->e((string) ($height ?? 960)) ?>"<?= ! empty($srcset) ? ' srcset="'.$view->e($srcset).'"' : '' ?><?= ! empty($sizes) ? ' sizes="'.$view->e($sizes).'"' : '' ?> loading="<?= $view->e($loading ?? 'lazy') ?>" decoding="async"<?= ! empty($priority) ? ' fetchpriority="high"' : '' ?>>
          </picture>
          <?= $credit ?? '' ?>
        </figure>
<?php } ?>
