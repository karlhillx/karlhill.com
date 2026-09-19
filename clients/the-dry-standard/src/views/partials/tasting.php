<section class="tasting">
  <h2>Tasting notes</h2>
  <ol class="tasting-flight">
    <?php foreach ($notes as $index => $note) { ?>
    <li>
      <p class="tasting-index"><?= sprintf('%02d', $index + 1) ?></p>
      <h3><?= $view->e($note['label']) ?></h3>
      <p><?= $view->e($note['text']) ?></p>
    </li>
    <?php } ?>
  </ol>
</section>
