<?php

namespace wgb\frontend\classes\services\apply_rule;

class CheckConditionsBuyProduct
{
    /**
     * Processes a single condition and returns the flag.
     *
     * @param array $condition The condition array.
     * @param array $cart_item The cart item array.
     * @return bool The flag indicating whether the condition is met.
     */
    public static function process_condition($condition, $cart_item)
    {
        $type = $condition['type'];
        if (method_exists(self::class, $type)) {
            return self::$type($condition, $cart_item);
        }

        // Default case: return true if the condition type is not recognized
        return true;
    }

    /**
     * Handles the 'product' condition type.
     */
    private static function product($condition, $cart_item)
    {
        $vales = $cart_item['product_id'];
        $condition_value = $condition['products'];
        return check_simple_operations($condition['method_option'], $vales, $condition_value);
    }

    /**
     * Handles the 'product_variation' condition type.
     */
    private static function product_variation($condition, $cart_item)
    {
		return true;
    }

    /**
     * Handles the 'product_category' condition type.
     */
    private static function product_category($condition, $cart_item)
    {
		return true;
    }

    /**
     * Handles the 'product_attribute' condition type.
     */
    private static function product_attribute($condition, $cart_item)
    {
		return true;
    }

    /**
     * Handles the 'product_tag' condition type.
     */
    private static function product_tag($condition, $cart_item)
    {
		return true;
    }

    /**
     * Handles the 'product_regular_price' condition type.
     */
    private static function product_regular_price($condition, $cart_item)
    {
		return true;
    }


    /**
     * Handles the 'product_stock_quantity' condition type.
     */
    private static function product_stock_quantity($condition, $cart_item)
    {
        return true;
    }

    /**
     * Handles the 'product_brand' condition type.
     */
    private static function product_brand($condition, $cart_item)
    {
		return true;
    }
}