<footer class="vcard">
	<span class="fn nickname">Corpsee</span>
	<a href="/" title="corpsee.com"><img class="logo" src="<?= FILE_PATH_URL; ?>pictures/corpsee-logo.jpg" width="110" height="100" alt="corpsee.com" /></a>
	<div class="version-date">
		v16 / 2010-<?= date('Y'); ?>.
	</div>
	<div class="banchmark">
		<small><?= $benchmark; ?><br /><?= $total['time']; ?>c / <?= $total['memory']; ?>.</small>
	</div>
	<div class="copyright" style="line-height: 1.2;">
		<a class="email" href="mailto:mail@corpsee.com" title="Email: mail@corpsee.com">mail@corpsee.com</a><br />
		<a class="email" href="mailto:poisoncorpsee@gmail.com" title="Email: poisoncorpsee@gmail.com">poisoncorpsee@gmail.com</a>
	</div>
</footer>