<?php


/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

use wgb\classes\helpers\Notice;
use wgb\frontend\classes\services\apply_rule\CheckRuleCondition;

class iThemeland_front_order
{
    private $gift_item_key;
    private $settings;
    private $check_rule_condition;


    public function __construct()
    {
        $this->gift_item_key = array();
        $this->settings = itg_get_settings();
        $this->check_rule_condition = new CheckRuleCondition($this->getCheckRuleConditionData());

        //add_action('wp_head', array($this, 'check_session_gift'));
        //add_action('woocommerce_cart_loaded_from_session', array($this, 'check_session_gift'));
        add_action('woocommerce_after_calculate_totals', array($this, 'check_session_gift_woocommerce_after_calculate_totals'));

        add_action('wp', array($this, 'pw_add_free_gifts'));

        add_action('wp_ajax_handel_pw_gift_show_variation', [$this, 'pw_gift_show_variation_function']);
        add_action('wp_ajax_nopriv_handel_pw_gift_show_variation', [$this, 'pw_gift_show_variation_function']);

        //Check Free Shipping
        add_filter('woocommerce_package_rates', [$this, 'filter_shipping_methods'], 100, 2);

        //Show popup in Checkout
        add_action('wp_ajax_handel_pw_gift_show_popup_checkout', [$this, 'pw_gift_show_popup_checkout_function']);
        add_action('wp_ajax_nopriv_handel_pw_gift_show_popup_checkout', [$this, 'pw_gift_show_popup_checkout_function']);

        //Ajax Manual Gift Products Add To Cart
        add_action('wp_ajax_ajax_add_free_gifts', [$this, 'itg_ajax_add_free_gifts']);
        add_action('wp_ajax_nopriv_ajax_add_free_gifts', [$this, 'itg_ajax_add_free_gifts']);

        //Ajax Manual Gift Products Add To Cart
        add_action('wp_ajax_itg_reloaditempopup', [$this, 'reload_item_popup']);
        add_action('wp_ajax_nopriv_itg_reloaditempopup', [$this, 'reload_item_popup']);

        // Test AJAX function for debugging
        add_action('wp_ajax_itg_test_ajax', [$this, 'test_ajax_function']);
        add_action('wp_ajax_nopriv_itg_test_ajax', [$this, 'test_ajax_function']);

        //add_action('woocommerce_new_order', array($this, 'add_gift_to_order_adv'), 99, 1);

        //If Change Change Payment Method Show Popup
        add_action('wp_ajax_wgb_check_rule_after_update_checkout', [$this, 'check_rule_after_update_checkout']);
        add_action('wp_ajax_nopriv_wgb_check_rule_after_checkout', [$this, 'check_rule_after_update_checkout']);
    }

    private function getCheckRuleConditionData(): array
    {
        return [
            'cart_contents'           => itg_get_cart_contents(),
            'gift_rule_exclude'       => [],
            'product_qty_in_cart'     => 0,
            'show_gift_item_for_cart' => [],
            'gift_item_variable'      => [],
        ];
    }

