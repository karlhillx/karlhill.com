<?php
$type = $type ?? 'text';
$required = ! empty($required);
$error = (string) ($error ?? '');
$id = 'field-'.preg_replace('/[^a-z0-9-]+/', '-', strtolower((string) $name));
?>
<div class="field<?= $error !== '' ? ' is-invalid' : '' ?>">
  <label for="<?= $view->e($id) ?>"><?= $view->e($label) ?><?php if ($required) { ?> <abbr title="required">*</abbr><?php } ?></label>
  <input
    id="<?= $view->e($id) ?>"
    name="<?= $view->e($name) ?>"
    type="<?= $view->e($type) ?>"
    value="<?= $view->e($value ?? '') ?>"
    <?php if ($required) { ?>required<?php } ?>
    <?php if (! empty($autocomplete)) { ?>autocomplete="<?= $view->e($autocomplete) ?>"<?php } ?>
    <?php if ($error !== '') { ?>aria-invalid="true" aria-describedby="<?= $view->e($id) ?>-error"<?php } ?>
  >
  <?php if (! empty($hint)) { ?><p class="field-hint"><?= $view->e($hint) ?></p><?php } ?>
  <?php if ($error !== '') { ?><p class="field-error" id="<?= $view->e($id) ?>-error"><?= $view->e($error) ?></p><?php } ?>
</div>
