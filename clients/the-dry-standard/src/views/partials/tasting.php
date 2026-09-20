<section class="tasting" id="tasting">
  <h2><?= $view->e($heading) ?></h2>
  <?php if (! empty($showGlance)) { ?>
  <dl class="tasting-glance">
    <?php if (! empty($tastes)) { ?>
    <div>
      <dt>Flavor profile</dt>
      <dd><ul class="tasting-chips"><?php foreach ($tastes as $taste) { ?><li><?= $view->e($taste) ?></li><?php } ?></ul></dd>
    </div>
    <?php } ?>
    <?php if (! empty($profile)) { ?>
    <div>
      <dt>Structure</dt>
      <dd><ul class="tasting-chips"><?php foreach ($profile as $item) { ?><li><?= $view->e($item) ?></li><?php } ?></ul></dd>
    </div>
    <?php } ?>
    <?php if (! empty($mouthfeel)) { ?>
    <div>
      <dt>Mouthfeel</dt>
      <dd><?= $view->e($mouthfeel) ?></dd>
    </div>
    <?php } ?>
    <?php if (! empty($assessments)) { ?>
    <div>
      <dt>Assessment</dt>
      <dd><ul class="tasting-chips"><?php foreach ($assessments as $item) { ?><li><?= $view->e($item) ?></li><?php } ?></ul></dd>
    </div>
    <?php } ?>
    <?php if (! empty($highlight)) { ?>
    <div>
      <dt>What stands out</dt>
      <dd><?= $view->e($highlight) ?></dd>
    </div>
    <?php } ?>
    <?php if (! empty($likeness)) { ?>
    <div>
      <dt><?= $view->e($likenessHeading) ?></dt>
      <dd><?= $view->e($likeness) ?></dd>
    </div>
    <?php } ?>
    <?php if (! empty($perfectFor)) { ?>
    <div>
      <dt>Perfect for</dt>
      <dd><?= $view->e($perfectFor) ?></dd>
    </div>
    <?php } ?>
    <?php if (! empty($drinkIfYouLike)) { ?>
    <div>
      <dt>Drink if you like</dt>
      <dd><ul class="tasting-chips"><?php foreach ($drinkIfYouLike as $item) { ?><li><?= $view->e($item) ?></li><?php } ?></ul></dd>
    </div>
    <?php } ?>
    <?php if (! empty($productionLine)) { ?>
    <div>
      <dt>Production</dt>
      <dd><?= $view->e($productionLine) ?></dd>
    </div>
    <?php } ?>
  </dl>
  <?php } ?>
  <?php if (! empty($notes)) { ?>
  <?php if (! empty($detailTitle)) { ?>
  <h3 class="tasting-detail-title"><?= $view->e($detailTitle) ?></h3>
  <?php } ?>
  <ol class="tasting-flight">
    <?php foreach ($notes as $index => $note) { ?>
    <li>
      <p class="tasting-index"><?= sprintf('%02d', $index + 1) ?></p>
      <h3><?= $view->e($note['label']) ?></h3>
      <p><?= $view->e($note['text']) ?></p>
    </li>
    <?php } ?>
  </ol>
  <?php } ?>
</section>