    public function check_session_gift_woocommerce_after_calculate_totals($cart = null)
    //public function check_session_gift($cart = null)
    {
        global $woocommerce;

        // Return if cart object is not initialized.
        if (!is_object(WC()->cart)) {
            return;
        }

        // Return if cart is empty.
        if (WC()->cart->get_cart_contents_count() == 0) {
            return;
        }
        if (!$this->check_rule_condition->pw_get_gift_for_cart_checkout()) {
            $products_removed = false;
            foreach (WC()->cart->get_cart() as $key => $value) {
                if (!isset($value['it_free_gift'])) {
                    continue;
                }
                WC()->cart->remove_cart_item($key);
                $products_removed = true;
            }

            if ($products_removed) {
                if (WC()->cart->get_cart_contents_count() > 0) {
                    Notice::add(get_option('itg_localization_free_gift_removed', 'Your Free Gift(s) were removed because your current cart contents is not eligible for a free gift'), 'notice');
                }
            }
            itg_unset_removed_automatic_free_gift_products_from_session();
            return;
        }

        $show_notice = false;
        if (!is_array($this->settings) || count($this->settings) <= 0) {
            $this->settings = itg_get_settings();
        }

        /** Check session if time rule changed and remove automatically session **/
        $session_gift_products = itg_get_removed_automatic_free_gift_products_from_session();
        if (itg_check_is_array($session_gift_products) || isset($session_gift_products['time'])) {
            if ($this->check_rule_condition->getGiftItemVariable()['rule_time'] != $session_gift_products['time']) {
                itg_unset_removed_automatic_free_gift_products_from_session();
            }
        }

        $count_info = itg_check_quantity_gift_in_session(WC()->cart->get_cart());
        foreach (WC()->cart->get_cart() as $key => $value) {
            if (!isset($value['it_free_gift'])) {
                continue;
            }

            if (!isset($this->check_rule_condition->getGiftItemVariable()['all_gifts'][$value['it_free_gift']['rule_gift_key']])) {
                WC()->cart->remove_cart_item($key);
                $show_notice = true;
                continue;
            }

            $uid = $this->check_rule_condition->getGiftItemVariable()['all_gifts'][$value['it_free_gift']['rule_gift_key']]['uid'];

            if ($value['it_free_gift']['time_add'] != $this->check_rule_condition->getGiftItemVariable()['rule_time']) {
                WC()->cart->remove_cart_item($key);
                itg_unset_removed_automatic_free_gift_products_from_session();
                $show_notice = true;
                continue;
            }

            if (!array_key_exists($value['it_free_gift']['rule_gift_key'], $this->check_rule_condition->getGiftItemVariable()['all_gifts'])) {
                WC()->cart->remove_cart_item($key);
                $show_notice = true;
                continue;
            }

            //Number Allow For Simple Method
            $pw_number_gift_allowed = $this->check_rule_condition->getGiftItemVariable()[$uid]['pw_number_gift_allowed'];

            //Number Allow For Other Method
            if (in_array($this->check_rule_condition->getGiftItemVariable()[$uid]['method'], array(
                'buy_x_get_x_repeat'
            ), true) && $this->check_rule_condition->getGiftItemVariable()['all_gifts'][$value['it_free_gift']['rule_gift_key']]['base_q'] == 'ind') {

                $pw_number_gift_allowed = $this->check_rule_condition->getGiftItemVariable()['all_gifts'][$value['it_free_gift']['rule_gift_key']]['q'];
            }

            //For Quantity is update
            $quantity_gift = $value['quantity'];
            if (
                isset($count_info['count_rule_gift'][$value['it_free_gift']['rule_id']]['q'])
                &&
                (!in_array($this->check_rule_condition->getGiftItemVariable()[$uid]['method'], array('buy_x_get_x_repeat'), true) || $this->check_rule_condition->getGiftItemVariable()['all_gifts'][$value['it_free_gift']['rule_gift_key']]['base_q'] != 'ind')
            )
            //if(isset($count_info['count_rule_gift'][$value['it_free_gift']['rule_id']]['q']))
            {
                for ($i = 0; $i < $value['quantity']; $i++) {
                    if ($count_info['count_rule_gift'][$value['it_free_gift']['rule_id']]['q'] > $pw_number_gift_allowed) {
                        if ($count_info['count_rule_gift'][$value['it_free_gift']['rule_id']]['q'] <= 1) {
                            WC()->cart->remove_cart_item($key);
                            $count_info['count_rule_gift'][$value['it_free_gift']['rule_id']]['q']--;

                            continue 2;
                        } else {
                            $quantity_gift = $quantity_gift - 1;
                            WC()->cart->set_quantity($key, $quantity_gift);

                            $count_info['count_rule_gift'][$value['it_free_gift']['rule_id']]['q']--;
                        }
                    } else {
                        //break;
                    }
                }
            }
            //For Quantity is update buy x get x ind
            if (array_key_exists(
                $value['it_free_gift']['rule_id'],
                $count_info['count_rule_gift']
            ) && $value['quantity'] > $pw_number_gift_allowed  && in_array($this->check_rule_condition->getGiftItemVariable()[$uid]['method'], array('buy_x_get_x_repeat'), true) && $this->check_rule_condition->getGiftItemVariable()['all_gifts'][$value['it_free_gift']['rule_gift_key']]['base_q'] == 'ind') {
                //if Quantity gift is <= 1  from Quantity less , else  kola less
                if ($value['quantity'] <= 1) {
                    WC()->cart->remove_cart_item($key);
                } else {
                    WC()->cart->set_quantity($key, $pw_number_gift_allowed);
                }
            }
            //check if any confuse and if item session wasn't in apply gift
            if (!array_key_exists(
                $value['it_free_gift']['rule_gift_key'],
                $this->check_rule_condition->getGiftItemVariable()['all_gifts']
            ) || count($this->check_rule_condition->getGiftItemVariable()['all_gifts'][$value['it_free_gift']['rule_gift_key']]) <= 0) {
                $show_notice = true;
                WC()->cart->remove_cart_item($key);
                continue;
            }
        }

        if ($show_notice) {
            Notice::add(get_option('itg_localization_free_gift_removed', 'Your Free Gift(s) were removed because your current cart contents is not eligible for a free gift'), 'notice');
        }

        if (isset($this->check_rule_condition->getShowGiftItemForCart()['gifts'])) {
            if ($this->settings['position'] == 'bottom_cart') {
                add_action('woocommerce_after_cart_table', [$this, 'display_gifts_bottom_cart']);
            }
            if ($this->settings['position'] == 'above_cart') {
                add_action('woocommerce_before_cart_table', [$this, 'display_gifts_bottom_cart']);
            } elseif ($this->settings['position'] == 'beside_coupon') {
                add_action('woocommerce_cart_coupon', [$this, 'display_gifts_in_Coupon_dropdown']);
            }

            //Show Popup
            add_action('wp_footer', array($this, 'layout_popup'));
        }
    }

