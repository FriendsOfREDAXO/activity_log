<?php

class rex_activity_log_cronjob extends \rex_cronjob
{
    /**
     * execute the cronjob
     * @return bool
     * @throws \rex_sql_exception
     */
    public function execute(): bool
    {
        $daysToKeep = (int) $this->getParam('days_to_keep', 7);

        $sql = \rex_sql::factory();
        $sql->setTable(\rex::getTable('activity_log'));
        $sql->setWhere('created_at < now() - interval ' . $daysToKeep . ' day');
        $sql->select('id');

        if ($sql->getRows()) {
            $deleteSql = \rex_sql::factory();
            $deleteSql->setTable(\rex::getTable('activity_log'));
            $deleteSql->setWhere('created_at < now() - interval ' . $daysToKeep . ' day');
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
    public function getTypeName(): string
    {
        return \rex_i18n::msg('activity_log_cronjob_title');
    }

    /**
     * set additional parameter
     * @return array[]
     */
    public function getParamFields()
    {
        $fields = [
            [
                'label' => rex_i18n::msg('activity_log_cronjob_clean'),
                'name' => 'days_to_keep',
                'type' => 'select',
                'default' => 7,
                'options' => [
                    1 => \rex_i18n::msg('activity_log_cronjob_clean_sing', 1),
                    3 => \rex_i18n::msg('activity_log_cronjob_clean_plur', 3),
                    7 => \rex_i18n::msg('activity_log_cronjob_clean_plur', 7),
                    14 => \rex_i18n::msg('activity_log_cronjob_clean_plur', 14),
                    30 => \rex_i18n::msg('activity_log_cronjob_clean_plur', 30),
                ],
            ],
        ];

        return $fields;
    }
}
