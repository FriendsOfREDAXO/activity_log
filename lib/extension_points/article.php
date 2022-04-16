<?php

namespace RexActivity\EP;

class article
{
    use ep_trait;

    /**
     * @var \rex_addon
     */
    private static $addon;


    public function __construct() {
        self::$addon = $this->addon();

        /**
         * TODO: prevent multiple entries (CLANG)
         */

        /**
         * a new article has been added
         */
        if(self::$addon->getConfig('article_added')) {
            $this->add('ART_ADDED', 'RexActivity\EP\article::message');
        }

        /**
         * a article has been updated
         */
        if(self::$addon->getConfig('article_updated')) {
            $this->update('ART_UPDATED', 'RexActivity\EP\article::message');
        }

        /**
         * a article has been deleted
         */
        if(self::$addon->getConfig('article_deleted')) {
            $this->delete('ART_DELETED', 'RexActivity\EP\article::message');
        }

        /**
         * the article status has been changed
         */
        if(self::$addon->getConfig('article_status')) {
            $this->update('ART_STATUS', 'RexActivity\EP\article::message');
        }
    }

    public static function message(array $params, string $type): string {
        $article = \rex_article::get($params['id']);

        $message = '<strong>Article:</strong> ';

        if($article) {
            $message .= '<a href="' . \rex_url::backendController([
                    'page' => 'content/edit',
                    'article_id' => $article->getId(),
                    'category_id' =>  $article->getCategoryId(),
                    'clang_id' => isset($params['clang_id']) ?: $params['clang'],
                    'mode' => 'edit'
                ]) . '">';
            $message .= $article->getName();
            $message .= '</a>';
        }
        else {
            $message .= ' ['.$params['id'].'] ';
            $message .= $params['name'];
        }

        $message .= ' - ';
        $message .= self::$addon->i18n('type_'.$type);

        return $message;
    }
}

