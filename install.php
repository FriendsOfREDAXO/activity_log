<?php
$addon = rex_addon::get('activity_log');

rex_sql_table::get(rex::getTable('activity_log'))
    ->ensurePrimaryIdColumn()
    ->ensureColumn(new rex_sql_column('created_at', 'datetime'))
    ->ensureColumn(new rex_sql_column('type', 'ENUM(\'info\',\'warning\',\'alert\',\'error\',\'notice\',\'critical\',\'debug\')'))
    ->ensureColumn(new rex_sql_column('message', 'text', true))
    ->ensureColumn(new rex_sql_column('causer_id', 'int', true))
    ->ensure();

$sql = rex_sql::factory();
$sql->setTable(rex::getTable('activity_log'));

if (class_exists('rex_scss_compiler')) {
    $compiler = new rex_scss_compiler();
    $compiler->setRootDir($addon->getPath());
    $compiler->setScssFile([$addon->getPath('scss/styles.scss')]);
    $compiler->setCssFile($addon->getPath('assets/css/styles.css'));
    $compiler->compile();
}