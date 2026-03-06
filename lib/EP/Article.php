<?php

namespace FriendsOfRedaxo\ActivityLog\EP;

use FriendsOfRedaxo\ActivityLog\Activity;
use rex;
use rex_addon_interface;
use rex_article;
use rex_clang;
use rex_extension;
use rex_extension_point;
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
            $this->addArticle();
        }

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('article_updated')) && self::$addon->getConfig('article_updated')) {
            $this->update('ART_UPDATED', static::class . '::message');
        }

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('article_deleted')) && self::$addon->getConfig('article_deleted')) {
            $this->deleteArticle();
        }

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('article_status')) && self::$addon->getConfig('article_status')) {
            $this->status('ART_STATUS', static::class . '::message');
        }
    }

    protected function getSource(): string
    {
        return 'article';
    }

    /**
     * Registriert den ART_ADDED-Handler mit Deduplication:
     * Da REDAXO ART_ADDED einmal pro Sprache feuert, wird nur der erste Aufruf
     * pro Artikel-ID geloggt.
     */
    private function addArticle(): void
    {
        $source = $this->getSource();
        rex_extension::register('ART_ADDED', static function (rex_extension_point $ep) use ($source): void {
            /** @var array<string, mixed> $params */
            $params = $ep->getParams();
            $articleId = (int) ($params['id'] ?? 0);

            /** @var array<int, bool> $logged */
            static $logged = [];

            if (isset($logged[$articleId])) {
                return;
            }
            $logged[$articleId] = true;

            $message = static::message($params, Activity::TYPE_ADD);

            Activity::message($message)
                ->type(Activity::TYPE_ADD)
                ->source($source)
                ->causer(rex::getUser())
                ->log();
        });
    }

    /**
     * Registriert den ART_DELETED-Handler mit Deduplication:
     * Da REDAXO ART_DELETED einmal pro Sprache feuert, wird nur der erste Aufruf
     * pro Artikel-ID geloggt.
     */
    private function deleteArticle(): void
    {
        $source = $this->getSource();
        rex_extension::register('ART_DELETED', static function (rex_extension_point $ep) use ($source): void {
            /** @var array<string, mixed> $params */
            $params = $ep->getParams();
            $articleId = (int) ($params['id'] ?? 0);

            /** @var array<int, bool> $logged */
            static $logged = [];

            if (isset($logged[$articleId])) {
                return;
            }
            $logged[$articleId] = true;

            $message = static::message($params, Activity::TYPE_DELETE);

            Activity::message($message)
                ->type(Activity::TYPE_DELETE)
                ->source($source)
                ->causer(rex::getUser())
                ->log();
        });
    }

    /**
     * @param array<string> $params
     * @param array<string>|null $additionalParams
     */
    public static function message(array $params, string $type, ?array $additionalParams = null): string
    {
        $message = '<strong>Article:</strong> ';

        // Do NOT call rex_article::get() for delete – the record is already gone
        // from DB when ART_DELETED fires, causing an SQL conflict.
        if ('delete' === $type) {
            $message .= rex_escape($params['name'] ?? '[' . $params['id'] . ']');
            $message .= ' - ' . self::$addon->i18n('type_' . $type);
            return $message;
        }

        /** @var rex_article|null $article */
        $article = rex_article::get((int) $params['id']);

        if (null === $article) {
            $message .= rex_escape($params['name'] ?? '[' . $params['id'] . ']');
        } else {
            $message .= '<a href="' . rex_url::backendController([
                'page' => 'content/edit',
                'article_id' => $article->getId(),
                'category_id' => $article->getCategoryId(),
                'clang_id' => $params['clang'] ?? rex_clang::getCurrentId(),
                'mode' => 'edit',
            ]) . '">';
            $message .= rex_escape($article->getName());
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
