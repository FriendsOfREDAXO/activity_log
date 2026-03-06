<?php

namespace FriendsOfRedaxo\ActivityLog;

use DateTime;
use rex;
use rex_cronjob;
use rex_i18n;
use rex_sql;
use rex_sql_exception;

class ActivityLogCronjob extends rex_cronjob
{
    /**
     * Execute the cronjob.
     *
     * @throws rex_sql_exception
     */
    public function execute(): bool
    {
        $daysToKeep = (int) $this->getParam('days_to_keep', 7);
        $source = (string) $this->getParam('source', '');

        $now = new DateTime();
        $now->modify('-' . $daysToKeep . ' day');
        $date = $now->format('Y-m-d H:i:s');

        $where = "created_at <= '$date'";
        if ('' !== $source) {
            $where .= ' AND source = ' . rex_sql::factory()->escape($source);
        }

        $sql = rex_sql::factory();
        $sql->setTable(rex::getTable('activity_log'));
        $sql->setWhere($where);
        $sql->select('id');

        if ($sql->getRows()) {
            $deleteSql = rex_sql::factory();
            $deleteSql->setTable(rex::getTable('activity_log'));
            $deleteSql->setWhere($where);
            $deleteSql->delete();
            $this->setMessage(rex_i18n::msg('activity_log_cron_deleted'));
        } else {
            $this->setMessage(rex_i18n::msg('activity_log_cron_nothing_to_delete'));
        }

        return true;
    }

    /**
     * Get the job name.
     */
    public function getTypeName(): string
    {
        return rex_i18n::msg('activity_log_cronjob_title');
    }

    /**
     * Additional parameter fields.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getParamFields(): array
    {
        // Alle aktuell in der Datenbank vorhandenen Quellen dynamisch laden
        $sourceOptions = ['' => rex_i18n::msg('activity_log_cronjob_source_all')];
        $sql = rex_sql::factory();
        $sql->setQuery('SELECT DISTINCT source FROM ' . rex::getTable('activity_log') . ' WHERE source IS NOT NULL AND source != \'\' ORDER BY source');
        foreach ($sql as $row) {
            $source = (string) $row->getValue('source');
            $sourceOptions[$source] = $source;
        }

        return [
            [
                'label' => rex_i18n::msg('activity_log_cronjob_clean'),
                'name' => 'days_to_keep',
                'type' => 'select',
                'default' => 7,
                'options' => [
                    1 => rex_i18n::msg('activity_log_cronjob_clean_sing', 1),
                    3 => rex_i18n::msg('activity_log_cronjob_clean_plur', 3),
                    7 => rex_i18n::msg('activity_log_cronjob_clean_plur', 7),
                    14 => rex_i18n::msg('activity_log_cronjob_clean_plur', 14),
                    30 => rex_i18n::msg('activity_log_cronjob_clean_plur', 30),
                    90 => rex_i18n::msg('activity_log_cronjob_clean_plur', 90),
                    180 => rex_i18n::msg('activity_log_cronjob_clean_plur', 180),
                    365 => rex_i18n::msg('activity_log_cronjob_clean_plur', 365),
                ],
            ],
            [
                'label' => rex_i18n::msg('activity_log_cronjob_source'),
                'name' => 'source',
                'type' => 'select',
                'default' => '',
                'options' => $sourceOptions,
            ],
        ];
    }
}
