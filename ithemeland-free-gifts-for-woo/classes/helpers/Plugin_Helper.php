<?php

namespace wgb\classes\helpers;

defined('ABSPATH') || exit(); // Exit if accessed directly

class Plugin_Helper
{
    public static function it_brands_is_active()
    {
        return (defined('AS_PLUGIN'));
    }
}
