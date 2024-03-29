<?php

namespace RexActivity\EP;

use rex;
use rex_activity;
use rex_addon;
use rex_addon_interface;
use rex_extension;
use rex_extension_point;
use rex_user;

use function is_callable;

trait ep_trait
{
    /**
     * @return rex_addon_interface
     */
    public function addon()
    {
        return rex_addon::get('activity_log');
    }

    /**
     * @return rex_user|null
     */
    public function user()
    {
        return rex::getUser();
    }

    public function add(string $extensionPoint, callable $messageCallback): void
    {
        $this->logExtensionPoint(rex_activity::TYPE_ADD, $extensionPoint, $messageCallback);
    }

    public function delete(string $extensionPoint, callable $messageCallback): void
    {
        $this->logExtensionPoint(rex_activity::TYPE_DELETE, $extensionPoint, $messageCallback);
    }

    public function update(string $extensionPoint, callable $messageCallback): void
    {
        $this->logExtensionPoint(rex_activity::TYPE_UPDATE, $extensionPoint, $messageCallback);
    }

    public function status(string $extensionPoint, callable $messageCallback): void
    {
        $this->logExtensionPoint(rex_activity::TYPE_EDIT, $extensionPoint, $messageCallback, ['type' => 'status']);
    }

    public function move(string $extensionPoint, callable $messageCallback): void
    {
        $this->logExtensionPoint(rex_activity::TYPE_UPDATE, $extensionPoint, $messageCallback, ['type' => 'move']);
    }

    /**
     * @param array<string>|null $additionalParams
     * @return void
     */
    public function logExtensionPoint(string $type, string $extensionPoint, callable $messageCallback = null, ?array $additionalParams = null)
    {
        rex_extension::register($extensionPoint, static function (rex_extension_point $ep) use ($messageCallback, $type, $additionalParams) {
            $params = $ep->getParams();
            $message = '';

            if (is_callable($messageCallback)) {
                $message = $messageCallback($params, $type, $additionalParams);
            }

            rex_activity::message($message)
                ->type($type)
                ->causer(rex::getUser())
                ->log();
        });
    }

    public static function getStatus(bool $status, $additionalParams): string
    {
        if (isset($additionalParams['type']) && 'status' === $additionalParams['type']) {
            return $status ? '&nbsp;&nbsp;<span class="small rex-online"><i class="rex-icon rex-icon-online"></i>&nbsp;online</span>' : '&nbsp;&nbsp;<span class="small rex-offline"><i class="rex-icon rex-icon-offline"></i>&nbsp;offline</span>';
        }

        return '';
    }
}
