          <fieldset class="facet<?= ! empty($collapsible) ? ' facet--collapsible' : '' ?>" data-facet="<?= $view->e($name) ?>"<?= ! empty($collapsed) ? ' data-facet-collapsed' : '' ?>>
            <legend class="facet-legend"><?= $view->e($legend) ?></legend>
            <?= $search ?>
            <div class="facet-list"><?= $items ?></div>
          </fieldset>
