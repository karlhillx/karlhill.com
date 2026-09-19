<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#14110e">
  <meta name="color-scheme" content="light">
  <base href="/clients/the-dry-standard/">
  <title><?= $view->e($fullTitle) ?></title>
  <meta name="description" content="<?= $view->e($description) ?>">
  <link rel="canonical" href="<?= $view->e($canonical) ?>">
  <meta property="og:site_name" content="<?= $view->e($siteName) ?>">
  <meta property="og:title" content="<?= $view->e($title) ?>">
  <meta property="og:description" content="<?= $view->e($description) ?>">
  <meta property="og:type" content="<?= $view->e($ogType) ?>">
  <meta property="og:url" content="<?= $view->e($canonical) ?>">
<?= $ogImage ?>
  <meta name="twitter:title" content="<?= $view->e($title) ?>">
  <meta name="twitter:description" content="<?= $view->e($description) ?>">
  <link rel="alternate" type="application/atom+xml" title="<?= $view->e($siteName) ?> reviews" href="<?= $view->e($feedUrl) ?>">
  <link rel="icon" href="<?= $view->e($iconUrl) ?>" type="image/svg+xml">
  <link rel="preload" href="<?= $view->e($fontDisplay) ?>" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="<?= $view->e($fontSans) ?>" as="font" type="font/woff2" crossorigin>
  <link rel="stylesheet" href="<?= $view->e($stylesheet) ?>">
  <?= $extraHead ?>
  <?= $jsonLd ?>
</head>
<body<?= ! empty($bodyClass) ? ' class="'.$view->e($bodyClass).'"' : '' ?><?= ! empty($bodyAttrs) ? ' '.$bodyAttrs : '' ?>>
  <a class="skip-link" href="#main">Skip to content</a>
  <?= $header ?>
  <main id="main">
    <?= $body ?>
  </main>
  <?= $footer ?>
  <script src="<?= $view->e($script) ?>" defer></script>
</body>
</html>
