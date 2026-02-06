<?php

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}
class iThemeland_cart_hook
{
    public function __construct()
    {

        add_action('woocommerce_checkout_create_order_line_item', array(__CLASS__, 'itg_woocommerce_add_order_item_meta_new'), 10, 4);
        add_action('woocommerce_checkout_update_order_meta', array(__CLASS__, 'itg_woocommerce_add_order_meta'), 1);
        add_filter('woocommerce_cart_item_quantity', array(__CLASS__, 'set_cart_item_quantity'), 9999, 2);

        add_filter('woocommerce_cart_item_remove_link', array(__CLASS__, 'handles_cart_item_remove_link'), 10, 2);
        //add_action( 'woocommerce_before_calculate_totals' , array( __CLASS__ , 'set_price' ) , 9999 , 1 ) ;		
        add_action('woocommerce_before_calculate_totals', array(__CLASS__, 'set_price'), 9999, 1);
        //add_action( 'woocommerce_after_calculate_totals' , array( __CLASS__ , 'set_price' ) , 1 , 1 ) ;		
        add_filter('woocommerce_cart_item_price', array(__CLASS__, 'set_cart_item_price'), 9999, 3);
        add_filter('woocommerce_cart_item_class', array($this, 'woo_cart_item_class'), 10, 3);
        // Unset removed automatic free gift products from session data
        add_action('woocommerce_checkout_order_processed', array(__CLASS__, 'unset_removed_automatic_free_gifts_session_data'), 10, 1);

        //if isn't price for some themes
        if (defined('WGBL_PRICE_ISSUE')) {
            add_filter('woocommerce_cart_item_price', array(__CLASS__, 'set_cart_item_price_is_issue'), 9999, 3);
            add_filter('woocommerce_product_get_price', array(__CLASS__, 'set_woocommerce_product_get_price_is_issue'), 9999, 2);
            add_filter('woocommerce_product_variation_get_price', array(__CLASS__, 'set_woocommerce_product_get_price_is_issue'), 9999, 2);
        }
        // Set the cart item subtotal.
        add_filter('woocommerce_cart_item_subtotal', array(__CLASS__, 'set_cart_item_subtotal'), 9999, 3);

        //Dynamic Pricing and Discounts
        add_filter('woocommerce_add_cart_item', array($this, 'change_price_gift_product'), 20, 2);
        add_filter('woocommerce_get_cart_item_from_session', array($this, 'change_price_gift_product'), 30, 2);

        //add_action( 'woocommerce_order_item_meta_end', array( $this, 'format_custom_meta_data' ), 20, 2 );		

        //add_action( 'woocommerce_check_cart_items' , array( __CLASS__ ,'action_woocommerce_check_cart_items'), 10 );		

        // May be add the custom cart item data.
        add_action('woocommerce_get_item_data', [$this, 'maybe_add_custom_item_data'], 10, 2);

        // add_action('woocommerce_after_cart_table', [$this, 'add_gift_pagination_container']);
    }

    // public function add_gift_pagination_container()
    // {
    // 	echo '<div id="pagination-container" class="gift-pagination"></div>';
    // }
    public function format_custom_meta_data($item_id, $item)
    {
        $settings = itg_get_settings();
        if ($settings['show_description'] == 'yes') {
            $dynamic_rules = $item->get_meta('_ywdpd_discounts');
            if (! empty($dynamic_rules)) {
                $custom_meta = '';

                foreach ($dynamic_rules['applied_discounts'] as $applied_discount) {

                    if (isset($applied_discount['set_id'])) {
                        $rule_id = $applied_discount['set_id'];
                        $rule    = ywdpd_get_rule($rule_id);
                    } else {
                        $rule = $applied_discount['by'];
                    }
                    if ($rule instanceof YWDPD_Price_Rule && ! empty($rule->get_name())) {
                        /**
                         * APPLY_FILTERS: ywdpd_rule_name
                         *
                         * Change the rule name.
                         *
                         * @param string $rule_name the price.
                         *
                         * @return string
                         */
                        $custom_meta .= '<li>' . apply_filters('ywdpd_rule_name', $rule->get_name(), $rule, $applied_discount) . '</li>';
                    }
                }
                if (! empty($custom_meta)) { ?>
                    <ul class="wc-item-meta">
                        <span style="font-weight: bold;"><?php esc_html_e('Offer applied:', 'ithemeland-free-gifts-for-woo'); ?></span>
                        <?php echo wp_kses_post($custom_meta); ?>
                    </ul>
<?php
                }
            }
        }
    }

