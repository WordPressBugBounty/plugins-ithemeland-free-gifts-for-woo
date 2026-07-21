<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly 

use ITFreeGift\classes\helpers\Sanitizer;
use ITFreeGift\classes\services\render\Condition_Render;

$itfreegift_html = '';
if (!empty($itfreegift_condition_item) && !empty($itfreegift_condition_item['type']) && isset($itfreegift_condition_id) && isset($itfreegift_rule_id)) {
    $itfreegift_condition_render_service = Condition_Render::get_instance();
    $itfreegift_condition_render_service->set_data([
        'condition_item' => $itfreegift_condition_item,
        'condition_id' => $itfreegift_condition_id,
        'rule_id' => $itfreegift_rule_id,
        'option_values' => (!empty($option_values) && is_array($option_values)) ? $option_values : [],
        'field_status' => ''
    ]);
    $itfreegift_html = $itfreegift_condition_render_service->extra_fields_render();
}

echo wp_kses($itfreegift_html, Sanitizer::allowed_html());
