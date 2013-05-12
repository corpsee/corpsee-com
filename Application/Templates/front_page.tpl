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
		<div class="wrapper-inner">
			<?php $this->getSubTemplate($subtemplates['content']); ?>
		</div>
	</div>
	<?php $this->getSubTemplate('footer'); ?>
	<span id="file_path" style="display: none;"><?php echo FILE_PATH_URL; ?></span>

	<?php foreach ($scripts as $script): ?>
		<script src="<?php echo $script; ?>" type="text/javascript"></script>
	<?php endforeach; ?>

<!--Google Analytics-->
<!--<script type="text/javascript">
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
</script>-->
<!--Yandex Metrika-->
<!--<script type="text/javascript">
(function (d, w, c)
{
	(w[c] = w[c] || []).push(function ()
	{
		try
		{
			w.yaCounter21230416 = new Ya.Metrika({id: 21230416, webvisor: true, clickmap: true, trackLinks: true, accurateTrackBounce: true});
		}
		catch (e) {}
	});

	var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); };
	s.type = "text/javascript";
	s.async = true;
	s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

	if (w.opera == "[object Opera]")
	{
		d.addEventListener("DOMContentLoaded", f, false);
	}
	else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/21230416" style="position:absolute; left:-9999px;" alt="" /></div></noscript>-->

</body>
</html>
