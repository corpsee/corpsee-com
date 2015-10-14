<?php
/**
 * @var array  $values
 */
?>
<h1>Добавить проект</h1>
<form name="form-crop" id="form-crop" action="" method="post">
    <div class="control-group">
        <label for="title" class="left">Статус</label>
        <div class="control">
            <input class="text validation" type="text" name="status" id="status" value="<?= $values['status']; ?>"/>
            <div class="msg"></div>
        </div>
    </div>
    <div class="action">
        <input class="submit" type="submit" name="submit" value="Загрузить"/>
    </div>
</form>
