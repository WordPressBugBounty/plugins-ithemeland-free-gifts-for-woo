<?php

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

$add_gift_lable = esc_html(get_option('itg_localization_add_gift', 'Add Gift'));
$select_gift = esc_html(get_option('itg_localization_select_gift', 'Select Gift'));

?>
<?php
/**
 * This hook is used to display the extra content before gift products content.
 * 
 * @since 2.0.0
 */
do_action('itg_before_gift_products_content');
?>

<div class="wgb-mt30 wgb-mb30">

    <h3><?php echo esc_html(get_option('itg_localization_our_gift', 'Our Gift')); ?></h3>
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
                <th><?php esc_html_e('Thumb', 'ithemeland-free-gifts-for-woocommerce'); ?></th>
                <th><?php esc_html_e('Name', 'ithemeland-free-gifts-for-woocommerce'); ?></th>
                <?php
                if ($settings['show_stock_quantity'] == 'true') {
                ?>
                    <th><?php esc_html_e('Gift Available', 'ithemeland-free-gifts-for-woocommerce'); ?></th>
                <?php
                }
                ?>
                <th><?php esc_html_e('Add To Cart', 'ithemeland-free-gifts-for-woocommerce'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($items as $key => $gift_product) {
                $_product       = wc_get_product($gift_product['product_id']);

                $link_classes = array('wgb-product-item-cnt');
                if ($gift_product['hide_add_to_cart']) {
                    $link_classes[] = 'disable-hover';
                }
            ?>
                <tr class="<?php echo esc_attr(implode(' ', $link_classes)); ?>">
                    <td class="wgb-product-item-td-thumb">
                        <?php
                        itg_render_product_image($_product);
                        ?>
                    </td>
                    <td>
                        <?php
                        itg_render_product_name($_product, $settings);
                        ?>
                    </td>
                    <?php
                    if ($settings['show_stock_quantity'] == 'true') {
                    ?>
                        <td>
                            <div class="it-wgb-item-overlay">
                                <?php
                                itg_render_stock_status($gift_product['stock_qty'], $settings, $gift_product);
                                ?>
                            </div>
                        </td>
                    <?php
                    }
                    ?>
                    <?php

                    do_action('it_free_gift_before_button_add_gift', $gift_product['product_id']);

                    if ($gift_product['add_or_select'] == 'select') {
                    ?>
                        <td>
                            <div class="wgb-add-gift-btn btn-select-gift-button" data-rule-id="<?php echo esc_attr($gift_product['rule_id']); ?>" data-id="<?php echo esc_attr($gift_product['product_id']); ?>">
                                <?php echo wp_kses_post($select_gift); ?>
                                <div class="wgb-loading-icon wgb-d-none">
                                    <div class="wgb-spinner wgb-spinner--2"></div>
                                </div>
                            </div>
                        </td>
                    <?php
                    } else {
                        $gift_id = $gift_product['rule_id'] . '-' . $gift_product['product_id'];

                        $gift_data = [
                            'gift_id' => $gift_id,
                            'product_id' => $gift_product['product_id'],
                            'rule_id' => $gift_product['rule_id'],
                        ];
                    ?>
                        <td>
                            <a class="<?php echo esc_attr(implode(' ', itg_get_gift_product_add_to_cart_classes($settings))); ?>" data-gift_id="<?php echo esc_attr($gift_id); ?>" data-product_id="<?php echo esc_attr($gift_product['product_id']); ?>" data-rule_id="<?php echo esc_attr($gift_product['rule_id']); ?>" href="<?php echo esc_url(itg_get_gift_product_add_to_cart_url($gift_data)); ?>">
                                <div class="wgb-loading-icon wgb-d-none">
                                    <div class="wgb-spinner wgb-spinner--2"></div>
                                </div>
                                <?php echo esc_html($add_gift_lable); ?>
                            </a>
                        </td>
                    <?php
                    }
                    ?>
                    <?php
                    do_action('it_free_gift_after_button_add_gift', $gift_product['product_id']);
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
do_action('itg_after_gift_products_content');
?>