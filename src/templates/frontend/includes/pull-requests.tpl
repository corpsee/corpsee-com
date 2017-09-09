<?php
/**
 * @var string  $requests_title
 * @var array   $pull_requests
 * @var integer $year
 */
?>
<h2><?= $requests_title; ?><?= isset($year) ? ('. ' . $year) : ''; ?></h2>

<style>

</style>

<table class="pull-requests">
<?php foreach ($pull_requests as $pull_request): ?>
    <tr>
        <td>
            <?= \DateTime::createFromFormat(POSTGRES, $pull_request['create_date'])->format('Y-m-d'); ?>
        </td>
        <td>
            <div class="status status-<?= $pull_request['status']; ?>"><?= $pull_request['status']; ?></div>
        </td>
        <td width="25%">
            <?= $pull_request['repository']; ?>
        </td>
        <td>
            <a href="https://github.com/<?= $pull_request['repository']; ?>/pull/<?= $pull_request['number']; ?>"><?= $pull_request['title']; ?></a>
        </td>
    </tr>
<?php endforeach; ?>
</table>
