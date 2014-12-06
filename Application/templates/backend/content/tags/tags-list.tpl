<h1>Метки изображений</h1>

<div class="tool-right-main">
    <?php if ($links['add']): ?>
        <a href="/admin/tag/add">Добавить метку <img src="<?= FILE_PATH_URL; ?>img/icons/add.png" title="добавить" alt="добавить" width="16" height="16"/></a><br/>
    <?php else: ?>
        Добавить метку <img align="middle" src="<?= FILE_PATH_URL; ?>img/icons/add_d.png" title="добавить" alt="добавить" width="16" height="16"/><br/>
    <?php endif; ?>
</div>

<table class="lite strip">
    <col width="50"/>
    <col/>
    <col/>
    <col/>
    <col width="100"/>
    <tr>
        <th>#</th>
        <th>Метка</th>
        <th>Дата создания</th>
        <th>Дата изменения</th>
        <th>Класс</th>
        <th>Изображения</th>
        <th>Редактирование</th>
    </tr>
    <?php foreach ($tags as $tag): ?>
        <tr>
            <?php foreach ($tag as $name => $field): ?>
                <td><?= $field; ?></td>
            <?php endforeach; ?>
            <td>
                <div class="tool-right">
                    <?php if ($links['delete']): ?>
                        <a href="/admin/tag/delete/<?= $tag['id']; ?>"><img src="<?= FILE_PATH_URL; ?>img/icons/delete.png" title="удалить" alt="удалить" width="16" height="16"/></a>
                    <?php else: ?>
                        <img src="<?= FILE_PATH_URL; ?>img/icons/delete_d.png" title="удалить" alt="удалить" width="16" height="16"/>
                    <?php endif; ?>

                    <?php if ($links['edit']): ?>
                        <a href="/admin/tag/edit/<?= $tag['id']; ?>"><img src="<?= FILE_PATH_URL; ?>img/icons/pencil.png" title="изменить" alt="изменить" width="16" height="16"/></a>
                    <?php else: ?>
                        <img src="<?= FILE_PATH_URL; ?>img/icons/pencil_d.png" title="изменить" alt="изменить" width="16" height="16"/>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
</table>