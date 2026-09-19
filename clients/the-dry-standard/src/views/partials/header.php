  <header class="site-header" data-header>
    <div class="header-inner">
      <a class="logo" href="<?= $view->e($homeUrl) ?>"<?= $homeCurrent ? ' aria-current="page"' : '' ?>>
        <img src="<?= $view->e($markUrl) ?>" alt="" width="18" height="18">
        <span>The Dry Standard</span>
      </a>
      <form class="header-search" action="<?= $view->e($searchUrl) ?>" method="get" role="search">
        <label class="visually-hidden" for="header-q">Search reviews</label>
        <input id="header-q" type="search" name="q" placeholder="Search the cellar" autocomplete="off" data-header-q>
      </form>
      <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
        <span class="nav-toggle-bar"></span>
        <span class="visually-hidden">Menu</span>
      </button>
      <nav class="site-nav" id="site-nav" data-nav aria-label="Primary"><?= $items ?></nav>
    </div>
    <div class="nav-backdrop" data-nav-backdrop hidden></div>
  </header>
