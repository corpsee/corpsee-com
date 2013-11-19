<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
	<head>
		<title><?= $page['title']; ?></title>
		<meta http-equiv="content-Language" content="ru" />
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<meta name="description" content="<?= $page['description']; ?>" />
		<meta name="keywords" content="<?= $page['keywords']; ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" href="/favicon.png">

		<?= $styles; ?>
	</head>
	<body>

		<div class="wrapper">
			<div class="wrapper-inner">
				<div class="container">
					<?= $this->subTemplate($subtemplates['content']); ?>
					<span id="file_path" style="display: none;"><?= FILE_PATH_URL; ?></span>
				</div>
			</div>
		</div>
		<footer>
			<a href="mailto:mail@corpsee.com" title="Email: mail@corpsee.com"><img src="<?= FILE_PATH_URL; ?>pictures/corpsee-logo.jpg" width="110" height="100" alt="Corpsee" /></a> 2010 — <?= date('Y'); ?>.
		</footer>

	<?= $scripts; ?>

	</body>
</html>