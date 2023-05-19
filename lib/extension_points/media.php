<?php

namespace RexActivity\EP;

class media
{
    use ep_trait;

    /**
     * @var \rex_addon_interface
     */
    private static $addon;

    public function __construct()
    {
        self::$addon = $this->addon();

        /**
         * new media has been added
         */
        if (is_bool(self::$addon->getConfig('media_added')) && self::$addon->getConfig('media_added')) {
            $this->add('MEDIA_ADDED', 'RexActivity\EP\media::message');
        }

        /**
         * media has been updated
         */
        if (is_bool(self::$addon->getConfig('media_updated')) && self::$addon->getConfig('media_updated')) {
            $this->update('MEDIA_UPDATED', 'RexActivity\EP\media::message');
        }

        /**
         * media has been deleted
         */
        if (is_bool(self::$addon->getConfig('media_deleted')) && self::$addon->getConfig('media_deleted')) {
            $this->delete('MEDIA_DELETED', 'RexActivity\EP\media::message');
        }
    }

    /**
     * @param array $params
     * @param string $type
     * @return string
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