    public function apply_on_cart_item($cart_object)
    {
        // Return if cart object is not initialized.
        if (!is_object($cart_object)) {
            return;
        }
        if (is_admin() && !defined('DOING_AJAX')) return;

        if (did_action('woocommerce_before_calculate_totals') >= 2) return;
        $min = PHP_FLOAT_MAX;

        // LOOP THROUGH THE CART TO FIND THE CHEAPEST ITEM
        foreach ($cart_object->cart_contents as $key => $value) {
            if (isset($value['it_free_gift'])) {
                continue;
            }
            $item_price = $value['data']->get_price();
            //$item_price = $subtotal_price / $value['quantity'];
            //$price = $value['data']->price; // Product original price
            if ($item_price <= $min) {
                $min = $item_price;
                $cheapest_item_key = $key;
                $item_quantity = $value['quantity'];
            }
        }

        $number_get_gift = 1;
        $price_gift = 0;
        $p1 = $min * ($item_quantity - $number_get_gift);
        $p2 = $number_get_gift * $price_gift;
        $sum = $p1 + $p2;
        $psum = $sum / $item_quantity;

        foreach ($cart_object->get_cart() as $cart_item_key => $cart_item) {
            if ($cheapest_item_key == $cart_item_key) {
                $cart_item['data']->set_price($psum);
                $cart_item['data']->set_sale_price($psum);
            }
        }
    }

    public function layout_popup()
    {
        if (!wgb_is_cart_page() && !wgb_is_checkout_page()) {
            return;
        }

        switch ($this->settings['layout_popup']) {
            case 'carousel':
                $layout = plugin_dir_path_wc_adv_gift . 'views/modal/carousel-layout.php';
                break;
            case 'list':
                $layout = plugin_dir_path_wc_adv_gift . 'views/modal/list-layout.php';
                break;
            default:
                $layout = plugin_dir_path_wc_adv_gift . 'views/modal/carousel-layout.php';
        }

        include $layout;
    }

    public function display_gifts_bottom_cart()
    {
        global $woocommerce;

        $is_child = false;

        switch ($this->settings['layout']) {
            case 'datatable':
                wp_enqueue_style('it-gift-datatables-style');
                wp_enqueue_script('it-gift-datatables-js');

                $template = 'datatable-layout.php';
                break;

            case 'grid':
                wp_enqueue_script('it-gift-grid-jquery');

                $template = 'grid-layout.php';
                break;

            case 'carousel':
                wp_enqueue_style('it-gift-owl-carousel-style');
                wp_enqueue_script('it-gift-owl-carousel-jquery');

                $template = 'carousel-layout.php';
                break;
        }

        if ($this->settings['child'] == 'true') {
            $is_child = true;
        }

        $atts_rule = [
            'gift_rule_exclude'   => $this->check_rule_condition->getGiftRuleExclude(),
            'quantity_products_in_cart' => $this->check_rule_condition->getProductQtyInCart(),
            'gifts_items_cart'        => $this->check_rule_condition->getShowGiftItemForCart(),
            'all_gift_items'          => $this->check_rule_condition->getGiftItemVariable(),
            'settings'  => $this->settings,
            'multi_level' => false,
            'is_child'  => $is_child,
        ];


        $atts = itg_get_gift_products_data_multilevel($atts_rule);
        //echo '<pre>';print_r($atts_rule);die;

        $atts = apply_filters('itgift_args_data_gift', $atts);


        if (count($atts['items']) <= 0) {
            return;
        }

        if ($this->settings['show_description'] == 'true') {
            $description = '';
            foreach ($this->check_rule_condition->getShowGiftItemForCart()['rule_details'] as $rule_item_key => $rule) {
                if (strlen($this->check_rule_condition->getShowGiftItemForCart()['rule_details'][$rule_item_key]['description']) > 0) {
                    $description .= '<div>' . $this->check_rule_condition->getShowGiftItemForCart()['rule_details'][$rule_item_key]['description'] . '</div>';
                }
            }
            $atts['rule_description'] = $description;
        }

        itg_get_template($template, $atts);
    }

