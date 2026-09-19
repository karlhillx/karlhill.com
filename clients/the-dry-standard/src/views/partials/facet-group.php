          <div class="facet<?= ! empty($collapsible) ? ' facet--collapsible' : '' ?>" role="group" aria-labelledby="archive-<?= $view->e($name) ?>-legend" data-facet="<?= $view->e($name) ?>"<?= ! empty($collapsed) ? ' data-facet-collapsed' : '' ?>>
            <p class="facet-legend" id="archive-<?= $view->e($name) ?>-legend"><?= $view->e($legend) ?></p>
            <?= $search ?>
            <div class="facet-list"><?= $items ?></div>
          </div>
