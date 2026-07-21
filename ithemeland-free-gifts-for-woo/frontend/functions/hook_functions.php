<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
//add to array add to cart meta
function itfreegift_array_addtocart_wc($cart_item_data)
{
    // (maybe) do something with the args.
    $cart_item_data = array_merge($cart_item_data, ['yith_wcp_child_component_data' => '']);
    return $cart_item_data;
}
//add_filter( 'itfreegift_array_addtocart', 'itfreegift_array_addtocart_wc', 10, 1 );


if (in_array($gift['method'], array('simple'), true) && $product_type != 'variable') {
    $itfreegift_pr_price = $product->get_price();
    if ($itfreegift_pr_price == '') {
        $itfreegift_pr_price = 0;
    }
    if ($this->gift_item_variable['all_gifts'][$gift_item_key]['value'] < ($count_info['subtotal_price'] + $itfreegift_pr_price)) {
        $itfreegift_flag_count = true;
    }
}
