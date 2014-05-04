<h1><?= $requests_title; ?></h1>

<?php foreach ($pull_requests as $pull_request): ?>
	<p><?= $pull_request['repository']; ?> — <a href="https://github.com/<?= $pull_request['repository']; ?>/pull/<?= $pull_request['number']; ?>"><?= $pull_request['title']; ?></a></p>
<?php endforeach; ?>

<div class="comeback"><a href="/<?= $language; ?>" title="<?= $comeback; ?>"><?= $comeback; ?></a></div>