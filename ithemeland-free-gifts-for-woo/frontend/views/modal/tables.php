<?php

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

$count_info = itg_check_quantity_gift_in_session();

foreach ($products_ids as $product_id) :
    //For exclude in select variations
    if (isset($gift_rule_exclude[$uid]) && in_array(
        $product_id,
        $gift_rule_exclude[$uid]
    )) {
        continue;
    }
    $gift_id  = $uid . '-' . $product_id;
    $_product = wc_get_product($product_id);

    if ($_product->post_type == 'product_variation') {
        $title = $_product->get_name();
    } else {
        $title = $_product->get_title();
    }

    $product_type = $_product->get_type();
    $product_variable = false;
    if ($product_type == 'variable') {
        $product_variable = true;
    }
    $item_hover = 'hovering';

    if (in_array(
        $gift_id,
        $count_info['gifts_set']
    ) && $gift_item_variable[$uid]['can_several_gift'] == 'no') {
        $item_hover = 'disable-hover';
    }

    /**  Check Quantity  **/
    $array_return   = itg_quantities_gift_stock($_product, $product_qty_in_cart, $product_id, $product_type, $settings, $item_hover);
    $item_hover     = $array_return['item_hover'];
    $text_stock_qty = $array_return['text_stock_qty'];
    $stock_status = $array_return['stock_status'];
?>
    <div class="wgb-popup-post-item <?php echo esc_attr($item_hover); ?> <?php echo esc_attr($stock_status); ?>">
        <div class="wgb-popup-post-thumbnail">
            <?php itg_render_product_image($_product); ?>
            <?php if ($settings['show_stock_quantity'] == 'true') : ?>
					<div class="wgb-item-overlay"><?php echo wp_kses_post( $text_stock_qty);?></div>
               <!--  <span class="wgb-product-item-stock-in-thumb"><?php echo esc_html($text_stock_qty); ?></span> -->
            <?php endif; ?>
        </div>
		<div class="wgb-popup-post-title">
		<?php
		itg_render_title_product_gift($title,$product_id,$settings,true)
		?>
		</div>
		<?php

		$gift_data=[
			'gift_id' => $gift_id,
			'product_id' => $product_id , 
			'rule_id' => $uid,
		];							
		?>
		<a class="<?php echo esc_attr(implode(' ', itg_get_gift_product_add_to_cart_classes($settings))); ?>"
		   data-gift_id="<?php echo esc_attr($gift_id); ?>"
		   data-product_id="<?php echo esc_attr($product_id); ?>"
		   data-rule_id="<?php echo esc_attr($uid); ?>"
		   href="<?php echo esc_url(itg_get_gift_product_add_to_cart_url( $gift_data ,'')); ?>"
		>
			<div class="wgb-loading-icon wgb-d-none">
				<div class="wgb-spinner wgb-spinner--2"></div>
			</div>				
			<?php esc_html_e('Add Gift', 'ithemeland-free-gifts-for-woocommerce'); ?>
			<?php //echo esc_html($add_gift_lable); ?>
		</a>		
    </div>
<?php endforeach; ?>
<div class="ith-btn-no-thanks-cnt">
	<div class="wgb-popup-close" >No Thanks</div>
</div>