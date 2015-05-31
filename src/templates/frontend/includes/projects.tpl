<?php
/**
 * @var string  $projects_title
 * @var array   $projects
 */
?>
<div class="content-block">
    <h2><?= $projects_title; ?></h2>

    <?php foreach ($projects as $project): ?>
        <div class="content-row">
            <a href="<?= $project['link']; ?>" title="><?= $project['title']; ?>"><?= $project['title']; ?></a> — <?= $project['description']; ?> (<?= $project['role']; ?>).
        </div>
    <?php endforeach; ?>

    <!--<div class="details-link">
        <a href="/<?= $language; ?>/bio/projects" title="<?= $projects_link; ?>"><?= $projects_link; ?></a>
    </div>-->
</div>
