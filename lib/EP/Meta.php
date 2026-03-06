<?php

namespace FriendsOfRedaxo\ActivityLog\EP;

use rex_addon_interface;
use rex_article;
use rex_clang;
use rex_url;

use function is_bool;

/**
 * Extension point handler for article meta updates.
 * Fix #46: clang_id may be missing from params – fallback to current clang.
 */
class Meta
{
    use EpTrait;

    /** @var rex_addon_interface */
    private static $addon;

    protected function getSource(): string
    {
        return 'meta';
    }

    public function __construct()
    {
        self::$addon = $this->addon();

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('meta_updated')) && self::$addon->getConfig('meta_updated')) {
            $this->update('ART_META_UPDATED', static::class . '::message');
        }
    }

    /**
     * @param array<string> $params
     */
    public static function message(array $params, string $type): string
    {
        /** @var rex_article|null $article */
        $article = rex_article::get((int) $params['id']);

        // Fix #46: clang_id may not be present in all contexts
        $clangId = $params['clang_id'] ?? rex_clang::getCurrentId();

        $message = '<strong>Meta Info:</strong> ';
        $message .= '<a href="' . rex_url::backendController([
            'page' => 'content/edit',
            'article_id' => $article ? $article->getId() : (int) $params['id'],
            'category_id' => $article ? $article->getCategoryId() : 0,
            'clang_id' => $clangId,
            'mode' => 'edit',
        ]) . '">';
        $message .= rex_escape($article ? $article->getName() : '[' . $params['id'] . ']');
        $message .= '</a>';
        $message .= ' - ';
        $message .= self::$addon->i18n('type_' . $type);

        return $message;
    }
}
