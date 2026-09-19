      <div class="shell archive-layout" data-archive<?= $locked ?>>
        <div class="filter-backdrop" data-filter-backdrop hidden></div>
        <form class="archive-sidebar" id="archive-filters" role="search" data-archive-filters>
          <div class="archive-sidebar-head">
            <p class="facet-legend">Filters</p>
            <button class="filter-close" type="button" data-filter-close>Close</button>
          </div>
          <div class="facet">
            <label class="facet-legend" for="archive-q">Search</label>
            <input id="archive-q" type="search" name="q" placeholder="Brand, product, origin, or method" data-archive-q autocomplete="off">
          </div>
          <?= $facets ?>
          <div class="archive-sidebar-actions">
            <p class="archive-clear"><button type="button" data-archive-clear>Clear filters</button></p>
            <button class="btn filter-apply" type="button" data-filter-close>Show results</button>
          </div>
        </form>
        <div class="archive-main">
          <div class="archive-toolbar">
            <p class="archive-count" data-archive-count></p>
            <div class="archive-toolbar-actions">
              <label class="archive-sort">Sort
                <select name="sort" data-archive-sort>
                  <option value="newest">Newest</option>
                  <option value="rating">Highest rated</option>
                  <option value="title">Name</option>
                </select>
              </label>
              <button class="filter-toggle" type="button" aria-expanded="false" aria-controls="archive-filters" data-filter-toggle>
                Filters
              </button>
            </div>
          </div>
          <div class="filter-chips" data-filter-chips hidden></div>
          <?= $list ?>
        </div>
      </div>
