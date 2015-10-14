<?php
/**
 * @var array $links
 * @var array $pull_requests
 */
?>
<h1>Запросы на слияние</h1>

<table class="lite strip">
    <col width="50"/>
    <col/>
    <col/>
    <col/>
    <col width="100"/>
    <tr>
        <th>#</th>
        <th>Репозиторий</th>
        <th>Номер</th>
        <th>Тело</th>
        <th>Заголовок</th>
        <th>Статус</th>
        <th>Коммитов</th>
        <th>Добавленных строк</th>
        <th>Удаленных строк</th>
        <th>Файлов</th>
        <th>Дата создания</th>
        <th>Редактирование</th>
    </tr>
    <?php foreach ($pull_requests as $pull_request): ?>
        <tr>
            <?php foreach ($pull_request as $name => $field): ?>
                <td><?= $field; ?></td>
            <?php endforeach; ?>
            <td>
                <div class="tool-right">
                    <?php if ($links['edit']): ?>
                        <a href="/admin/pull_request/edit/<?= $pull_request['id']; ?>"><img src="<?= FILE_PATH_URL; ?>img/icons/pencil.png" title="изменить" alt="изменить" width="16" height="16"/></a>
                    <?php else: ?>
                        <img src="<?= FILE_PATH_URL; ?>img/icons/pencil_d.png" title="изменить" alt="изменить" width="16" height="16"/>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
