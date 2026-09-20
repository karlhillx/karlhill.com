  <header class="site-header" data-header>
    <div class="header-inner">
      <a class="logo" href="<?= $view->e($homeUrl) ?>"<?= $homeCurrent ? ' aria-current="page"' : '' ?>>
        <img src="<?= $view->e($markUrl) ?>" alt="" width="22" height="22">
        <span>The Dry Standard</span>
        <?php if (! empty($isBeta)) { ?>
        <span class="site-beta" title="<?= $view->e($betaNote ?? 'Public beta') ?>">Beta</span>
        <?php } ?>
      </a>
      <button class="search-toggle" type="button" aria-expanded="false" aria-controls="header-search" data-search-toggle>
        <span class="visually-hidden">Search</span>
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><circle cx="7" cy="7" r="4.25" stroke="currentColor" stroke-width="1.4"/><path d="M10.2 10.2 13.5 13.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
      </button>
      <form class="header-search" id="header-search" action="<?= $view->e($searchUrl) ?>" method="get" role="search" data-search-panel>
        <label class="visually-hidden" for="header-q">Search reviews</label>
        <span class="header-search-icon" aria-hidden="true">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><circle cx="7" cy="7" r="4.25" stroke="currentColor" stroke-width="1.4"/><path d="M10.2 10.2 13.5 13.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
        </span>
        <input id="header-q" type="search" name="q" placeholder="Search the cellar" autocomplete="off" data-header-q data-analytics-search>
      </form>
      <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
        <span class="nav-toggle-bar"></span>
        <span class="visually-hidden">Menu</span>
      </button>
      <nav class="site-nav" id="site-nav" data-nav aria-label="Primary"><?= $items ?></nav>
    </div>
    <div class="nav-backdrop" data-nav-backdrop hidden inert></div>
  </header>
