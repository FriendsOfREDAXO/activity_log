<?php

namespace RexActivity\EP;

use rex_addon_interface;
use rex_article;
use rex_url;

use function is_bool;

class article
{
    use ep_trait;

    /** @var rex_addon_interface */
    private static $addon;

    public function __construct()
    {
        self::$addon = $this->addon();

        /**
         * TODO: prevent multiple entries (CLANG).
         */

        /**
         * a new article has been added.
         */
        if (is_bool(self::$addon->getConfig('article_added')) && self::$addon->getConfig('article_added')) {
            $this->add('ART_ADDED', 'RexActivity\EP\article::message');
        }

        /**
         * a article has been updated.
         */
        if (is_bool(self::$addon->getConfig('article_updated')) && self::$addon->getConfig('article_updated')) {
            $this->update('ART_UPDATED', 'RexActivity\EP\article::message');
        }

        /**
         * a article has been deleted.
         */
        if (is_bool(self::$addon->getConfig('article_deleted')) && self::$addon->getConfig('article_deleted')) {
            $this->delete('ART_DELETED', 'RexActivity\EP\article::message');
        }

        /**
         * the article status has been changed.
         */
        if (is_bool(self::$addon->getConfig('article_status')) && self::$addon->getConfig('article_status')) {
            $this->status('ART_STATUS', 'RexActivity\EP\article::message');
        }
    }

    /**
     * @param array<string> $params
     * @param array<string>|null $additionalParams
     */
    public static function message(array $params, string $type, ?array $additionalParams = null): string
    {
        $article = rex_article::get((int) $params['id']);

        $message = '<strong>Article:</strong> ';

        if (null !== $article) {
            $clang = $params['clang'];

            if (isset($params['clang_id'])) {
                $clang = $params['clang_id'];
            }

            $message .= '<a href="' . rex_url::backendController([
                'page' => 'content/edit',
                'article_id' => $article->getId(),
                'category_id' => $article->getCategoryId(),
                'clang_id' => $clang,
                'mode' => 'edit',
            ]) . '">';
            $message .= $article->getName();
            $message .= '</a>';
        } else {
            $message .= ' [' . $params['id'] . '] ';
            $message .= $params['name'];
        }

        $message .= ' - ';

        if (isset($additionalParams['type'])) {
            $message .= self::$addon->i18n('type_' . $additionalParams['type']);

            if (null !== $article) {
                $message .= self::getStatus($article->isOnline(), $additionalParams);
            }
        } else {
            $message .= self::$addon->i18n('type_' . $type);
        }

        return $message;
    }
}
