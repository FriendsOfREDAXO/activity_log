<?php

namespace RexActivity\EP;

class clang
{
    use ep_trait;

    /**
     * @var \rex_addon
     */
    private static $addon;

    public function __construct() {
        self::$addon = $this->addon();

        /**
         * a new clang has been added
         */
        if(self::$addon->getConfig('clang_added')) {
            $this->add('CLANG_ADDED', 'RexActivity\EP\clang::message');
        }

        /**
         * a clang has been updated
         */
        if(self::$addon->getConfig('clang_updated')) {
            $this->update('CLANG_UPDATED', 'RexActivity\EP\clang::message');
        }

        /**
         * a clang has been deleted
         */
        if(self::$addon->getConfig('clang_deleted')) {
            $this->delete('CLANG_DELETED', 'RexActivity\EP\clang::message');
        }
    }

    public static function message(array $params, string $type): string {
        $message = '<strong>Language:</strong> ';
        $message .= '<a href="' . \rex_url::backendController([
                'page' => 'system/lang',
            ]) . '">';
        $message .= $params['name'];
        $message .= '</a>';
        $message .= ' - ';
        $message .= self::$addon->i18n('type_'.$type);

        return $message;
    }
}

