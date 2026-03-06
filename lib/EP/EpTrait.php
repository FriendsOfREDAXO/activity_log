<?php

namespace FriendsOfREDAXO\ActivityLog\EP;

use FriendsOfREDAXO\ActivityLog\Activity;
use rex;
use rex_addon;
use rex_addon_interface;
use rex_extension;
use rex_extension_point;
use rex_user;

use function is_callable;

trait EpTrait
{
    public function addon(): rex_addon_interface
    {
        return rex_addon::get('activity_log');
    }

    public function user(): ?rex_user
    {
        return rex::getUser();
    }

    /**
     * Override in each EP class to provide the log source identifier.
     */
    protected function getSource(): string
    {
        return '';
    }

    public function add(string $extensionPoint, callable $messageCallback): void
    {
        $this->logExtensionPoint(Activity::TYPE_ADD, $extensionPoint, $messageCallback);
    }

    public function delete(string $extensionPoint, callable $messageCallback): void
    {
        $this->logExtensionPoint(Activity::TYPE_DELETE, $extensionPoint, $messageCallback);
    }

    public function update(string $extensionPoint, callable $messageCallback): void
    {
        $this->logExtensionPoint(Activity::TYPE_UPDATE, $extensionPoint, $messageCallback);
    }

    public function status(string $extensionPoint, callable $messageCallback): void
    {
        $this->logExtensionPoint(Activity::TYPE_EDIT, $extensionPoint, $messageCallback, ['type' => 'status']);
    }

    public function move(string $extensionPoint, callable $messageCallback): void
    {
        $this->logExtensionPoint(Activity::TYPE_UPDATE, $extensionPoint, $messageCallback, ['type' => 'move']);
    }

    /**
     * @param array<string>|null $additionalParams
     */
    public function logExtensionPoint(string $type, string $extensionPoint, ?callable $messageCallback = null, ?array $additionalParams = null): void
    {
        $source = $this->getSource();
        rex_extension::register($extensionPoint, static function (rex_extension_point $ep) use ($messageCallback, $type, $additionalParams, $source) {
            $params = $ep->getParams();
            $message = '';

            if (is_callable($messageCallback)) {
                $message = $messageCallback($params, $type, $additionalParams);
            }

            Activity::message($message)
                ->type($type)
                ->source($source)
                ->causer(rex::getUser())
                ->log();
        });
    }

    public static function getStatus(bool $status, ?array $additionalParams): string
    {
        if (isset($additionalParams['type']) && 'status' === $additionalParams['type']) {
            return $status
                ? '&nbsp;&nbsp;<span class="small rex-online"><i class="rex-icon rex-icon-online"></i>&nbsp;online</span>'
                : '&nbsp;&nbsp;<span class="small rex-offline"><i class="rex-icon rex-icon-offline"></i>&nbsp;offline</span>';
        }

        return '';
    }
}
