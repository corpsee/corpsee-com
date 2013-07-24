			<div class="sort-menu">
				Упорядочить список: &nbsp;&nbsp;
				<a href="/gallery/list">по дате</a>&nbsp;&nbsp; | &nbsp;&nbsp;
				по меткам
			</div>

			<h1>Графика по меткам</h1>

			<div class="tag-menu-bytag">
				<?php shuffle($tags); ?>

				<?php foreach ($tags as $tag): ?>
					<?php if($tag['class'] == 'tag0'): ?>
						<span class="<?php echo $tag['class']; ?>"><?php echo $tag['tag']; ?></span>
					<?php else: ?>
						<a class="<?php echo $tag['class']; ?>" href="#tag-<?php echo $tag['tag']; ?>"><?php echo $tag['tag']; ?></a>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>

			<?php foreach ($tags_with_pictures as $tag): ?>
			<?php //if ($tag['class'] != 'tag0'): ?>

				<div class="charter">
					<h2><a id="tag-<?php echo $tag['tag']; ?>" name="tag-<?php echo $tag['tag']; ?>"><?php echo $tag['tag']; ?></a></h2>
				</div>

				<?php $i = 0; ?>
				<?php foreach ($tag['pictures'] as $picture): ?>

					<?php if ($i == 4) { $i = 1; } else { $i++; }; ?>

					<?php if ($i == 1): ?>
						<div class="img-str">
							<div class="img-box img-box-first">
                    			<a rel="lightbox[gallery]" href="<?php echo FILE_PATH_URL; ?>pictures/x/<?php echo $picture['image']; ?>.jpg" class="pirobox_gr" title="«<?php echo $picture['title']; ?>» / <?php echo $picture['create_date']; ?>"><img src="<?php echo FILE_PATH_URL; ?>pictures/xgray/<?php echo $picture['image']; ?>-gray.jpg" id="<?php echo $picture['image']; ?>" width="200" height="90" class="gr-col" alt="«<?php echo $picture['title']; ?>» / <?php echo $picture['create_date']; ?>" /></a>
							</div>
					<?php elseif ($i == 4): ?>
							<div class="img-box img-box-last">
                    			<a rel="lightbox[gallery]" href="<?php echo FILE_PATH_URL; ?>pictures/x/<?php echo $picture['image']; ?>.jpg" class="pirobox_gr" title="«<?php echo $picture['title']; ?>» / <?php echo $picture['create_date']; ?>"><img src="<?php echo FILE_PATH_URL; ?>pictures/xgray/<?php echo $picture['image']; ?>-gray.jpg" id="<?php echo $picture['image']; ?>" width="200" height="90" class="gr-col" alt="«<?php echo $picture['title']; ?>» / <?php echo $picture['create_date']; ?>" /></a>
							</div>
						</div>
					<?php else: ?>
							<div class="img-box">
                    			<a rel="lightbox[gallery]" href="<?php echo FILE_PATH_URL; ?>pictures/x/<?php echo $picture['image']; ?>.jpg" class="pirobox_gr" title="«<?php echo $picture['title']; ?>» / <?php echo $picture['create_date']; ?>"><img src="<?php echo FILE_PATH_URL; ?>pictures/xgray/<?php echo $picture['image']; ?>-gray.jpg" id="<?php echo $picture['image']; ?>" width="200" height="90" class="gr-col" alt="«<?php echo $picture['title']; ?>» / <?php echo $picture['create_date']; ?>" /></a>
							</div>
					<?php endif; ?>

				<?php endforeach; ?>

				<?php if ($i != 4): ?>
					</div>
				<?php endif; ?>

			<?php //endif; ?>
			<?php endforeach; ?>