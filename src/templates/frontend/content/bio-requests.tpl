<?php
/**
 * @var string  $language
 * @var string  $comeback
 * @var integer $year
 * @var array   $years
 */
?>

<div class="comeback">
    <?php foreach ($years as $yearNumber => $yearCount): ?>
        <?php if ($yearNumber === $year): ?>
            <span><?= $yearNumber; ?></span>
        <?php else: ?>
            <a href="/<?= $language; ?>/bio/requests/<?= $yearNumber; ?>" title="<?= $yearNumber; ?>"><?= $yearNumber; ?></a>
        <?php endif; ?>
        (<?= $yearCount; ?>)
        &nbsp;&nbsp;<?php if ($yearNumber !== 2013): ?>|&nbsp;&nbsp;<?php endif; ?>
    <?php endforeach; ?>
</div>

<?= $this->subTemplate('frontend/includes/pull-requests'); ?>

<div class="comeback">
    <a href="/<?= $language; ?>" title="<?= $comeback; ?>"><?= $comeback; ?></a>
</div>
