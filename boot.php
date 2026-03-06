<?php

use FriendsOfRedaxo\ActivityLog\ActivityLogCronjob;
use FriendsOfRedaxo\ActivityLog\EP\ActivityExtensionPoints;

if (rex::isBackend() && is_object(rex::getUser())) {
    /** @var rex_addon $this */
    rex_view::addCssFile($this->getAssetsUrl('css/styles.css'));

    /**
     * Hook into extension points.
     */
    new ActivityExtensionPoints();
}

if (rex_addon::get('cronjob')->isAvailable() && !rex::isSafeMode()) {
    rex_cronjob_manager::registerType(ActivityLogCronjob::class);
}
