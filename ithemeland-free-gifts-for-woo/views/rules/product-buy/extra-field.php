<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 

use ITFreeGift\classes\helpers\Sanitizer;
use ITFreeGift\classes\services\render\Product_Buy_Render;

$itfreegift_html = '';

if (!empty($itfreegift_product_buy_item) && !empty($itfreegift_product_buy_item['type']) && isset($product_buy_id) && isset($itfreegift_rule_id)) {
    $itfreegift_product_buy_render_service = Product_Buy_Render::get_instance();
    $itfreegift_product_buy_render_service->set_data([
        'product_buy_item' => $itfreegift_product_buy_item,
        'product_buy_id' => $product_buy_id,
        'rule_id' => $itfreegift_rule_id,
        'option_values' => (!empty($option_values) && is_array($option_values)) ? $option_values : [],
        'field_status' => ((!empty($itfreegift_rule_item)) && in_array($itfreegift_rule_item['method'], ['simple', 'subtotal', 'subtotal_repeat', 'get_group_of_products'])) ? 'disabled' : ''
    ]);
    $itfreegift_html = $itfreegift_product_buy_render_service->extra_fields_render();
}

echo wp_kses($itfreegift_html, Sanitizer::allowed_html());
