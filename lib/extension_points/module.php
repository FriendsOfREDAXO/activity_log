<?php

namespace RexActivity\EP;

use rex_addon_interface;
use rex_url;

use function is_bool;

class module
{
    use ep_trait;

    /** @var rex_addon_interface */
    private static $addon;

    public function __construct()
    {
        self::$addon = $this->addon();

        /**
         * a new module has been added.
         */
        if (is_bool(self::$addon->getConfig('module_added')) && self::$addon->getConfig('module_added')) {
            $this->add('MODULE_ADDED', 'RexActivity\EP\module::message');
        }

        /**
         * a module has been updated.
         */
        if (is_bool(self::$addon->getConfig('module_updated')) && self::$addon->getConfig('module_updated')) {
            $this->update('MODULE_UPDATED', 'RexActivity\EP\module::message');
        }

        /**
         * a module has been deleted.
         */
        if (is_bool(self::$addon->getConfig('module_deleted')) && self::$addon->getConfig('module_deleted')) {
            $this->delete('MODULE_DELETED', 'RexActivity\EP\module::message');
        }
    }

    public static function message(array $params, string $type): string
    {
        $message = '<strong>Module:</strong> ';

        if ('delete' === $type) {
            $message .= '[' . $params['id'] . ']';
        } else {
            $message .= '<a href="' . rex_url::backendController([
                'page' => 'modules',
                'module_id' => $params['id'],
                'function' => 'edit',
            ]) . '">';
            $message .= $params['name'];
            $message .= '</a>';
        }

        $message .= ' ' . self::$addon->i18n('type_' . $type);
        return $message;
    }
}
