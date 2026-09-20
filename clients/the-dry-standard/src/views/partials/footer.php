  <footer class="site-footer">
    <div class="shell footer-grid">
      <div>
        <p class="logo-text">The Dry Standard</p>
        <p>Independent reviews of dealcoholized beer, wine, spirits, and cocktails at 0.5% ABV or less. We score what remains in the glass — not the lifestyle around it.</p>
        <?php if (! empty($editorMailto)) { ?>
        <p class="footer-editor"><a href="<?= $view->e($editorMailto) ?>"><?= $view->e($editorEmail) ?></a></p>
        <?php } ?>
      </div>
      <div>
        <p class="footer-label">The cellar</p>
        <a href="<?= $view->e($reviewsUrl) ?>">All reviews</a>
        <a href="<?= $view->e($stylesUrl) ?>">Styles</a>
        <?php foreach ($categories as $category) { ?>
        <a href="<?= $view->e($category['href']) ?>"><?= $view->e($category['label']) ?></a>
        <?php } ?>
      </div>
      <div>
        <p class="footer-label">Read</p>
        <a href="<?= $view->e($guidesUrl) ?>">Learn</a>
        <a href="<?= $view->e($methodsUrl) ?>">How it’s made</a>
        <a href="<?= $view->e($brandsUrl) ?>">Brands</a>
        <a href="<?= $view->e($aboutUrl) ?>">About</a>
        <a href="<?= $view->e($methodologyUrl) ?>">Methodology</a>
        <a href="<?= $view->e($collectionsUrl) ?>">Collections</a>
        <a href="<?= $view->e($bestUrl) ?>">Best of the cellar</a>
        <a href="<?= $view->e($compareUrl) ?>">Compare bottles</a>
      </div>
      <div>
        <p class="footer-label">Industry</p>
        <a href="<?= $view->e($industryUrl) ?>">For Brands &amp; Industry</a>
        <a href="<?= $view->e($submitUrl) ?>">Submit a product</a>
        <a href="<?= $view->e($partnershipsUrl) ?>">Partnerships</a>
      </div>
    </div>
    <div class="copyright">
      <p>© <?= $view->e($year ?? date('Y')) ?> The Dry Standard.</p>
      <nav class="copyright-nav" aria-label="Legal">
        <a href="<?= $view->e($privacyUrl) ?>">Privacy</a>
        <a href="<?= $view->e($feedUrl) ?>">RSS</a>
      </nav>
    </div>
  </footer>
