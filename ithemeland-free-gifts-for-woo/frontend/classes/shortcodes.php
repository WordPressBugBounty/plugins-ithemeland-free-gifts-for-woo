<?php

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

use wgb\classes\helpers\Sanitizer;
use wgb\frontend\classes\services\apply_rule\CheckRuleCondition;

class iThemeland_front_shortcodes
{
    private $gift_item_key;
    private $settings;
    private $check_rule_condition;

    public function __construct()
    {
        $this->gift_item_key = array();
        $this->settings = itg_get_settings();

        $this->check_rule_condition = new CheckRuleCondition($this->getCheckRuleConditionData());

        add_action('init', [$this, 'init']);
        add_action('wp_ajax_it_gift_shortcode_show_popup', [$this, 'shortcode_show_popup']);
        add_action('wp_ajax_nopriv_it_gift_shortcode_show_popup', [$this, 'shortcode_show_popup']);
    }

    public function init()
    {
        $shortcodes = apply_filters(
            'itg_load_shortcodes',
            [
                'itg_gift_products',
                'itg_gift_notice',
            ]
        );

        foreach ($shortcodes as $shortcode_name) {
            add_shortcode($shortcode_name, [$this,  'process_shortcode']);
        }
    }

    private function getCheckRuleConditionData(): array
    {
        return [
            'cart_contents'           => itg_get_cart_contents(),
            'gift_rule_exclude'       => [],
            'product_qty_in_cart'     => 0,
            'show_gift_item_for_cart' => [],
            'gift_item_variable'      => [],
        ];
    }

    public function process_shortcode($atts, $content, $tag)
    {
        $shortcode_name = str_replace('itg_', '', $tag);
        $function = 'shortcode_' . $shortcode_name;

        switch ($shortcode_name) {
            case 'gift_products':
            case 'gift_notice':
                ob_start();
                self::$function($atts, $content); // output for shortcode.
                $content = ob_get_contents();
                ob_end_clean();
                break;

            default:
                ob_start();
                /**
                 * This hook is used to display the short code content.
                 * 
                 * @since 1.0
                 */
                do_action("itg_shortcode_{$shortcode_name}_content");
                $content = ob_get_contents();
                ob_end_clean();
                break;
        }

        return $content;
    }

    /**
     * Shortcode for Notice.
     * */
    public function shortcode_gift_notice($atts, $content)
    {
        $atts_shortcode = shortcode_atts(
            [
                'type'  => '',
                'value' => '',
            ],
            $atts,
            'itg_gift_notice'
        );
        if ($atts['type'] == 'cart_price') {
            $cart_items = itg_get_cart_contents();
            $cart_subtotal = it_get_cart_subtotal($cart_items);
            $cart_subtotal = $cart_subtotal['subtotal_with_tax'];
            if ($cart_subtotal < $atts['value']) {
                echo esc_html(wc_price($atts['value'] - $cart_subtotal));
            }
        }


        if ($atts['type'] == 'cart_count') {
            $cart_items = itg_get_cart_contents();
            $sum_value       = itg_get_wc_cart_sum_of_item_quantities($cart_items);
            if ($sum_value < $atts['value']) {
                echo esc_html($atts['value'] - $sum_value);
            }
        }
    }

    /**
     * Shortcode for the gift products.
     * */
    public function shortcode_gift_products($atts, $content)
    {
        $atts_shortcode = shortcode_atts(
            [
                'type' => 'dropdown',
                'number_per_page' => 6,
                'desktop_columns' => 'wgb-col-md-2',
                'tablet_columns' => 'wgb-col-sm-2',
                'mobile_columns' => 'wgb-col-2',
                'speed' => 5000,
                'desktop' => 5,
                'tablet' => 3,
                'mobile' => 1,
                'loop' => true,
                'rtl' => false,
                'nav' => false,
                'dots' => false,
            ],
            $atts,
            'itg_products_gift'
        );

        $flag = true;
        $gift_items = $this->check_rule_condition->pw_get_gift_for_cart_checkout();

        if (empty($gift_items)) {
            itg_get_template('shortcode-layout.php', ['data_args' => []]);
            $flag = false;
        } else {
            $this->gift_item_key = $gift_items;
        }

        $show_gift_items = $this->check_rule_condition->getShowGiftItemForCart();

        // Structure the gifts data properly
        $gifts_data = [
            'gifts' => [],
            'rule_details' => $show_gift_items['rule_details'] ?? []
        ];

        // Add gifts from gift_items
        if (!empty($gift_items['all_gifts'])) {
            foreach ($gift_items['all_gifts'] as $gift_key => $gift) {
                $rule_id = $gift['uid'];
                $rule_data = $gift_items[$rule_id] ?? [];

                $gifts_data['gifts'][$gift_key] = [
                    'uid' => $gift['uid'],
                    'item' => $gift['id_product'],
                    'q' => $gift['q'] ?? '',
                    'method' => $rule_data['method'] ?? 'subtotal_repeat',
                    'base_q' => $rule_data['based_on'] ?? 'ind',
                    'can_several_gift' => $rule_data['can_several_gift'] ?? 'yes',
                    'key' => $gift_key
                ];
            }
        }

        echo wp_kses('<span class="itg-shortoces-products" data-type="' . $atts['type'] . '"  >', Sanitizer::allowed_html());

        if ($flag) {
            $args_data = [
                'gift_rule_exclude' => $this->check_rule_condition->getGiftRuleExclude(),
                'quantity_products_in_cart' => $this->check_rule_condition->getProductQtyInCart(),
                'gifts_items_cart' => $gifts_data,
                'all_gift_items' => $this->check_rule_condition->getGiftItemVariable(),
                'settings' => $this->settings,
                'multi_level' => false,
                'is_child'  => true,
            ];

            $rule_products = [
                'data_args' => itg_get_gift_products_data_multilevel($args_data),
                'template' => $atts['type']
            ];

            itg_get_template('shortcode-layout.php', $rule_products);
        }
        echo '</span>';
        return;
    }

    public function shortcode_show_popup()
    {
        $type = sanitize_text_field(wp_unslash($_POST['type'])); //phpcs:ignore
        echo do_shortcode('[itg_gift_products type="' . $type . '"]');
        wp_die();
    }
}

new iThemeland_front_shortcodes();
