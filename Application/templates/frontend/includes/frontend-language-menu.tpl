<div class="language-menu">
	<?php $i = 1; foreach ($language_links as $language_key => $link): ?>

		<?php if ($language !== $language_key): ?>
			<a href="<?= $link; ?>"><?= $language_key; ?></a>
		<?php else: ?>
			<?= $language_key; ?>
		<?php endif; ?>

		<?php if ($i < sizeof($language_links)): ?>
			&nbsp; | &nbsp;
		<?php endif; $i++; ?>

	<?php endforeach; ?>
</div>