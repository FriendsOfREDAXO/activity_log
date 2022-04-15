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
}

