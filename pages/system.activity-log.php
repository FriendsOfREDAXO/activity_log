<?php
$table = \rex::getTable('activity_log');

if (\rex_post('delete_old_logs') && \rex_post('delete_old_logs') == 1) {
    $sql = \rex_sql::factory();
    $sql->setTable($table);
    $sql->setWhere('created_at < now() - interval 7 day');
    $sql->delete();
}

if (\rex_post('delete_all_logs') && \rex_post('delete_all_logs') == 1) {
    $sql = \rex_sql::factory();
    $sql->setTable($table);
    $sql->delete();
}

$addon = \rex_addon::get('activity_log');
$type = \rex_get('type');
$user = \rex_get('user');

$query = 'SELECT created_at,type,message,causer_id FROM ' . $table;
$where = [];

if ($type && $type !== '') {
    $where[] = 'type = "' . $type . '"';
}

if ($user && $user !== '') {
    $where[] = 'causer_id = ' . $user;
}

if ($where) {
    $query .= ' WHERE ' . implode(' AND ', $where);
}

$query .= ' ORDER BY created_at DESC';

$list = \rex_list::factory($query, 100, 'rex_activity');

$list->addTableAttribute('class', 'rex-activity-table');
$list->setColumnFormat('created_at', 'date', 'd.m.Y - H:i:s');
$list->setColumnSortable('created_at');

$list->setColumnLabel('created_at', 'Time');
$list->setColumnLabel('type', 'Type');
$list->setColumnLabel('message', 'Message');
$list->setColumnLabel('causer_id', 'User');

$list->setColumnFormat('causer_id', 'custom', 'rex_activity::userListCallback');
$list->setColumnFormat('message', 'custom', 'rex_activity::messageListCallback');
$list->setColumnFormat('type', 'custom', 'rex_activity::typeListCallback');


$filterFragment = new \rex_fragment();
$filterFragment->setVar('type', $type);
$filterFragment->setVar('user', $user);
$content = $filterFragment->parse('filter-form.php');

$content .= $list->get();

$content .= '<hr><form method="post" action="' . \rex_url::currentBackendPage() . '" style="text-align: right">
    <button type="submit" class="btn btn-danger" name="delete_old_logs" value="1">' . $addon->i18n('delete_older_than_7_days') . '</button>
    <button type="submit" class="btn btn-danger" name="delete_all_logs" value="1">' . $addon->i18n('delete_all') . '</button>
</form>';

$fragment = new \rex_fragment();
$fragment->setVar('body', $content, false);
echo $fragment->parse('core/page/section.php');
