    <header class="page-header">
      <div class="shell">
        <?= $breadcrumbs ?>
        <p class="kicker"><?= $view->e($kicker) ?></p>
        <h1><?= $view->e($title) ?></h1>
        <p class="lede"><?= $view->e($description) ?></p>
        <?php if (! empty($searchable)) { ?>
        <form class="directory-search" data-directory role="search">
          <label class="visually-hidden" for="directory-q">Filter this index</label>
          <input id="directory-q" type="search" placeholder="<?= $view->e($searchPlaceholder ?? 'Find a name') ?>" data-directory-q autocomplete="off">
          <p class="archive-count" data-directory-count></p>
        </form>
        <?php } ?>
        <?= $letterNav ?? '' ?>
      </div>
    </header>
    <section class="section section--tight">
      <div class="shell">
        <?= $listing ?>
        <p class="empty" data-directory-empty hidden>Nothing matches that name.</p>
      </div>
    </section>
