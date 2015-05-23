<?php
/**
 * @var string  $language
 * @var string  $comeback
 * @var integer $year
 */
?>

<div class="comeback">
    <?php for ($i = (integer)date('Y'); $i >= 2013; $i--): ?>
        <?php if ($i === $year): ?>
            <span><?= $i; ?></span> &nbsp;&nbsp;
        <?php else: ?>
            <a href="/<?= $language; ?>/bio/requests/<?= $i; ?>" title="<?= $i; ?>"><?= $i; ?></a> &nbsp;&nbsp;
        <?php endif; ?>
    <?php endfor; ?>
</div>

<?= $this->subTemplate('frontend/includes/pull-requests'); ?>

<div class="comeback">
    <a href="/<?= $language; ?>" title="<?= $comeback; ?>"><?= $comeback; ?></a>
</div>
