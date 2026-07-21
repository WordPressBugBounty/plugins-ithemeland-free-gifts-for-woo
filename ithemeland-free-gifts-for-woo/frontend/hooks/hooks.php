<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

//add_filter('itfreegift_redirect_after_click_gift_item', 'itfreegift_redirect_after_click_gift_item' , 20, 1);
function itfreegift_redirect_after_click_gift_item($redirect)
{
    $PageID = 1;
    $redirect = get_permalink($PageID);
    return $redirect;
}

//add_filter('itfreegift_subtotal_include_tax','itfreegift_subtotal_include_tax');
function itfreegift_subtotal_include_tax($include_tax)
{
    return false;
}

//add_filter('itfreegift_gift_cart_subtotal','itfreegift_gift_cart_subtotal');
function itfreegift_gift_cart_subtotal($items_cart_subtotal)
{
    $items_cart_subtotal['subtotal'] = WC()->cart->cart_contents_total;
    return $items_cart_subtotal;
}

add_filter('itfreegift_redirect_link', function ($id) {

    $link = 'https://test.com/checkout/';

    return $link;
});

add_filter('itfreegift_args_data_gift', 'itfreegift_args_data_gift');
function itfreegift_args_data_gift($data)
{
    if (count($data['items']) <= 0) {
        return $data;
    }
    foreach ($data['items'] as $key => $itfreegift_gift_product) {
        if (!$itfreegift_gift_product['hide_add_to_cart']) {
            return $data;
        }
    }
    $data['items'] = [];
    return $data;
}

add_filter('itfreegift_permalink_add_to_cart_url', function ($id) {

    $link = 'https://test.com/checkout/';

    return $link;
});

add_filter('itfreegift_gift_product_name', 'itfreegift_gift_product_name_function', 10, 2);
function itfreegift_gift_product_name_function($product_name, $product)
{
    $product_name = '<a>' . $product->get_name() . '</a>';
    return $product_name;
}
