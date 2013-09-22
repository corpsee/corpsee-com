<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">

<head>
	<title><?= $page['title']; ?></title>
	<meta http-equiv="content-Language" content="ru" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="<?= $page['description']; ?>" />
	<meta name="keywords" content="<?= $page['keywords']; ?>" />
	<?= $styles; ?>
</head>

<body>
	<div class="wrapper">
		<div class="language-menu">
			<?php foreach ($languages as $lang_key => $lang): ?>

				<?php if ($language !== $lang): ?>
					<a href="/<?= $lang; ?>/<?= $page_link; ?>"><?= $lang; ?></a>
				<?php else: ?>
					<?= $lang; ?>
				<?php endif; ?>

				<?php if ($lang_key < (sizeof($languages) - 1)): ?>
					&nbsp; | &nbsp;
				<?php endif; ?>

			<?php endforeach; ?>
		</div>
		<a class="wrapper-header" href="/<?= $language; ?>/gallery/list"></a>
		<div class="wrapper-inner">
			<?= $this->subTemplate($subtemplates['content']); ?>
		</div>
	</div>
	<?= $this->subTemplate('footer'); ?>
	<span id="file_path" style="display: none;"><?php echo FILE_PATH_URL; ?></span>

	<?php echo $scripts; ?>

<!--Google Analytics-->
<script type="text/javascript">
var _gaq = _gaq || [];
var pluginUrl =
 '//www.google-analytics.com/plugins/ga/inpage_linkid.js';
_gaq.push(['_require', 'inpage_linkid', pluginUrl]);
_gaq.push(['_setAccount', 'UA-17998345-1']);
_gaq.push(['_trackPageview']);

(function ()
{
	var ga = document.createElement('script');
	ga.type = 'text/javascript';
	ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(ga, s);
})();
</script>

</body>
</html>
