<?php

class rex_activity
{
    const TYPE_INFO = 'info';
    const TYPE_WARNING = 'warning';
    const TYPE_ERROR = 'error';
    const TYPE_NOTICE = 'notice';
    const TYPE_CRITICAL = 'critical';
    const TYPE_DEBUG = 'debug';
    const TYPE_ADD = 'add';
    const TYPE_UPDATE = 'update';
    const TYPE_EDIT = 'edit';
    const TYPE_DELETE = 'delete';

    /**
     * @var rex_addon $addon
     */
    private static $addon;
    private static $table;
    private static $activity = null;
    private static $message = null;
    private static $type = null;
    private static $causer = null;

    /**
     * @throws rex_sql_exception
     */
    public static function __constructStatic() {
        self::$table = rex::getTable('activity_log');
        self::$addon = \rex_addon::get('rex_activity');
        self::$addon->setConfig('cleared', false);

        /** clear old entries */
        self::clearEntries();

        if (self::$activity === null) {
            self::$activity = new self;
        }
    }

    /**
     * create log
     * @return void
     * @throws rex_exception
     * @throws rex_sql_exception
     */
    public static function log() {
        if (is_null(self::$message)) {
            throw new rex_exception('A message must be provided.');
        }

        $sql = rex_sql::factory();
        $sql->setTable(self::$table);
        $sql->setValue('created_at', date("Y-m-d H:i:s"));
        $sql->setValue('type', self::$type ?: self::TYPE_NOTICE);
        $sql->setValue('message', self::$message);
        $sql->setValue('causer_id', self::$causer);
        $sql->insert();
    }

    /**
     * add message
     * @param string $message
     * @return null
     */
    public static function message(string $message) {
        self::$message = $message;
        return self::$activity;
    }

    /**
     * add type, default info
     * @param string $type
     * @return null
     */
    public static function type(string $type) {
        self::$type = $type;
        return self::$activity;
    }

    /**
     * add a causer
     * @param rex_user|int $user
     * @return null
     */
    public static function causer($user) {
        if (is_numeric($user)) {
            self::$causer = $user;
        }
        elseif ($user instanceof rex_user) {
            self::$causer = $user->getId();
        }

        return self::$activity;
    }

    /**
     * list callback - user column
     * @param $params
     * @return string
     */
    public static function userListCallback($params): string {
        if ($params['subject']) {
            $user = rex_user::get($params['subject']);
            return '<a class="btn btn-sm btn-primary" href="' . rex_url::backendController(['page' => 'users/users', 'user_id' => $user->getId()]) . '" title="' . $user->getName() . '"><i class="rex-icon rex-icon-user"></i> ' . $user->getName() . '</a>';
        }

        return '';
    }

    /**
     * list callback - type column
     * @param $params
     * @return string
     */
    public static function typeListCallback($params): string {
        if ($params['value']) {
            return '<span class="badge ' . $params['value'] . '">' . $params['value'] . '</span>';
        }

        return '';
    }

    /**
     * list callback - message column
     * @param $params
     * @return string
     */
    public static function messageListCallback($params): string {
        return $params['subject'];
    }

    /**
     * clear entries older than 7 days...
     * TODO...
     * @throws rex_sql_exception
     */
    public static function clearEntries(): void {
        if (self::$addon->getConfig('cleared')) {
            return;
        }

        $sql = rex_sql::factory();
        $sql->setTable(self::$table);
        $sql->setWhere('DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 7 DAY)');

        if ($sql->getRows()) {
            $sql->delete();

            self::$addon->setConfig('cleared', true);
        }
    }
}

rex_activity::__constructStatic();
