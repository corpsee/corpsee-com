		<img class="crop" id="cropbox" src="<?= FILE_PATH_URL; ?>pictures/x/<?= $image['image']; ?>.jpg" width="<?= $image['width']; ?>" height="<?= $image['height']; ?>" />

		<form action="" method="post" name="cropform" id="cropform">
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
			<input class="submit" type="submit" name="submit" value="Обрезать" />
		</form>