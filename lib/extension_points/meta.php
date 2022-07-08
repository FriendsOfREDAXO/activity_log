<?php

namespace RexActivity\EP;

class meta
{
    use ep_trait;

    /**
     * @var \rex_addon_interface
     */
    private static $addon;

    public function __construct() {
        self::$addon = $this->addon();

        /**
         * article meta has been updated
         */
        if (is_bool(self::$addon->getConfig('meta_updated')) && self::$addon->getConfig('meta_updated')) {
                $this->update('ART_META_UPDATED', 'RexActivity\EP\meta::message');
        }
    }

    /**
     * @param array $params
     * @param string $type
     * @return string
     */
    public static function message(array $params, string $type): string {
        /** @var \rex_article $article */
        $article = \rex_article::get($params['id']);

        $message = '<strong>Meta Info:</strong> ';
        $message .= '<a href="' . \rex_url::backendController([
                'page' => 'content/edit',
                'article_id' => $article->getId(),
                'category_id' => $article->getCategoryId(),
                'clang_id' => $params['clang_id'],
                'mode' => 'edit'
            ]) . '">';
        $message .= $article->getName();
        $message .= '</a>';
        $message .= ' - ';
        $message .= self::$addon->i18n('type_'.$type);

        return $message;
    }
}

