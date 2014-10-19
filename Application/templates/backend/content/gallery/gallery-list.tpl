<h1>Изображения галереи</h1>

<div class="tool-right-main">
    <?php if ($links['add']): //TODO: FILE_PATH_URL . 'img/icons/add.png' move to the controller ?>
        <a href="/admin/gallery/add">Добавить изображение <img src="<?= FILE_PATH_URL; ?>img/icons/add.png"
                                                               title="добавить" alt="добавить" width="16" height="16"/></a>
        <br/>
    <?php else: ?>
        Добавить изображение <img align="middle" src="<?= FILE_PATH_URL; ?>img/icons/add_d.png" title="добавить"
                                  alt="добавить" width="16" height="16"/><br/>
    <?php endif; ?>
</div>

<table class="lite strip">
    <col width="50"/>
    <col/>
    <col/>
    <col/>
    <col/>
    <col/>
    <col/>
    <col/>
    <col width="100"/>
    <tr>
        <th>#</th>
        <th>название</th>
        <th>изображения</th>
        <th>описание</th>
        <th>дата создания</th>
        <th>дата добавления</th>
        <th>дата посл. изменения</th>
        <th>метки</th>
        <th>редактирование</th>
    </tr>

    <?php foreach ($pictures as $key => $picture): ?>

        <tr>

            <?php foreach ($picture as $name => $field): ?>

                <?php if ($name == 'image'): ?>

                    <td>
                        <img src="<?= FILE_PATH_URL; ?>pictures/xmin/<?= $field; ?>-min.jpg"/><br/>
                    </td>

                <?php else: ?>

                    <td><?= $field; ?></td>

                <?php endif; ?>
            <?php endforeach; ?>
            <td>
                <div class="tool-right">
                    <?php if ($links['delete']): ?>
                        <a href="/admin/gallery/delete/<?= $picture['id']; ?>"><img
                                src="<?= FILE_PATH_URL; ?>img/icons/delete.png" title="удалить" alt="удалить" width="16"
                                height="16"/></a>
                    <?php else: ?>
                        <img src="<?= FILE_PATH_URL; ?>img/icons/delete_d.png" title="удалить" alt="удалить" width="16"
                             height="16"/>
                    <?php endif; ?>

                    <?php if ($links['edit']): ?>
                        <a href="/admin/gallery/edit/<?= $picture['id']; ?>"><img
                                src="<?= FILE_PATH_URL; ?>img/icons/pencil.png" title="изменить информацию"
                                alt="изменить информацию" width="16" height="16"/></a>
                    <?php else: ?>
                        <img src="<?= FILE_PATH_URL; ?>img/icons/pencil_d.png" title="изменить информацию"
                             alt="изменить информацию" width="16" height="16"/>
                    <?php endif; ?>

                    <?php if ($links['editimage']): ?>
                        <a href="/admin/gallery/editimage/<?= $picture['id']; ?>"><img
                                src="<?= FILE_PATH_URL; ?>img/icons/pencil.png" title="изменить изображение"
                                alt="изменить изображение" width="16" height="16"/></a>
                    <?php else: ?>
                        <img src="<?= FILE_PATH_URL; ?>img/icons/pencil_d.png" title="изменить изображение"
                             alt="изменить изображение" width="16" height="16"/>
                    <?php endif; ?>

                    <?php if ($links['crop']): ?>
                        <a href="/admin/gallery/crop/<?= $picture['image']; ?>"><img
                                src="<?= FILE_PATH_URL; ?>img/icons/picture.png" title="заново обрезать изображение"
                                alt="заново обрезать изображение" width="16" height="16"/></a>
                    <?php else: ?>
                        <img src="<?= FILE_PATH_URL; ?>img/icons/picture_d.png" title="заново обрезать изображение"
                             alt="заново обрезать изображение" width="16" height="16"/>
                    <?php endif; ?>
                </div>
            </td>
        </tr>

    <?php endforeach; ?>
</table>