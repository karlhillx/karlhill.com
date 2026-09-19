              <?php if (! empty($factsPeek)) { ?>
              <ul class="identity" aria-label="Product identity">
                <?php foreach ($factsPeek as $item) { ?>
                <li>
                  <span><?= $view->e($item['label']) ?></span>
                  <?php if (! empty($item['href'])) { ?>
                  <a href="<?= $view->e($item['href']) ?>"><?= $view->e($item['value']) ?></a>
                  <?php } else { ?>
                  <?= $view->e($item['value']) ?>
                  <?php } ?>
                </li>
                <?php } ?>
              </ul>
              <?php } ?>
              <?= $badge ?? '' ?>
              <?php if (! empty($verifiedLabel)) { ?>
              <p class="identity-verified"><?= $view->e($verifiedLabel) ?></p>
              <?php } ?>
