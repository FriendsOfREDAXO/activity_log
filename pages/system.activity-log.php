<?php
$table = \rex::getTable('activity_log');

if(rex_post('delete_old_logs') && rex_post('delete_old_logs') == 1) {
    $sql = rex_sql::factory();
    $sql->setTable($table);
    $sql->setWhere('created_at < now() - interval 7 day');
    $sql->delete();
}

if(rex_post('delete_all_logs') && rex_post('delete_all_logs') == 1) {
    $sql = rex_sql::factory();
    $sql->setTable($table);
    $sql->delete();
}

$addon = rex_addon::get('activity_log');
$types = rex_get('type');

$query = 'SELECT created_at,type,message,causer_id FROM ' . $table;

if ($types !== '' && is_array($types)) {
    $query .= ' WHERE FIND_IN_SET(type, "' . implode(',', rex_get('type')) . '")';
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

//$content = '<form action="' . rex_url::currentBackendPage() . '">
//    <label class="checkbox-inline">
//      <input name="type[]" type="checkbox" id="type_info" value="info" ' . (is_array($types) && is_numeric(array_search('info', $types)) ? "checked" : "") . '> info
//    </label>
//    <label class="checkbox-inline">
//      <input name="type[]" type="checkbox" id="type_error" value="error" ' . (is_array($types) && is_numeric(array_search('error', $types)) ? "checked" : "") . '> error
//    </label>
//    <label class="checkbox-inline">
//      <input name="type[]" type="checkbox" id="type_critical" value="critical" ' . (is_array($types) && is_numeric(array_search('critical', $types)) ? "checked" : "") . '> critical
//    </label>
//    <button type="submit" name="filter" value="1">Filter</button>
//</form>';

$content = $list->get();

$content .= '<hr><form method="post" action="' . rex_url::currentBackendPage() . '" style="text-align: right">
    <button type="submit" class="btn btn-danger" name="delete_old_logs" value="1">'.$addon->i18n('delete_older_than_7_days').'</button>
    <button type="submit" class="btn btn-danger" name="delete_all_logs" value="1">'.$addon->i18n('delete_all').'</button>
</form>';

$fragment = new rex_fragment();
$fragment->setVar('body', $content, false);
echo $fragment->parse('core/page/section.php');
