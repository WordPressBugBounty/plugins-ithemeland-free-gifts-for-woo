<?php

namespace wgb\frontend\classes\services\apply_rule\condition_types;

use wgb\frontend\classes\services\apply_rule\helpers\RuleHandler;

class Subtotal
{
    /**
     * Check if the cart meets the conditions for the "Subtotal" rule.
     *
     * @param float $cart_subtotal The cart subtotal.
     * @param array $rule_values The rule values.
     * @return bool Returns true if the condition is met, otherwise false.
     */
    private static function checkCondition(float $cart_subtotal, array $rule_values): bool
    {
        if ($cart_subtotal <= 0) {
            return false;
        }

        $operator = isset($rule_values['quantity']['comparison_operator']) ? $rule_values['quantity']['comparison_operator'] : 'greater_than';
        $subtotal_amount = $rule_values['quantity']['subtotal_amount'];

        if (empty($operator)) {
            return $cart_subtotal > $subtotal_amount;
        }

        switch ($operator) {
            case 'greater_than':
                return $cart_subtotal > $subtotal_amount;
            case 'greater_than_or_equal':
                return $cart_subtotal >= $subtotal_amount;
            case 'less_than':
                return $cart_subtotal < $subtotal_amount;
            case 'less_than_or_equal':
                return $cart_subtotal <= $subtotal_amount;
            default:
                return $cart_subtotal > $subtotal_amount; // Default to greater than
        }
    }

    /**
     * Apply the "Subtotal" rule to the cart if the condition is met.
     *
     * @param float $cart_subtotal The cart subtotal.
     * @param array $rule_values The rule values.
     * @param array $pw_gifts_cache_simple_childes The list of gift products.
     * @param array $pw_gifts_cache_simple_variation The list of gift variations.
     * @param array &$gift_item_variable The array to store gift items.
     * @param array &$show_gift_item_for_cart The array to store gift items for display.
     * @return void
     */
    public static function applyRule(
        float $cart_subtotal,
        array $rule_values,
        array $pw_gifts_cache_simple_childes,
        array $pw_gifts_cache_simple_variation,
        array &$gift_item_variable,
        array &$show_gift_item_for_cart
    ): void {

        // apply filters to the cart subtotal based on the rule values
        $cart_subtotal = apply_filters('wgb_rule_appending_cart_subtotal', $cart_subtotal, $rule_values);
        // Check if the condition is met
        if (self::checkCondition($cart_subtotal, $rule_values)) {
            // Calculate the quantity of gifts
            $qty = $rule_values['quantity']['get'];

            // Add gift items to the cart
            RuleHandler::add_gift_items_to_cart(
                $qty, // Quantity of gifts to add
                $rule_values,
                $pw_gifts_cache_simple_childes,
                $gift_item_variable
            );

            $rule_values['quantity']['auto_add_gift_to_cart'] = 'no';

            RuleHandler::display_gifts(
                $rule_values,
                $qty, // Quantity of gifts to display
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
}
