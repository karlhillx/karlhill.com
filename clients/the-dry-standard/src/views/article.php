    <article class="article">
      <header class="page-header">
        <div class="shell shell--narrow">
          <?= $breadcrumbs ?>
          <p class="kicker"><?= $view->e($kicker) ?></p>
          <h1><?= $view->e($title) ?></h1>
          <?php if ($summary !== '') { ?>
          <p class="lede"><?= $view->e($summary) ?></p>
          <?php } ?>
        </div>
      </header>
      <div class="section">
        <div class="shell shell--narrow prose">
          <?= $bodyHtml ?>
        </div>
      </div>
      <?php if (! empty($related)) { ?>
      <section class="section section--paper">
        <div class="shell stack">
          <?= $view->render('partials/section-head', [
              'kicker' => 'From the cellar',
              'title' => $relatedHeading ?? 'Reviewed with this method',
          ]) ?>
          <div class="ledger"><?= $related ?></div>
        </div>
      </section>
      <?php } ?>
    </article>
