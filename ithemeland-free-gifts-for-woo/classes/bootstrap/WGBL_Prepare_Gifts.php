<?php

namespace ITFreeGift\classes\bootstrap;

defined('ABSPATH') || exit(); // Exit if accessed directly

class WGBL_Prepare_Gifts
{
    private static $instance;

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    private function __construct()
    {
        $egift = get_option('wgb_egift');
        if (!empty($egift) && $egift < time()) {
            add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts'], 100);
            add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts'], 100);
        }
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('wgb-egift', WGBL_JS_URL . 'common/egift.js', ['jquery'], WGBL_VERSION, true); //phpcs:ignore
    }
}
