    <section class="empty-page">
      <?= $view->render('partials/page-header', [
          'kicker' => '404',
          'title' => 'That bottle is not on the shelf',
          'lede' => 'This address is not a published review, guide, or method. Search the cellar, or start from a category.',
      ]) ?>
      <div class="section section--tight">
        <div class="shell stack">
          <?= $categoryRail ?>
          <p><a class="btn" href="<?= $view->e($reviewsUrl) ?>">Browse the cellar</a></p>
        </div>
      </div>
    </section>
