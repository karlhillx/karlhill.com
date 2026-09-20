<?php

use DryStandard\Referral;

if (! empty($groups) || ! empty($bibliography)) { ?>
<section class="sources-panel" id="provenance">
  <details class="provenance" data-provenance-panel>
    <summary>
      <span class="provenance-summary-title"><?= $view->e($title ?? 'Sources') ?></span>
      <?php if (! empty($summary)) { ?>
      <span class="provenance-summary-meta"><?= $view->e($summary) ?></span>
      <?php } ?>
    </summary>
    <div class="provenance-body">
      <p class="provenance-lede">Product facts are only as strong as their sources. Editorial tasting notes are opinion.</p>
      <?php if (! empty($groups)) { ?>
      <ul class="provenance-groups">
      <?php foreach ($groups as $group) { ?>
      <li>
        <div class="provenance-group-head">
          <span class="provenance-confidence provenance-confidence--<?= $view->e($group['confidenceClass']) ?>"><?= $view->e($group['confidence']) ?></span>
          <span class="provenance-kind"><?= $view->e($group['kind']) ?></span>
          <?php if (! empty($group['href'])) { ?>
          <a href="<?= $view->e($group['href']) ?>"<?= Referral::externalAttributeHtml($group['href'], standalone: true) ?>><?= $view->e($group['source']) ?></a>
          <?php } elseif (! empty($group['source'])) { ?>
          <span><?= $view->e($group['source']) ?></span>
          <?php } ?>
        </div>
        <p class="provenance-fields"><?= $view->e(implode(' · ', $group['fields'])) ?></p>
        <?php if (! empty($group['note'])) { ?>
        <p class="provenance-note"><?= $view->e($group['note']) ?></p>
        <?php } ?>
      </li>
      <?php } ?>
      </ul>
      <?php } ?>
      <?php if (! empty($bibliography)) { ?>
      <ol class="sources-bibliography">
        <?php foreach ($bibliography as $item) { ?>
        <li><a href="<?= $view->e($item['url']) ?>"<?= Referral::externalAttributeHtml($item['url'], standalone: true) ?>><?= $view->e($item['title']) ?></a></li>
        <?php } ?>
      </ol>
      <?php } ?>
    </div>
  </details>
</section>
<?php } ?>
