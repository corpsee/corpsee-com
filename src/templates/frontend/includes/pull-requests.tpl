<?php
/**
 * @var string  $requests_title
 * @var array   $pull_requests
 * @var integer $year
 */
?>
<h2><?= $requests_title; ?><?= isset($year) ? ('. ' . $year) : ''; ?></h2>

<?php foreach ($pull_requests as $pull_request): ?>
    <div class="pull_request">
        <div class="pull_request_meta">
            <?= \DateTime::createFromFormat(POSTGRES, $pull_request['create_date'])->format('Y-m-d'); ?>
            <div class="status status-<?= $pull_request['status']; ?>"><?= $pull_request['status']; ?></div>
            <?= $pull_request['repository']; ?>
        </div>
        <div class="pull_request_description">
            <a href="https://github.com/<?= $pull_request['repository']; ?>/pull/<?= $pull_request['number']; ?>"><?= $pull_request['title']; ?></a>
        </div>
    </div>
<?php endforeach; ?>
