<?php
/**
 * @var string $language
 * @var string $sort_header
 * @var string $sort_by_tags
 * @var string $sort_by_date
 * @var string $header
 * @var array  $pictures
 * @var string $comeback
 */
?>
<div class="sort-menu">
    <?= $sort_header; ?> &nbsp;&nbsp;
    <a href="/<?= $language; ?>/gallery/list"><?= $sort_by_date; ?></a>&nbsp;&nbsp; | &nbsp;&nbsp;
    <a href="/<?= $language; ?>/gallery/bytag"><?= $sort_by_tags; ?></a>
</div>

<h1><?= $header; ?></h1>

<?php $i = 0; ?>
<?php foreach ($pictures as $picture): ?>

    <?php if ($i == 4) {
        $i = 1;
    } else {
        $i++;
    }; ?>

    <?php if ($i == 1): ?>
        <div class="img-str">
        <div class="img-box first">
            <a rel="lightbox[gallery]" href="<?= FILE_PATH_URL; ?>pictures/x/<?= $picture['image']; ?>.jpg"
               class="pirobox_gr" title="«<?= $picture['title']; ?>» / <?= $picture['create_date']; ?>"><img src="<?= FILE_PATH_URL; ?>pictures/xgray/<?= $picture['image']; ?>-gray.jpg" id="<?= $picture['image']; ?>" width="200" height="90" class="gr-col" alt="«<?= $picture['title']; ?>» / <?= $picture['create_date']; ?>"/></a>
        </div>
    <?php elseif ($i == 4): ?>
        <div class="img-box last">
            <a rel="lightbox[gallery]" href="<?= FILE_PATH_URL; ?>pictures/x/<?= $picture['image']; ?>.jpg"
               class="pirobox_gr" title="«<?= $picture['title']; ?>» / <?= $picture['create_date']; ?>"><img src="<?= FILE_PATH_URL; ?>pictures/xgray/<?= $picture['image']; ?>-gray.jpg" id="<?= $picture['image']; ?>" width="200" height="90" class="gr-col" alt="«<?= $picture['title']; ?>» / <?= $picture['create_date']; ?>"/></a>
        </div>
        </div>
    <?php
    else: ?>
        <div class="img-box">
            <a rel="lightbox[gallery]" href="<?= FILE_PATH_URL; ?>pictures/x/<?= $picture['image']; ?>.jpg"
               class="pirobox_gr" title="«<?= $picture['title']; ?>» / <?= $picture['create_date']; ?>"><img src="<?= FILE_PATH_URL; ?>pictures/xgray/<?= $picture['image']; ?>-gray.jpg" id="<?= $picture['image']; ?>" width="200" height="90" class="gr-col" alt="«<?= $picture['title']; ?>» / <?= $picture['create_date']; ?>"/></a>
        </div>
    <?php endif; ?>

<?php endforeach; ?>

<?php if ($i != 4): ?>
    </div>
<?php endif; ?>
<div class="comeback"><a href="/<?= $language; ?>/gallery/list" title="<?= $comeback; ?>"><?= $comeback; ?></a></div>
