<?php if ($rating !== null) { ?>
<p class="score<?= ! empty($compact) ? ' score--compact' : '' ?>" aria-label="Score <?= (int) $rating ?> out of 100">
  <span><?= (int) $rating ?></span><small>/100</small>
  <?php if (! empty($methodologyUrl)) { ?>
  <a class="score-method" href="<?= $view->e($methodologyUrl) ?>">How we score</a>
  <?php } ?>
</p>
<?php } ?>
