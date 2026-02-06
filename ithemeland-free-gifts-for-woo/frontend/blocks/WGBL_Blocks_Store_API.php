<?php

namespace wgb\frontend\blocks;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class WGBL_Blocks_Store_API
{
    const IDENTIFIER = 'wgb-free-gifts';

    public static function init()
    {
        if (function_exists('woocommerce_store_api_register_update_callback')) {
            woocommerce_store_api_register_update_callback(
                array(
                    'namespace' => self::IDENTIFIER,
                    'callback' => array('\wgb\frontend\blocks\WGBL_Blocks_Store_API', 'rest_handle_endpoint'),
                )
            );
        }

        add_filter('woocommerce_store_api_product_quantity_minimum', array(__CLASS__, 'filter_cart_item_qty'), 10, 3);
        add_filter('woocommerce_store_api_product_quantity_maximum', array(__CLASS__, 'filter_cart_item_qty'), 10, 3);
    }

    public static function rest_handle_endpoint($args) {}

    public static function filter_cart_item_qty($value, $product, $cart_item)
    {
        if (!isset($cart_item['it_free_gift'])) {
            return $value;
        }

        return $cart_item['quantity'];
    }
}
