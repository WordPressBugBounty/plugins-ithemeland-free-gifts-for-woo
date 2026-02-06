<?php


//add_filter('itg_redirect_after_click_gift_item', 'itg_redirect_after_click_gift_item' , 20, 1);
function itg_redirect_after_click_gift_item( $redirect ) {
	$PageID=1;
	$redirect= get_permalink($PageID);
	return $redirect;
}

//add_filter('itg_subtotal_include_tax','itg_subtotal_include_tax');
function itg_subtotal_include_tax($include_tax){
	  return false;
}

//add_filter('it_gift_cart_subtotal','it_gift_cart_subtotal');
function it_gift_cart_subtotal($items_cart_subtotal){
	  $items_cart_subtotal['subtotal']= WC()->cart->cart_contents_total;
	  return $items_cart_subtotal;
}

add_filter( 'itgift_redirect_link', function ( $id ) {
	
	$link='https://test.com/checkout/';

	return $link;
} );

add_filter('itgift_args_data_gift','itgift_args_data_gift');
function itgift_args_data_gift($data){
	if (count($data['items']) <= 0) {
		return $data;
	}
	foreach ($data['items'] as $key => $gift_product) {
		if (!$gift_product['hide_add_to_cart']) {
			return $data;
		}
	}
	$data['items']=[];
	return $data;
}

add_filter( 'itgift_permalink_add_to_cart_url', function ( $id ) {
	
	$link='https://test.com/checkout/';

	return $link;
} );

add_filter( 'itg_gift_product_name', 'cutome_itg_gift_product_name', 10, 2 );
function cutome_itg_gift_product_name($product_name, $product)
{
	$product_name = '<a>'.$product->get_name().'</a>';
	return $product_name;
}
?>