    public function change_price_gift_product($cart_item_data, $cart_item_key)
    {

        if (isset($cart_item_data['it_free_gift'])) {
            $price = apply_filters('itg_gift_product_price', $cart_item_data['it_free_gift']['price'], $cart_item_key, $cart_item_data);

            $cart_item_data['data']->update_meta_data('has_dynamic_price', true);
            $cart_item_data['data']->set_price($price);
        }

        return $cart_item_data;
    }

    public function woo_cart_item_class($class, $cart_item, $cart_item_key)
    {
        if (isset($cart_item['it_free_gift'])) {
            $class .= ' ' . 'wgb-gift-cart-item';
        }

        return $class;
    }

    public static function itg_woocommerce_add_order_meta($order_id)
    {
        $order           = wc_get_order($order_id);
        $set_gift = false;

        foreach ($order->get_items() as $key => $value) {
            if (isset($value['it_free_gift'])) {
                $set_gift = true;
                break;
            }
        }
        if ($set_gift) {
            $order->add_order_note($note);
            update_post_meta($order_id, 'gift_set', 'yes');

            // Set id in the order.
            // Improvement for HPOS compatibility.
            $order->add_meta_data('gift_set', 'yes');
            $order->save();
        }
    }

    public static function itg_woocommerce_add_order_item_meta_new($item, $cart_item_key, $values, $order)
    {
        if (! isset($values['it_free_gift'])) {
            return;
        }
        // Update order item meta.

        $item->add_meta_data('_free_gift', 'yes');
        $item->add_meta_data('_free_gift_method', $values['it_free_gift']['method']);
        $item->add_meta_data('_free_gift_type', $values['it_free_gift']['type']);
        $item->add_meta_data('_rule_id_free_gift', $values['it_free_gift']['rule_id']);
    }

    public static function set_cart_item_quantity($quantity, $cart_item_key)
    {
        // Return if cart object is not initialized.
        if (! is_object(WC()->cart)) {
            return $quantity;
        }

        $cart_items = WC()->cart->get_cart();

        // check if product is a gift product
        if (! isset($cart_items[$cart_item_key]['it_free_gift'])) {
            return $quantity;
        }

        return $cart_items[$cart_item_key]['quantity'];
    }


    public static function handles_cart_item_remove_link($remove_link, $cart_item_key)
    {
        // Return if cart object is not initialized.
        if (! is_object(WC()->cart)) {
            return $remove_link;
        }
        $cart_items = WC()->cart->get_cart();

        // Check if the product is a gift product.
        if (! isset($cart_items[$cart_item_key]['it_free_gift']['type'])) {
            return $remove_link;
        }

        // Return link if the product is a manual gift product.
        if ('auto' != $cart_items[$cart_item_key]['it_free_gift']['type']) {
            return $remove_link;
        }

        return '';
    }

    public static function set_price($cart_object)
    {
        // Return if cart object is not initialized.
        if (! is_object($cart_object)) {
            return;
        }

        foreach ($cart_object->cart_contents as $key => $value) {
            if (! isset($value['it_free_gift'])) {
                continue;
            }

            $price = apply_filters('itg_gift_product_price', $value['it_free_gift']['price'], $key, $value);

            $value['data']->set_price($price);
        }
    }

    public static function set_cart_item_price($price, $cart_item, $cart_item_key)
    {

        $settings = itg_get_settings();
        $free_txt = get_option('itg_localization_free', 'Free');
        // check if product is a gift product
        if (! isset($cart_item['it_free_gift'])) {
            return $price;
        }
        $multiply_qty = false;
        $product_id = ! empty($cart_item['variation_id']) ? $cart_item['variation_id'] : $cart_item['product_id'];
        $product    = wc_get_product($product_id);
        if (! is_object($product)) {
            return $price;
        }

        $price_temp = $cart_item['it_free_gift']['price'];

        $product_price = ($multiply_qty) ? (float) $cart_item['quantity'] * (float) $product->get_price() : $product->get_price();
        if ($settings['display_price'] == 'yes') {
            if ($price_temp > 0) {
                $display_price = '<del>' . wc_price($product_price) . '</del> <ins>' . wc_price($price_temp) . '</ins>';
            } else {
                $display_price = '<del>' . wc_price($product_price) . '</del> <ins>' . wc_price(0) . '</ins>';
            }
        } else {
            if ($price_temp > 0) {
                $display_price = wc_price($cart_item['it_free_gift']['price']);
            } else {
                $display_price = $free_txt;
            }
        }

        return $display_price;
    }