    public function filter_shipping_methods($rates, $package)
    {
        $this->check_rule_condition->pw_get_gift_for_cart_checkout();
        $free_shipping_exists = $this->check_rule_condition->free_shipping_exists();

        if (!itg_check_is_array($free_shipping_exists)) {
            return $rates;
        }

        $unique_array = array_values(array_unique(array_merge(...array_values($free_shipping_exists))));

        foreach ($rates as $key => $rate) {
            $instance_id = itg_get_rate_instance_id($rate);
            if (itg_shipping_method_selected($instance_id, $unique_array)) {
                $rate->set_cost(0);
            }
        }
        return $rates;
    }

    public function display_gifts_click_notice_checkout_popup()
    {
        if (!wgb_is_checkout_page()) {
            return;
        }

        $gift_available = itg_check_gift_available($this->check_rule_condition->getShowGiftItemForCart(), $this->check_rule_condition->getGiftItemVariable(), $this->check_rule_condition->getGiftRuleExclude());
        if (!is_array($gift_available['av_gifts']) || count($gift_available['av_gifts']) <= 0) {
            return;
        }

        $notice = '<span class="itg-checkout-notice"></span>' . get_option('itg_localization_notice_checkout_message');

        $popup_link = sprintf('<a class="btn-select-gift-popup-button" href="">%s</a>', get_option('itg_localization_notice_checkout_message_link_here'));
        $notice        = str_replace('[popup_link]', $popup_link, $notice);

        $cart_page_url = sprintf('<a href="%s">%s</a>', wc_get_cart_url(), get_option('itg_localization_notice_checkout_message_link_here'));
        $notice        = str_replace('[cart_link]', $cart_page_url, $notice);
        //echo $notice;
        Notice::add($notice, 'notice');
    }


    public function display_gifts_in_Coupon_dropdown()
    {
        $atts = [
            'gift_rule_exclude'   => $this->check_rule_condition->getGiftRuleExclude(),
            'quantity_products_in_cart' => $this->check_rule_condition->getProductQtyInCart(),
            'gifts_items_cart'        => $this->check_rule_condition->getShowGiftItemForCart(),
            'all_gift_items'          => $this->check_rule_condition->getGiftItemVariable(),
            'settings'  => $this->settings,
            'multi_level' => false,
            'is_child'  => true,
        ];


        $atts = itg_get_gift_products_data_multilevel($atts);
        itg_get_template('dropdown-layout.php', $atts);
    }

