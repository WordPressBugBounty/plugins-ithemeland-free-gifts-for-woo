<?php
function wgbl_woocommerce_required_error()
{
    $class = 'notice notice-error';
    $message = esc_html__('WooCommerce Plugin is Inactive !');
    printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
}

add_action('admin_notices', 'wgbl_woocommerce_required_error');
