<?php
/**
 * @var string $language
 * @var string $comeback
 */
?>
<?= $this->subTemplate('frontend/includes/pull-requests'); ?>

<div class="comeback">
    <a href="/<?= $language; ?>" title="<?= $comeback; ?>"><?= $comeback; ?></a>
</div>