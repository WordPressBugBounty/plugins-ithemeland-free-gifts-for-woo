<?php

namespace wgbl\classes\helpers;

class Plugin_Helper
{
    public static function it_brands_is_active()
    {
        return (defined('AS_PLUGIN'));
    }
}
