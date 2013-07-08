<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
	<title>Ошибка 403. Доступ запрещен.</title>
	<meta http-equiv="content-Language" content="ru" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="<?php echo FILE_PATH_URL; ?>styles/frontend.min.css" />
</head>
<body>
<div class="wrapper">
	<div class="wrapper-inner">
		<div id="nav">
			Упорядочить список: &nbsp;&nbsp;
			<a href="/">по дате</a>&nbsp;&nbsp; | &nbsp;&nbsp;
			<a href="/bytag">по меткам</a>
		</div>

		<h1>Ошибка 403</h1>
		<p>Доступ по этому адресу запрещен. Попробуйте <a href="/admin" title="Вход для администратора сайта">войти</a> в административную панель.</p>
		<p><a href="/" title="Вернуться на главную">Вернуться на главную</a></p>
	</div>
</div>
<div id="footer" class="vcard">
	<span class="fn nickname">Corpsee</span>
	<img id="logo" class="logo" src="<?php echo FILE_PATH_URL; ?>pictures/corpsee-logo.jpg" width="110" height="100" alt="corpsee.com" />
	<div id="year">v5 / 2010-<?php echo date('Y'); ?>.</div>
	<div id="links">
		<a class="email" href="mailto:mail@corpsee.com" title="Email: mail@corpsee.com">mail@corpsee.com</a>
	</div>
</div>

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
