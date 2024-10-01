<?php
/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

$add_gift = esc_html(get_option('itg_localization_add_gift'));
$select_gift = esc_html(get_option('itg_localization_select_gift'));


	$product_items = '';
	foreach ($items as $key => $gift_product) 
	{
		$_product       = wc_get_product( $gift_product[ 'product_id' ] ) ;
		if ( $gift_product[ 'hide_add_to_cart' ] ) {
			continue ;
		}
		
		
		$data_id= $gift_product['rule_id'].'-'.$gift_product['product_id'];
		
		//$img_url = itg_render_product_image( $_product , [50, 50] , false );
		
		$img_url = (!empty($_product->get_image_id())) ? wp_get_attachment_image_src($_product->get_image_id(), [50, 50]) : wc_placeholder_img_src([50, 50]);
		$img_url = (is_array($img_url) && !empty($img_url[0])) ? $img_url[0] : $img_url;
	
		
		$title = $_product->get_title();
		if ($_product->post_type == 'product_variation') {
			$title = $_product->get_name();
		}	
	
		$product_items .='<option value="' . esc_attr( $data_id ). '" data-imagesrc="' . esc_url($img_url) .'"
				data-description="' . esc_attr($title). '">' . esc_html($title) . '
		</option>';				
	}
	if ($product_items == '') {
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
	do_action( 'itg_before_gift_products_content' ) ;
	?>
    <div class="wgb-gift-products-dropdown">
		<?php echo wp_kses_post($product_items); ?>
    </div>
	<?php
	/**
	 * This hook is used to display the extra content after gift products content.
	 * 
	 * @since 2.0.0
	 */
	do_action( 'itg_after_gift_products_content' ) ;
	?>
</div>