      <div class="shell archive-layout<?= $compact ?? '' ?>" data-archive<?= $locked ?>>
        <div class="filter-backdrop" data-filter-backdrop hidden inert></div>
        <form class="archive-sidebar" id="archive-filters" role="search" method="get" data-archive-filters data-analytics-filters>
          <div class="archive-sidebar-head">
            <p class="facet-legend">Filters</p>
            <button class="filter-close" type="button" data-filter-close>Close</button>
          </div>
          <input id="archive-q" type="hidden" name="q" value="<?= $view->e($q ?? '') ?>" data-archive-q>
          <?= $facets ?>
          <div class="archive-sidebar-actions">
            <p class="archive-clear"><a href="<?= $view->e($clearHref ?? '') ?>">Clear filters</a></p>
            <button class="btn filter-apply" type="submit">Show results</button>
          </div>
        </form>
        <div class="archive-main" data-archive-main>
          <div class="archive-toolbar">
            <p class="archive-count" data-archive-count aria-live="polite"><?= $view->e($countLabel ?? '') ?></p>
            <div class="archive-toolbar-actions">
              <label class="archive-sort">Sort
                <select name="sort" form="archive-filters" data-archive-sort>
                  <option value="newest"<?= ($sort ?? '') === 'newest' ? ' selected' : '' ?>>Newest</option>
                  <option value="rating"<?= ($sort ?? '') === 'rating' ? ' selected' : '' ?>>Highest rated</option>
                  <option value="title"<?= ($sort ?? '') === 'title' ? ' selected' : '' ?>>Name</option>
                </select>
              </label>
              <button class="filter-toggle" type="button" aria-expanded="false" aria-controls="archive-filters" data-filter-toggle>
                Filters
              </button>
            </div>
          </div>
          <div class="filter-chips" data-filter-chips<?= empty($hasChips) ? ' hidden' : '' ?>><?= $chips ?? '' ?></div>
          <?= $list ?>
        </div>
      </div>
