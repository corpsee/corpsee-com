<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?= $language; ?>">

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

		<!-- Header link-image -->
		<a class="wrapper-header" href="<?= $gallery_link; ?>"></a>

		<div class="wrapper-inner">
			<?= $content; ?>
			<div class="comeback"><a href="/<?= $language; ?>" title="<?= $comeback; ?>"><< &nbsp;<?= $comeback; ?></a></div>
		</div>
	</div>

	<?= $this->subTemplate('frontend/includes/frontend-footer'); ?>

	<?= $scripts; ?>

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

