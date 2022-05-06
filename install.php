<?php
$addon = rex_addon::get('activity_log');

rex_sql_table::get(rex::getTable('activity_log'))
    ->ensurePrimaryIdColumn()
    ->ensureColumn(new rex_sql_column('created_at', 'datetime'))
    ->ensureColumn(new rex_sql_column('type', 'ENUM(\'info\',\'warning\',\'alert\',\'error\',\'notice\',\'critical\',\'debug\',\'update\',\'add\',\'edit\',\'delete\')'))
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

if (!$this->hasConfig()) {
    $defaultConfig = [
        'article_added' => false,
        'article_updated' => false,
        'article_status' => false,
        'article_deleted' => false,
        'category_added' => false,
        'category_updated' => false,
        'category_deleted' => false,
        'category_status' => false,
        'slice_added' => false,
        'slice_updated' => false,
        'slice_deleted' => false,
        'slice_moved' => false,
        'meta_updated' => false,
        'clang_added' => false,
        'clang_updated' => false,
        'clang_deleted' => false,
        'user_added' => false,
        'user_updated' => false,
        'user_deleted' => false,
        'media_added' => false,
        'media_updated' => false,
        'media_deleted' => false,
        'template_added' => false,
        'template_updated' => false,
        'template_deleted' => false,
        'module_added' => false,
        'module_updated' => false,
        'module_deleted' => false,
    ];

    rex_config::set($this->getPackageId(), $defaultConfig);
}