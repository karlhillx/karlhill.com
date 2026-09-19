    <?= $view->render('partials/page-header', [
    'breadcrumbs' => $breadcrumbs,
    'kicker' => 'Industry',
    'title' => 'Editorial samples',
    'lede' => 'Brands may send products to The Dry Standard for independent editorial consideration.',
    'narrow' => true,
]) ?>
    <section class="section">
      <div class="shell shell--narrow prose">
        <p>A sample is not a review. It is not a promise of coverage, a positive score, or a listing in the public cellar.</p>
        <h2>What we will and will not do</h2>
        <ul>
          <li>Submission does not guarantee publication.</li>
          <li>Submission does not guarantee a favorable review.</li>
          <li>Samples generally will not be returned.</li>
          <li>Editorial coverage stays independent of who paid for the bottle.</li>
        </ul>
        <h2>How to send a product</h2>
        <p>Start with the <a href="<?= $view->e($submitUrl) ?>">product submission form</a>. If a sample is useful, we will reply with shipping instructions. We do not publish a receiving address on this page.</p>
        <p>Please wait for that reply before shipping. Unsolicited parcels without a matching submission may be refused.</p>
        <p><a href="<?= $view->e($industryUrl) ?>">Back to For Brands &amp; Industry</a></p>
      </div>
    </section>
