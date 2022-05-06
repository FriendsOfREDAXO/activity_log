<?php

namespace RexActivity\EP;

class rex_activity_extension_points
{
    public function __construct() {
        new \RexActivity\EP\article();
        new \RexActivity\EP\slice();
        new \RexActivity\EP\meta();
        new \RexActivity\EP\media();
        new \RexActivity\EP\clang();
        new \RexActivity\EP\user();
        new \RexActivity\EP\category();
        new \RexActivity\EP\template();
        new \RexActivity\EP\module();
    }
}
