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
do_action('itfreegift_before_gift_products_content');
?>
<div class="adv-gift-section  wgb-frontend-gifts">
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

    <div class="wgb-grid-cnt">
        <?php
        $itfreegift_pagination_type = "number";
        if ($itfreegift_pagination_type == 'load_more') {
        ?>
            <div class="pw_gift_pagination_div wgb-item-layout2">
                <div class="wgb-row" id="items_list">
                <?php
            }
                ?>
                <?php
                $page = 1;
                $itfreegift_i = 1;
                $itfreegift_t = 1;
                $itfreegift_count_items = count($items);
                foreach ($items as $itfreegift_key => $itfreegift_gift_product) {

                    $itfreegift__product       = wc_get_product($itfreegift_gift_product['product_id']);

                    $itfreegift_link_classes = array('wgb-product-item-cnt');
                    if ($itfreegift_gift_product['hide_add_to_cart']) {
                        $itfreegift_link_classes[] = 'disable-hover';
                    }

                    $itfreegift_active  = 'pw-gift-deactive';
                    if ($page == 1) {
                        $itfreegift_active  = ' pw-gift-active ';
                    }

                    if ($itfreegift_i == 1 && $itfreegift_pagination_type == 'number') {
                ?>
                        <div class="page_<?php echo esc_attr($page); ?> pw_gift_pagination_div <?php echo esc_attr($itfreegift_active); ?> wgb-item-layout2">
                            <div class="wgb-row">

                            <?php
                        }
                            ?>
                            <div class="<?php echo esc_attr($settings['desktop_columns']); ?> <?php echo esc_attr($settings['tablet_columns']); ?> <?php echo esc_attr($settings['mobile_columns']); ?> <?php echo esc_attr($itfreegift_i); ?>">
                                <div class="<?php echo esc_attr(implode(' ', $itfreegift_link_classes)); ?>">
                                    <div class="wgb-item-thumb">
                                        <?php
                                        itfreegift_render_product_image($itfreegift__product);
                                        ?>
                                        <?php
                                        if ($settings['show_stock_quantity'] == 'true') {
                                        ?>
                                            <div class="wgb-item-overlay">
                                                <?php
                                                itfreegift_render_stock_status($itfreegift_gift_product['stock_qty'], $settings, $itfreegift_gift_product);
                                                ?>
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
                                        ?>
                                            <div class="gift-price">
                                                <?php
                                                itfreegift_render_price_gift($itfreegift__product, $itfreegift_gift_product);
                                                ?>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <?php

                                        do_action('itfreegift_free_gift_before_button_add_gift', $itfreegift_gift_product['product_id']);

                                        if ($itfreegift_gift_product['add_or_select'] == 'select') {
                                        ?>
                                            <div class="wgb-add-gift-btn btn-select-gift-button"
                                                data-rule-id="<?php echo esc_attr($itfreegift_gift_product['rule_id']); ?>" data-id="<?php echo esc_attr($itfreegift_gift_product['product_id']); ?>">
                                                <div class="wgb-loading-icon wgb-d-none">
                                                    <div class="wgb-spinner wgb-spinner--2"></div>
                                                </div>
                                                <?php echo esc_html($itfreegift_select_gift); ?>
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
                                </div>
                            </div>
                        <?php

                        if (($itfreegift_i == $settings['number_per_page'] ||  $itfreegift_count_items == $itfreegift_t) && $itfreegift_pagination_type == 'number') {
                            //$insert_div  = false;
                            echo '</div></div>';
                            $itfreegift_i = 0;
                            $page++;
                        }
                        $itfreegift_i++;
                        $itfreegift_t++;
                    }
                        ?>
                        <?php
                        if ($page > 1 && $itfreegift_pagination_type == 'number') {
                            $itfreegift_max_num_pages = intval($page) - 1;
                        ?>
                            <div class="wgb-pagination-cnt">
                                <input type="hidden" id="wgb-cart-pagination-max-num-pages" vaue="<?php echo esc_attr($itfreegift_max_num_pages); ?>">
                                <div class="wgb-paging-item">
                                    <span><?php echo esc_html__('Page', 'ithemeland-free-gifts-for-woo'); ?>
                                        <strong id="wgb-cart-pagina	tion-current-page">1</strong>
                                        <?php echo esc_html__('of', 'ithemeland-free-gifts-for-woo'); ?>
                                        <?php echo esc_html($itfreegift_max_num_pages); ?>
                                    </span>
                                    <div class="wgb-pages">
                                        <?php
                                        $itfreegift_active = 'wgb-active-page';
                                        for ($itfreegift_i = 1; $itfreegift_i <= $itfreegift_max_num_pages; $itfreegift_i++) {

                                        ?>
                                            <a href="javascript:;" class="pw_gift_pagination_num <?php echo esc_attr($itfreegift_active); ?>" data-page-id="page_<?php echo esc_attr($itfreegift_i); ?>"><?php echo esc_attr($itfreegift_i); ?></a>
                                        <?php
                                            $itfreegift_active = '';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div id="loadMoregifts"><?php echo esc_html__('Load more', 'ithemeland-free-gifts-for-woo'); ?></div>
                            <input type="hidden" id="wgb-count-item" value="<?php echo esc_attr($itfreegift_count_items); ?>">
                            </div>
                        </div>
                    <?php
                        }
                    ?>
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