<?php

namespace RexActivity\EP;

class slice
{
    use ep_trait;

    /**
     * @var \rex_addon
     */
    private static $addon;

    public function __construct() {
        self::$addon = $this->addon();

        /**
         * a new slice has been added
         */
        if(self::$addon->getConfig('slice_added')) {
            $this->add('SLICE_ADDED', 'RexActivity\EP\slice::message');
        }

        /**
         * a slice has been updated
         */
        if(self::$addon->getConfig('slice_updated')) {
            $this->update('SLICE_UPDATED', 'RexActivity\EP\slice::message');
        }

        /**
         * a slice has been deleted
         */
        if(self::$addon->getConfig('slice_deleted')) {
            $this->delete('SLICE_DELETED', 'RexActivity\EP\slice::message');
        }
    }

    public static function message(array $params, string $type): string {
        $module = \rex_sql::factory()->getArray('SELECT * from ' . \rex::getTable('module') . ' WHERE id = ' . $params['module_id']);

        $message = '<strong>Slice:</strong> ';
        $message .= '<a href="' . \rex_url::backendController([
                'page' => 'content/edit',
                'article_id' => $params['article_id'],
                'slice_id' => $params['slice_id'],
                'clang_id' => $params['clang_id'],
                'ctype' => $params['ctype'],
                'function' => 'edit'
            ]) . '">';
        $message .= $module[0]['name'];
        $message .= '</a>';
        $message .= ' ' . self::$addon->i18n('type_'.$type);

        /** @var \rex_article $article */
        $article = \rex_article::get($params['article_id']);

        $message .= ' - ';
        $message .= '<a href="' . \rex_url::backendController([
                'page' => 'content/edit',
                'article_id' => $article->getId(),
                'category_id' => $article->getCategoryId(),
                'clang_id' => $params['clang_id'],
                'mode' => 'edit'
            ]) . '">';
        $message .= $article->getName();
        $message .= '</a>';

        return $message;
    }
}

