<?php

namespace RexActivity\EP;

class category
{
    use ep_trait;

    /**
     * @var \rex_addon
     */
    private static $addon;

    public function __construct() {
        self::$addon = $this->addon();

        /**
         * a new category has been added
         */
        if(self::$addon->getConfig('category_added')) {
            $this->add('CAT_ADDED', 'RexActivity\EP\category::message');
        }

        /**
         * a category has been updated
         */
        if(self::$addon->getConfig('category_updated')) {
            $this->update('CAT_UPDATED', 'RexActivity\EP\category::message');
        }

        /**
         * a category has been deleted
         */
        if(self::$addon->getConfig('category_deleted')) {
            $this->delete('CAT_DELETED', 'RexActivity\EP\category::message');
        }
    }

    public static function message(array $params, string $type): string {
        $message = '<strong>Category:</strong> ';
        $message .= '<a href="' . \rex_url::backendController([
                'page' => 'structure',
                'article_id' => 0,
                'category_id' =>  $params['id'],
                'clang_id' => $params['clang'],
            ]) . '">';
        $message .= $params['name'];
        $message .= '</a>';
        $message .= ' - ';
        $message .= self::$addon->i18n('type_'.$type);

        return $message;
    }
}

