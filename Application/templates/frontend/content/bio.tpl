<?= $content; ?>
<!-- Icons by Ruslan Stepanov (http://hands-creative.com/icon/). License: CC BY 3.0 (http://creativecommons.org/licenses/by/3.0/)-->
<div class="social-links">
	<a href="//github.com/corpsee" title="GitHub"><img src="<?= FILE_PATH_URL; ?>img/social/github.png" width="48" height="48" /></a>
	<a href="//habrahabr.ru/users/corpsee/" title="Habrahabr"><img src="<?= FILE_PATH_URL; ?>img/social/habra.png" width="48" height="48" /></a>
	<a href="//corpsee.livejournal.com" title="Livejournal"><img src="<?= FILE_PATH_URL; ?>img/social/lj.png" width="48" height="48" /></a>
	<a href="//lastfm.ru/user/C0rpsee" title="Last.fm"><img src="<?= FILE_PATH_URL; ?>img/social/lastfm.png" width="48" height="48" /></a>
	<a href="//vk.com/corpsee" title="VKontakte"><img src="<?= FILE_PATH_URL; ?>img/social/vk.png" width="48" height="48" /></a>
	<a href="//plus.google.com/105089674426302229199" title="Google+"><img src="<?= FILE_PATH_URL; ?>img/social/google.png" width="48" height="48" /></a>
	<a href="//icq.com/whitepages/cmd.php?uin=199054845&action=message" title="ICQ: 199054845"><img src="<?= FILE_PATH_URL; ?>img/social/icq.png" width="48" height="48" /></a>
	<a href="skype:poisoncorpsee?chat" title="Skype: poisoncorpsee"><img src="<?= FILE_PATH_URL; ?>img/social/skype.png" width="48" height="48" /></a>
	<a href="mailto:poisoncorpsee@gmail.com" title="Email: poisoncorpsee@gmail.com"><img src="<?= FILE_PATH_URL; ?>img/social/email.png" width="48" height="48" /></a>
</div>

<h2><?= $requests_title; ?></h2>

<?php foreach ($pull_requests as $pull_request): ?>
	<?php
		$data = $pull_request['data'];
		$data = preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $data);
		$data = unserialize($data);
	?>
	<p><?= $data['base']['repo']['full_name']; ?> — <a href="<?= $data['html_url']; ?>"><?= $data['title']; ?></a></p>
<?php endforeach; ?>

<div class="comeback"><a href="/<?= $language; ?>/bio/requests" title="<?= $requests_link; ?>"><?= $requests_link; ?></a></div>


