              <?php if (! empty($factsPeek) || ! empty($badge) || ! empty($verifiedLabel)) { ?>
              <div class="product-facts identity-block">
                <?php if (! empty($factsPeek)) { ?>
                <ul class="identity" aria-label="Product identity">
                  <?php foreach ($factsPeek as $item) { ?>
                  <li>
                    <span class="identity-label"><?= $view->e($item['label']) ?></span>
                    <span class="identity-value-wrap">
                      <?php if (! empty($item['href'])) { ?>
                      <a class="identity-value" href="<?= $view->e($item['href']) ?>"><?= $view->e($item['value']) ?></a>
                      <?php } else { ?>
                      <span class="identity-value"><?= $view->e($item['value']) ?></span>
                      <?php } ?>
                      <?php if (! empty($item['confidence'])) { ?>
                      <span class="identity-confidence"><?= $view->e($item['confidence']) ?></span>
                      <?php } ?>
                    </span>
                  </li>
                  <?php } ?>
                </ul>
                <?php } ?>
                <?php if (! empty($badge) || ! empty($verifiedLabel) || ! empty($factsPeek)) { ?>
                <div class="identity-foot">
                  <?= $badge ?? '' ?>
                  <?php if (! empty($verifiedLabel) && empty($badge)) { ?>
                  <p class="identity-verified"><?= $view->e($verifiedLabel) ?></p>
                  <?php } ?>
                  <p class="identity-sources"><a href="#provenance">Sources &amp; verification</a></p>
                </div>
                <?php } ?>
              </div>
              <?php } ?>
