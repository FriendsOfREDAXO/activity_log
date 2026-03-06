<?php

namespace FriendsOfRedaxo\ActivityLog\EP;

use rex_addon;

class ActivityExtensionPoints
{
    public function __construct()
    {
        new Article();
        new Slice();
        new Meta();
        new Media();
        new Clang();
        new User();
        new Category();
        new Template();
        new Module();

        if (rex_addon::get('yform')->isAvailable()) {
            new Yform();
        }
    }
}
