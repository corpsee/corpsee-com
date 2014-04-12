<h1><?= $requests_title; ?></h1>

<?php foreach ($pull_requests as $pull_request): ?>
	<?php
		$data = $pull_request['data'];
		$data = preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $data);
		$data = unserialize($data);
	?>
	<p><?= $data['base']['repo']['full_name']; ?> — <a href="<?= $data['html_url']; ?>"><?= $data['title']; ?></a></p>
<?php endforeach; ?>

<div class="comeback"><a href="/<?= $language; ?>" title="<?= $comeback; ?>"><?= $comeback; ?></a></div>


