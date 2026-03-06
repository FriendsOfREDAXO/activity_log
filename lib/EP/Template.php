<?php

namespace FriendsOfRedaxo\ActivityLog\EP;

use rex_addon_interface;
use rex_url;

use function is_bool;

class Template
{
    use EpTrait;

    /** @var rex_addon_interface */
    private static $addon;

    protected function getSource(): string
    {
        return 'template';
    }

    public function __construct()
    {
        self::$addon = $this->addon();

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('template_added')) && self::$addon->getConfig('template_added')) {
            $this->add('TEMPLATE_ADDED', static::class . '::message');
        }

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('template_updated')) && self::$addon->getConfig('template_updated')) {
            $this->update('TEMPLATE_UPDATED', static::class . '::message');
        }

        /** @phpstan-ignore-next-line */
        if (is_bool(self::$addon->getConfig('template_deleted')) && self::$addon->getConfig('template_deleted')) {
            $this->delete('TEMPLATE_DELETED', static::class . '::message');
        }
    }

    /**
     * @param array<string> $params
     */
    public static function message(array $params, string $type): string
    {
        $message = '<strong>Template:</strong> ';

        if ('delete' === $type) {
            $message .= '[' . $params['id'] . ']';
        } else {
            $message .= '<a href="' . rex_url::backendController([
                'page' => 'templates',
                'template_id' => $params['id'],
                'function' => 'edit',
            ]) . '">';
            $message .= $params['name'];
            $message .= '</a>';
        }

        $message .= ' ' . self::$addon->i18n('type_' . $type);
        return $message;
    }
}
