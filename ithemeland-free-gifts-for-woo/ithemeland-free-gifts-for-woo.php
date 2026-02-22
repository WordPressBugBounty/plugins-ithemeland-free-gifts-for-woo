<?php
/*
	Plugin Name: iThemeland Free Gifts For Woo Lite
	Plugin URI: https://ithemelandco.com/plugins/free-gifts-for-woocommerce/?utm_source=wp.org&utm_medium=web_links&utm_campaign=user-lite-buy
	Description: Free Gifts for WooCommerce allows you to offer Free Gifts to your customers whenever they make a purchase on your site.
	Author: iThemelandco
	Version: 4.0.0
	Tags: woocommerce,woocommerce gift
	Text Domain: ithemeland-free-gifts-for-woo
	Domain Path: /languages
	Author URI: https://www.ithemelandco.com
	Requires Plugins: woocommerce
	Tested up to: WP 6.9
	Requires PHP: 7.0	
	WC requires at least: 3.9
	WC tested up to: 10.5.2
	Requires at least: 4.6.1
	License: GPLv2
*/

use wgb\classes\bootstrap\WGBL;

defined('ABSPATH') || exit();

require_once __DIR__ . '/vendor/autoload.php';

//define('WGBL_PRICE_ISSUE', 1 );
define('WGBL_NAME', 'ithemeland-free-gifts-for-woo');
define('WGBL_PLUGIN', WGBL_NAME . '/' . WGBL_NAME . '.php');
define('WGBL_LABEL', 'iThemeland Free Gifts For Woo Lite');
define('WGBL_PLUGINS_DIR', trailingslashit(ABSPATH . 'wp-content/plugins'));
define('WGBL_LITE_PLUGIN', 'ithemeland-free-gifts-for-woo/ithemeland-free-gifts-for-woo.php');
define('WGBL_ADDONS_URL', admin_url('admin.php?page=wgb-addons'));
define('WGBL_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define('WGBL_MAIN_PAGE', admin_url('admin.php?page=wgb'));
define('WGBL_REPORTS_PAGE', admin_url('admin.php?page=wgb&tab=reports'));
define('WGBL_ACTIVATION_PAGE', admin_url('admin.php?page=ithemeland-activation'));
define('WGBL_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('WGBL_FW_URL', trailingslashit(WGBL_URL . 'framework'));
define('WGBL_FW_DIR', trailingslashit(WGBL_DIR . 'framework'));
define('WGBL_VIEWS_DIR', trailingslashit(WGBL_DIR . 'views'));
define('WGBL_LANGUAGES_DIR', dirname(plugin_basename(__FILE__)) . '/languages/');
define('WGBL_ASSETS_DIR', trailingslashit(WGBL_DIR . 'assets'));
define('WGBL_ASSETS_URL', trailingslashit(WGBL_URL . 'assets'));
define('WGBL_CSS_URL', trailingslashit(WGBL_ASSETS_URL . 'css'));
define('WGBL_IMAGES_URL', trailingslashit(WGBL_ASSETS_URL . 'images'));
define('WGBL_JS_URL', trailingslashit(WGBL_ASSETS_URL . 'js'));
define('WGBL_BLOCKS_URL', trailingslashit(WGBL_URL . 'blocks'));
define('WGBL_FRONTEND_DIR', trailingslashit(WGBL_DIR . 'frontend'));
define('WGBL_FRONTEND_URL', trailingslashit(WGBL_URL . 'frontend'));
define('WGBL_UPGRADE_URL', 'https://ithemelandco.com/plugins/free-gifts-for-woocommerce/?utm_source=wp.org&utm_medium=web_links&utm_campaign=user-lite-buy');
define('WGBL_UPGRADE_TEXT', 'Download Pro Version');
//define('WGBL_WP_TESTED', '6.6');
define('WGBL_WP_REQUIRE', '5.0.0');
define('WGBL_VERSION', '4.0.0');
define('WGBL_LITE_VERSION', '2.7.1');

register_activation_hook(__FILE__, ['wgb\classes\bootstrap\WGBL', 'activate']);
register_deactivation_hook(__FILE__, ['wgb\classes\bootstrap\WGBL', 'deactivate']);

add_action('init', ['wgb\classes\bootstrap\WGBL', 'wgb_wp_init']);
add_action('wp_loaded', ['wgb\classes\bootstrap\WGBL', 'wp_loaded']);

add_action('plugins_loaded', function () {
    if (!isitProPluginActive()) {
        if (WGBL::is_initable()) {
            require_once __DIR__ . '/frontend/main.php';
            WGBL::init();
        }
    }
});

// HPOS compatibility to the plugin.
add_action('before_woocommerce_init', function () {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

// Cart & Checkout blocks compatibility to the plugin.
add_action('before_woocommerce_init', function () {
    if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);
    }
});

if (!function_exists('isitProPluginActive')) {
    function isitProPluginActive()
    {
        $active_plugins = apply_filters('active_plugins', get_option('active_plugins', array()));
        if (is_multisite()) {
            $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
        }
        return in_array('ithemeland-free-gifts-for-woocommerce/ithemeland-free-gifts-for-woocommerce.php', $active_plugins, false) || array_key_exists('ithemeland-free-gifts-for-woocommerce/ithemeland-free-gifts-for-woocommerce.php', $active_plugins);
    }
}
