              <?php if (! empty($factsPeek) || ! empty($badge)) { ?>
              <div class="product-facts identity-block">
                <?php if (! empty($factsPeek)) { ?>
                <ul class="identity" aria-label="Product identity">
                  <?php foreach ($factsPeek as $item) {
                      $key = (string) ($item['key'] ?? '');
                      $itemClass = 'identity-item'.($key !== '' ? ' identity-item--'.$view->e($key) : '');
                      $tone = (string) ($item['tone'] ?? '');
                      $asPill = ! empty($item['pill']);
                      ?>
                  <li class="<?= $itemClass ?>">
                    <span class="identity-label"><?= $view->e($item['label']) ?></span>
                    <?php if ($asPill) { ?>
                    <span class="identity-pill<?= $tone !== '' ? ' badge badge--'.$view->e($tone) : '' ?>">
                      <?php if (! empty($item['href'])) { ?>
                      <a href="<?= $view->e($item['href']) ?>"><?= $view->e($item['value']) ?></a>
                      <?php } else { ?>
                      <?= $view->e($item['value']) ?>
                      <?php } ?>
                    </span>
                    <?php } elseif (! empty($item['href'])) { ?>
                    <a class="identity-value" href="<?= $view->e($item['href']) ?>"><?= $view->e($item['value']) ?></a>
                    <?php } else { ?>
                    <span class="identity-value"><?= $view->e($item['value']) ?></span>
                    <?php } ?>
                  </li>
                  <?php } ?>
                </ul>
                <?php } ?>
                <p class="identity-sources"><a href="<?= $view->e(($pageUrl ?? '').'#provenance') ?>">Sources</a></p>
              </div>
              <?php } ?>
