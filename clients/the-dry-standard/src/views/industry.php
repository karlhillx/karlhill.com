    <?= $view->render('partials/page-header', [
    'breadcrumbs' => $breadcrumbs,
    'kicker' => 'Industry',
    'title' => 'For Brands & Industry',
    'lede' => 'The Dry Standard is an independent editorial resource for non-alcoholic drinks. Brands, producers, importers, distributors, and other industry partners may submit products for editorial consideration or inquire about collaborations.',
    'narrow' => true,
]) ?>
    <section class="section">
      <div class="shell shell--narrow stack">
        <p class="lede lede--follow">Editorial coverage is independent of samples, advertising, and any commercial relationship. Submission does not guarantee publication or a favorable review.</p>
        <div class="industry-doors">
          <a class="industry-door" href="<?= $view->e($submitUrl) ?>">
            <p class="kicker">Products</p>
            <h2>Submit a product</h2>
            <p>Share a SKU for the editorial queue. We verify facts before anything is published.</p>
          </a>
          <a class="industry-door" href="<?= $view->e($samplesUrl) ?>">
            <p class="kicker">Samples</p>
            <h2>Editorial samples</h2>
            <p>How to send a bottle for tasting, and the rules that keep that tasting independent.</p>
          </a>
          <a class="industry-door" href="<?= $view->e($partnershipsUrl) ?>">
            <p class="kicker">Collaborations</p>
            <h2>Partnerships &amp; business inquiries</h2>
            <p>Advertising, distribution, product feeds, and other partnerships — without a pitch deck on the public site.</p>
          </a>
        </div>
        <p class="fine-print">Readers looking for methodology can start with <a href="<?= $view->e($aboutUrl) ?>">About</a>. This page is the front desk for companies.</p>
      </div>
    </section>
