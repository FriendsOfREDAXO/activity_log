<?php

class rex_activity_log_cronjob extends \rex_cronjob
{
    /**
     * execute the cronjob
     * @return bool
     * @throws \rex_sql_exception
     */
    public function execute(): bool {
        $sql = \rex_sql::factory();
        $sql->setTable(\rex::getTable('activity_log'));
        $sql->setWhere('created_at < now() - interval 7 day');
        $sql->select('id');

        if ($sql->getRows()) {
            $deleteSql = \rex_sql::factory();
            $deleteSql->setTable(\rex::getTable('activity_log'));
            $deleteSql->setWhere('created_at < now() - interval 7 day');
            $deleteSql->delete();
            $this->setMessage(\rex_i18n::msg('activity_log_cron_deleted'));
        }
        else {
            $this->setMessage(\rex_i18n::msg('activity_log_cron_nothing_to_delete'));
        }

        return true;
    }

    /**
     * get the job name
     * @return string
     */
    public function getTypeName(): string {
        return \rex_i18n::msg('activity_log_cronjob_title');
    }
}
