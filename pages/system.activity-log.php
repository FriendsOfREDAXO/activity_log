<?php

/** @var rex_addon $this */

$table = rex::getTable('activity_log');
$isAdmin = rex::getUser()->isAdmin();

if ($isAdmin && rex_post('delete_old_logs') && 1 == rex_post('delete_old_logs')) {
    $now = (new DateTime());
    $now->modify('-7 day');
    $date = $now->format('Y-m-d H:i:s');

    $sql = rex_sql::factory();
    $sql->setTable($table);
    $sql->setWhere("created_at <= '$date'");
    $sql->delete();
}

if ($isAdmin && rex_post('delete_all_logs') && 1 == rex_post('delete_all_logs')) {
    $sql = rex_sql::factory();
    $sql->setTable($table);
    $sql->delete();
}

if ($isAdmin && rex_post('delete_single_log')) {
    $sql = rex_sql::factory();
    $sql->setTable($table);
    $sql->setWhere('id = ' . rex_post('delete_single_log'));
    $sql->delete();
}

$addon = rex_addon::get('activity_log');
$type = rex_get('type', 'string', '');
$user = rex_get('user', 'string', '');
$source = rex_get('source', 'string', '');
$search = rex_get('search', 'string', '');
$clear = rex_get('clear_filter', 'string', '');

$query = 'SELECT id,created_at,source,type,message,causer_id FROM ' . $table;
$where = [];

if ($clear) {
    $type = $user = $source = $search = '';
}

if ('' !== $type) {
    $where[] = 'type = ' . rex_sql::factory()->escape($type);
}

if ('' !== $user) {
    $where[] = 'causer_id = ' . (int) $user;
}

if ('' !== $source) {
    $where[] = 'source = ' . rex_sql::factory()->escape($source);
}

if ('' !== $search) {
    $escapedSearch = str_replace(['%', '_', '\\'], ['\\%', '\\_', '\\\\'], $search);
    $where[] = 'message LIKE ' . rex_sql::factory()->escape('%' . $escapedSearch . '%');
}

if ($where) {
    $query .= ' WHERE ' . implode(' AND ', $where);
}

$query .= ' ORDER BY created_at DESC';

$list = rex_list::factory($query, $this->getConfig('rows_per_page') ?: 100, 'rex_activity');

$list->removeColumn('id');
$list->addTableAttribute('class', 'table table-striped table-hover rex-activity-table');
$list->setColumnFormat('created_at', 'date', 'd.m.Y - H:i:s');
$list->setColumnSortable('created_at');

$list->setColumnLabel('created_at', $addon->i18n('col_time'));
$list->setColumnLabel('type', $addon->i18n('col_type'));
$list->setColumnLabel('source', $addon->i18n('col_source'));
$list->setColumnLabel('message', $addon->i18n('col_message'));
$list->setColumnLabel('causer_id', $addon->i18n('col_user'));

$list->setColumnFormat('causer_id', 'custom', 'rex_activity::userListCallback');
$list->setColumnFormat('message', 'custom', 'rex_activity::messageListCallback');
$list->setColumnFormat('type', 'custom', 'rex_activity::typeListCallback');
$list->setColumnFormat('source', 'custom', 'rex_activity::sourceListCallback');

if ($isAdmin) {
    $list->addColumn('delete', '', -1, ['<th></th>', '<td class="rex-table-icon">###VALUE###</td>']);
    $list->setColumnFormat('delete', 'custom', static function ($params) use ($list) {
        return '<button type="submit" class="btn btn-danger btn-sm" name="delete_single_log" value="' . $list->getValue('id') . '"><i class="rex-icon rex-icon-delete"></i></button>';
    });
}

// Filter-Parameter durch Pager-Links und List-Form (delete_single_log) durchreichen
if ('' !== $type) {
    $list->addParam('type', $type);
}
if ('' !== $user) {
    $list->addParam('user', $user);
}
if ('' !== $source) {
    $list->addParam('source', $source);
}
if ('' !== $search) {
    $list->addParam('search', $search);
}

$filterFragment = new rex_fragment();
$filterFragment->setVar('type', $clear ? '' : $type);
$filterFragment->setVar('user', $clear ? '' : $user);
$filterFragment->setVar('source', $clear ? '' : $source);
$filterFragment->setVar('search', $clear ? '' : $search);
$content = $filterFragment->parse('filter-form.php');

$content .= $list->get();

// Filter als Hidden-Inputs in der Lösch-Form erhalten
$filterHidden = '';
foreach (['type' => $type, 'user' => $user, 'source' => $source, 'search' => $search] as $k => $v) {
    if ('' !== $v) {
        $filterHidden .= '<input type="hidden" name="' . $k . '" value="' . htmlspecialchars($v) . '">';
    }
}

if ($isAdmin) {
    $content .= '<hr><form method="post" action="' . rex_url::currentBackendPage() . '" style="text-align: right">' . $filterHidden . '
        <button type="submit" class="btn btn-danger" name="delete_old_logs" value="1">' . $addon->i18n('delete_older_than_7_days') . '</button>
        <button type="submit" class="btn btn-danger" name="delete_all_logs" value="1">' . $addon->i18n('delete_all') . '</button>
    </form>';
}

$fragment = new rex_fragment();
$fragment->setVar('body', $content, false);
echo $fragment->parse('core/page/section.php');
