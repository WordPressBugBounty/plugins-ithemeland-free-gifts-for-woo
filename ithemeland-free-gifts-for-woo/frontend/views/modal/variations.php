<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Validate field value
 * @var $products_ids
 * @var $uid
 * @var $gift_item_variable
 * @var $gift_rule_exclude
 * @var $product_qty_in_cart
 * @var $view
 * @var $settings
 */

$itfreegift_retrieved_group_input_value = WC()->cart->get_cart();

$itfreegift_count_info = itfreegift_check_quantity_gift_in_session($itfreegift_retrieved_group_input_value);

$itfreegift_no_thanks = esc_html(itfreegift_get_localization('no_thanks', 'No Thanks'));

foreach ($products_ids as $itfreegift_product_id) :
    //For exclude in select variations
    if (isset($gift_rule_exclude[$uid]) && in_array(
        $itfreegift_product_id,
        $gift_rule_exclude[$uid]
    )) {
        continue;
    }
    $itfreegift_gift_id  = $uid . '-' . $itfreegift_product_id;
    $itfreegift__product = wc_get_product($itfreegift_product_id);

    if (!$itfreegift__product instanceof \WC_Product) {
        continue;
    }

    if ($itfreegift__product->post_type == 'product_variation') {
        $title = $itfreegift__product->get_name();
        //$title = $itfreegift__product->get_name().implode(" - ", $itfreegift__product->get_variation_attributes());
    } else {
        $title = $itfreegift__product->get_title();
    }

    $itfreegift_pw_number_gift_allowed = $gift_item_variable[$uid]['pw_number_gift_allowed'];
    //Number Allow For Other Method
    if (in_array($gift_item_variable[$uid]['method'], array(
        'buy_x_get_x_repeat'
    ), true) && $gift_item_variable[$uid]['based_on'] == 'ind') {
        $itfreegift_pw_number_gift_allowed = $gift_item_variable['all_gifts'][$itfreegift_gift_id]['q'];
    }

    $itfreegift_product_type = $itfreegift__product->get_type();
    $itfreegift_product_variable = false;
    if ($itfreegift_product_type == 'variable') {
        $itfreegift_product_variable = true;
    }
    $itfreegift_item_hover = 'hovering';

    if (has_filter('itfreegift_free_gift_disable_hover')) {
        $itfreegift_disable = apply_filters('itfreegift_free_gift_disable_hover', $gift_item_key, $gift_item_variable[$uid], $itfreegift_count_info, $gift_item_variable, $itfreegift__product);
        if ($itfreegift_disable) {
            $itfreegift_item_hover = 'disable-hover';
        }
    }

    if (in_array(
        $itfreegift_gift_id,
        $itfreegift_count_info['gifts_set']
    ) && $gift_item_variable[$uid]['can_several_gift'] == 'no') {
        $itfreegift_item_hover = 'disable-hover';
    }
    /**  Check Quantity  **/
    $itfreegift_array_return   = itfreegift_deprecated_quantities_gift_stock($itfreegift__product, $product_qty_in_cart, $itfreegift_product_id, $itfreegift_product_type, $settings, $itfreegift_item_hover, $itfreegift_pw_number_gift_allowed, $uid, $itfreegift_count_info, $gift_item_variable[$uid]['can_several_gift']);
    $itfreegift_item_hover     = $itfreegift_array_return['item_hover'];
    $itfreegift_text_stock_qty = $itfreegift_array_return['text_stock_qty'];
    $itfreegift_stock_status = $itfreegift_array_return['stock_status'];
?>
    <div class="wgb-popup-post-item <?php echo esc_attr($itfreegift_item_hover); ?> <?php echo esc_attr($itfreegift_stock_status); ?>">
        <div class="wgb-popup-post-thumbnail">
            <?php itfreegift_render_product_image($itfreegift__product); ?>
            <?php if ($settings['show_stock_quantity'] == 'true') : ?>
                <div class="wgb-item-overlay"><?php echo esc_html($itfreegift_text_stock_qty); ?></div>
                <!--  <span class="wgb-product-item-stock-in-thumb"><?php echo esc_html($itfreegift_text_stock_qty); ?></span> -->
            <?php endif; ?>
        </div>
        <div class="wgb-popup-post-title">
            <?php
            itfreegift_render_title_product_gift($title, $itfreegift_product_id, $settings, true);
            ?>
        </div>
        <?php

        $itfreegift_gift_data = [
            'gift_id' => $itfreegift_gift_id,
            'product_id' => $itfreegift_product_id,
            'rule_id' => $uid,
        ];
        ?>
        <a class="<?php echo esc_attr(implode(' ', itfreegift_get_gift_product_add_to_cart_classes($settings))); ?>" data-gift_id="<?php echo esc_attr($itfreegift_gift_id); ?>" data-product_id="<?php echo esc_attr($itfreegift_product_id); ?>" data-rule_id="<?php echo esc_attr($uid); ?>" href="<?php echo esc_url(itfreegift_get_gift_product_add_to_cart_url($itfreegift_gift_data, '')); ?>">
            <div class="wgb-loading-icon wgb-d-none">
                <div class="wgb-spinner wgb-spinner--2"></div>
            </div>
            <?php esc_html_e('Add Gift', 'ithemeland-free-gifts-for-woo'); ?>
            <?php //echo esc_html($itfreegift_add_gift_label); 
            ?>
        </a>
    </div>

<?php endforeach; ?>
<div class="ith-btn-no-thanks-cnt">
    <div class="itg-popup-close"><?php echo esc_html($itfreegift_no_thanks); ?></div>
</div>
