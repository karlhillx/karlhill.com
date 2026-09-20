<?php if (! empty($groups)) { ?>
<details class="provenance" id="provenance">
  <summary>
    <span class="provenance-summary-title">Evidence</span>
    <span class="provenance-summary-meta"><?= $view->e($summary) ?></span>
  </summary>
  <div class="provenance-body">
    <p class="provenance-lede">Product facts are only as strong as their sources. Editorial tasting notes are opinion.</p>
    <ul class="provenance-groups">
      <?php foreach ($groups as $group) { ?>
      <li>
        <div class="provenance-group-head">
          <span class="provenance-confidence provenance-confidence--<?= $view->e($group['confidenceClass']) ?>"><?= $view->e($group['confidence']) ?></span>
          <span class="provenance-kind"><?= $view->e($group['kind']) ?></span>
          <?php if (! empty($group['href'])) { ?>
          <a href="<?= $view->e($group['href']) ?>" rel="nofollow noopener"><?= $view->e($group['source']) ?></a>
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
  </div>
</details>
<?php } ?>