    public static function set_cart_item_subtotal($price, $cart_item, $cart_item_key)
    {

        // Check if the product is a gift product.
        if (!isset($cart_item['it_free_gift'])) {
            return $price;
        }
        $settings = itg_get_settings();
        $free_txt = get_option('itg_localization_free', 'Free');
        $multiply_qty = false;
        $product_id = ! empty($cart_item['variation_id']) ? $cart_item['variation_id'] : $cart_item['product_id'];
        $product    = wc_get_product($product_id);
        if (! is_object($product)) {
            return $price;
        }

        $price_temp = $cart_item['it_free_gift']['price'];

        $product_price = ($multiply_qty) ? (float) $cart_item['quantity'] * (float) $product->get_price() : $product->get_price();
        if ($settings['display_price'] == 'yes') {

            if ($price_temp > 0) {
                $display_price = '<del>' . wc_price($product_price) . '</del> <ins>' . wc_price($price_temp) . '</ins>';
            } else {
                $display_price = '<del>' . wc_price($product_price) . '</del> <ins>' . wc_price(0) . '</ins>';
            }
        } else {
            if ($price_temp > 0) {
                $display_price = wc_price($cart_item['it_free_gift']['price']);
            } else {
                $display_price = $free_txt;
            }
        }

        return $display_price;
    }

    public static function set_cart_item_price_is_issue($price, $cart_item, $cart_item_key)
    {


        // check if product is a gift product
        if (! isset($cart_item['it_free_gift'])) {
            return $price;
        }
        $settings = itg_get_settings();
        $free_txt = get_option('itg_localization_free', 'Free');
        $multiply_qty = false;
        $product_id = ! empty($cart_item['variation_id']) ? $cart_item['variation_id'] : $cart_item['product_id'];
        $product    = wc_get_product($product_id);
        if (! is_object($product)) {
            return $price;
        }

        $price_temp = $cart_item['it_free_gift']['price'];

        $product_price = ($multiply_qty) ? (float) $cart_item['quantity'] * (float) $product->get_price() : $product->get_price();
        if ($settings['display_price'] == 'yes') {
            if ($price_temp > 0) {
                $display_price = '<del>' . wc_price($product_price) . '</del> <ins>' . wc_price($price_temp) . '</ins>';
            } else {
                $display_price = '<del>' . wc_price($product_price) . '</del> <ins>' . wc_price(0) . '</ins>';
            }
        } else {
            if ($price_temp > 0) {
                $display_price = wc_price($cart_item['it_free_gift']['price']);
            } else {
                $display_price = $free_txt;
            }
        }
        echo "<script type='text/javascript'>
			jQuery(document).ready(function(){
					jQuery('.wgb-gift-cart-item').find('.product-subtotal').html('" . wp_kses_post($display_price) . "');
			});
		</script>";
    }

    public static function set_woocommerce_product_get_price_is_issue($price, $product)
    {

        if (is_admin() && ! defined('DOING_AJAX')) {
            return $price;
        }
        if (! is_object(WC()->cart)) {
            return $price;
        }
        foreach (WC()->cart->get_cart() as $key => $cart_item) {
            $product_id = $cart_item['product_id'];
            $variation_id = $cart_item['variation_id'];
            if ($product->get_id() === $product_id || $product->get_id() === $variation_id) {
                if (isset($cart_item['it_free_gift'])) {
                    return $cart_item['it_free_gift']['price'];
                }
            }
        }
        return $price;
    }

    /**
     *  May be add the custom cart item data.
     * 
     * @return array
     */
    public static function maybe_add_custom_item_data($item_data, $cart_item)
    {
        $settings = itg_get_settings();
        if ($settings['show_gift_type_lable'] == 'true') {
            if (!isset($cart_item['it_free_gift']) || !itg_check_is_array($cart_item['it_free_gift'])) {
                return $item_data;
            }

            $display_label = esc_html(get_option('itg_localization_our_gift', 'Free Product'));
            $type_label = esc_html(get_option('itg_localization_gift_cart_type_label', 'Type'));

            if (empty($type_label) && empty($display_label)) {
                return $item_data;
            }

            $item_data[] = array(
                'name' => $type_label,
                'display' => $display_label,
            );
        }

        return $item_data;
    }

    /**
     * Unset removed automatic free gift products from session data
     * 
     * @since 2.1.1
     * */
    public static function unset_removed_automatic_free_gifts_session_data($order_id)
    {
        if ('shop_order' !== get_post_type($order_id)) {
            return;
        }

        // Unset session values
        itg_unset_removed_automatic_free_gift_products_from_session();
    }
}
new iThemeland_cart_hook();
