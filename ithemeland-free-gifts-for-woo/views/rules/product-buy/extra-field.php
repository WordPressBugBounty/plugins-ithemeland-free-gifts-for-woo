<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 

use wgbl\classes\helpers\Sanitizer;
use wgbl\classes\services\render\Product_Buy_Render;

$html = '';

if (!empty($product_buy_item) && !empty($product_buy_item['type']) && isset($product_buy_id) && isset($rule_id)) {
    $product_buy_render_service = Product_Buy_Render::get_instance();
    $product_buy_render_service->set_data([
        'product_buy_item' => $product_buy_item,
        'product_buy_id' => $product_buy_id,
        'rule_id' => $rule_id,
        'option_values' => (!empty($option_values) && is_array($option_values)) ? $option_values : [],
        'field_status' => ((!empty($rule_item)) && in_array($rule_item['method'], ['simple', 'subtotal', 'subtotal_repeat'])) ? 'disabled' : ''
    ]);
    $html = $product_buy_render_service->extra_fields_render();
}

echo wp_kses($html, Sanitizer::allowed_html_tags());
