<?php

namespace ITFreeGift\frontend\blocks;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class WGBL_Blocks_Store_API
{
    const IDENTIFIER = 'wgb-free-gifts';
    private static $filters_registered = false;
    private static $update_callback_registered = false;

    public static function init()
    {
        if (!self::$update_callback_registered && function_exists('woocommerce_store_api_register_update_callback')) {
            woocommerce_store_api_register_update_callback(
                array(
                    'namespace' => self::IDENTIFIER,
                    'callback' => array('\ITFreeGift\frontend\blocks\WGBL_Blocks_Store_API', 'rest_handle_endpoint'),
                )
            );
            self::$update_callback_registered = true;
        }

        if (self::$filters_registered) {
            return;
        }

        add_filter('woocommerce_store_api_product_quantity_minimum', array(__CLASS__, 'filter_cart_item_qty'), 10, 3);
        add_filter('woocommerce_store_api_product_quantity_maximum', array(__CLASS__, 'filter_cart_item_qty'), 10, 3);
        add_filter('woocommerce_cart_item_is_purchasable', array(__CLASS__, 'filter_gift_cart_item_purchasable'), 10, 3);
        add_filter('woocommerce_is_purchasable', array(__CLASS__, 'filter_existing_gift_product_purchasable'), 10, 2);
        add_filter('woocommerce_variation_is_purchasable', array(__CLASS__, 'filter_existing_gift_product_purchasable'), 10, 2);
        self::$filters_registered = true;
    }

    public static function rest_handle_endpoint($args) {}

    public static function filter_cart_item_qty($value, $product, $cart_item)
    {
        if (!isset($cart_item['it_free_gift'])) {
            return $value;
        }

        return $cart_item['quantity'];
    }

    /**
     * Keep a valid gift cart line purchasable after its runtime price becomes
     * zero. Cart/Checkout Blocks validate existing lines through the Store API
     * and otherwise can reject a legitimate gift with
     * woocommerce_rest_cart_item_error.
     */
    public static function filter_gift_cart_item_purchasable($purchasable, $product, $cart_item)
    {
        if (empty($cart_item['it_free_gift']) || !($product instanceof \WC_Product)) {
            return $purchasable;
        }

        $gift_product_id = (int) ($cart_item['it_free_gift']['product_id'] ?? 0);
        $cart_product_id = (int) $product->get_id();
        if ($gift_product_id <= 0 || $gift_product_id !== $cart_product_id) {
            return $purchasable;
        }

        return $product->exists()
            && $product->get_status() === 'publish'
            && $product->is_in_stock();
    }

    /**
     * Store API calls WC_Product::is_purchasable() directly and does not use
     * woocommerce_cart_item_is_purchasable. Limit this override to a product
     * that is already present in the current cart as a verified gift line.
     */
    public static function filter_existing_gift_product_purchasable($purchasable, $product)
    {
        if ($purchasable || !($product instanceof \WC_Product) || !function_exists('WC') || !WC() || !(WC()->cart instanceof \WC_Cart)) {
            return $purchasable;
        }

        if (!$product->exists() || $product->get_status() !== 'publish' || !$product->is_in_stock()) {
            return false;
        }

        $product_id = (int) $product->get_id();
        foreach (WC()->cart->get_cart() as $cart_item) {
            if (
                !empty($cart_item['it_free_gift']['product_id'])
                && (int) $cart_item['it_free_gift']['product_id'] === $product_id
            ) {
                return true;
            }
        }

        return $purchasable;
    }
}
