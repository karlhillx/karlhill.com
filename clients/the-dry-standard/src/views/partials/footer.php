  <footer class="site-footer">
    <div class="shell footer-grid">
      <div>
        <p class="logo-text">The Dry Standard</p>
        <p>Independent reviews of dealcoholized beer, wine, spirits, and cocktails at 0.5% ABV or less. We score what remains in the glass — not the lifestyle around it.</p>
      </div>
      <div>
        <p class="footer-label">The cellar</p>
        <a href="<?= $view->e($reviewsUrl) ?>">All reviews</a>
        <?php foreach ($categories as $category) { ?>
        <a href="<?= $view->e($category['href']) ?>"><?= $view->e($category['label']) ?></a>
        <?php } ?>
      </div>
      <div>
        <p class="footer-label">Read</p>
        <a href="<?= $view->e($guidesUrl) ?>">Guides</a>
        <a href="<?= $view->e($methodsUrl) ?>">Methods</a>
        <a href="<?= $view->e($brandsUrl) ?>">Brands</a>
        <a href="<?= $view->e($aboutUrl) ?>">About &amp; methodology</a>
        <a href="<?= $view->e($bestUrl) ?>">Best of the cellar</a>
        <a href="<?= $view->e($feedUrl) ?>">RSS</a>
      </div>
    </div>
    <p class="copyright">© <?= $view->e($year ?? date('Y')) ?> The Dry Standard.</p>
  </footer>
