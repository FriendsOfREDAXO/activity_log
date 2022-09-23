<?php

namespace RexActivity\EP;

class slice
{
    use ep_trait;

    /**
     * @var \rex_addon_interface
     */
    private static $addon;

    public function __construct()
    {
        self::$addon = $this->addon();

        /**
         * a new slice has been added
         */
        if (is_bool(self::$addon->getConfig('slice_added')) && self::$addon->getConfig('slice_added')) {
            $this->add('SLICE_ADDED', 'RexActivity\EP\slice::message');
        }

        /**
         * a slice has been updated
         */
        if (is_bool(self::$addon->getConfig('slice_updated')) && self::$addon->getConfig('slice_updated')) {
            $this->update('SLICE_UPDATED', 'RexActivity\EP\slice::message');
        }

        /**
         * a slice has been deleted
         */
        if (is_bool(self::$addon->getConfig('slice_deleted')) && self::$addon->getConfig('slice_deleted')) {
            $this->delete('SLICE_DELETED', 'RexActivity\EP\slice::message');
        }

        /**
         * a slice has been moved
         */
        if (is_bool(self::$addon->getConfig('slice_moved')) && self::$addon->getConfig('slice_moved')) {
            $this->move('SLICE_MOVE', 'RexActivity\EP\slice::message');
        }
    }

    /**
     * @param array<string> $params
     * @param string $type
     * @param array<string>|null $additionalParams
     * @return string
     * @throws \rex_sql_exception
     */
    public static function message(array $params, string $type, ?array $additionalParams = null): string
    {
        if (isset($additionalParams['type']) && $additionalParams['type'] === 'move') {
            $slice = \rex_sql::factory()->getArray('SELECT id, ctype_id, module_id from ' . \rex::getTable('article_slice') . ' WHERE id = ' . $params['slice_id']);

            if (!empty($slice)) {
                $params['module_id'] = $slice[0]['module_id'];
                $params['ctype'] = $slice[0]['ctype_id'];
            }
        }

        $module = \rex_sql::factory()->getArray('SELECT * from ' . \rex::getTable('module') . ' WHERE id = ' . $params['module_id']);

        $message = '<strong>Slice:</strong> ';
        $message .= '<a href="' . \rex_url::backendController([
                'page' => 'content/edit',
                'article_id' => $params['article_id'],
                'slice_id' => $params['slice_id'],
                'clang_id' => isset($params['clang_id']) ?: $params['clang'],
                'ctype' => $params['ctype'],
                'function' => 'edit'
            ]) . '">';
        $message .= $module[0]['name'];
        $message .= '</a>';

        if (isset($additionalParams['type'])) {
            $message .= ' ' . self::$addon->i18n('type_' . $additionalParams['type']);
        }
        else {
            $message .= ' ' . self::$addon->i18n('type_' . $type);
        }

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

        if (isset($additionalParams['type'], $params['direction'])) {
            if ($params['direction'] === 'moveup') {
                $message .= '  <span class="small"><i class="rex-icon rex-icon-up"></i></span>';
            }
            elseif ($params['direction'] === 'movedown') {
                $message .= '  <span class="small"><i class="rex-icon rex-icon-down"></i></span>';
            }
        }

        return $message;
    }
}
