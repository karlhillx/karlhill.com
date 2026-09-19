              <?php if (! empty($factsPeek) || ! empty($badge) || ! empty($verifiedLabel)) { ?>
              <div class="identity-block">
                <?php if (! empty($factsPeek)) { ?>
                <ul class="identity" aria-label="Product identity">
                  <?php foreach ($factsPeek as $item) { ?>
                  <li>
                    <span><?= $view->e($item['label']) ?></span>
                    <?php if (! empty($item['href'])) { ?>
                    <a class="identity-value" href="<?= $view->e($item['href']) ?>"><?= $view->e($item['value']) ?></a>
                    <?php } else { ?>
                    <span class="identity-value"><?= $view->e($item['value']) ?></span>
                    <?php } ?>
                  </li>
                  <?php } ?>
                </ul>
                <?php } ?>
                <?php if (! empty($badge) || ! empty($verifiedLabel)) { ?>
                <div class="identity-foot">
                  <?= $badge ?? '' ?>
                  <?php if (! empty($verifiedLabel)) { ?>
                  <p class="identity-verified"><?= $view->e($verifiedLabel) ?></p>
                  <?php } ?>
                </div>
                <?php } ?>
              </div>
              <?php } ?>
