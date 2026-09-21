<?php

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

$itfreegift_add_gift_label = esc_html(itfreegift_get_localization('add_gift', 'Add Gift'));
$itfreegift_select_gift = esc_html(itfreegift_get_localization('select_gift', 'Select Gift'));

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

<div class="wgb-mt30 wgb-mb30">

    <h3><?php echo esc_html(itfreegift_get_localization('our_gift', 'Our Gift')); ?></h3>
    <?php
    if (isset($rule_description)) {
    ?>
        <div><?php echo do_shortcode($rule_description); ?></div>
    <?php
    }
    ?>
    <table class="it-gift-products-table display" style="width:100%">
        <thead>
            <tr>
                <th><?php esc_html_e('Thumb', 'ithemeland-free-gifts-for-woo'); ?></th>
                <th><?php esc_html_e('Name', 'ithemeland-free-gifts-for-woo'); ?></th>
                <?php
                if ($settings['show_stock_quantity'] == 'true') {
                ?>
                    <th><?php esc_html_e('Gift Available', 'ithemeland-free-gifts-for-woo'); ?></th>
                <?php
                }
                ?>
                <th><?php esc_html_e('Add To Cart', 'ithemeland-free-gifts-for-woo'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($items as $itfreegift_key => $itfreegift_gift_product) {
                $itfreegift__product       = wc_get_product($itfreegift_gift_product['product_id']);
                $itfreegift_qty = $itfreegift_gift_product['stock_qty'];
                $itfreegift_link_classes = array('wgb-product-item-cnt');
                if ($itfreegift_gift_product['hide_add_to_cart']) {
                    $itfreegift_link_classes[] = 'disable-hover';
                    $itfreegift_qty = 1;
                }
            ?>
                <tr class="<?php echo esc_attr(implode(' ', $itfreegift_link_classes)); ?>">
                    <td class="wgb-product-item-td-thumb">
                        <?php
                        itfreegift_render_product_image($itfreegift__product);
                        ?>
                    </td>
                    <td>
                        <?php
                        itfreegift_render_product_name($itfreegift__product, $settings);
                        ?>
                    </td>
                    <?php
                    if ($settings['show_stock_quantity'] == 'true') {
                    ?>
                        <td>
                            <div class="it-wgb-item-overlay">
                                <?php
                                itfreegift_render_stock_status($itfreegift_gift_product['stock_qty'], $settings, $itfreegift_gift_product);
                                ?>
                            </div>
                        </td>
                    <?php
                    }
                    ?>
                    <?php

                    do_action('itfreegift_free_gift_before_button_add_gift', $itfreegift_gift_product['product_id']);

                    if ($itfreegift_gift_product['add_or_select'] == 'select') {
                    ?>
                        <td>
                            <div class="wgb-add-gift-btn btn-select-gift-button" data-rule-id="<?php echo esc_attr($itfreegift_gift_product['rule_id']); ?>" data-id="<?php echo esc_attr($itfreegift_gift_product['product_id']); ?>">
                                <?php echo wp_kses($itfreegift_select_gift, Sanitizer::allowed_html()); ?>
                                <div class="wgb-loading-icon wgb-d-none">
                                    <div class="wgb-spinner wgb-spinner--2"></div>
                                </div>
                            </div>
                        </td>
                    <?php
                    } else {
                        $itfreegift_gift_id = $itfreegift_gift_product['rule_id'] . '-' . $itfreegift_gift_product['product_id'];

                        $itfreegift_gift_data = [
                            'gift_id' => $itfreegift_gift_id,
                            'product_id' => $itfreegift_gift_product['product_id'],
                            'rule_id' => $itfreegift_gift_product['rule_id'],
                        ];
                    ?>
                        <td>
                            <div class='itg-gift-product-add-to-cart-actions'>
                                <?php
                                if ($settings['enable_ajax_add_to_cart'] == 'true' && $settings['enabled-qty'] == 'true') {
                                ?>
                                    <span class='itg-gift-product-qty-container'>
                                        <input class='itg-gift-product-qty' type='number' min='1' size='5' max='<?php echo esc_attr($itfreegift_qty); ?>' value='1' />
                                    </span>
                                <?php } ?>
                                <span>
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
                                </span>
                            </div>
                        </td>
                    <?php
                    }
                    ?>
                    <?php
                    do_action('itfreegift_free_gift_after_button_add_gift', $itfreegift_gift_product['product_id']);
                    ?>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
<?php
/**
 * This hook is used to display the extra content after gift products content.
 * 
 * @since 2.0.0
 */
do_action('itfreegift_after_gift_products_content');
?>
