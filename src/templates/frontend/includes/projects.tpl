<?php
/**
 * @var string  $projects_title
 * @var array   $projects
 */
?>
<h2><?= $projects_title; ?></h2>

<?php foreach ($projects as $project): ?>
    <div class="pull_request">
        <div style="float: left; margin-right: 20px;"><a href="<?= $project['link']; ?>" title="><?= $project['title']; ?>"><?= $project['title']; ?></a></div>
        <div style="float: left; margin-right: 20px;"><?= $project['description']; ?></div>
        <div style="float: left;"><?= $project['role']; ?></div>
    </div>
<?php endforeach; ?>
