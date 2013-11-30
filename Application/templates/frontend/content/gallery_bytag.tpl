			<div class="sort-menu">
				<?= $sort_header; ?> &nbsp;&nbsp;
				<a href="/<?= $language; ?>/gallery/list"><?= $sort_by_date; ?></a>&nbsp;&nbsp; | &nbsp;&nbsp;
				<?= $sort_by_tags; ?>
			</div>

			<h1><?= $header; ?></h1>

			<div class="tag-menu-bytag">
				<?php shuffle($tags); ?>

				<?php foreach ($tags as $tag): ?>
					<?php if($tag['class'] == 'tag0'): ?>
						<span class="<?= $tag['class']; ?>"><?= $tag['tag']; ?></span>
					<?php else: ?>
						<a class="<?= $tag['class']; ?>" href="#tag-<?= $tag['tag']; ?>"><?= $tag['tag']; ?></a>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>

			<?php foreach ($tags_with_pictures as $tag): ?>

				<div class="charter">
					<h2><a id="tag-<?= $tag['tag']; ?>" name="tag-<?= $tag['tag']; ?>"><?= $tag['tag']; ?></a></h2>
				</div>

				<?php $i = 0; ?>
				<?php foreach ($tag['pictures'] as $picture): ?>

					<?php if ($i == 4) { $i = 1; } else { $i++; }; ?>

					<?php if ($i == 1): ?>
						<div class="img-str">
							<div class="img-box img-box-first">
                    			<a rel="lightbox[gallery]" href="<?= FILE_PATH_URL; ?>pictures/x/<?= $picture['image']; ?>.jpg" class="pirobox_gr" title="«<?= $picture['title']; ?>» / <?= $picture['create_date']; ?>"><img src="<?= FILE_PATH_URL; ?>pictures/xgray/<?= $picture['image']; ?>-gray.jpg" id="<?= $picture['image']; ?>" width="200" height="90" class="gr-col" alt="«<?= $picture['title']; ?>» / <?= $picture['create_date']; ?>" /></a>
							</div>
					<?php elseif ($i == 4): ?>
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

				<?php if ($i != 4): ?>
					</div>
				<?php endif; ?>

			<?php endforeach; ?>