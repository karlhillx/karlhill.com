<?php if ($items !== []): ?>
      <nav class="category-rail" aria-label="<?= $view->e($label ?? 'Categories') ?>">
        <?php foreach ($items as $item): ?>
        <a href="<?= $view->e($item['href']) ?>"<?= ! empty($item['current']) ? ' aria-current="page"' : '' ?>>
          <span><?= $view->e($item['label']) ?></span>
          <?php if (isset($item['count'])): ?>
          <span class="category-rail-count"><?= $view->e((string) $item['count']) ?></span>
          <?php endif; ?>
        </a>
        <?php endforeach; ?>
      </nav>
<?php endif; ?>
