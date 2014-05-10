<h1><?= $requests_title; ?></h1>

<?php foreach ($pull_requests as $pull_request): ?>
	<div class="pull_request">
		<?= \DateTime::createFromFormat('U', $pull_request['create_date'])->format('Y-m-d'); ?>
		<div class="status"><?= $pull_request['status']; ?></div>
		<?= $pull_request['repository']; ?> —
		<a href="https://github.com/<?= $pull_request['repository']; ?>/pull/<?= $pull_request['number']; ?>"><?= $pull_request['title']; ?></a>
	</div>
<?php endforeach; ?>

<div class="comeback"><a href="/<?= $language; ?>" title="<?= $comeback; ?>"><?= $comeback; ?></a></div>