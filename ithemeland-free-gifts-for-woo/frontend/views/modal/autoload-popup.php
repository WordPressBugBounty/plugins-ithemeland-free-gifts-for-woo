<?php
//wp_enqueue_style( 'it-gift-datatables-style' );
//wp_enqueue_style( 'it-gift-popup-style' );
//wp_enqueue_script( 'it-gift-datatables-js' );
//wp_enqueue_script( "pw-gift-custom-js" );

wp_enqueue_style('it-gift-datatables-style');
wp_enqueue_script('it-gift-datatables-js');

wp_enqueue_style('it-gift-style');
wp_enqueue_script('pw-gift-add-jquery-adv');

$product_item = '';

$retrieved_group_input_value = WC()->session->get('gift_group_order_data');
$count_info                 = itg_check_quantity_gift_in_session();

$add_gift = esc_html(get_option('itg_localization_add_gift', 'Add Gift'));
$select_gift = esc_html(get_option('itg_localization_select_gift', 'Select Gift'));

$flag_return = true;
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
            $stock_status = $array_return['stock_status'];
            $flag_count     = false;

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
                $item_hover = 'disable-hover';
            }
            //For PopUp ( if gifts isn't )
            if ($item_hover != 'disable-hover') {
                $flag_return = false;
            }
            $title = $_product->get_title();
            if ($_product->post_type == 'product_variation') {
                $title = $_product->get_name();
            }
            $product_type = $product->get_type();

            $product_item .= '<tr>';
            $product_item .= '<td>
                        ' . itg_render_product_image($_product, false) . '
                      </td>';
            $product_item .= '<td><a href="' . get_permalink($product_id) . '">' . sprintf("%s", $title) . '</a></td>';

            $product_item .= '<td>
                            <div class="wgb-add-gift-btn btn-add-gift-button" data-id="' . esc_attr($gift_id) . '">
                                <span>' . $add_gift . '</span>
                                <div class="wgb-loading-icon wgb-d-none">
                                    <div class="wgb-spinner wgb-spinner--2"></div>
                                </div>
                            </div>
                          </td>';

            if ($this->settings['show_stock_quantity'] == 'true') {
                $product_item .= '<td>' . $text_stock_qty . '</td>';
            }
            $product_item .= '</tr>';
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
            $item_hover = 'disable-hover';
        }
        $title = $product->get_title();
        if ($product->post_type == 'product_variation') {
            $title = $product->get_name();
        }
        if ($item_hover != 'disable-hover') {
            $flag_return = false;
        }

        $product_item .= '<tr class="' . esc_attr($item_hover) . '">';
        $product_item .= '<td class="wgb-product-item-td-thumb">' . itg_render_product_image($product, false);
        if ($this->settings['show_stock_quantity'] == 'true') {
            $product_item .= '<span class="wgb-product-item-stock-in-thumb">' . $text_stock_qty . '</span>';
        }
        $product_item .= '</td>';
        $product_item .= '<td><a href="' . get_permalink($gift['item']) . '">' . sprintf("%s", $title) . '</a></td>';

        if ($item_hover == 'disable-hover') {
            $product_item .= '<td>' . esc_html__('Out of stock', 'ithemeland-free-gifts-for-woocommerce-lite') . '</td>';
        } else {
            $product_item .= '<td>
                            <div class="wgb-add-gift-btn btn-add-gift-button" data-id="' . esc_attr($gift['key']) . '">
                                <span>' . $add_gift . '</span>
                                <div class="wgb-loading-icon wgb-d-none">
                                    <div class="wgb-spinner wgb-spinner--2"></div>
                                </div>
                            </div>
                          </td>';
            $product_item .= '</tr>';
        }
    }
}

if ($product_item == '' || $flag_return) {
    return;
}

?>

<div id="wgb-modal" class="wgb-popup wgb-active-modal">
    <div class="wgb-page wgb-popup-box wgb-page-current wgb-page-scaleUp">
        <div class="wgb-popup-body">
            <div class="wgb-popup-close wgb-popup-close">
                <div class="wgb-leftright"></div>
                <div class="wgb-rightleft"></div>
            </div>
            <div class="wgb-modal-cnt scrollbar-macosx wgb-popup-posts wgb-row">
                <table class="it-gift-products-table display" style="width:100%">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Thumb', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                            <th><?php esc_html_e('Name', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                            <th><?php esc_html_e('Add To Cart', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo wp_kses_post($product_item); ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th><?php esc_html_e('Thumb', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                            <th><?php esc_html_e('Name', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                            <th><?php esc_html_e('Add To Cart', 'ithemeland-free-gifts-for-woocommerce-lite'); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>