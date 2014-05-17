			<div class="sort-menu">
				<?= $sort_header; ?> &nbsp;&nbsp;
				<?= $sort_by_date; ?>&nbsp;&nbsp; | &nbsp;&nbsp;
				<a href="/<?= $language; ?>/gallery/bytag"><?= $sort_by_tags; ?></a>
			</div>

			<h1><?= $header; ?></h1>

			<div class="tag-menu">
				<?php shuffle($tags); ?>

				<?php foreach ($tags as $tag): ?>
					<?php if($tag['class'] == 'tag0'): ?>
						<span class="<?= $tag['class']; ?>"><?= $tag['tag']; ?></span>
					<?php else: ?>
						<a class="<?= $tag['class']; ?>" href="/<?= $language; ?>/gallery/onetag/<?= $tag['tag']; ?>"><?= $tag['tag']; ?></a>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>

			<?php foreach ($pictures as $key => $value): ?>

				<div class="charter">
					<h2><?= $key; ?></h2>
				</div>

				<?php $i = 0; ?>
				<?php foreach ($value as $picture): ?>

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

			<?php endforeach; ?>