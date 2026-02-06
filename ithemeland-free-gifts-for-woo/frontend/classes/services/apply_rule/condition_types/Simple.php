<?php

namespace wgb\frontend\classes\services\apply_rule\condition_types;

use wgb\frontend\classes\services\apply_rule\helpers\RuleHandler;

class Simple
{
    /**
     * Apply the "Simple" rule to the cart.
     *
     * @param array $rule_values The rule values.
     * @param array $pw_gifts_cache_simple_childes The list of gift products.
     * @param array $pw_gifts_cache_simple_variation The list of gift variations.
     * @param array &$gift_item_variable The array to store gift items.
     * @param array &$show_gift_item_for_cart The array to store gift items for display.
     * @return void
     */
    public static function applyRule(
        array $rule_values,
        array $pw_gifts_cache_simple_childes,
        array $pw_gifts_cache_simple_variation,
        array &$gift_item_variable,
        array &$show_gift_item_for_cart
    ): void {

        // Add gift items to the cart
        RuleHandler::add_gift_items_to_cart(
            $rule_values['quantity']['get'],
            $rule_values,
            $pw_gifts_cache_simple_childes,
            $gift_item_variable
        );

        $rule_values['quantity']['auto_add_gift_to_cart'] = 'no';

        RuleHandler::display_gifts(
            $rule_values,
            $rule_values['quantity']['get'],
            $pw_gifts_cache_simple_variation,
            $show_gift_item_for_cart
        );
        
        // Add all gifts to the global list
        RuleHandler::add_all_gift_to_global(
            $pw_gifts_cache_simple_childes,
            $rule_values,
            $gift_item_variable
        );

        // Add rule details for display
        RuleHandler::add_rule_details(
            $show_gift_item_for_cart,
            $rule_values
        );
    }
}
