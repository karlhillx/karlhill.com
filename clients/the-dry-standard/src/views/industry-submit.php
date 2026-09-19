<?php
$old = $old ?? [];
$errors = $errors ?? [];
$value = static fn (string $key, string $default = ''): string => (string) ($old[$key] ?? $default);
$checked = static fn (string $key, string $want): bool => $value($key) === $want;
$selected = static fn (string $key, string $want): bool => $value($key) === $want;
?>
    <?= $view->render('partials/page-header', [
        'breadcrumbs' => $breadcrumbs,
        'kicker' => 'Industry',
        'title' => 'Submit a product',
        'lede' => 'For editorial consideration only. We do not publish inbound submissions automatically, and a sample does not buy a score.',
        'narrow' => true,
    ]) ?>
    <section class="section">
      <div class="shell shell--narrow">
        <?php if (! empty($sent)) { ?>
        <p class="notice notice--ok" role="status">Received. We reply within three business days from <?= $view->e($editorEmail ?? 'drinkdrystandard@gmail.com') ?>. Nothing is published until an editor verifies it.</p>
        <?php } ?>
        <form class="intake-form" method="post" action="<?= $view->e($action) ?>" novalidate>
          <input type="hidden" name="_token" value="<?= $view->e($csrf ?? '') ?>">
          <p class="visually-hidden" hidden>
            <label for="submit-fax">Fax</label>
            <input id="submit-fax" type="text" name="fax" tabindex="-1" autocomplete="off">
          </p>
          <fieldset>
            <legend>Required</legend>
            <?= $view->render('partials/form-field', ['name' => 'company', 'label' => 'Company', 'value' => $value('company'), 'error' => $errors['company'] ?? '', 'required' => true, 'autocomplete' => 'organization']) ?>
            <?= $view->render('partials/form-field', ['name' => 'brand', 'label' => 'Brand', 'value' => $value('brand'), 'error' => $errors['brand'] ?? '', 'required' => true]) ?>
            <?= $view->render('partials/form-field', ['name' => 'product_name', 'label' => 'Product name', 'value' => $value('product_name'), 'error' => $errors['product_name'] ?? '', 'required' => true]) ?>
            <div class="field<?= ! empty($errors['category']) ? ' is-invalid' : '' ?>">
              <label for="submit-category">Category</label>
              <select id="submit-category" name="category" required<?= ! empty($errors['category']) ? ' aria-invalid="true"' : '' ?>>
                <option value="">Select one</option>
                <?php foreach ($categories as $category) { ?>
                <option value="<?= $view->e($category['value']) ?>"<?= $selected('category', $category['value']) ? ' selected' : '' ?>><?= $view->e($category['label']) ?></option>
                <?php } ?>
                <option value="other"<?= $selected('category', 'other') ? ' selected' : '' ?>>Other</option>
              </select>
              <?php if (! empty($errors['category'])) { ?><p class="field-error" id="submit-category-error"><?= $view->e($errors['category']) ?></p><?php } ?>
            </div>
            <?= $view->render('partials/form-field', ['name' => 'contact_name', 'label' => 'Contact name', 'value' => $value('contact_name'), 'error' => $errors['contact_name'] ?? '', 'required' => true, 'autocomplete' => 'name']) ?>
            <?= $view->render('partials/form-field', ['name' => 'contact_email', 'label' => 'Contact email', 'type' => 'email', 'value' => $value('contact_email'), 'error' => $errors['contact_email'] ?? '', 'required' => true, 'autocomplete' => 'email']) ?>
            <div class="field<?= ! empty($errors['role']) ? ' is-invalid' : '' ?>">
              <label for="submit-role">Your relationship to the product</label>
              <select id="submit-role" name="role" required>
                <option value="">Select one</option>
                <?php foreach (['brand' => 'Brand / producer', 'importer' => 'Importer', 'distributor' => 'Distributor', 'pr' => 'PR or agency', 'retailer' => 'Retailer', 'other' => 'Other'] as $roleValue => $roleLabel) { ?>
                <option value="<?= $view->e($roleValue) ?>"<?= $selected('role', $roleValue) ? ' selected' : '' ?>><?= $view->e($roleLabel) ?></option>
                <?php } ?>
              </select>
              <?php if (! empty($errors['role'])) { ?><p class="field-error"><?= $view->e($errors['role']) ?></p><?php } ?>
            </div>
          </fieldset>
          <fieldset>
            <legend>Helpful if you have it</legend>
            <?= $view->render('partials/form-field', ['name' => 'abv', 'label' => 'ABV', 'value' => $value('abv'), 'error' => $errors['abv'] ?? '', 'hint' => 'Must be 0.5% or less to be reviewed.']) ?>
            <?= $view->render('partials/form-field', ['name' => 'website', 'label' => 'Company website', 'type' => 'url', 'value' => $value('website'), 'error' => $errors['website'] ?? '']) ?>
            <?= $view->render('partials/form-field', ['name' => 'product_url', 'label' => 'Product URL', 'type' => 'url', 'value' => $value('product_url'), 'error' => $errors['product_url'] ?? '']) ?>
            <?= $view->render('partials/form-field', ['name' => 'ean', 'label' => 'UPC / EAN / GTIN', 'value' => $value('ean'), 'error' => $errors['ean'] ?? '']) ?>
            <?= $view->render('partials/form-field', ['name' => 'country', 'label' => 'Country of origin', 'value' => $value('country'), 'error' => $errors['country'] ?? '']) ?>
            <?= $view->render('partials/form-field', ['name' => 'producer', 'label' => 'Manufacturer / producer', 'value' => $value('producer'), 'error' => $errors['producer'] ?? '']) ?>
            <div class="field">
              <label for="submit-method">Production or dealcoholization method</label>
              <textarea id="submit-method" name="method" rows="3"><?= $view->e($value('method')) ?></textarea>
            </div>
            <div class="field">
              <label for="submit-notes">Distribution, ingredients, or other notes</label>
              <textarea id="submit-notes" name="notes" rows="4"><?= $view->e($value('notes')) ?></textarea>
            </div>
            <div class="field">
              <label>
                <input type="checkbox" name="sample_offered" value="1"<?= $checked('sample_offered', '1') ? ' checked' : '' ?>>
                We can send a sample if useful
              </label>
            </div>
          </fieldset>
          <p class="fine-print">We will not publish this form as a review. <?php if (! empty($editorMailto)) { ?>We reply within three business days from <a href="<?= $view->e($editorMailto) ?>"><?= $view->e($editorEmail) ?></a>. <?php } ?>See the <a href="<?= $view->e($samplesUrl) ?>">editorial sample policy</a> and <a href="<?= $view->e($privacyUrl) ?>">privacy policy</a>.</p>
          <p><button class="btn" type="submit">Send for editorial consideration</button></p>
        </form>
      </div>
    </section>
