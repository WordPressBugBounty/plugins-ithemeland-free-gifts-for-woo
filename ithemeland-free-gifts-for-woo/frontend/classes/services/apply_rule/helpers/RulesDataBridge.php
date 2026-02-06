<?php

namespace wgb\frontend\classes\services\apply_rule\helpers;

if (!defined('ABSPATH')) {
    exit;
}

class RulesDataBridge
{
    public $pw_gifts_cache_simple_childes;
    public $pw_gifts_cache_simple_variation;
    public $gift_item_variable;
    public $show_gift_item_for_cart;
    public $item_cart;
    public $free_shipping_exists;

    // New properties for additional data
    public $rule_value;
    public $quantity_groups;
    public $price_groups;
    public $cheapest_item_id;
    public $cart_subtotal;

    public function __construct(
        $pw_gifts_cache_simple_childes,
        $pw_gifts_cache_simple_variation,
        &$gift_item_variable,
        &$show_gift_item_for_cart,
        $item_cart,
        &$free_shipping_exists,
        $rule_value,
        $quantity_groups,
        $price_groups,
        $cheapest_item_id,
        $cart_subtotal
    ) {
        $this->pw_gifts_cache_simple_childes = $pw_gifts_cache_simple_childes;
        $this->pw_gifts_cache_simple_variation = $pw_gifts_cache_simple_variation;
        $this->gift_item_variable = &$gift_item_variable;
        $this->show_gift_item_for_cart = &$show_gift_item_for_cart;
        $this->item_cart = $item_cart;
        $this->free_shipping_exists = &$free_shipping_exists;

        // Assign new properties
        $this->rule_value = $rule_value;
        $this->quantity_groups = $quantity_groups;
        $this->price_groups = $price_groups;
        $this->cheapest_item_id = $cheapest_item_id;
        $this->cart_subtotal = $cart_subtotal;
    }
}
