<?php

$addon = rex_addon::get('activity_log');
$types = rex_get('type');

$query = 'SELECT created_at,type,message,causer_id FROM rex_activity_log';

if ($types !== '' && is_array($types)) {
    $query .= ' WHERE FIND_IN_SET(type, "' . implode(',', rex_get('type')) . '")';
}
$query .= ' ORDER BY created_at DESC';

$list = \rex_list::factory($query, 100, 'rex_activity');

$list->addTableAttribute('class', 'rex-activity-table');
$list->setColumnFormat('created_at', 'date', 'd.m.Y - H:i:s');
$list->setColumnSortable('created_at');

$list->setColumnLabel('created_at', 'Time');
$list->setColumnLabel('type', 'Typ');
$list->setColumnLabel('message', 'Message');
$list->setColumnLabel('causer_id', 'User');

$list->setColumnFormat('causer_id', 'custom', 'rex_activity::userListCallback');
$list->setColumnFormat('message', 'custom', 'rex_activity::messageListCallback');

$list->setRowAttributes(function ($list) {
    switch ($list->getValue('type')) {
        case rex_activity::TYPE_CRITICAL:
            return 'class="rex-activity-critical"';
        case rex_activity::TYPE_ERROR:
            return 'class="rex-activity-error"';
        case rex_activity::TYPE_INFO:
            return 'class="rex-activity-info"';
        case rex_activity::TYPE_DEBUG:
            return 'class="rex-activity-debug"';
        case rex_activity::TYPE_NOTICE:
            return 'class="rex-activity-notice"';
        case rex_activity::TYPE_WARNING:
            return 'class="rex-activity-warning"';
    }
});

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

$fragment = new rex_fragment();
$fragment->setVar('body', $content, false);
echo $fragment->parse('core/page/section.php');
