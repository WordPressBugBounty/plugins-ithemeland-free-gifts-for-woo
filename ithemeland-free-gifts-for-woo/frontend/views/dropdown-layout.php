<?php

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

use ITFreeGift\classes\helpers\Sanitizer;

$itfreegift_add_gift = esc_html(itfreegift_get_localization('add_gift', false));
$itfreegift_select_gift = esc_html(itfreegift_get_localization('select_gift', false));

if (empty($items)) {
    return;
}

$itfreegift_product_items = '';
foreach ($items as $itfreegift_key => $itfreegift_gift_product) {
    $itfreegift__product       = wc_get_product($itfreegift_gift_product['product_id']);
    if ($itfreegift_gift_product['hide_add_to_cart']) {
        continue;
    }


    $itfreegift_data_id = $itfreegift_gift_product['rule_id'] . '-' . $itfreegift_gift_product['product_id'];

    //$itfreegift_img_url = itfreegift_render_product_image( $itfreegift__product , [50, 50] , false );

    $itfreegift_img_url = (!empty($itfreegift__product->get_image_id())) ? wp_get_attachment_image_src($itfreegift__product->get_image_id(), [50, 50]) : wc_placeholder_img_src([50, 50]);
    $itfreegift_img_url = (is_array($itfreegift_img_url) && !empty($itfreegift_img_url[0])) ? $itfreegift_img_url[0] : $itfreegift_img_url;


    $title = $itfreegift__product->get_title();
    if ($itfreegift__product->post_type == 'product_variation') {
        $title = $itfreegift__product->get_name();
    }

    $itfreegift_product_items .= '<option value="' . esc_attr($itfreegift_data_id) . '" data-imagesrc="' . esc_url($itfreegift_img_url) . '"
				data-description="' . esc_attr($title) . '">' . esc_html($title) . '
		</option>';
}
if ($itfreegift_product_items == '') {
    return;
}
?>
<div class="demo-live">
    <?php
    /**
     * This hook is used to display the extra content before gift products content.
     * 
     * @since 2.0.0
     */

    do_action('itfreegift_before_gift_products_content');
    ?>
    <div class="wgb-gift-products-dropdown">
        <?php echo wp_kses($itfreegift_product_items, Sanitizer::allowed_html()); ?>
    </div>
    <?php
    /**
     * This hook is used to display the extra content after gift products content.
     * 
     * @since 2.0.0
     */
    do_action('itfreegift_after_gift_products_content');
    ?>
</div>
