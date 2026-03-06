<?php

namespace FriendsOfREDAXO\ActivityLog\EP;

use rex_addon_interface;

use function is_bool;

class Media
{
    use EpTrait;

    /** @var rex_addon_interface */
    private static $addon;

    public function __construct()
    {
        self::$addon = $this->addon();

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('media_added')) && self::$addon->getConfig('media_added')) {
            $this->add('MEDIA_ADDED', static::class . '::message');
        }

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('media_updated')) && self::$addon->getConfig('media_updated')) {
            $this->update('MEDIA_UPDATED', static::class . '::message');
        }

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('media_deleted')) && self::$addon->getConfig('media_deleted')) {
            $this->delete('MEDIA_DELETED', static::class . '::message');
        }
    }

    /**
     * @param array<string> $params
     */
    public static function message(array $params, string $type): string
    {
        $message = '<strong>Media:</strong> ';
        $message .= $params['filename'];
        $message .= ' - ';
        $message .= self::$addon->i18n('type_' . $type);

        return $message;
    }
}
