<?php

namespace FriendsOfRedaxo\ActivityLog\EP;

use rex_addon_interface;
use rex_category;
use rex_clang;
use rex_url;

use function is_bool;

class Category
{
    use EpTrait;

    /** @var rex_addon_interface */
    private static $addon;

    public function __construct()
    {
        self::$addon = $this->addon();

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('category_added')) && self::$addon->getConfig('category_added')) {
            $this->add('CAT_ADDED', static::class . '::message');
        }

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('category_updated')) && self::$addon->getConfig('category_updated')) {
            $this->update('CAT_UPDATED', static::class . '::message');
        }

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('category_deleted')) && self::$addon->getConfig('category_deleted')) {
            $this->delete('CAT_DELETED', static::class . '::message');
        }

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('category_status')) && self::$addon->getConfig('category_status')) {
            $this->status('CAT_STATUS', static::class . '::message');
        }
    }

    protected function getSource(): string
    {
        return 'category';
    }

    /**
     * @param array<string> $params
     * @param array<string>|null $additionalParams
     */
    public static function message(array $params, string $type, ?array $additionalParams = null): string
    {
        $message = '<strong>Category:</strong> ';

        // Do NOT call rex_category::get() for delete – the record is already gone
        // from DB when CAT_DELETED fires, causing an SQL conflict.
        if ('delete' === $type) {
            $message .= rex_escape($params['name'] ?? '[' . $params['id'] . ']');
            $message .= ' - ' . self::$addon->i18n('type_' . $type);
            return $message;
        }

        $category = rex_category::get((int) $params['id']);

        if (null === $category) {
            $message .= rex_escape($params['name'] ?? '[' . $params['id'] . ']');
        } else {
            $message .= '<a href="' . rex_url::backendController([
                'page' => 'structure',
                'article_id' => 0,
                'category_id' => $params['id'],
                'clang_id' => $params['clang'] ?? rex_clang::getCurrentId(),
            ]) . '">';
            $message .= rex_escape($category->getName());
            $message .= '</a>';
        }

        $message .= ' - ';

        if (isset($additionalParams['type'])) {
            $message .= self::$addon->i18n('type_' . $additionalParams['type']);
            if (null !== $category) {
                $message .= self::getStatus($category->isOnline(), $additionalParams);
            }
        } else {
            $message .= self::$addon->i18n('type_' . $type);
        }

        return $message;
    }
}
