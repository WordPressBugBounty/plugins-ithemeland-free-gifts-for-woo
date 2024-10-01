<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly 

use wgbl\classes\helpers\Sanitizer;
use wgbl\classes\services\render\Condition_Render;

$html = '';
if (!empty($condition_item) && !empty($condition_item['type']) && isset($condition_id) && isset($rule_id)) {
    $condition_render_service = Condition_Render::get_instance();
    $condition_render_service->set_data([
        'condition_item' => $condition_item,
        'condition_id' => $condition_id,
        'rule_id' => $rule_id,
        'option_values' => (!empty($option_values) && is_array($option_values)) ? $option_values : [],
        'field_status' => ''
    ]);
    $html = $condition_render_service->extra_fields_render();
}

echo wp_kses($html, Sanitizer::allowed_html_tags());
