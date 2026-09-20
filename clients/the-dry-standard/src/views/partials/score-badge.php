<?php if ($rating !== null) { ?>
<p class="score<?= ! empty($compact) ? ' score--compact' : '' ?>" aria-label="Score <?= (int) $rating ?> out of 100<?= ! empty($band) ? ', '.$view->e($band) : '' ?>">
  <span><?= (int) $rating ?></span><small>/100</small>
  <?php if (! empty($band)) { ?>
  <em class="score-band"><?= $view->e($band) ?></em>
  <?php } ?>
</p>
<?php } ?>
