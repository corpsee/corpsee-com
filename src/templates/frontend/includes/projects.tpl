<?php
/**
 * @var string  $projects_title
 * @var array   $projects
 */
?>

<?php if ($projects): ?>
    <h2><?= $projects_title; ?></h2>

    <table class="projects">
        <?php foreach ($projects as $project): ?>
            <tr>
                <td width="20%">
                    <a href="<?= $project['link']; ?>" title="<?= $project['title']; ?>"><?= $project['title']; ?></a>
                </td>
                <td>
                    <?= $project['description']; ?> (<?= $project['role']; ?>)
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>