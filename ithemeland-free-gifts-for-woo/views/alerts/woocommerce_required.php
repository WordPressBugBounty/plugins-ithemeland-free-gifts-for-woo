<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 

function wgb_woocommerce_required_error()
{
    $class = 'notice notice-error';
    $message = esc_html__('WooCommerce Plugin is Inactive !', 'ithemeland-free-gifts-for-woo');
    printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
}

add_action('admin_notices', 'wgb_woocommerce_required_error');
