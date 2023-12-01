<?php

namespace RexActivity\EP;

use rex_addon;

class rex_activity_extension_points
{
    public function __construct()
    {
        new \RexActivity\EP\article();
        new \RexActivity\EP\slice();
        new \RexActivity\EP\meta();
        new \RexActivity\EP\media();
        new \RexActivity\EP\clang();
        new \RexActivity\EP\user();
        new \RexActivity\EP\category();
        new \RexActivity\EP\template();
        new \RexActivity\EP\module();

        /**
         * check if yform is available.
         */
        if (rex_addon::get('yform')->isAvailable()) {
            new \RexActivity\EP\yform();
        }
    }
}
