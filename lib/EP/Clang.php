<?php

namespace FriendsOfREDAXO\ActivityLog\EP;

use rex_addon_interface;
use rex_clang;
use rex_url;

use function is_bool;

class Clang
{
    use EpTrait;

    /** @var rex_addon_interface */
    private static $addon;

    protected function getSource(): string
    {
        return 'clang';
    }

    public function __construct()
    {
        self::$addon = $this->addon();

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('clang_added')) && self::$addon->getConfig('clang_added')) {
            $this->add('CLANG_ADDED', static::class . '::message');
        }

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('clang_updated')) && self::$addon->getConfig('clang_updated')) {
            $this->update('CLANG_UPDATED', static::class . '::message');
        }

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('clang_deleted')) && self::$addon->getConfig('clang_deleted')) {
            $this->delete('CLANG_DELETED', static::class . '::message');
        }
    }

    /**
     * @param array<string> $params
     */
    public static function message(array $params, string $type): string
    {
        $message = '<strong>Language:</strong> ';
        $message .= '<a href="' . rex_url::backendController(['page' => 'system/lang']) . '">';
        $message .= $params['name'];
        $message .= '</a>';
        $message .= ' - ';
        $message .= self::$addon->i18n('type_' . $type);

        return $message;
    }
}
