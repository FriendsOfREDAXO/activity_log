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

    public function logExtensionPoint(string $type, string $extensionPoint, callable $messageCallback) {
        \rex_extension::register($extensionPoint, static function (\rex_extension_point $ep) use ($messageCallback, $type) {
            $params = $ep->getParams();
            $message = '';

            if (is_callable($messageCallback)) {
                $message = $messageCallback($params, $type);
            }

            \rex_activity::message($message)
                ->type($type)
                ->causer(\rex::getUser())
                ->log();
        });
    }
}

