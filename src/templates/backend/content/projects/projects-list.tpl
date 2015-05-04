<?php
/**
 * @var array $links
 * @var array $projects
 */
?>
<h1>Метки изображений</h1>

<div class="tool-right-main">
    <?php if ($links['add']): ?>
        <a href="/admin/project/add">Добавить проект <img src="<?= FILE_PATH_URL; ?>img/icons/add.png" title="добавить" alt="добавить" width="16" height="16"/></a><br/>
    <?php else: ?>
        Добавить проект <img align="middle" src="<?= FILE_PATH_URL; ?>img/icons/add_d.png" title="добавить" alt="добавить" width="16" height="16"/><br/>
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
        <th>Проект</th>
        <th>Описание</th>
        <th>Ссылка</th>
        <th>Роль</th>
        <th>Изображение</th>
        <th>Дата добавления</th>
        <th>Дата изменения</th>
        <th>Редактирование</th>
    </tr>
    <?php foreach ($projects as $project): ?>
        <tr>
            <?php foreach ($project as $name => $field): ?>
                <td><?= $field; ?></td>
            <?php endforeach; ?>
            <td>
                <div class="tool-right">
                    <?php if ($links['delete']): ?>
                        <a href="/admin/project/delete/<?= $project['id']; ?>"><img src="<?= FILE_PATH_URL; ?>img/icons/delete.png" title="удалить" alt="удалить" width="16" height="16"/></a>
                    <?php else: ?>
                        <img src="<?= FILE_PATH_URL; ?>img/icons/delete_d.png" title="удалить" alt="удалить" width="16" height="16"/>
                    <?php endif; ?>

                    <?php if ($links['edit']): ?>
                        <a href="/admin/project/edit/<?= $project['id']; ?>"><img src="<?= FILE_PATH_URL; ?>img/icons/pencil.png" title="изменить" alt="изменить" width="16" height="16"/></a>
                    <?php else: ?>
                        <img src="<?= FILE_PATH_URL; ?>img/icons/pencil_d.png" title="изменить" alt="изменить" width="16" height="16"/>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
