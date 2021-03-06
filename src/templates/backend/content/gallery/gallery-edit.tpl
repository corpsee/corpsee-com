<?php
/**
 * @var array  $values
 * @var string $tags
 */
?>
<h1>Изменить картинку</h1>
<form name="form-crop" id="form-crop" action="" method="post" enctype="multipart/form-data">
    <div class="control-group">
        <label for="title" class="left">Название</label>
        <div class="control">
            <input class="text validation" type="text" name="title" id="title" value="<?= $values['title']; ?>"/>
            <div class="msg"></div>
        </div>
    </div>
    <div class="control-group">
        <label for="description" class="left">Описание</label>
        <div class="control">
            <textarea rows="10" class="text validation" name="description" id="description">
                <?= $values['description']; ?>
            </textarea>
            <div class="msg"></div>
        </div>
    </div>
    <div class="control-group">
        <label for="tags" class="left">Метки через запятую</label>
        <div class="control">
            <input class="text validation" type="text" name="tags" id="tags" value="<?= $values['tags']; ?>"/>
            <p class="help"><em><strong>Уже существующие метки:</strong><br/><?= $tags; ?></em></p>
            <div class="msg"></div>
        </div>
    </div>
    <div class="control-group">
        <label for="create_date" class="left">Дата создания</label>
        <div class="control">
            <input
                class="text validation"
                type="text"
                name="create_date"
                id="create_date"
                value="<?= $values['create_date']; ?>"
            />
            <div class="msg"></div>
        </div>
    </div>
    <div class="action">
        <input class="submit" type="submit" name="submit" value="Загрузить"/>
    </div>
</form>
