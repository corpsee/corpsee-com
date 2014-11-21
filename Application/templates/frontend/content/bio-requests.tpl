<h1><?= $requests_title; ?></h1>

<?php foreach ($pull_requests as $pull_request): ?>
    <div class="pull_request">
        <div class="pull_request_meta">
            <?= \DateTime::createFromFormat('U', $pull_request['create_date'])->format('Y-m-d'); ?>
            <div class="status status-<?= $pull_request['status']; ?>"><?= $pull_request['status']; ?></div>
            <?= $pull_request['repository']; ?>
        </div>
        <div class="pull_request_description">
            <a href="https://github.com/<?= $pull_request['repository']; ?>/pull/<?= $pull_request['number']; ?>"><?= $pull_request['title']; ?></a>
        </div>
    </div>
<?php endforeach; ?>

<div class="comeback"><a href="/<?= $language; ?>" title="<?= $comeback; ?>"><?= $comeback; ?></a></div>