    public function pw_add_free_gifts()
    {
        if (!isset($_REQUEST['pw_add_gift'])) { //phpcs:ignore
            return;
        }

        // Return if cart object is not initialized.
        if (!is_object(WC()->cart)) {
            return;
        }

        // return if cart is empty
        if (WC()->cart->get_cart_contents_count() == 0) {
            return;
        }
        if (!($this->gift_item_key = $this->check_rule_condition->pw_get_gift_for_cart_checkout())) {
            return;
        }
        $gift = sanitize_text_field(wp_unslash($_REQUEST['pw_add_gift'])); //phpcs:ignore

        if (!isset($_REQUEST['qty']) || !is_numeric($_REQUEST['qty'])) { //phpcs:ignore
            $qty = 1;
        } else
            $qty = sanitize_text_field(wp_unslash($_REQUEST['qty']));  //phpcs:ignore


        if (!array_key_exists($gift, $this->check_rule_condition->getGiftItemVariable()['all_gifts'])) {
            wp_safe_redirect(get_permalink());
            exit();
        }
        //$retrieved_group_input_value = WC()->session->get('gift_group_order_data');
        //$count_info                 = itg_check_quantity_gift_in_session($retrieved_group_input_value);

        $retrieved_group_input_value = WC()->cart->get_cart();

        $count_info = itg_check_quantity_gift_in_session($retrieved_group_input_value);

        $uid        = $this->check_rule_condition->getGiftItemVariable()['all_gifts'][$gift]['uid'];
        $id_product = $this->check_rule_condition->getGiftItemVariable()['all_gifts'][$gift]['id_product'];


        //Number Allow For Simple Method
        $pw_number_gift_allowed = $this->check_rule_condition->getGiftItemVariable()[$uid]['pw_number_gift_allowed'];
        //Number Allow For Other Method
        if (in_array($this->check_rule_condition->getGiftItemVariable()[$uid]['method'], array(
            'buy_x_get_x_repeat'
        ), true) && $this->check_rule_condition->getGiftItemVariable()['all_gifts'][$gift]['base_q'] == 'ind') {
            $pw_number_gift_allowed = $this->check_rule_condition->getGiftItemVariable()['all_gifts'][$gift]['q'];
        }


        if (
            !in_array($this->check_rule_condition->getGiftItemVariable()[$uid]['method'], array('buy_x_get_x_repeat'))
            ||
            $this->check_rule_condition->getGiftItemVariable()[$uid]['based_on'] != 'ind'
        ) {
            if (
                array_key_exists($uid, $count_info['count_rule_gift'])
                &&
                $qty > ($pw_number_gift_allowed - $count_info['count_rule_gift'][$uid]['q'])
            ) {
                //die;
                wp_safe_redirect(get_permalink());
                exit();
            }
        }


        if (isset($count_info['count_rule_product'][$gift]) && $count_info['count_rule_product'][$gift]['q'] >= $pw_number_gift_allowed) {
            wp_safe_redirect(get_permalink());
            exit();
        }

        if (in_array($gift, $count_info['gifts_set']) && $this->check_rule_condition->getGiftItemVariable()[$uid]['can_several_gift'] == 'no') {
            wp_safe_redirect(get_permalink());
            exit();
        }

        //Check Qty For Add bu user
        if ($qty > $pw_number_gift_allowed) {
            $qty = $pw_number_gift_allowed;
        }

        $count_selected = 0;
        if (isset($count_info['count_rule_product'][$gift])) {
            $count_selected = $count_info['count_rule_product'][$gift]['q'];
        }
        $result = $pw_number_gift_allowed - $count_selected;

        if ($qty > $result) {
            $qty = $result;
        }

        if ($qty > 1 && $this->check_rule_condition->getGiftItemVariable()[$uid]['can_several_gift'] == 'no') {
            $qty = 1;
        }

        $product = wc_get_product($id_product);
        $pr_price = $product->get_price();
        if (in_array($this->check_rule_condition->getGiftItemVariable()[$uid]['method'], array('simplea'), true)) {
            if ($pr_price == '') {
                $pr_price = 0;
            }
            if ($this->check_rule_condition->getGiftItemVariable()['all_gifts'][$gift]['value'] < ($count_info['subtotal_price'] + $pr_price)) {
                wp_safe_redirect(get_permalink());
                exit();
            }
        }

        // Return if product id is not proper product.
        if (!$product) {
            return;
        }
        $_price_for_gift = 0;
        $_price_for_gift = get_post_meta($id_product, '_price_for_gift', true);
        $_price_for_gift = str_replace(",", ".", $_price_for_gift);
        if ($_price_for_gift == '') {
            $_price_for_gift = 0;
        }

        // Add to Gift product in cart
        $cart_item_data = array(
            'it_free_gift' => array(
                'method'       => $this->check_rule_condition->getGiftItemVariable()[$uid]['method'],
                'type'            => 'manual',
                'rule_id'    => $uid,
                'rule_gift_key'    => $gift,
                'product_id' => $id_product,
                'price'      => $_price_for_gift,
                'base_price'      => $pr_price,
                'time_add'   => $this->check_rule_condition->getGiftItemVariable()['rule_time']
            ),
        );

        $cart_item_data = apply_filters('itgift_array_addtocart', $cart_item_data);

        WC()->cart->add_to_cart($id_product, $qty, 0, array(), $cart_item_data);

        Notice::add(get_option('itg_localization_free_gift_added', 'Gift product added successfully'), 'success');

        wp_safe_redirect(apply_filters('itgift_redirect_link', get_permalink()));

        //wp_safe_redirect(get_permalink());
        exit();
    }


