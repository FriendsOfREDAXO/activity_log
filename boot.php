<?php

if (rex::isBackend() && is_object(rex::getUser())) {
    rex_view::addCssFile($this->getAssetsUrl('css/styles.css'));

    /**
     * hook into extension points
     */
    new \RexActivity\EP\rex_activity_extension_points();
}
