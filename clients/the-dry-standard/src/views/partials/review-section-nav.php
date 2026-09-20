<nav class="review-section-nav" aria-label="Sections on this page">
  <a href="<?= $view->e($pageUrl) ?>#tasting" data-section-target="tasting">At a glance</a>
  <a href="<?= $view->e($pageUrl) ?>#review-essay" data-section-target="review-essay">Review</a>
  <?php if (! empty($hasServe)) { ?>
  <a href="<?= $view->e($pageUrl) ?>#how-to-drink" data-section-target="how-to-drink">Serve</a>
  <?php } ?>
  <a href="<?= $view->e($pageUrl) ?>#how-it-was-made" data-section-target="how-it-was-made">How it was made</a>
  <a href="<?= $view->e($pageUrl) ?>#provenance" data-section-target="provenance">Sources</a>
  <a href="<?= $view->e($pageUrl) ?>#facts" data-section-target="facts">Facts</a>
  <?php if (! empty($compareHref)) { ?>
  <a href="<?= $view->e($compareHref) ?>">Compare</a>
  <?php } ?>
</nav>
