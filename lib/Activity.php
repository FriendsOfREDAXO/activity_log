<?php

namespace FriendsOfRedaxo\ActivityLog;

use DateTime;
use rex;
use rex_addon;
use rex_exception;
use rex_sql;
use rex_sql_exception;
use rex_url;
use rex_user;

use function assert;
use function is_int;

class Activity
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

    private static rex_addon $addon;
    private static string $table = '';
    private static ?self $activity = null;
    private static ?string $message = null;
    private static ?string $type = null;
    private static ?int $causer = null;
    private static ?string $source = null;

    /**
     * @throws rex_sql_exception
     */
    public static function __constructStatic(): void
    {
        self::$table = rex::getTable('activity_log');
        $addon = rex_addon::get('activity_log');
        assert($addon instanceof rex_addon);
        self::$addon = $addon;
        self::$addon->setConfig('cleared', false);

        if (null === self::$activity) {
            self::$activity = new self();
        }
    }

    /**
     * Create log entry.
     *
     * @throws rex_exception
     * @throws rex_sql_exception
     */
    public static function log(): void
    {
        if (null === self::$message) {
            throw new rex_exception('A message must be provided.');
        }

        /**
         * Return if user is admin and addon config is set.
         */
        $currentUser = rex::getUser();
        if (self::$addon->getConfig('disable_for_admins') && null !== $currentUser && $currentUser->isAdmin()) {
            return;
        }

        $sql = rex_sql::factory();
        $sql->setTable(self::$table);
        $sql->setValue('created_at', date('Y-m-d H:i:s'));
        $sql->setValue('type', self::$type ?: self::TYPE_NOTICE);
        $sql->setValue('message', self::$message);
        $sql->setValue('causer_id', self::$causer);
        $sql->setValue('source', self::$source);
        $sql->insert();

        // Reset state after logging
        self::$message = null;
        self::$type = null;
        self::$causer = null;
        self::$source = null;
    }

    /**
     * Set message.
     */
    public static function message(string $message): self
    {
        self::$message = $message;
        assert(null !== self::$activity);
        return self::$activity;
    }

    /**
     * Set type (default: notice).
     */
    public static function type(string $type): self
    {
        self::$type = $type;
        assert(null !== self::$activity);
        return self::$activity;
    }

    /**
     * Set source (e.g. 'article', 'yform', 'media').
     */
    public static function source(string $source): self
    {
        self::$source = $source;
        assert(null !== self::$activity);
        return self::$activity;
    }

    /**
     * Set causer.
     */
    public static function causer(rex_user|int|null $user): self
    {
        if (is_int($user)) {
            self::$causer = $user;
        } elseif ($user instanceof rex_user) {
            self::$causer = $user->getId();
        }

        assert(null !== self::$activity);
        return self::$activity;
    }

    /**
     * List callback – user column.
     *
     * @param array<string, mixed> $params
     */
    public static function userListCallback(array $params): string
    {
        if ($params['subject']) {
            /** @var rex_user|null $user */
            $user = rex_user::get($params['subject']);

            if (null === $user) {
                return rex_escape(self::$addon->i18n('deleted_user') . ' [' . $params['subject'] . ']');
            }

            $name = rex_escape('' !== $user->getName() ? $user->getName() : $user->getLogin());

            if (rex::getUser() instanceof rex_user && rex::getUser()->isAdmin()) {
                return '<a class="btn btn-sm btn-primary" href="' . rex_url::backendController(['page' => 'users/users', 'user_id' => $user->getId()]) . '" title="' . $name . '"><i class="rex-icon rex-icon-user"></i> ' . $name . '</a>';
            }

            return '<i class="rex-icon rex-icon-user"></i> ' . $name;
        }

        return '';
    }

    /**
     * List callback – type column.
     *
     * @param array<string, mixed> $params
     */
    public static function typeListCallback(array $params): string
    {
        if ($params['value']) {
            return '<span class="badge ' . $params['value'] . '">' . $params['value'] . '</span>';
        }

        return '';
    }

    /**
     * List callback – message column.
     *
     * @param array<string, mixed> $params
     */
    public static function messageListCallback(array $params): string
    {
        return $params['subject'];
    }

    /**
     * List callback – source column.
     *
     * @param array<string, mixed> $params
     */
    public static function sourceListCallback(array $params): string
    {
        $source = $params['subject'];
        if ('' === $source || null === $source) {
            return '<span class="text-muted">–</span>';
        }
        return '<span class="badge rex-activity-source rex-activity-source-' . rex_escape($source) . '">' . rex_escape(ucfirst($source)) . '</span>';
    }

    /**
     * Delete entries older than the given number of days.
     * Optionally restricted to a specific source (e.g. 'article', 'media').
     *
     * @throws rex_sql_exception
     */
    public static function clearEntries(int $daysToKeep = 7, ?string $source = null): void
    {
        if (self::$addon->getConfig('cleared')) {
            return;
        }

        $now = new DateTime();
        $now->modify('-' . $daysToKeep . ' day');
        $date = $now->format('Y-m-d H:i:s');

        $where = "created_at <= '$date'";
        if (null !== $source && '' !== $source) {
            $where .= ' AND source = ' . rex_sql::factory()->escape($source);
        }

        $sql = rex_sql::factory();
        $sql->setTable(self::$table);
        $sql->setWhere($where);
        $sql->select('id');

        if ($sql->getRows()) {
            $deleteSql = rex_sql::factory();
            $deleteSql->setTable(self::$table);
            $deleteSql->setWhere($where);
            $deleteSql->delete();

            self::$addon->setConfig('cleared', true);
        }
    }
}

Activity::__constructStatic();
