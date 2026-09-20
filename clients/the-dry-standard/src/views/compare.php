    <article class="compare" data-compare-page>
      <?= $view->render('partials/page-header', [
          'breadcrumbs' => $breadcrumbs,
          'kicker' => 'The cellar',
          'title' => 'Compare bottles',
          'lede' => 'Two to four bottles side by side — score, ABV, production, structure, and flavor. Quality first; process always labeled.',
          'narrow' => false,
      ]) ?>

      <?php if ($count === 0) { ?>
      <section class="section">
        <div class="shell stack">
          <p class="compare-empty">Pick a starting set, or add bottles from any review card with <strong>Compare</strong>.</p>
          <?php if ($clusters !== []) { ?>
          <?= $view->render('partials/section-head', [
              'kicker' => 'Start here',
              'title' => 'Style clusters ready to weigh',
          ]) ?>
          <div class="compare-clusters">
            <?php foreach ($clusters as $cluster) { ?>
            <a class="compare-cluster" href="<?= $view->e($cluster['href']) ?>">
              <span class="compare-cluster-kicker"><?= $view->e($cluster['meta']) ?></span>
              <strong><?= $view->e($cluster['label']) ?></strong>
              <span><?= (int) $cluster['count'] ?> bottles</span>
            </a>
            <?php } ?>
          </div>
          <?php } ?>
          <?= $picker ?>
          <p class="fine-print"><a href="<?= $view->e($reviewsUrl) ?>">Browse the full cellar</a> and tick Compare on cards.</p>
        </div>
      </section>
      <?php } else { ?>
      <section class="section">
        <div class="shell stack">
          <div class="compare-toolbar">
            <p class="compare-count"><?= (int) $count ?> of 4 bottles</p>
            <div class="compare-toolbar-actions">
              <?= $picker ?>
              <?php if ($count > 0) { ?>
              <a class="btn btn--ghost" href="<?= $view->e($clearHref) ?>">Clear</a>
              <?php } ?>
            </div>
          </div>

          <div class="compare-table-wrap">
            <table class="compare-table">
              <thead>
                <tr>
                  <th scope="col"><span class="visually-hidden">Field</span></th>
                  <?php foreach ($columns as $column) { ?>
                  <th scope="col">
                    <div class="compare-head">
                      <a class="compare-media" href="<?= $view->e($column['href']) ?>" tabindex="-1" aria-hidden="true"><?= $column['figure'] ?></a>
                      <p class="compare-brand"><?= $view->e($column['brand']) ?></p>
                      <h2 class="compare-title"><a href="<?= $view->e($column['href']) ?>"><?= $view->e($column['title']) ?></a></h2>
                      <a class="compare-remove" href="<?= $view->e($column['removeHref']) ?>">Remove</a>
                    </div>
                  </th>
                  <?php } ?>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">Score</th>
                  <?php foreach ($columns as $column) { ?>
                  <td>
                    <?php if ($column['score'] !== null) { ?>
                    <span class="compare-score"><?= (int) $column['score'] ?></span>
                    <?php if (! empty($column['band'])) { ?>
                    <span class="compare-band"><?= $view->e($column['band']) ?></span>
                    <?php } ?>
                    <?php } else { ?>
                    —
                    <?php } ?>
                  </td>
                  <?php } ?>
                </tr>
                <tr>
                  <th scope="row">ABV</th>
                  <?php foreach ($columns as $column) { ?>
                  <td><?= $view->e($column['abv']) ?></td>
                  <?php } ?>
                </tr>
                <tr>
                  <th scope="row">Category</th>
                  <?php foreach ($columns as $column) { ?>
                  <td><?= $view->e($column['category']) ?></td>
                  <?php } ?>
                </tr>
                <tr>
                  <th scope="row">Style</th>
                  <?php foreach ($columns as $column) { ?>
                  <td><?= $view->e($column['style']) ?></td>
                  <?php } ?>
                </tr>
                <tr>
                  <th scope="row">Production</th>
                  <?php foreach ($columns as $column) { ?>
                  <td><?= $view->e($column['production']) ?></td>
                  <?php } ?>
                </tr>
                <tr>
                  <th scope="row">Method</th>
                  <?php foreach ($columns as $column) { ?>
                  <td><?= $view->e($column['method']) ?></td>
                  <?php } ?>
                </tr>
                <tr>
                  <th scope="row">Price</th>
                  <?php foreach ($columns as $column) { ?>
                  <td><?= $view->e($column['price']) ?></td>
                  <?php } ?>
                </tr>
                <tr>
                  <th scope="row">Flavor</th>
                  <?php foreach ($columns as $column) { ?>
                  <td>
                    <?php if ($column['descriptors'] === []) { ?>
                    —
                    <?php } else { ?>
                    <ul class="tasting-chips compare-chips" role="list">
                      <?php foreach ($column['descriptors'] as $descriptor) { ?>
                      <li><?= $view->e($descriptor) ?></li>
                      <?php } ?>
                    </ul>
                    <?php } ?>
                  </td>
                  <?php } ?>
                </tr>
                <tr>
                  <th scope="row">Structure</th>
                  <?php foreach ($columns as $column) { ?>
                  <td>
                    <?php if ($column['structure'] === []) { ?>
                    —
                    <?php } else { ?>
                    <ul class="tasting-chips compare-chips" role="list">
                      <?php foreach ($column['structure'] as $item) { ?>
                      <li><?= $view->e($item) ?></li>
                      <?php } ?>
                    </ul>
                    <?php } ?>
                  </td>
                  <?php } ?>
                </tr>
              </tbody>
            </table>
          </div>

          <?php if ($clusters !== [] && $count < 2) { ?>
          <div class="compare-clusters">
            <?php foreach ($clusters as $cluster) { ?>
            <a class="compare-cluster" href="<?= $view->e($cluster['href']) ?>">
              <span class="compare-cluster-kicker"><?= $view->e($cluster['meta']) ?></span>
              <strong><?= $view->e($cluster['label']) ?></strong>
              <span><?= (int) $cluster['count'] ?> bottles</span>
            </a>
            <?php } ?>
          </div>
          <?php } ?>
        </div>
      </section>
      <?php } ?>
    </article>
