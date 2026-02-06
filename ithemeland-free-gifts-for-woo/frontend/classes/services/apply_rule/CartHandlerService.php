<?php

namespace wgb\frontend\classes\services\apply_rule;

class CartHandlerService
{
    public static function get_cart_contents()
    {
        if (! is_object(WC()->cart)) {
            return '';
        }
        $filter_items = [];
        foreach (WC()->cart->get_cart() as $key => $value) {

            if (isset($value['it_free_gift'])) {
                continue;
            }
            $filter_items[$key] = $value;
        }
        return $filter_items;
    }

    public static function cart_contents_quantity($condition)
    {
        return true;
    }


    public static function cart_total($condition)
    {
        $condition_value = $condition['value'];
        $get_total    = WC()->cart->total;
        return check_basic_operations($condition['method_option'], $get_total, $condition_value);
    }

    public static function cart_subtotal($condition)
    {
        if ($condition['type'] == 'cart_subtotal') {

            $condition_value = $condition['value'];
            $subtotal_value    = it_get_cart_subtotal(self::get_cart_contents());

            return check_basic_operations($condition['method_option'], $subtotal_value['subtotal'], $condition_value);
        }

        return true;
    }

    public static function cart_items($condition, $rule_values)
    {
         return true;
    }
}
