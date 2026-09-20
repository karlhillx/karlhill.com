<?php if ($rating !== null) { ?>
<p class="score<?= ! empty($compact) ? ' score--compact' : '' ?>" aria-label="Score <?= (int) $rating ?> out of 100">
  <span><?= (int) $rating ?></span><small>/100</small>
</p>
<?php } ?>
