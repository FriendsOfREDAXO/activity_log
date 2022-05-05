<?php

namespace RexActivity\EP;

trait ep_trait
{
    public function addon() {
        return \rex_addon::get('activity_log');
    }

    public function user() {
        return \rex::getUser();
    }

    public function add(string $extensionPoint, callable $messageCallback): void {
        $this->logExtensionPoint(\rex_activity::TYPE_ADD, $extensionPoint, $messageCallback);
    }

    public function delete(string $extensionPoint, callable $messageCallback): void {
        $this->logExtensionPoint(\rex_activity::TYPE_DELETE, $extensionPoint, $messageCallback);
    }

    public function update(string $extensionPoint, callable $messageCallback): void {
        $this->logExtensionPoint(\rex_activity::TYPE_UPDATE, $extensionPoint, $messageCallback);
    }

    public function status(string $extensionPoint, callable $messageCallback): void {
        $this->logExtensionPoint(\rex_activity::TYPE_EDIT, $extensionPoint, $messageCallback, ['type' => 'status']);
    }

    public function logExtensionPoint(string $type, string $extensionPoint, callable $messageCallback, $additionalParams = null) {
        \rex_extension::register($extensionPoint, static function (\rex_extension_point $ep) use ($messageCallback, $type, $additionalParams) {
            $params = $ep->getParams();
            $message = '';

            if (is_callable($messageCallback)) {
                $message = $messageCallback($params, $type, $additionalParams);
            }

            \rex_activity::message($message)
                ->type($type)
                ->causer(\rex::getUser())
                ->log();
        });
    }

    public static function getStatus(bool $status, $additionalParams): string {
        if(isset($additionalParams['type']) && $additionalParams['type'] === 'status') {
            return $status ? '&nbsp;&nbsp;<span class="small rex-online"><i class="rex-icon rex-icon-online"></i>&nbsp;online</span>' : '&nbsp;&nbsp;<span class="small rex-offline"><i class="rex-icon rex-icon-offline"></i>&nbsp;offline</span>';
        }

        return '';
    }
}

