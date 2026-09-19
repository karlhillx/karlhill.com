<?php
$old = $old ?? [];
$errors = $errors ?? [];
$value = static fn (string $key, string $default = ''): string => (string) ($old[$key] ?? $default);
$selected = static fn (string $key, string $want): bool => $value($key) === $want;
?>
    <?= $view->render('partials/page-header', [
        'breadcrumbs' => $breadcrumbs,
        'kicker' => 'Industry',
        'title' => 'Partnerships & business inquiries',
        'lede' => 'A professional inbox for collaborations. Keep it specific; we read it as editors, not as a media kit mill.',
        'narrow' => true,
    ]) ?>
    <section class="section">
      <div class="shell shell--narrow">
        <?php if (! empty($sent)) { ?>
        <p class="notice notice--ok" role="status">Received. We reply within three business days from <?= $view->e($editorEmail ?? 'drinkdrystandard@gmail.com') ?>.</p>
        <?php } ?>
        <?php if (! empty($failed)) { ?>
        <p class="notice notice--error" role="alert">Couldn't save that just now. Email <?= $view->e($editorEmail ?? 'drinkdrystandard@gmail.com') ?> directly and we'll pick it up from there.</p>
        <?php } ?>
        <form class="intake-form" method="post" action="<?= $view->e($action) ?>" novalidate>
          <input type="hidden" name="_token" value="<?= $view->e($csrf ?? '') ?>">
          <p class="visually-hidden" hidden>
            <label for="inquiry-fax">Fax</label>
            <input id="inquiry-fax" type="text" name="fax" tabindex="-1" autocomplete="off">
          </p>
          <?= $view->render('partials/form-field', ['name' => 'name', 'label' => 'Name', 'value' => $value('name'), 'error' => $errors['name'] ?? '', 'required' => true, 'autocomplete' => 'name']) ?>
          <?= $view->render('partials/form-field', ['name' => 'organization', 'label' => 'Organization', 'value' => $value('organization'), 'error' => $errors['organization'] ?? '', 'required' => true, 'autocomplete' => 'organization']) ?>
          <?= $view->render('partials/form-field', ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'value' => $value('email'), 'error' => $errors['email'] ?? '', 'required' => true, 'autocomplete' => 'email']) ?>
          <div class="field<?= ! empty($errors['topic']) ? ' is-invalid' : '' ?>">
            <label for="inquiry-topic">Topic</label>
            <select id="inquiry-topic" name="topic" required>
              <option value="">Select one</option>
              <?php foreach ([
                  'advertising' => 'Advertising or sponsorship',
                  'retail' => 'Retail or distribution',
                  'partnership' => 'Partnership or collaboration',
                  'other' => 'Something else',
              ] as $topicValue => $topicLabel) { ?>
              <option value="<?= $view->e($topicValue) ?>"<?= $selected('topic', $topicValue) ? ' selected' : '' ?>><?= $view->e($topicLabel) ?></option>
              <?php } ?>
            </select>
            <?php if (! empty($errors['topic'])) { ?><p class="field-error"><?= $view->e($errors['topic']) ?></p><?php } ?>
          </div>
          <?= $view->render('partials/form-field', ['name' => 'url', 'label' => 'Company URL', 'type' => 'url', 'value' => $value('url'), 'error' => $errors['url'] ?? '']) ?>
          <div class="field<?= ! empty($errors['message']) ? ' is-invalid' : '' ?>">
            <label for="inquiry-message">Message</label>
            <textarea id="inquiry-message" name="message" rows="6" required><?= $view->e($value('message')) ?></textarea>
            <?php if (! empty($errors['message'])) { ?><p class="field-error"><?= $view->e($errors['message']) ?></p><?php } ?>
          </div>
          <p class="fine-print"><?php if (! empty($editorMailto)) { ?>We reply within three business days from <a href="<?= $view->e($editorMailto) ?>"><?= $view->e($editorEmail) ?></a>. <?php } ?>This is not a product-submission form. To have a bottle considered for review, <a href="<?= $view->e($submitUrl) ?>">submit a product</a>. See the <a href="<?= $view->e($privacyUrl) ?>">privacy policy</a>.</p>
          <p><button class="btn" type="submit">Send inquiry</button></p>
        </form>
      </div>
    </section>
