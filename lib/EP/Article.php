<?php

namespace FriendsOfREDAXO\ActivityLog\EP;

use rex_addon;
use rex_addon_interface;
use rex_article;
use rex_url;

use function is_bool;

class Article
{
    use EpTrait;

    /** @var rex_addon_interface */
    private static $addon;

    public function __construct()
    {
        self::$addon = $this->addon();

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('article_added')) && self::$addon->getConfig('article_added')) {
            $this->add('ART_ADDED', static::class . '::message');
        }

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('article_updated')) && self::$addon->getConfig('article_updated')) {
            $this->update('ART_UPDATED', static::class . '::message');
        }

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('article_deleted')) && self::$addon->getConfig('article_deleted')) {
            $this->delete('ART_DELETED', static::class . '::message');
        }

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('article_status')) && self::$addon->getConfig('article_status')) {
            $this->status('ART_STATUS', static::class . '::message');
        }
    }

    /**
     * @param array<string> $params
     * @param array<string>|null $additionalParams
     */
    public static function message(array $params, string $type, ?array $additionalParams = null): string
    {
        /** @var rex_article|null $article */
        $article = rex_article::get((int) $params['id']);

        $message = '<strong>Article:</strong> ';

        if ('delete' === $type || null === $article) {
            $message .= $params['name'] ?? '[' . $params['id'] . ']';
        } else {
            $message .= '<a href="' . rex_url::backendController([
                'page' => 'content/edit',
                'article_id' => $article->getId(),
                'category_id' => $article->getCategoryId(),
                'clang_id' => $params['clang'] ?? \rex_clang::getCurrentId(),
                'mode' => 'edit',
            ]) . '">';
            $message .= $article->getName();
            $message .= '</a>';
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
