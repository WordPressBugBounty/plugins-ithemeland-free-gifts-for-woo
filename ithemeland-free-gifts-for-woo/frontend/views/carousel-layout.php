<?php

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

$itfreegift_add_gift_label = esc_html(get_option('itg_localization_add_gift', 'Add Gift'));
$itfreegift_select_gift = esc_html(get_option('itg_localization_select_gift', 'Select Gift'));

if (empty($items)) {
    return;
}

?>
<?php
/**
 * This hook is used to display the extra content before gift products content.
 * 
 * @since 2.0.0
 */

use ITFreeGift\classes\helpers\Sanitizer;

do_action('itfreegift_before_gift_products_content');
?>
<div class="adv-gift-section wgb-product-cnt wgb-frontend-gifts wgb-item-layout2">
    <div class="wgb-header-cnt">
        <h2 class="wgb-title text-capitalize font-weight-bold"><?php echo esc_html(get_option('itg_localization_our_gift', 'Our Gift')); ?></h2>
        <?php
        if (isset($rule_description)) {
        ?>
            <div><?php echo do_shortcode($rule_description); ?></div>
        <?php
        }
        ?>
    </div>

    <div class="wgb-owl-carousel owl-carousel it-owl-carousel-items" id="pw_slider_adv_gift">
        <?php
        foreach ($items as $itfreegift_key => $itfreegift_gift_product) {
            $itfreegift__product = wc_get_product($itfreegift_gift_product['product_id']);

            $itfreegift_link_classes = array('wgb-product-item-cnt');
            if ($itfreegift_gift_product['hide_add_to_cart']) {
                $itfreegift_link_classes[] = 'disable-hover';
            }
        ?>
            <div class="<?php echo esc_attr(implode(' ', $itfreegift_link_classes)); ?>">
                <div class="wgb-item-thumb">
                    <?php
                    itfreegift_render_product_image($itfreegift__product);
                    ?>
                    <div class="wgb-item-overlay"></div>
                    <?php
                    if ($settings['show_stock_quantity'] == 'true') {
                    ?>
                        <div class="wgb-stock">
                            <div class="gift-product-stock">
                                <?php
                                itfreegift_render_stock_status($itfreegift_gift_product['stock_qty'], $settings, $itfreegift_gift_product);
                                ?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="wgb-item-content">
                    <h2 class="wgb-item-title font-weight-bold">
                        <?php
                        itfreegift_render_product_name($itfreegift__product, $settings);
                        ?>
                    </h2>
                    <?php

                    if ($settings['display_price'] == 'yes') {
                        itfreegift_render_price_gift($itfreegift__product, $itfreegift_gift_product);
                    }
                    ?>
                </div>
                <?php

                do_action('itfreegift_free_gift_before_button_add_gift', $itfreegift_gift_product['product_id']);

                if ($itfreegift_gift_product['add_or_select'] == 'select') {
                ?>
                    <div class="wgb-add-gift-btn btn-select-gift-button"
                        data-rule-id="<?php echo esc_attr($itfreegift_gift_product['rule_id']); ?>" data-id="<?php echo esc_attr($itfreegift_gift_product['product_id']); ?>">
                        <div class="wgb-loading-icon wgb-d-none">
                            <div class="wgb-spinner wgb-spinner--2"></div>
                        </div>
                        <?php echo wp_kses($itfreegift_select_gift, Sanitizer::allowed_html()); ?>
                    </div>
                <?php
                } else {
                    $itfreegift_gift_id = $itfreegift_gift_product['rule_id'] . '-' . $itfreegift_gift_product['product_id'];
                    $itfreegift_gift_data = [
                        'gift_id' => $itfreegift_gift_id,
                        'product_id' => $itfreegift_gift_product['product_id'],
                        'rule_id' => $itfreegift_gift_product['rule_id'],
                    ];
                ?>
                    <a class="<?php echo esc_attr(implode(' ', itfreegift_get_gift_product_add_to_cart_classes($settings))); ?>"
                        data-gift_id="<?php echo esc_attr($itfreegift_gift_id); ?>"
                        data-product_id="<?php echo esc_attr($itfreegift_gift_product['product_id']); ?>"
                        data-rule_id="<?php echo esc_attr($itfreegift_gift_product['rule_id']); ?>"
                        href="<?php echo esc_url(itfreegift_get_gift_product_add_to_cart_url($itfreegift_gift_data)); ?>">
                        <div class="wgb-loading-icon wgb-d-none">
                            <div class="wgb-spinner wgb-spinner--2"></div>
                        </div>
                        <?php echo esc_html($itfreegift_add_gift_label); ?>
                    </a>
                <?php
                }
                ?>
                <?php
                do_action('itfreegift_free_gift_after_button_add_gift', $itfreegift_gift_product['product_id']);
                ?>
            </div>
        <?php     }  ?>
    </div>
</div>
<?php
/**
 * This hook is used to display the extra content after gift products content.
 * 
 * @since 2.0.0
 */
do_action('itfreegift_after_gift_products_content');
?>