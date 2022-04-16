<?php

namespace RexActivity\EP;

class user
{
    use ep_trait;

    /**
     * @var \rex_addon
     */
    private static $addon;

    public function __construct() {
        self::$addon = $this->addon();

        /**
         * new user has been added
         */
        if(self::$addon->getConfig('user_added')) {
            $this->add('USER_ADDED', 'RexActivity\EP\user::message');
        }

        /**
         * user has been updated
         */
        if(self::$addon->getConfig('user_updated')) {
            $this->update('USER_UPDATED', 'RexActivity\EP\user::message');
        }

        /**
         * user has been deleted
         */
        if(self::$addon->getConfig('user_deleted')) {
            $this->delete('USER_DELETED', 'RexActivity\EP\user::message');
        }
    }

    private function message(array $params, string $type): string {
        $message = '<strong>User:</strong> ';
        $message .= '<a href="' . \rex_url::backendController(['page' => 'users/users', 'user_id' => $params['user']->getId()]) . '" title="' . $params['user']->getName() . '">' . $params['user']->getName() . '</a>';
        $message .= ' - ';
        $message .= self::$addon->i18n('type_'.$type);

        return $message;
    }
}

