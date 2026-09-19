    <?= $view->render('partials/page-header', [
    'breadcrumbs' => $breadcrumbs,
    'kicker' => $kicker,
    'title' => $title,
    'lede' => $description,
    'afterLede' => ($searchable ?? false)
        ? '<form class="directory-search" data-directory role="search">
          <label class="visually-hidden" for="directory-q">Filter this index</label>
          <input id="directory-q" type="search" placeholder="'.$view->e($searchPlaceholder ?? 'Find a name').'" data-directory-q autocomplete="off">
          <p class="archive-count" data-directory-count></p>
        </form>'.($letterNav ?? '')
        : ($letterNav ?? ''),
]) ?>
    <section class="section section--tight">
      <div class="shell">
        <?= $listing ?>
        <p class="empty" data-directory-empty hidden>Nothing matches that name.</p>
      </div>
    </section>
