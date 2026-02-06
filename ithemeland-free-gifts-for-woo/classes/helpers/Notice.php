<?php

namespace wgb\classes\helpers;

defined('ABSPATH') || exit(); // Exit if accessed directly

class Notice
{
    public static function add($message, $type)
    {
        $settings = apply_filters('wgb_notice_settings', ['enable' => true]);
        if (!empty($settings['enable']) && $settings['enable'] === true) {
            wc_add_notice($message, $type);
        }
    }

    public static function print($message, $type)
    {
        $settings = apply_filters('wgb_notice_settings', ['enable' => true]);
        if (!empty($settings['enable']) && $settings['enable'] === true) {
            wc_print_notice($message, $type);
        }
    }

    public static function print_notices()
    {
        $settings = apply_filters('wgb_notice_settings', ['enable' => true]);
        if (!empty($settings['enable']) && $settings['enable'] === true) {
            wc_print_notices();
        }
    }
}
