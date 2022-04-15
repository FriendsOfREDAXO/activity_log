<?php

namespace RexActivity\EP;

class rex_activity_extension_points
{
    public function __construct() {
        new \RexActivity\EP\article();
        new \RexActivity\EP\slice();
        new \RexActivity\EP\meta();
    }
}
