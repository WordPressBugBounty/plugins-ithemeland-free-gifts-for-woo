<?php

namespace wgb\classes\repositories;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wgb\classes\helpers\Sanitizer;

class OfferRule
{
    private static $instance;

    private $option_name;
    private $offer_bar_rules;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->option_name = 'wgb_offer_rules';
    }

    public function update($rules)
    {
        if (!empty($rules['option_values'])) {
            $this->update_option_values(Sanitizer::array($rules['option_values']));
        }

        return update_option($this->option_name, $rules);
    }

    private function update_option_values($values)
    {
        return update_option('wgb_offer_rules_option_values', $values);
    }

    public function get_option_values()
    {
        return get_option('wgb_offer_rules_option_values', []);
    }

    public function get_rules()
    {
        return get_option($this->option_name, []);
    }

    public function get_rule_types()
    {
        return  [
            'offer_bar' => esc_html__('Offer in single', 'ithemeland-free-gifts-for-woo'),
        ];
    }

    public function get_offer_bar_rules()
    {
        if (!empty($this->offer_bar_rules)) {
            return $this->offer_bar_rules;
        }

        $this->offer_bar_rules = [];
        $rules_data = get_option($this->option_name, array());

        if (!empty($rules_data)  && !empty($rules_data['items'])) {
            $this->offer_bar_rules = array_filter($rules_data['items'], function ($rule) {
                return isset($rule['type']) && $rule['type'] === 'offer_bar';
            });
        }

        $this->offer_bar_rules = apply_filters('wgb_active_offer_bar_rules', $this->offer_bar_rules);
        return $this->offer_bar_rules;
    }
}
