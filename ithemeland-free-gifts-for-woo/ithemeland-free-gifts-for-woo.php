<?php
/*
	Plugin Name: iThemeland Free Gifts For Woo Lite
	Plugin URI: https://ithemelandco.com/plugins/free-gifts-for-woocommerce/?utm_source=wp.org&utm_medium=web_links&utm_campaign=user-lite-buy
	Description: Free Gifts for WooCommerce allows you to offer Free Gifts to your customers whenever they make a purchase on your site.
	Author: iThemelandco
	Version: 2.7.5
	Tags: woocommerce,woocommerce gift
	Text Domain: ithemeland-free-gifts-for-woo
	Domain Path: /languages
	Author URI: https://www.ithemelandco.com
	Requires Plugins: woocommerce
	Tested up to: WP 6.8
	Requires PHP: 7.0	
	WC requires at least: 3.9
	WC tested up to: 9.8.5
	Requires at least: 4.6.1
	License: GPLv2 or later
*/

defined('ABSPATH') || exit();

require_once __DIR__ . '/vendor/autoload.php';

define('WGBL_NAME', 'ithemeland-free-gifts-for-woo');
define('WGBL_LABEL', 'iThemeland Free Gift Lite');
define('WGBL_DESCRIPTION', 'Be professionals with managing data in the reliable and flexible way!');
define('WGBL_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define('WGBL_MAIN_PAGE', admin_url('admin.php?page=wgbl'));
define('WGBL_REPORTS_PAGE', admin_url('admin.php?page=wgbl-reports'));
define('WGBL_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('WGBL_VIEWS_DIR', trailingslashit(WGBL_DIR . 'views'));
define('WGBL_LANGUAGES_DIR', dirname(plugin_basename(__FILE__)) . '/languages/');
define('WGBL_ASSETS_DIR', trailingslashit(WGBL_DIR . 'assets'));
define('WGBL_ASSETS_URL', trailingslashit(WGBL_URL . 'assets'));
define('WGBL_FW_DIR', trailingslashit(WGBL_DIR . 'framework'));
define('WGBL_FW_URL', trailingslashit(WGBL_URL . 'framework'));
define('WGBL_CSS_URL', trailingslashit(WGBL_ASSETS_URL . 'css'));
define('WGBL_IMAGES_URL', trailingslashit(WGBL_ASSETS_URL . 'images'));
define('WGBL_JS_URL', trailingslashit(WGBL_ASSETS_URL . 'js'));
define('WGBL_WP_TESTED', '6.8');
define('WGBL_WP_REQUIRE', '5.0.0');
define('WGBL_VERSION', '2.7.5');
define('WGBL_UPGRADE_URL', 'https://ithemelandco.com/plugins/free-gifts-for-woocommerce/?utm_source=wp.org&utm_medium=web_links&utm_campaign=user-lite-buy');
define('WGBL_UPGRADE_TEXT', 'Download Pro Version');

register_activation_hook(__FILE__, ['wgbl\classes\bootstrap\WGBL', 'activate']);
register_deactivation_hook(__FILE__, ['wgbl\classes\bootstrap\WGBL', 'deactivate']);

add_action('init', ['wgbl\classes\bootstrap\WGBL', 'wgbl_wp_init']);
add_action('wp_loaded', ['wgbl\classes\bootstrap\WGBL', 'wp_loaded']);

add_action('plugins_loaded', 'wgbl_init');
function wgbl_init()
{
	if(!isitProPluginActive()){
        if (!class_exists('WooCommerce')) {
            wgbl\classes\bootstrap\WGBL::wgbl_woocommerce_required();
        } else {
			
            require_once __DIR__ . '/frontend/main.php';
            wgbl\classes\bootstrap\WGBL::init();
        }
		add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'itg_action_links');
	}
}


function itg_action_links($links)
{
    return array_merge(array(
        '<a href="' . esc_url(apply_filters('woocommerce_docs_url', 'https://ithemelandco.com/plugins/free-gifts-for-woocommerce/?utm_source=wp.org&utm_medium=web_links&utm_campaign=user-lite-buy', WGBL_NAME)) . '"><span style="color: red;background-color: #FFFF00">' . __('Get Pro Version', 'ithemeland-free-gifts-for-woo') . '</span></a>',
        '<a href="' . esc_url(apply_filters('woocommerce_docs_url', 'https://ithemelandco.com/docs/free-gifts-for-woocommerce/?utm_source=wp.org&utm_medium=web_links&utm_campaign=user-lite-buy', WGBL_NAME)) . '">' . __('Docs', 'ithemeland-free-gifts-for-woo') . '</a>',

    ), $links);
}


// HPOS compatibility to the plugin.
add_action('before_woocommerce_init', 'itg_free_declare_WC_HPOS_compatibility');
/**
 * Declare the plugin is compatibility with WC HPOS.
 * 
 * @since 2.0.0
 */
function itg_free_declare_WC_HPOS_compatibility()
{
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
}


if(!function_exists('isitProPluginActive')){
    function isitProPluginActive()
    {
        $active_plugins = apply_filters('active_plugins', get_option('active_plugins', array()));
        if (is_multisite()) {
            $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
        }
        return in_array('ithemeland-free-gifts-for-woocommerce/ithemeland-free-gifts-for-woocommerce.php', $active_plugins, false) || array_key_exists('ithemeland-free-gifts-for-woocommerce/ithemeland-free-gifts-for-woocommerce.php', $active_plugins);
    }
}