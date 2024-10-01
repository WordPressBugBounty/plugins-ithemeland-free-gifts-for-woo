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
<div class="adv-gift-section  wgb-frontend-gifts">
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
	
    <div class="wgb-grid-cnt">	
		<?php
		$paginition_type = "number"; 
		if ($paginition_type =='load_more') {
		?>
			<div class="pw_gift_pagination_div wgb-item-layout2">
				<div class="wgb-row" id="items_list">
		<?php 
		}
	?>
		<?php						
		$page = 1;
		$i = 1;
		$t = 1;
		$count_items= count($items);
		foreach ( $items as $key => $gift_product) 
		{
			$_product       = wc_get_product( $gift_product[ 'product_id' ] ) ;

			$link_classes = array( 'wgb-product-item-cnt' ) ;
			if ( $gift_product[ 'hide_add_to_cart' ] ) {
				$link_classes[] = 'disable-hover' ;
			}
			
			$active  = 'pw-gift-deactive';
			if ($page == 1) {
				$active  = ' pw-gift-active ';
			}
						
			if ($i == 1 && $paginition_type =='number') {
			?>
				<div class="page_<?php echo esc_attr($page);?> pw_gift_pagination_div <?php echo esc_attr($active);?> wgb-item-layout2">
						<div class="wgb-row">
				
			<?php
			}
			?>
			<div class="<?php echo esc_attr($settings['view_gift_in_cart']['desktop_columns']);?> <?php echo esc_attr($settings['view_gift_in_cart']['tablet_columns']);?> <?php echo esc_attr($settings['view_gift_in_cart']['mobile_columns']);?> <?php echo esc_attr($i);?>">
				<div class="<?php echo esc_attr( implode( ' ', $link_classes ) ) ; ?>">
					<div class="wgb-item-thumb">
						<?php
						 itg_render_product_image($_product);
						 ?>		
						<?php
						if ($settings['show_stock_quantity'] == 'true')
						{
						?>
							<div class="wgb-item-overlay">
								<?php
								itg_render_stock_status($gift_product[ 'stock_qty' ] , $settings ,$gift_product );
								?>
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
							?>
							<div class="gift-price" >
							<?php
								itg_render_price_gift($_product , $gift_product);
							?>
							</div>
						<?php
						}
						?>
						<?php
						
						do_action('it_free_gift_before_button_add_gift' , $gift_product[ 'product_id' ] );
						
						if ( $gift_product[ 'add_or_select' ]=='select' ) {					
						?>
						<div class="wgb-add-gift-btn btn-select-gift-button" 
						data-rule-id="<?php echo esc_attr($gift_product['rule_id']);?>" data-id="<?php echo esc_attr($gift_product['product_id']);?>">
							<div class="wgb-loading-icon wgb-d-none">
								<div class="wgb-spinner wgb-spinner--2"></div>
							</div>
							<?php echo esc_html($select_gift);?>
						</div>
						<?php 
						} else{
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
				</div>
			</div>
	<?php
			
			if (($i == $settings['view_gift_in_cart']['number_per_page'] ||  $count_items == $t) && $paginition_type =='number') {
				//$insert_div  = false;
				echo '</div></div>';
				$i = 0;
				$page++;
			}
			$i++;
			$t++;
		}
		?>
		<?php
		if ($page > 1 && $paginition_type =='number' ) {
			$max_num_pages = intval($page) - 1;
			?>
			<div class="wgb-pagination-cnt">
				<input type="hidden" id="wgb-cart-pagination-max-num-pages" value="<?php echo esc_attr($max_num_pages);?>">
				<div class="wgb-paging-item">
					<span><?php echo esc_html__('Page', 'ithemeland-free-gifts-for-woocommerce');?>
						<strong id="wgb-cart-pagination-current-page">1</strong>
						<?php echo esc_html__('of', 'ithemeland-free-gifts-for-woocommerce');?>
						<?php echo esc_html($max_num_pages);?>
					</span>
					<div class="wgb-pages">
						<?php
						$active='wgb-active-page';
						for( $i=1 ; $i <= $max_num_pages ; $i++ )
						{
							
						?>
							<a href="javascript:;" class="pw_gift_pagination_num <?php echo esc_attr($active);?>" data-page-id="page_<?php echo esc_attr($i);?>"><?php echo esc_attr($i);?></a>	
						<?php
							$active='';
						}
						?>
					</div>
				</div>
			</div>
			<?php
		}
		else 
		{
		?>
				<div id="loadMoregifts"><?php echo esc_html__('Load more', 'ithemeland-free-gifts-for-woocommerce');?></div>
				<input type="hidden" id="wgb-count-item" value="<?php echo esc_attr($count_items);?>">
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
do_action( 'itg_after_gift_products_content' ) ;
?>	