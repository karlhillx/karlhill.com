<aside class="editor-desk" aria-label="Editor contact">
  <p class="kicker">Who replies</p>
  <p><strong><?= $view->e($name) ?></strong>, <?= $view->e(strtolower((string) $role)) ?></p>
  <?php if (! empty($mailto)) { ?>
  <p><a href="<?= $view->e($mailto) ?>"><?= $view->e($email) ?></a></p>
  <?php } ?>
  <p>We reply within three business days. Sample shipping instructions are sent in that reply — we do not publish a receiving address.</p>
</aside>
