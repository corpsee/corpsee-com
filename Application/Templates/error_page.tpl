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
		<a class="wrapper-header" href="/<?= $language; ?>/gallery/list"></a>
		<div class="wrapper-inner">
			<?= $content; ?>
			<div class="comeback"><a href="/<?= $language; ?>" title="<?= $comeback; ?>"><< &nbsp;<?= $comeback; ?></a></div>
		</div>
	</div>
	<div class="footer vcard">
		<span class="fn nickname">Corpsee</span>
		<a href="/" title="corpsee.com"><img class="logo" src="<?= FILE_PATH_URL; ?>pictures/corpsee-logo.jpg" width="110" height="100" alt="corpsee.com" /></a>

		<div class="version-date">
			v9 / 2010-<?= date('Y'); ?>.
		</div>
		<div class="copyright">
			<a class="email" href="mailto:mail@corpsee.com" title="Email: mail@corpsee.com">mail@corpsee.com</a>
		</div>
	</div>
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