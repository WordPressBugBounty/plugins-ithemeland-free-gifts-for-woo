<?php

namespace wgb\frontend\classes\services\apply_rule;

use wgb\frontend\classes\services\apply_rule\helpers\RulesDataBridge;
use wgb\frontend\classes\services\apply_rule\condition_types\Simple;
use wgb\frontend\classes\services\apply_rule\condition_types\Subtotal;

if (!defined('ABSPATH')) {
    exit;
}

class ApplyRuleService
{
    public static function applyRuleMethod(RulesDataBridge $rules_bridge)
    {
        $method = $rules_bridge->rule_value['method'] ?? '';

        if (empty($method)) {
            throw new \InvalidArgumentException("Rule method is required");
        }

        if (method_exists(self::class, $method)) {
            self::$method($rules_bridge);
        }
        // else {
        //     // Handle unknown method (optional)
        //     throw new \InvalidArgumentException("Unknown rule method: $method");
        // }
    }

    private static function simple(RulesDataBridge $rules_bridge)
    {
        Simple::applyRule(
            $rules_bridge->rule_value,
            $rules_bridge->pw_gifts_cache_simple_childes,
            $rules_bridge->pw_gifts_cache_simple_variation,
            $rules_bridge->gift_item_variable,
            $rules_bridge->show_gift_item_for_cart
        );
    }

    private static function subtotal(RulesDataBridge $rules_bridge)
    {
        Subtotal::applyRule(
            $rules_bridge->cart_subtotal,
            $rules_bridge->rule_value,
            $rules_bridge->pw_gifts_cache_simple_childes,
            $rules_bridge->pw_gifts_cache_simple_variation,
            $rules_bridge->gift_item_variable,
            $rules_bridge->show_gift_item_for_cart
        );
    }
}
