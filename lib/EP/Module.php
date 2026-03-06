<?php

namespace FriendsOfRedaxo\ActivityLog\EP;

use rex_addon_interface;
use rex_url;

use function is_bool;

class Module
{
    use EpTrait;

    /** @var rex_addon_interface */
    private static $addon;

    protected function getSource(): string
    {
        return 'module';
    }

    public function __construct()
    {
        self::$addon = $this->addon();

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('module_added')) && self::$addon->getConfig('module_added')) {
            $this->add('MODULE_ADDED', static::class . '::message');
        }

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('module_updated')) && self::$addon->getConfig('module_updated')) {
            $this->update('MODULE_UPDATED', static::class . '::message');
        }

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('module_deleted')) && self::$addon->getConfig('module_deleted')) {
            $this->delete('MODULE_DELETED', static::class . '::message');
        }
    }

    /**
     * @param array<string> $params
     */
    public static function message(array $params, string $type): string
    {
        $message = '<strong>Module:</strong> ';

        if ('delete' === $type) {
            $message .= '[' . $params['id'] . ']';
        } else {
            $message .= '<a href="' . rex_url::backendController([
                'page' => 'modules',
                'module_id' => $params['id'],
                'function' => 'edit',
            ]) . '">';
            $message .= rex_escape($params['name']);
            $message .= '</a>';
        }

        $message .= ' ' . self::$addon->i18n('type_' . $type);
        return $message;
    }
}
