<h1>Добавить проект</h1>
<form name="form-crop" id="form-crop" action="" method="post" enctype="multipart/form-data">
    <div class="control-group">
        <label for="title" class="left">Название</label>
        <div class="control">
            <input class="text validation" type="text" name="title" id="title" value=""/>
            <div class="msg"></div>
        </div>
    </div>
    <div class="control-group">
        <label for="description" class="left">Описание</label>
        <div class="control">
            <textarea rows="10" class="text validation" name="description" id="description"></textarea>
            <div class="msg"></div>
        </div>
    </div>
    <div class="control-group">
        <label for="link" class="left">Ссылка</label>
        <div class="control">
            <input class="text validation" type="text" name="link" id="link" value=""/>
            <div class="msg"></div>
        </div>
    </div>
    <div class="control-group">
        <label for="role" class="left">Роль</label>
        <div class="control">
            <input class="text validation" type="text" name="role" id="role" value=""/>
            <div class="msg"></div>
        </div>
    </div>
    <div class="control-group">
        <label for="order" class="left">Приоритет</label>
        <div class="control">
            <input class="text validation" type="text" name="order" id="order" value="0"/>
            <div class="msg"></div>
        </div>
    </div>
    <div class="control-group">
        <label for="file" class="left">Изображение</label>
        <div class="control">
            <input type="hidden" name="MAX_FILE_SIZE" value="50000000"/>
            <input class="file" type="file" name="file" id="file" value=""/>
            <div class="msg"></div>
        </div>
    </div>
    <div class="action">
        <input class="submit" type="submit" name="submit" value="Загрузить"/>
    </div>
</form>
