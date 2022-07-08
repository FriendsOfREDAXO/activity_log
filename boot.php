<?php

if (rex::isBackend() && is_object(rex::getUser())) {
    /** @var rex_addon $this */
    rex_view::addCssFile($this->getAssetsUrl('css/styles.css'));

    /**
     * hook into extension points
     */
    new \RexActivity\EP\rex_activity_extension_points();
}

if (rex_addon::get('cronjob')->isAvailable() && !rex::isSafeMode()) {
    rex_cronjob_manager::registerType('rex_activity_log_cronjob');
}