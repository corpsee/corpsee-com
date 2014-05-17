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

<h2><?= $pictures_title; ?></h2>

<?php $i = 0; ?>
<?php foreach ($pictures as $picture): ?>

	<?php if ($i === 4) { $i = 1; } else { $i++; }; ?>

	<?php if ($i === 1): ?>
		<div class="img-str">
			<div class="img-box img-box-first">
				<a rel="lightbox[gallery]" href="<?= FILE_PATH_URL; ?>pictures/x/<?= $picture['image']; ?>.jpg" class="pirobox_gr" title="«<?= $picture['title']; ?>» / <?= $picture['create_date']; ?>"><img src="<?= FILE_PATH_URL; ?>pictures/xgray/<?= $picture['image']; ?>-gray.jpg" id="<?= $picture['image']; ?>" width="200" height="90" class="gr-col" alt="«<?= $picture['title']; ?>» / <?= $picture['create_date']; ?>" /></a>
			</div>
	<?php elseif ($i === 4): ?>
			<div class="img-box img-box-last">
				<a rel="lightbox[gallery]" href="<?= FILE_PATH_URL; ?>pictures/x/<?= $picture['image']; ?>.jpg" class="pirobox_gr" title="«<?= $picture['title']; ?>» / <?= $picture['create_date']; ?>"><img src="<?= FILE_PATH_URL; ?>pictures/xgray/<?= $picture['image']; ?>-gray.jpg" id="<?= $picture['image']; ?>" width="200" height="90" class="gr-col" alt="«<?= $picture['title']; ?>» / <?= $picture['create_date']; ?>" /></a>
			</div>
		</div>
	<?php else: ?>
			<div class="img-box">
				<a rel="lightbox[gallery]" href="<?= FILE_PATH_URL; ?>pictures/x/<?= $picture['image']; ?>.jpg" class="pirobox_gr" title="«<?= $picture['title']; ?>» / <?= $picture['create_date']; ?>"><img src="<?= FILE_PATH_URL; ?>pictures/xgray/<?= $picture['image']; ?>-gray.jpg" id="<?= $picture['image']; ?>" width="200" height="90" class="gr-col" alt="«<?= $picture['title']; ?>» / <?= $picture['create_date']; ?>" /></a>
			</div>
	<?php endif; ?>

<?php endforeach; ?>

<?php if ($i !== 4): ?>
	</div>
<?php endif; ?>

<div class="comeback"><a href="/<?= $language; ?>/gallery/list" title="<?= $pictures_link; ?>"><?= $pictures_link; ?></a></div>

<h2><?= $requests_title; ?></h2>

<?php foreach ($pull_requests as $pull_request): ?>
	<div class="pull_request">
		<?= \DateTime::createFromFormat('U', $pull_request['create_date'])->format('Y-m-d'); ?>
		<div class="status"><?= $pull_request['status']; ?></div>
		<?= $pull_request['repository']; ?> —
		<a href="https://github.com/<?= $pull_request['repository']; ?>/pull/<?= $pull_request['number']; ?>"><?= $pull_request['title']; ?></a>
	</div>
<?php endforeach; ?>

<div class="comeback"><a href="/<?= $language; ?>/bio/requests" title="<?= $requests_link; ?>"><?= $requests_link; ?></a></div>


