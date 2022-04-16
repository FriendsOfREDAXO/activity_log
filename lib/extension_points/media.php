<?php

namespace RexActivity\EP;

class media
{
    use ep_trait;

    /**
     * @var \rex_addon
     */
    private static $addon;

    public function __construct() {
        self::$addon = $this->addon();

        /**
         * new media has been added
         */
        if(self::$addon->getConfig('media_added')) {
            $this->add('MEDIA_ADDED', 'RexActivity\EP\media::message');
        }

        /**
         * media has been updated
         */
        if(self::$addon->getConfig('media_updated')) {
            $this->update('MEDIA_UPDATED', 'RexActivity\EP\media::message');
        }

        /**
         * media has been deleted
         */
        if(self::$addon->getConfig('media_deleted')) {
            $this->delete('MEDIA_DELETED', 'RexActivity\EP\media::message');
        }
    }

    private function message(array $params, string $type): string {
        $message = '<strong>Media:</strong> ';

        $message .= $params['filename'];
        $message .= ' - ';
        $message .= self::$addon->i18n('type_'.$type);

        return $message;
    }
}

