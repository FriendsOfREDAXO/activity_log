<?php

class rex_activity
{
    public const TYPE_INFO = 'info';
    public const TYPE_WARNING = 'warning';
    public const TYPE_ERROR = 'error';
    public const TYPE_NOTICE = 'notice';
    public const TYPE_CRITICAL = 'critical';
    public const TYPE_DEBUG = 'debug';
    public const TYPE_ADD = 'add';
    public const TYPE_UPDATE = 'update';
    public const TYPE_EDIT = 'edit';
    public const TYPE_DELETE = 'delete';

    /** @var rex_addon|null */
    private static $addon;
    private static $table = '';
    private static $activity;
    private static $message;
    private static $type;
    private static $causer;

    /**
     * @throws rex_sql_exception
     */
    public static function __constructStatic()
    {
        self::$table = \rex::getTable('activity_log');
        self::$addon = \rex_addon::get('activity_log');
        self::$addon->setConfig('cleared', false);

        /** clear old entries */
        // self::clearEntries();

        if (null === self::$activity) {
            self::$activity = new self();
        }
    }

    /**
     * create log.
     * @throws rex_exception
     * @throws rex_sql_exception
     * @return void
     */
    public static function log()
    {
        if (null === self::$message) {
            throw new rex_exception('A message must be provided.');
        }

        /**
         * Return if user is admin and addon config is set.
         */
        if (self::$addon->getConfig('disable_for_admins') && rex::getUser()->isAdmin()) {
            return;
        }

        $sql = rex_sql::factory();
        $sql->setTable(self::$table);
        $sql->setValue('created_at', date('Y-m-d H:i:s'));
        $sql->setValue('type', self::$type ?: self::TYPE_NOTICE);
        $sql->setValue('message', self::$message);
        $sql->setValue('causer_id', self::$causer);
        $sql->insert();
    }

    /**
     * add message.
     * @return rex_activity|null
     */
    public static function message(string $message)
    {
        self::$message = $message;
        return self::$activity;
    }

    /**
     * add type, default info.
     * @return null
     */
    public static function type(string $type)
    {
        self::$type = $type;
        return self::$activity;
    }

    /**
     * add a causer.
     * @param rex_user|int $user
     * @return null
     */
    public static function causer($user)
    {
        if (is_numeric($user)) {
            self::$causer = $user;
        } elseif ($user instanceof rex_user) {
            self::$causer = $user->getId();
        }

        return self::$activity;
    }

    /**
     * list callback - user column.
     */
    public static function userListCallback($params): string
    {
        if ($params['subject']) {
            /** @var \rex_user $user */
            $user = rex_user::get($params['subject']);

            if (null === $user) {
                return '<a href="#" class="btn btn-sm btn-primary rex-activity-btn-disabled"><i class="rex-icon rex-icon-user"></i> ' . self::$addon->i18n('deleted_user') . ' [' . $params['subject'] . ']</a>';
            }

            $name = '' !== $user->getName() ? $user->getName() : $user->getLogin();
            return '<a class="btn btn-sm btn-primary" href="' . rex_url::backendController(['page' => 'users/users', 'user_id' => $user->getId()]) . '" title="' . $name . '"><i class="rex-icon rex-icon-user"></i> ' . $name . '</a>';
        }

        return '';
    }

    /**
     * list callback - type column.
     */
    public static function typeListCallback($params): string
    {
        if ($params['value']) {
            return '<span class="badge ' . $params['value'] . '">' . $params['value'] . '</span>';
        }

        return '';
    }

    /**
     * list callback - message column.
     */
    public static function messageListCallback($params): string
    {
        return $params['subject'];
    }

    /**
     * clear entries older than 7 days...
     * TODO...
     * @throws rex_sql_exception
     */
    public static function clearEntries(): void
    {
        if (self::$addon->getConfig('cleared')) {
            return;
        }

        $now = (new \DateTime());
        $now->modify('-7 day');
        $date = $now->format('Y-m-d H:i:s');

        $sql = rex_sql::factory();
        $sql->setTable(self::$table);
        $sql->setWhere("created_at <= '$date'");
        $sql->select('id');

        if ($sql->getRows()) {
            $deleteSql = \rex_sql::factory();
            $deleteSql->setTable(self::$table);
            $deleteSql->setWhere("created_at <= '$date'");
            $deleteSql->delete();

            self::$addon->setConfig('cleared', true);
        }
    }
}

rex_activity::__constructStatic();
