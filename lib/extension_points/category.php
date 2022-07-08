<?php

namespace RexActivity\EP;

class category
{
    use ep_trait;

    /**
     * @var \rex_addon_interface
     */
    private static $addon;

    public function __construct() {
        self::$addon = $this->addon();

        /**
         * a new category has been added
         */
        if (is_bool(self::$addon->getConfig('category_added')) && self::$addon->getConfig('category_added')) {
            $this->add('CAT_ADDED', 'RexActivity\EP\category::message');
        }

        /**
         * a category has been updated
         */
        if (is_bool(self::$addon->getConfig('category_updated')) && self::$addon->getConfig('category_updated')) {
            $this->update('CAT_UPDATED', 'RexActivity\EP\category::message');
        }

        /**
         * a category has been deleted
         */
        if (is_bool(self::$addon->getConfig('category_deleted')) && self::$addon->getConfig('category_deleted')) {
            $this->delete('CAT_DELETED', 'RexActivity\EP\category::message');
        }

        /**
         * the category status has been changed
         */
        if (is_bool(self::$addon->getConfig('category_status')) && self::$addon->getConfig('category_status')) {
            $this->status('CAT_STATUS', 'RexActivity\EP\category::message');
        }
    }

    /**
     * @param array<string> $params
     * @param string $type
     * @param array<string>|null $additionalParams
     * @return string
     */
    public static function message(array $params, string $type, ?array $additionalParams = null): string {
        $category = \rex_category::get((int) $params['id']);
        $message = '<strong>Category:</strong> ';

        if($type === 'delete' && $params !== []) {
            $message .= $params['name'];
            $message .= ' [' . $params['id'] . ']';
        }
        else {
            $message .= '<a href="' . \rex_url::backendController([
                    'page' => 'structure',
                    'article_id' => 0,
                    'category_id' =>  $params['id'],
                    'clang_id' => $params['clang'],
                ]) . '">';

            if($category !== null) {
                $message .= $category->getName();
            }

            $message .= '</a>';
        }

        $message .= ' - ';

        if(isset($additionalParams['type'])) {
            $message .= self::$addon->i18n('type_'.$additionalParams['type']);

            if($category !== null) {
                $message .= self::getStatus($category->isOnline(), $additionalParams);
            }
        }
        else {
            $message .= self::$addon->i18n('type_'.$type);
        }

        return $message;
    }
}

