<?php

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

wp_enqueue_style('it-gift-dropdown-css');
wp_enqueue_script('it-gift-dropdown-js');

wp_enqueue_script('pw-gift-add-jquery-adv');

$product_item = '';

$retrieved_group_input_value = WC()->session->get('gift_group_order_data');
$count_info                 = itg_check_quantity_gift_in_session();

foreach ($this->show_gift_item_for_cart['gifts'] as $gift_item_key => $gift) {
    if (isset($gift['auto']) && $gift['auto'] == 'yes') {
        continue;
    }
    $text_stock_qty = 'in stock';
    $item_hover     = 'hovering';
    $disable        = false;
    $img_html       = $title_html = '';

    $product      = wc_get_product($gift['item']);
    $product_type = $product->get_type();
	if (!$product->is_in_stock()) {    
        continue;
    }	
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
            if (isset($this->gift_rule_exclude[$gift['uid']]) && in_array(
                $product_id,
                $this->gift_rule_exclude[$gift['uid']]
            )) {
                continue;
            }
            $item_hover = 'hovering';

            $array_return   = itg_quantities_gift_stock($_product, $this->product_qty_in_cart, $product_id, $product_type, $this->settings, $item_hover);
            $item_hover     = $array_return['item_hover'];
            $text_stock_qty = $array_return['text_stock_qty'];

            $flag_count = false;

            if (in_array($gift['method'], array('buy_x_get_x_repeat',), true) && $gift['base_q'] == 'ind') {
                if (array_key_exists($gift_item_key, $retrieved_group_input_value) && $retrieved_group_input_value[$gift_item_key]['q'] >= $this->gift_item_variable['all_gifts'][$gift_item_key]['q']) {
                    $flag_count = true;
                }
            } elseif (array_key_exists($gift['uid'], $count_info['count_rule_gift']) && $count_info['count_rule_gift'][$gift['uid']]['q'] >= $gift['pw_number_gift_allowed']) {
                $flag_count = true;
            }

            if (
                $flag_count ||
                (in_array($gift_id, $count_info['gifts_set']) && $gift['can_several_gift'] == 'no')
                ||
                (in_array($gift_id, $count_info['gifts_set']) && $this->gift_item_variable[$gift['uid']]['can_several_gift'] == 'no')
            ) {
                continue;
                $item_hover = 'disable-hover';
            }
            $title = $_product->get_title();
            if ($_product->post_type == 'product_variation') {
                $title = $_product->get_name();
            }
            $product_type = $product->get_type();
            $img_html   = itg_render_product_image($product, false);
            $item = '';
            $show_stock_quantity = '';
            if ($this->settings['show_stock_quantity'] == 'true') {
                $show_stock_quantity .= '<div class="wgb-item-overlay"></div>
                    ' . sprintf("%s", $text_stock_qty);
            }
            $img_url = (!empty($product->get_image_id())) ? wp_get_attachment_image_src($product->get_image_id(), [50, 50]) : wc_placeholder_img_src([50, 50]);
            $img_url = (is_array($img_url) && !empty($img_url[0])) ? $img_url[0] : $img_url;
            $product_item .= '
                        <option value="' . esc_attr($gift_id) . '" data-imagesrc="' . esc_url($img_url) . '"
                                data-description="' . sprintf("%s", $title) . '">' . sprintf("%s", $title) . '
                        </option>			
			';
        }
    } //End Variable
    else {
        $flag_count = false;

        $array_return   = itg_quantities_gift_stock($product, $this->product_qty_in_cart, $gift['item'], $product_type, $this->settings, $item_hover);
        $item_hover     = $array_return['item_hover'];
        $text_stock_qty = $array_return['text_stock_qty'];

        if (in_array($gift['method'], array(
            'buy_x_get_x_repeat',
        ), true) && $gift['base_q'] == 'ind') {
            if (
                array_key_exists($gift_item_key, $retrieved_group_input_value) && $retrieved_group_input_value[$gift_item_key]['q'] >= $this->gift_item_variable['all_gifts'][$gift_item_key]['q']
            ) {
                $flag_count = true;
            }
        } elseif (array_key_exists($gift['uid'], $count_info['count_rule_gift']) && $count_info['count_rule_gift'][$gift['uid']]['q'] >= $gift['pw_number_gift_allowed']) {
            $flag_count = true;
        }

        if (
            $flag_count ||
            (in_array($gift_item_key, $count_info['gifts_set']) && $gift['can_several_gift'] == 'no')
        ) {
            continue;
            $item_hover = 'disable-hover';
        }
        $title = $product->get_title();
        if ($product->post_type == 'product_variation') {
            $title = $product->get_name();
        }

        //		$product_type = $product->get_type();

        $img_html   = itg_render_product_image($product, false);
        $show_stock_quantity = '';
        if ($this->settings['show_stock_quantity'] == 'true') {
            $show_stock_quantity .= '<div class="wgb-item-overlay"></div>
                    ' . sprintf("%s", $text_stock_qty);
        }

        $img_url = (!empty($product->get_image_id())) ? wp_get_attachment_image_src($product->get_image_id(), [50, 50]) : wc_placeholder_img_src([50, 50]);
        $img_url = (is_array($img_url) && !empty($img_url[0])) ? $img_url[0] : $img_url;
        $product_item .= '
                        <option value="' . esc_attr($gift['key']) . '" data-imagesrc="' . esc_url($img_url) . '"
                                data-description="' . sprintf("%s", $title) . '">' . sprintf("%s", $title) . '
                        </option>			
			';
    }
}

if ($product_item == '') {
    return;
}

?>

<div class="demo-live">
    <div id="wgb-gift-products-dropdown">
        <?php echo wp_kses_post($product_item); ?>
    </div>
</div>