<?php

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

$itfreegift_add_gift_label = esc_html(get_option('itg_localization_add_gift', 'Add Gift'));
$itfreegift_select_gift = esc_html(get_option('itg_localization_select_gift', 'Select Gift'));
$itfreegift_no_thanks = esc_html(get_option('itg_localization_no_thanks', 'No Thanks'));

/**
 * This hook is used to display the extra content before gift products content.
 * 
 * @since 2.0.0
 */
do_action('itfreegift_before_gift_products_content');
?>

<div class="adv-gift-section wgb-product-cnt wgb-frontend-gifts wgb-item-layout2">
    <div class="wgb-owl-carousel owl-carousel it-owl-carousel-items" id="pw_slider_adv_gift">

        <?php
        foreach ($items as $itfreegift_key => $itfreegift_gift_product) :
            $itfreegift__product = wc_get_product($itfreegift_gift_product['product_id']);

            $itfreegift_link_classes = array('wgb-product-item-cnt');
            if ($itfreegift_gift_product['hide_add_to_cart']) {
                $itfreegift_link_classes[] = 'disable-hover';
            }
        ?>

            <div class="<?php echo esc_attr(implode(' ', $itfreegift_link_classes)); ?>">
                <div class="wgb-item-thumb">
                    <?php itfreegift_render_product_image($itfreegift__product, 'woocommerce_thumbnail', true); ?>
                    <div class="wgb-item-overlay"></div>
                    <?php if ($settings['show_stock_quantity'] == 'true') : ?>
                        <div class="wgb-stock">
                            <div class="gift-product-stock">
                                <?php itfreegift_render_stock_status($itfreegift_gift_product['stock_qty'], $settings, $itfreegift_gift_product, true); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
                <div class="wgb-item-content">
                    <h2 class="wgb-item-title font-weight-bold">
                        <?php itfreegift_render_product_name($itfreegift__product, $settings, true); ?>
                    </h2>
                    <?php
                    if ($settings['display_price'] == 'yes') {
                        itfreegift_render_price_gift($itfreegift__product, $itfreegift_gift_product, true);
                    }
                    ?>
                </div>
                <?php
                do_action('itfreegift_free_gift_before_button_add_gift', $itfreegift_gift_product['product_id']);
                $itfreegift_gift_id = $itfreegift_gift_product['rule_id'] . '-' . $itfreegift_gift_product['product_id'];
                $itfreegift_gift_data = [
                    'gift_id' => $itfreegift_gift_id,
                    'product_id' => $itfreegift_gift_product['product_id'],
                    'rule_id' => $itfreegift_gift_product['rule_id'],
                ];
                ?>
                <a class="<?php echo esc_attr(implode(' ', itfreegift_get_gift_product_add_to_cart_classes($settings))); ?>" data-gift_id="<?php echo esc_attr($itfreegift_gift_id); ?>" data-itg_mode="popup" data-product_id="<?php echo esc_attr($itfreegift_gift_product['product_id']); ?>" data-rule_id="<?php echo esc_attr($itfreegift_gift_product['rule_id']); ?>" href="<?php echo esc_url(itfreegift_get_gift_product_add_to_cart_url($itfreegift_gift_data)); ?>">
                    <div class="wgb-loading-icon wgb-d-none">
                        <div class="wgb-spinner wgb-spinner--2"></div>
                    </div>
                    <?php echo esc_html($itfreegift_add_gift_label); ?>
                </a>

                <?php do_action('itfreegift_free_gift_after_button_add_gift', $itfreegift_gift_product['product_id']); ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="ith-btn-no-thanks-cnt">
    <div class="wgb-popup-close itg-popup-close"><?php echo esc_html($itfreegift_no_thanks); ?></div>
</div>

<?php
/**
 * This hook is used to display the extra content after gift products content.
 *
 * @since 2.0.0
 */
do_action('itfreegift_after_gift_products_content');
