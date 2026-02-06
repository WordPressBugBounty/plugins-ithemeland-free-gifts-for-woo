<?php

//add to array add to cart meta
function itgift_array_addtocart_wc( $cart_item_data ) {
    // (maybe) do something with the args.
	$cart_item_data=array_merge( $cart_item_data ,['yith_wcp_child_component_data'=>''] );
	return $cart_item_data;
}
//add_filter( 'itgift_array_addtocart', 'itgift_array_addtocart_wc', 10, 1 );




if (in_array($gift['method'], array('simple'), true) && $product_type != 'variable') {
	$pr_price=$product->get_price();
	if($pr_price==''){
			$pr_price=0;
	}
	if($this->gift_item_variable['all_gifts'][$gift_item_key]['value'] < ($count_info['subtotal_price']+$pr_price)){
		$flag_count = true;
	}
}
	
?>