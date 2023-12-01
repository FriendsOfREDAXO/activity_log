<?php

namespace RexActivity\EP;

use rex_addon_interface;
use rex_csrf_token;

use rex_url;

use rex_yform_manager_table;

use function is_bool;

class yform
{
    use ep_trait;

    /** @var rex_addon_interface */
    private static $addon;

    public function __construct()
    {
        self::$addon = $this->addon();

        /**
         * a new dataset has been added.
         */
        if (is_bool(self::$addon->getConfig('yform_added')) && self::$addon->getConfig('yform_added')) {
            $this->add('YFORM_DATA_ADDED', 'RexActivity\EP\yform::message');
        }

        /**
         * a dataset has been updated.
         */
        if (is_bool(self::$addon->getConfig('yform_updated')) && self::$addon->getConfig('yform_updated')) {
            $this->update('YFORM_DATA_UPDATED', 'RexActivity\EP\yform::message');
        }

        /**
         * a dataset has been deleted.
         */
        if (is_bool(self::$addon->getConfig('yform_deleted')) && self::$addon->getConfig('yform_deleted')) {
            $this->delete('YFORM_DATA_DELETE', 'RexActivity\EP\yform::message');
        }
    }

    /**
     * @param array<string> $params
     * @param array<string>|null $additionalParams
     */
    public static function message(array $params, string $type, ?array $additionalParams = null): string
    {
        /** @var rex_yform_manager_table $table */
        $table = $params['table'];
        $message = '<strong>YForm Dataset:</strong> ';

        if ('delete' === $type) {
            $message .= $table->getTableName() . ' - [' . $params['data_id'] . ']';
        } else {
            $csrfKey = $table->getCSRFKey();
            $params = [
                'table_name' => $table->getTableName(),
                'data_id' => $params['data_id'],
                'func' => 'edit',
            ];
            $params += rex_csrf_token::factory($csrfKey)->getUrlParams();
            $message .= '<a href="' . rex_url::backendPage('yform/manager/data_edit', $params) . '">';
            $message .= $table->getTableName() . ' - [' . $params['data_id'] . ']';
            $message .= '</a>';
        }

        $message .= ' ' . self::$addon->i18n('type_' . $type);
        return $message;
    }
}
