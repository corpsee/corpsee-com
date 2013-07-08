<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
	<head>
		<title><?php echo $page['title']; ?></title>
		<meta http-equiv="content-Language" content="ru" />
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<meta name="description" content="<?php echo $page['description']; ?>" />
		<meta name="keywords" content="<?php echo $page['keywords']; ?>" />

		<?php foreach ($styles as $style): ?>
			<link href="<?php echo $style; ?>" rel="stylesheet" type="text/css" />
		<?php endforeach; ?>
	</head>
	<body>

	<div class="wrapper">
		<?php $this->subTemplate('back_menu'); ?>
		<div class="content">
			<?php $this->subTemplate($subtemplates['content']); ?>
			<span id="file_path" style="display: none;"><?php echo FILE_PATH_URL; ?></span>
		</div>
	</div>
	<footer>
		<a href="mailto:mail@corpsee.com" title="Email: mail@corpsee.com"><img src="<?php echo FILE_PATH_URL; ?>pictures/corpsee-logo.jpg" width="110" height="100" alt="Corpsee" /></a> 2010 — <?php echo date('Y'); ?>.
	</footer>

	<?php foreach ($scripts as $script): ?>
		<script src="<?php echo $script; ?>" type="text/javascript"></script>
	<?php endforeach; ?>

    </body>
</html>