    public function itg_ajax_add_free_gifts()
    {
        // Add error logging for debugging

        // Check nonce with better error handling
        if (!isset($_REQUEST['itg_security']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['itg_security'])), 'jkhKJSdd4576d234Z')) {
            wp_send_json_error(array('error' => 'Security check failed'));
            return;
        }

        try {
            if (!isset($_POST) && !isset($_REQUEST)) {
                throw new exception(__('Cannot process action1', 'ithemeland-free-gifts-for-woo'));
            }

            $gift_product_id = (isset($_REQUEST['gift_product_id'])) ? sanitize_text_field(wp_unslash($_REQUEST['gift_product_id'])) : 0;
            if (empty($gift_product_id)) {
                throw new exception(__('Cannot process action2', 'ithemeland-free-gifts-for-woo'));
            }
            // Return if cart object is not initialized.
            if (!is_object(WC()->cart)) {
                throw new exception(__('Cannot process action3', 'ithemeland-free-gifts-for-woo'));
            }
            // return if cart is empty
            if (WC()->cart->get_cart_contents_count() == 0) {
                throw new exception(__('Cannot process action4', 'ithemeland-free-gifts-for-woo'));
            }

            if (!($this->gift_item_key = $this->check_rule_condition->pw_get_gift_for_cart_checkout())) {
                throw new exception(__('Cannot process action5', 'ithemeland-free-gifts-for-woo'));
            }

            if (!isset($_POST['add_qty']) || !is_numeric($_POST['add_qty'])) {
                $qty = 1;
            } else {
                $qty = (isset($_REQUEST['add_qty'])) ? sanitize_text_field(wp_unslash($_REQUEST['add_qty'])) : 0; //
            }


            //wp_send_json_success(array( 'reload' => $this->check_rule_condition->getGiftItemVariable(),'gift_product_id' => $gift_product_id ));			

            if (!array_key_exists($gift_product_id, $this->check_rule_condition->getGiftItemVariable()['all_gifts'])) {
                throw new exception(__('Cannot process action6', 'ithemeland-free-gifts-for-woo'));
            }

            $retrieved_group_input_value = WC()->cart->get_cart();
            $count_info = itg_check_quantity_gift_in_session($retrieved_group_input_value);
            $uid        = $this->check_rule_condition->getGiftItemVariable()['all_gifts'][$gift_product_id]['uid'];
            $id_product = $this->check_rule_condition->getGiftItemVariable()['all_gifts'][$gift_product_id]['id_product'];
            //Number Allow For Simple Method
            $pw_number_gift_allowed = $this->check_rule_condition->getGiftItemVariable()[$uid]['pw_number_gift_allowed'];
            //Number Allow For Other Method
            if (in_array($this->check_rule_condition->getGiftItemVariable()[$uid]['method'], array(
                'buy_x_get_x_repeat'
            ), true) && $this->check_rule_condition->getGiftItemVariable()['all_gifts'][$gift_product_id]['base_q'] == 'ind') {
                $pw_number_gift_allowed = $this->check_rule_condition->getGiftItemVariable()['all_gifts'][$gift_product_id]['q'];
            }

            if (
                !in_array($this->check_rule_condition->getGiftItemVariable()[$uid]['method'], array('buy_x_get_x_repeat'))
                ||
                $this->check_rule_condition->getGiftItemVariable()[$uid]['based_on'] != 'ind'
            ) {
                if (
                    array_key_exists($uid, $count_info['count_rule_gift'])
                    &&
                    $qty > ($pw_number_gift_allowed - $count_info['count_rule_gift'][$uid]['q'])
                ) {
                    throw new exception(__('Cannot process action7', 'ithemeland-free-gifts-for-woo'));
                }
            }

            if (isset($count_info['count_rule_product'][$gift_product_id]) && $count_info['count_rule_product'][$gift_product_id]['q'] >= $pw_number_gift_allowed) {
                throw new exception(__('Cannot process action8', 'ithemeland-free-gifts-for-woo'));
            }

            if (in_array($gift_product_id, $count_info['gifts_set']) && $this->check_rule_condition->getGiftItemVariable()[$uid]['can_several_gift'] == 'no') {
                throw new exception(__('Cannot process action9', 'ithemeland-free-gifts-for-woo'));
            }
            //Check Qty For Add bu user
            if ($qty > $pw_number_gift_allowed) {
                $qty = $pw_number_gift_allowed;
            }

            $count_selected = 0;
            if (isset($count_info['count_rule_product'][$gift_product_id])) {
                $count_selected = $count_info['count_rule_product'][$gift_product_id]['q'];
            }
            $result = $pw_number_gift_allowed - $count_selected;

            if ($qty > $result) {
                $qty = $result;
            }

            if ($qty > 1 && $this->check_rule_condition->getGiftItemVariable()[$uid]['can_several_gift'] == 'no') {
                $qty = 1;
            }

            $product = wc_get_product($id_product);
            $pr_price = $product->get_price();
            // Return if product id is not proper product.
            if (!$product) {
                return;
            }
            $_price_for_gift = 0;
            $_price_for_gift = get_post_meta($id_product, '_price_for_gift', true);
            $_price_for_gift = str_replace(",", ".", $_price_for_gift);
            if ($_price_for_gift == '') {
                $_price_for_gift = 0;
            }

            // Add to Gift product in cart
            $cart_item_data = array(
                'it_free_gift' => array(
                    'method'       => $this->check_rule_condition->getGiftItemVariable()[$uid]['method'],
                    'type'            => 'manual',
                    'rule_id'    => $uid,
                    'rule_gift_key'    => $gift_product_id,
                    'product_id' => $id_product,
                    'price'      => $_price_for_gift,
                    'base_price'      => $pr_price,
                    'time_add'   => $this->check_rule_condition->getGiftItemVariable()['rule_time']
                ),
            );
            $cart_item_data = apply_filters('itgift_array_addtocart', $cart_item_data);

            // Add to cart with error handling
            $cart_item_key = WC()->cart->add_to_cart($id_product, $qty, 0, array(), $cart_item_data);

            if (!$cart_item_key) {
                throw new exception(__('Failed to add gift to cart', 'ithemeland-free-gifts-for-woo'));
            }

            // Create notice
            ob_start();
            Notice::print(get_option('itg_localization_free_gift_added', 'Gift product added successfully'), 'success');
            $notice = ob_get_clean();

            // Send success response
            $response = array(
                'ok' => 1,
                'qty_add' => $result,
                'notice' => $notice,
                'cart_item_key' => $cart_item_key
            );

            wp_send_json_success($response);
        } catch (Exception $ex) {
            wp_send_json_error(array('error' => $ex->getMessage()));
        }
    }

    public function pw_gift_show_popup_checkout_function()
    {
        if (!isset($_REQUEST['security']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['security'])), 'jkhKJSdd4576d234Z')) {
            wp_send_json_error(array('error' => 'Nance'));
        }

        // global $woocommerce;
        if (!($this->gift_item_key = $this->check_rule_condition->pw_get_gift_for_cart_checkout())) {
            wp_send_json_error(array('error' => 'item is not available'));
        }
        $settings = $this->settings;
        $atts_rule = [
            'gift_rule_exclude' => $this->check_rule_condition->getGiftRuleExclude(),
            'quantity_products_in_cart' => $this->check_rule_condition->getProductQtyInCart(),
            'gifts_items_cart' => $this->check_rule_condition->getShowGiftItemForCart(),
            'all_gift_items' => $this->check_rule_condition->getGiftItemVariable(),
            'settings'  => $settings,
            'multi_level' => false,
            'is_child'  => true,
        ];

        $atts = itg_get_gift_products_data_multilevel($atts_rule);

        if (count($atts['items']) <= 0) {
            wp_send_json_error(array('error' => 'item is not available'));
        }
        $items = $atts['items'];
        $layout = wgb_get_active_layout_popup_items($this->settings['layout_popup']);

        ob_start();
        require $layout;
        $html = ob_get_clean();
        wp_send_json_success(array(
            'ok' => 1,
            'layout' => $settings['layout_popup'],
            'result' => $html
        ));
        wp_die();
    }

    public function reload_item_popup()
    {
        check_ajax_referer('jkhKJSdd4576d234Z', 'itg_security');
        //try {
        if (!($this->gift_item_key = $this->check_rule_condition->pw_get_gift_for_cart_checkout())) {
            wp_send_json_error(array('error' => $ex->getMessage()));
        }
        $settings = $this->settings;
        $atts_rule = [
            'gift_rule_exclude'         => $this->check_rule_condition->getGiftRuleExclude(),
            'quantity_products_in_cart' => $this->check_rule_condition->getProductQtyInCart(),
            'gifts_items_cart'          => $this->check_rule_condition->getShowGiftItemForCart(),
            'all_gift_items'            => $this->check_rule_condition->getGiftItemVariable(),
            'settings'                  => $settings,
            'multi_level'               => false,
            'is_child'                  => true,
        ];

        $atts = itg_get_gift_products_data_multilevel($atts_rule);

        $items = $atts['items'];
        $flag = true;
        $html = 'unselectable';
        foreach ($items as $key => $gift_product) {
            if (!$gift_product['hide_add_to_cart']) {
                $flag = true;
                break;
            }
            $flag = false;
        }

        if ($flag) {
            $layout = wgb_get_active_layout_popup_items($this->settings['layout_popup']);
            ob_start();
            require $layout;
            $html = ob_get_clean();
        }
        wp_send_json_success(array('result' => $html, 'layout' => $this->settings['layout_popup']));
        //wp_send_json_error(array( 'result' =>'unselectable' ));
        //wp_send_json_success(array( 'ok' => 1,'result' =>$html ));

        /*
			if(count($atts['items']) <= 0)
			{
				throw new exception(__('Cannot process action2', 'ithemeland-free-gifts-for-woo'));
			}
			

			
			$template = 'carousel-layout.php';
			itg_get_template($template, $atts , 'modal/');
		} catch (Exception $ex) {
			wp_send_json_error(array( 'error' => $ex->getMessage() ));
		}				
		//require  plugin_dir_path_wc_adv_gift . 'views/modal/notice-checkout/carousel_notice.php';
//echo '<pre>';print_r($atts_rule);die;		
	//	require  plugin_dir_path_wc_adv_gift . 'views/modal/notice-checkout/carousel_notice.php';		
					
		//wp_send_json_error(array( 'error' => 'item isnt available' ));		
      //  $nonce = $_REQUEST['itg_security'];
      //  if (!wp_verify_nonce($nonce, 'jkhKJSdd4576d234Z')) {
       //     wp_send_json_error(array( 'error' => 'Nance' ));
       // }
		
*/
        wp_die();
    }

    public function pw_gift_show_variation_function()
    {
        $ret = '';

        if (!isset($_REQUEST['security']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['security'])), 'jkhKJSdd4576d234Z')) {
            wp_die('Forbidden!!!');
        }

        global $woocommerce;
        if (!isset($_POST['pw_gift_variable'])) {
            wp_die();
        }
        if (!$this->check_rule_condition->pw_get_gift_for_cart_checkout()) {
            return;
        }

        wp_enqueue_script('pw-gift-add-jquery-adv');
        //$view = 'variations.php';
        //		switch ( $view ) {
        //			case 'carousel':
        //				$view = 'carousel.php';
        //				break;
        //			case 'grid':
        //				$view = 'grid.php';
        //				break;
        //			case 'tables':
        //				$view = 'tables.php';
        //				break;
        //		}

        $variable  = (isset($_REQUEST['gift_product_id'])) ? sanitize_text_field(wp_unslash($_POST['pw_gift_variable'])) : 0;
        $p_product = wc_get_product($variable);

        $product_type = $p_product->get_type();

        if ($product_type == 'variable') {
            $variation_ids = version_compare(
                WC()->version,
                '2.7.0',
                '>='
            ) ? $p_product->get_visible_children() : $p_product->get_children(true);

            $atts = [
                'products_ids'        => $variation_ids,
                'uid'                 => (isset($_POST['pw_gift_uid'])) ? sanitize_text_field(wp_unslash($_POST['pw_gift_uid'])) : '',
                'gift_item_variable'  => $this->check_rule_condition->getGiftItemVariable(),
                'gift_rule_exclude'   => $this->check_rule_condition->getGiftRuleExclude(),
                'product_qty_in_cart' => $this->check_rule_condition->getProductQtyInCart(),
                'settings'            => $this->settings,
                'view'                => 'modal',
            ];

            itg_get_template('modal/variations.php', $atts);

            /* 
		   wc_get_template($view, array(
                'products_ids'        => $variation_ids,
                'uid'                 => sanitize_text_field($_POST['pw_gift_uid']),
                'gift_item_variable'  => $this->check_rule_condition->getGiftItemVariable(),
                'gift_rule_exclude'   => $this->check_rule_condition->getGiftRuleExclude(),
                'product_qty_in_cart' => $this->check_rule_condition->getProductQtyInCart(),
                'settings'            => $this->settings,
                'view'                => 'modal',
            ), '', plugin_dir_path_wc_adv_gift . 'views/modal/');
			*/
        }

        wp_die();
    }

    public function check_rule_after_update_checkout()
    {

        global $woocommerce;
        $data = 'no';
        if ($this->gift_item_key = $this->check_rule_condition->pw_get_gift_for_cart_checkout()) {

            $data = 'yes';
        }
        wp_send_json_success(array('gift_avilible' => $data));
    }

    public function test_ajax_function()
    {
        wp_send_json_success(array('message' => 'AJAX test successful!'));
    }
}

new iThemeland_front_order();
