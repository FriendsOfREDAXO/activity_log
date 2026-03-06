<?php

namespace FriendsOfRedaxo\ActivityLog\EP;

use rex_addon_interface;
use rex_url;

use function is_bool;

class User
{
    use EpTrait;

    /** @var rex_addon_interface */
    private static $addon;

    public function __construct()
    {
        self::$addon = $this->addon();

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('user_added')) && self::$addon->getConfig('user_added')) {
            $this->add('USER_ADDED', static::class . '::message');
        }

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('user_updated')) && self::$addon->getConfig('user_updated')) {
            $this->update('USER_UPDATED', static::class . '::message');
        }

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('user_deleted')) && self::$addon->getConfig('user_deleted')) {
            $this->delete('USER_DELETED', static::class . '::message');
        }
    }

    protected function getSource(): string
    {
        return 'user';
    }

    /**
     * @param array<string> $params
     */
    public static function message(array $params, string $type): string
    {
        $message = '<strong>User:</strong> ';
        $message .= '<a href="' . rex_url::backendController([
            'page' => 'users/users',
            'user_id' => $params['user']->getId(),
        ]) . '" title="' . rex_escape($params['user']->getName()) . '">';
        $message .= rex_escape($params['user']->getName());
        $message .= '</a>';
        $message .= ' - ';
        $message .= self::$addon->i18n('type_' . $type);

        return $message;
    }
}
