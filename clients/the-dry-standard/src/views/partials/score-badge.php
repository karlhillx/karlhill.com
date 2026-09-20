<?php if ($rating !== null) { ?>
<p class="score<?= ! empty($compact) ? ' score--compact' : '' ?><?= ! empty($scoreKind) ? ' score--research' : '' ?>" aria-label="<?= $view->e(($scoreKind ? $scoreKind.' ' : '').'Score '.(int) $rating.' out of 100') ?>">
  <span><?= (int) $rating ?></span><small>/100</small>
  <?php if (! empty($scoreKind)) { ?>
  <span class="score-kind"><?= $view->e($scoreKind) ?></span>
  <?php } ?>
  <?php if (! empty($methodologyUrl)) { ?>
  <a class="score-method" href="<?= $view->e($methodologyUrl) ?>">How we score</a>
  <?php } ?>
</p>
<?php } ?>
