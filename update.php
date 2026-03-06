<?php

rex_sql_table::get(rex::getTable('activity_log'))
    ->ensureColumn(new rex_sql_column('source', 'varchar(191)', true))
    ->ensure();
