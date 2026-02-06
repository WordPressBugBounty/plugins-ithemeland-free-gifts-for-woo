<?php

namespace wgb\frontend\classes\services\apply_rule\helpers;

use wgb\frontend\classes\services\views\generator\Pagination;

class RuleHandler
{
    public static function paginate_gift_array(array $gift_items, array $rule_values, int $page = 1, int $per_page = 10): array
    {
        $pagination = new Pagination();
        $result = $pagination->paginate($gift_items, $page, $per_page);

        return [
            'items'        => $result['items'],
            'total'        => $result['total'],
            'total_pages'  => $result['total_pages'],
            'current_page' => $result['current_page'],
            'per_page'     => $result['per_page'],
            'rule_details' => [
                'uid'         => $rule_values['uid'],
                'rule_name'   => $rule_values['rule_name'],
                'description' => $rule_values['description'],
            ]
        ];
    }
    public static function add_gift_items_to_cart($qty, $rule_values, $pw_gifts_cache_simple_childes, array &$gift_item_variable): void
    {
        $base_on = self::calculate_base_on($rule_values);
        // Add gift items to cart
        $gift_item_variable[$rule_values['uid']] = [
            'uid'                    => $rule_values['uid'],
            'method'                 => $rule_values['method'],
            'pw_number_gift_allowed' => $qty,
            'can_several_gift'       => $rule_values['quantity']['same_gift'] ?? false,
            'auto_add'               => $rule_values['quantity']['auto_add_gift_to_cart'] ?? false,
            'based_on'               => $base_on,
            'gifts'                  => $pw_gifts_cache_simple_childes,
        ];
    }

    public static function display_gifts($rule_values, $qty, $pw_gifts_cache_simple_variation, array &$show_gift_item_for_cart): void
    {
        $base_q = self::calculate_base_on($rule_values);
        // Display gifts if not auto-added
        foreach ($pw_gifts_cache_simple_variation as $gift) {
            $id = $rule_values['uid'] . '-' . $gift;
            $show_gift_item_for_cart['gifts'][$id] = [
                "item"                   => $gift,
                "uid"                    => $rule_values['uid'],
                "key"                    => $id,
                "pw_number_gift_allowed" => $qty,
                "can_several_gift"       => $rule_values['quantity']['same_gift'] ?? false,
                'method'                 => $rule_values['method'],
                'base_q'                 => $base_q,
                'auto'                   => $rule_values['quantity']['auto_add_gift_to_cart'] ?? false,
            ];
        }
    }

    public static function add_all_gift_to_global($pw_gifts_cache_simple_childes, $rule_values, array &$gift_item_variable): void
    {

        $base_q = self::calculate_base_on($rule_values);
        $qty_repeat = '';

        // Add all gifts to the global list
        foreach ($pw_gifts_cache_simple_childes as $gift) {
            $id = $rule_values['uid'] . '-' . $gift;
            $gift_item_variable['all_gifts'][$id] = [
                'uid'        => $rule_values['uid'],
                'id_product' => $gift,
                'base_q'     => $base_q,
                'q'          => $qty_repeat
            ];
        }
    }

    public static function add_rule_details(array &$show_gift_item_for_cart, array $rule_values): void
    {
        $show_gift_item_for_cart['rule_details'][$rule_values['uid']] = [
            'uid'         => $rule_values['uid'],
            'rule_name'   => $rule_values['rule_name'],
            'description' => $rule_values['description'],
        ];
    }

    public static function calculate_base_on(array $rule_values): string
    {
        if (isset($rule_values['quantities_based_on']) && !empty($rule_values['quantities_based_on'])) {
            return in_array($rule_values['quantities_based_on'], [
                'each_individual_product',
                'each_individual_variation',
                'each_individual_cart_line_item'
            ]) ? 'ind' : 'all';
        }

        // Return an empty string if 'quantities_based_on' is not set or empty
        return '';
    }
}
