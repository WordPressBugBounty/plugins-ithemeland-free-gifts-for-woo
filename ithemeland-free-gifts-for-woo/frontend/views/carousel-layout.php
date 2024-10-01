<?php
/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

$add_gift_lable = esc_html(get_option('itg_localization_add_gift','Add Gift'));
$select_gift = esc_html(get_option('itg_localization_select_gift','Select Gift'));

?>
<?php
/**
 * This hook is used to display the extra content before gift products content.
 * 
 * @since 2.0.0
 */
do_action( 'itg_before_gift_products_content' ) ;
?>
<div class="adv-gift-section wgb-product-cnt wgb-frontend-gifts wgb-item-layout2">
	<div class="wgb-header-cnt">
		<h2 class="wgb-title text-capitalize font-weight-bold"><?php echo esc_html(get_option('itg_localization_our_gift','Our Gift')); ?></h2>
		<?php
		if(isset($rule_description))
		{
		?>			
			<div><?php echo do_shortcode($rule_description); ?></div>
		<?php 
		}
		?>
	</div>

    <div class="wgb-owl-carousel owl-carousel it-owl-carousel-items" id="pw_slider_adv_gift">
		<?php
		foreach ($items as $key => $gift_product) 
		{
			$_product       = wc_get_product( $gift_product[ 'product_id' ] ) ;

			$link_classes = array( 'wgb-product-item-cnt' ) ;
			if ( $gift_product[ 'hide_add_to_cart' ] ) {
				$link_classes[] = 'disable-hover' ;
			}
			?>
			<div class="<?php echo esc_attr( implode( ' ', $link_classes ) ) ; ?>">
				<div class="wgb-item-thumb">
				<?php
				 itg_render_product_image($_product);
				 ?>
					<div class="wgb-item-overlay"></div>
					<?php
					if ($settings['show_stock_quantity'] == 'true') {
						?>
						<div class="wgb-stock">
							<div class="gift-product-stock">
								<?php
								itg_render_stock_status($gift_product[ 'stock_qty' ] , $settings ,$gift_product );
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
						itg_render_product_name($_product,$settings);
						?>						
					</h2>
					<?php 

					if ($settings['display_price'] == 'yes') {
						itg_render_price_gift($_product , $gift_product);
					}
					?>
				</div>
				<?php
				
				do_action('it_free_gift_before_button_add_gift' , $gift_product[ 'product_id' ] );
				
				if ($gift_product[ 'add_or_select' ]=='select' ) 
				{
				?>
				<div class="wgb-add-gift-btn btn-select-gift-button" 
				data-rule-id="<?php echo esc_attr($gift_product['rule_id']);?>" data-id="<?php echo esc_attr($gift_product['product_id']);?>">
					<div class="wgb-loading-icon wgb-d-none">
						<div class="wgb-spinner wgb-spinner--2"></div>
					</div>
					<?php echo wp_kses_post($select_gift);?>
				</div>
				<?php 
				} 
				else{
					$gift_id= $gift_product['rule_id'].'-'.$gift_product['product_id'];
					$gift_data=[
						'gift_id' => $gift_id,
						'product_id' => $gift_product['product_id'] , 
						'rule_id' => $gift_product['rule_id'],
					];					
					?>
					<a class="<?php echo esc_attr(implode(' ', itg_get_gift_product_add_to_cart_classes($settings))); ?>"
					   data-gift_id="<?php echo esc_attr($gift_id); ?>"
					   data-product_id="<?php echo esc_attr($gift_product['product_id']); ?>"
					   data-rule_id="<?php echo esc_attr($gift_product['rule_id']); ?>"
					   href="<?php echo esc_url(itg_get_gift_product_add_to_cart_url( $gift_data )); ?>"
					>
						<div class="wgb-loading-icon wgb-d-none">
							<div class="wgb-spinner wgb-spinner--2"></div>
						</div>				
						<?php echo esc_html($add_gift_lable); ?>
					</a>
				<?php 
				}
				?>
				<?php
				do_action('it_free_gift_after_button_add_gift' , $gift_product[ 'product_id' ] );
				?>
			</div>				
<?php 	}  ?>
	</div>
</div>
<?php
/**
 * This hook is used to display the extra content after gift products content.
 * 
 * @since 2.0.0
 */
do_action( 'itg_after_gift_products_content' ) ;
?>	
    
