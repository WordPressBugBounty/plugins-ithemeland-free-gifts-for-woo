<?php

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Validate field value
 * @var $products_ids
 * @var $gift_item_variable
 * @var $gift_rule_exclude
 * @var $product_qty_in_cart
 * @var $view
 * @var $settings
 */

$add_gift = get_option('itg_localization_add_gift', 'Add Gift');
$product_item = '';

$retrieved_group_input_value = WC()->session->get('gift_group_order_data');
$count_info                 = itg_check_quantity_gift_in_session();

foreach ($products_ids as $gift_item_key => $gift) {
    if (isset($gift['auto']) && $gift['auto'] == 'yes') {
        continue;
    }
    $text_stock_qty = 'in stock';
    $item_hover     = 'hovering';
    $disable        = false;
    $img_html       = $title_html = '';

    $product      = wc_get_product($gift['item']);
    $product_type = $product->get_type();
    if ($product_type == 'variable') {
        $variation_ids = version_compare(
            WC()->version,
            '2.7.0',
            '>='
        ) ? $product->get_visible_children() : $product->get_children(true);
        foreach ($variation_ids as $product_id) {
            $_product = wc_get_product($product_id);
            $gift_id  = $gift['uid'] . '-' . $product_id;
            //For exclude in select variations
            if (isset($gift_rule_exclude[$gift['uid']]) && in_array(
                $product_id,
                $gift_rule_exclude[$gift['uid']]
            )) {
                continue;
            }
            $item_hover = 'hovering';

            $array_return   = itg_quantities_gift_stock($_product, $product_qty_in_cart, $product_id, $product_type, $settings, $item_hover);
            $item_hover     = $array_return['item_hover'];
            $text_stock_qty = $array_return['text_stock_qty'];

            $flag_count = false;

            if (in_array($gift['method'], array('buy_x_get_x_repeat',), true) && $gift['base_q'] == 'ind') {
                if (array_key_exists($gift_item_key, $retrieved_group_input_value) && $retrieved_group_input_value[$gift_item_key]['q'] >= $gift_item_variable['all_gifts'][$gift_item_key]['q']) {
                    $flag_count = true;
                }
            } elseif (array_key_exists($gift['uid'], $count_info['count_rule_gift']) && $count_info['count_rule_gift'][$gift['uid']]['q'] >= $gift['pw_number_gift_allowed']) {
                $flag_count = true;
            }

            if (
                $flag_count ||
                (in_array($gift_id, $count_info['gifts_set']) && $gift['can_several_gift'] == 'no')
                ||
                (in_array($gift_id, $count_info['gifts_set']) && $gift_item_variable[$gift['uid']]['can_several_gift'] == 'no')
            ) {
                $item_hover = 'disable-hover';
            }
            $title = $_product->get_title();
            if ($_product->post_type == 'product_variation') {
                $title = $_product->get_name();
            }
            $product_type = $product->get_type();

            $product_item .= '<div class="wgb-popup-post-item ' . esc_attr($item_hover) . '">';
            $product_item .= '<div class="wgb-popup-post-thumbnail">
                        ' . itg_render_product_image($product, false) . '
                      </div>';
            $product_item .= '<a class="wgb-popup-post-title" href="' . get_permalink($product_id) . '">' . sprintf("%s", $title) . '</a>';

            $product_item .= '<div class="wgb-popup-post-add-button">
                            <div class="wgb-add-gift-btn btn-add-gift-button" data-id="' . esc_attr($gift_id) . '">
                                <span>' . $add_gift . '</span>
                                <div class="wgb-loading-icon wgb-d-none">
                                    <div class="wgb-spinner wgb-spinner--2"></div>
                                </div>
                            </div>
                          </div>';

            if ($settings['show_stock_quantity'] == 'true') {
                $product_item .= '<td>' . $text_stock_qty . '</td>';
            }
            $product_item .= '</div>';
        }
    } //End Variable
    else {
        $flag_count = false;

        $array_return   = itg_quantities_gift_stock($product, $product_qty_in_cart, $gift['item'], $product_type, $settings, $item_hover);
        $item_hover     = $array_return['item_hover'];
        $text_stock_qty = $array_return['text_stock_qty'];
        if (in_array($gift['method'], array(
            'buy_x_get_x_repeat',
        ), true) && $gift['base_q'] == 'ind') {
            if (array_key_exists($gift_item_key, $retrieved_group_input_value) && $retrieved_group_input_value[$gift_item_key]['q'] >= $gift_item_variable['all_gifts'][$gift_item_key]['q']) {
                $flag_count = true;
            }
        } elseif (array_key_exists($gift['uid'], $count_info['count_rule_gift']) && $count_info['count_rule_gift'][$gift['uid']]['q'] >= $gift['pw_number_gift_allowed']) {
            $flag_count = true;
        }

        if ($flag_count || (in_array($gift_item_key, $count_info['gifts_set']) && $gift['can_several_gift'] == 'no')) {
            $item_hover = 'disable-hover';
        }

        $title = $product->get_title();
        if ($product->post_type == 'product_variation') {
            $title = $product->get_name();
        }

        $product_item .= '<div class="wgb-popup-post-item ' . esc_attr($item_hover) . '">';
        $product_item .= '<div class="wgb-popup-post-thumbnail">' . itg_render_product_image($product, false);
        if ($settings['show_stock_quantity'] == 'true') {
            $product_item .= '<span class="wgb-product-item-stock-in-thumb">' . $text_stock_qty . '</span>';
        }
        $product_item .= '</div>';

        $product_item .= '<a class="wgb-popup-post-title" href="' . get_permalink($gift['item']) . '">' . sprintf("%s", $title) . '</a>';

        if ($item_hover == 'disable-hover') {
            $product_item .= '<div class="wgb-popup-post-add-button">' . esc_html__('Out of stock', 'ithemeland-free-gifts-for-woocommerce-lite') . '</div>';
        } else {
            $product_item .= '<div class="wgb-popup-post-add-button">
                            <div class="wgb-add-gift-btn btn-add-gift-button" data-id="' . esc_attr($gift['key']) . '">
                                <span>' . $add_gift . '</span>
                                <div class="wgb-loading-icon wgb-d-none">
                                    <div class="wgb-spinner wgb-spinner--2"></div>
                                </div>
                            </div>
                          </div>';
        }

        $product_item .= '</div>';
    }
}

if ($product_item == '') {
    return;
}

echo wp_kses_post($product_item);
