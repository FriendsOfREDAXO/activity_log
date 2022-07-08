<?php

namespace RexActivity\EP;

class template
{
    use ep_trait;

    /**
     * @var \rex_addon_interface
     */
    private static $addon;

    public function __construct() {
        self::$addon = $this->addon();

        /**
         * a new template has been added
         */
        if (is_bool(self::$addon->getConfig('template_added')) && self::$addon->getConfig('template_added')) {
            $this->add('TEMPLATE_ADDED', 'RexActivity\EP\template::message');
        }

        /**
         * a template has been updated
         */
        if (is_bool(self::$addon->getConfig('template_updated')) && self::$addon->getConfig('template_updated')) {
            $this->update('TEMPLATE_UPDATED', 'RexActivity\EP\template::message');
        }

        /**
         * a template has been deleted
         */
        if (is_bool(self::$addon->getConfig('template_deleted')) && self::$addon->getConfig('template_deleted')) {
            $this->delete('TEMPLATE_DELETED', 'RexActivity\EP\template::message');
        }
    }

    /**
     * @param array $params
     * @param string $type
     * @return string
     */
    public static function message(array $params, string $type): string {
        $message = '<strong>Template:</strong> ';

        if($type === 'delete') {
            $message .= '[' . $params['id'] . ']';
        }
        else {
            $message .= '<a href="' . \rex_url::backendController([
                    'page' => 'templates',
                    'template_id' => $params['id'],
                    'function' => 'edit'
                ]) . '">';
            $message .= $params['name'];
            $message .= '</a>';
        }

        $message .= ' ' . self::$addon->i18n('type_'.$type);
        return $message;
    }
}

