          <div class="facet<?= ! empty($collapsible) ? ' facet--collapsible' : '' ?>" role="group" aria-labelledby="archive-<?= $view->e($name) ?>-legend" data-facet="<?= $view->e($name) ?>"<?= ! empty($collapsed) ? ' data-facet-collapsed' : '' ?>>
            <?php if (! empty($collapsible)) { ?>
            <button class="facet-legend facet-toggle" type="button" id="archive-<?= $view->e($name) ?>-legend" data-facet-toggle aria-expanded="<?= ! empty($collapsed) ? 'false' : 'true' ?>">
              <?= $view->e($legend) ?>
            </button>
            <?php } else { ?>
            <p class="facet-legend" id="archive-<?= $view->e($name) ?>-legend"><?= $view->e($legend) ?></p>
            <?php } ?>
            <?= $search ?>
            <div class="facet-list"><?= $items ?></div>
          </div>
