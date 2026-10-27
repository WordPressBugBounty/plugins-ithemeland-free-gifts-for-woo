<?php


/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

use ITFreeGift\classes\helpers\Notice;
use ITFreeGift\frontend\classes\services\apply_rule\CheckRuleCondition;

class iThemeland_front_order
{
    private $gift_item_key;
    private $settings;
    private $check_rule_condition;
    private $rule_evaluation_in_progress = false;
    private $stabilizing_cart_rules = false;
    private $synchronizing_store_api_response = false;


    public function __construct()
    {
        $this->gift_item_key = array();
        $this->settings = itfreegift_get_settings();
        $this->check_rule_condition = new CheckRuleCondition($this->getCheckRuleConditionData());

        $this->checkout_gifts_init();

        //add_action('wp_head', array($this, 'check_session_gift'));
        //add_action('woocommerce_cart_loaded_from_session', array($this, 'check_session_gift'));
        add_action('woocommerce_after_calculate_totals', array($this, 'check_session_gift_woocommerce_after_calculate_totals'));
        add_action('woocommerce_add_to_cart', array($this, 'synchronize_store_api_cart_after_add'), 999, 6);
        // CartSchema validates the cart before taking its item snapshot. This
        // covers add/update/remove, batch requests and GET using the same state.
        add_action('woocommerce_store_api_cart_errors', array($this, 'synchronize_store_api_cart_response'), 1, 2);

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

        //add_action('woocommerce_new_order', array($this, 'add_gift_to_order_adv'), 99, 1);

        //If Change Change Payment Method Show Popup
        add_action('wp_ajax_wgb_check_rule_after_update_checkout', [$this, 'check_rule_after_update_checkout']);
        add_action('wp_ajax_nopriv_wgb_check_rule_after_checkout', [$this, 'check_rule_after_update_checkout']);
    }

    /**
     * Register the configured classic-checkout placement for the gift selector.
     */
    public function checkout_gifts_init()
    {
        $position = $this->settings['checkout_position'] ?? 'before_checkout_form';
        if ($position === 'none') {
            return;
        }

        switch ($position) {
            case 'after_checkout_billing_form':
                add_action('woocommerce_checkout_after_customer_details', [$this, 'display_gift_for_checkout'], 20);
                break;
            case 'custom_hook':
                if (!empty($this->settings['custom_hook_name'])) {
                    $priority = !empty($this->settings['custom_hook_priority']) ? absint($this->settings['custom_hook_priority']) : 10;
                    add_action($this->settings['custom_hook_name'], [$this, 'display_gift_for_checkout'], $priority);
                }
                break;
            case 'before_checkout_form':
            default:
                add_action('woocommerce_checkout_before_customer_details', [$this, 'display_gift_for_checkout'], 20);
                break;
        }
    }

    public function display_gift_for_checkout()
    {
        $layout = $this->settings['checkout_layout'] ?? 'carousel';
        echo do_shortcode('[itg_gift_products type="' . esc_attr($layout) . '"]');
    }

    private function getCheckRuleConditionData(): array
    {
        return [
            'cart_contents'           =>    itfreegift_get_cart_contents(),
            'gift_rule_exclude'       => [],
            'product_qty_in_cart'     => 0,
            'show_gift_item_for_cart' => [],
            'gift_item_variable'      => [],
        ];
    }

    public function check_session_gift_woocommerce_after_calculate_totals($cart = null)
    //public function check_session_gift($cart = null)
    {
        if ($this->rule_evaluation_in_progress || $this->stabilizing_cart_rules) {
            return;
        }

        $this->rule_evaluation_in_progress = true;
        try {
            // Reconcile rules and quantities before exposing the cart. Nested
            // calculations triggered by add_to_cart must not re-enter this loop.
            for ($pass = 0; $pass < 5; $pass++) {
                $before = $this->get_gift_cart_state_signature();
                $this->process_cart_rules_after_totals($cart);
                if ($before === $this->get_gift_cart_state_signature()) {
                    break;
                }
                $this->stabilize_cart_after_gift_mutation();
            }
        } finally {
            $this->rule_evaluation_in_progress = false;
        }
    }

    private function process_cart_rules_after_totals($cart = null)
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
                    Notice::add(itfreegift_get_localization('free_gift_removed', 'Your Free Gift(s) were removed because your current cart contents is not eligible for a free gift'), 'notice');
                }
            }
            itfreegift_unset_removed_automatic_free_gift_products_from_session();
            return;
        }

        $show_notice = false;
        if (!is_array($this->settings) || count($this->settings) <= 0) {
            $this->settings = itfreegift_get_settings();
        }

        /** Check session if time rule changed and remove automatically session **/
        $session_gift_products = itfreegift_get_removed_automatic_free_gift_products_from_session();
        if (itfreegift_check_is_array($session_gift_products) || isset($session_gift_products['time'])) {
            if ($this->check_rule_condition->getGiftItemVariable()['rule_time'] != $session_gift_products['time']) {
                itfreegift_unset_removed_automatic_free_gift_products_from_session();
            }
        }

        $count_info = itfreegift_check_quantity_gift_in_session(WC()->cart->get_cart());
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
                itfreegift_unset_removed_automatic_free_gift_products_from_session();
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
            Notice::add(itfreegift_get_localization('free_gift_removed', 'Your Free Gift(s) were removed because your current cart contents is not eligible for a free gift'), 'notice');
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
            if (!itfreegift_is_checkout_page() || $this->is_checkout_popup_allowed()) {
                add_action('wp_footer', array($this, 'layout_popup'));
            }
        }
    }

    /**
     * Settle Free-edition rule state before Store API or WooCommerce AJAX
     * serializes the cart. This does not add Pro auto-add behavior to Free.
     */
    public function synchronize_store_api_cart_after_add($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data)
    {
        if ($this->rule_evaluation_in_progress || $this->stabilizing_cart_rules || !function_exists('WC') || !WC() || !is_object(WC()->cart)) {
            return;
        }

        $is_store_api = isset(WC()->cart->cart_context) && WC()->cart->cart_context === 'store-api';
        $is_ajax = function_exists('wp_doing_ajax')
            ? wp_doing_ajax()
            : (defined('DOING_AJAX') && DOING_AJAX);
        if (!$is_store_api && !$is_ajax) {
            return;
        }

        if (isset($cart_item_data['it_free_gift'])) {
            return;
        }

        WC()->cart->calculate_totals();
    }

    public function synchronize_store_api_cart_response($errors, $cart)
    {
        if ($this->synchronizing_store_api_response || $this->rule_evaluation_in_progress || $this->stabilizing_cart_rules || !is_object($cart)) {
            return;
        }
        $this->synchronizing_store_api_response = true;
        try {
            $cart->calculate_totals();
        } finally {
            $this->synchronizing_store_api_response = false;
        }
    }

    private function stabilize_cart_after_gift_mutation()
    {
        if ($this->stabilizing_cart_rules || !function_exists('WC') || !WC() || !is_object(WC()->cart)) {
            return;
        }

        $this->stabilizing_cart_rules = true;
        try {
            WC()->cart->calculate_totals();
        } finally {
            $this->stabilizing_cart_rules = false;
        }
    }

    private function get_gift_cart_state_signature()
    {
        if (!function_exists('WC') || !WC() || !is_object(WC()->cart)) {
            return '';
        }

        $gift_state = array();
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            if (!isset($cart_item['it_free_gift'])) {
                continue;
            }

            $gift_state[$cart_item_key] = array(
                'quantity' => isset($cart_item['quantity']) ? (float) $cart_item['quantity'] : 0,
                'gift' => $cart_item['it_free_gift'],
            );
        }
        ksort($gift_state);

        return md5(wp_json_encode($gift_state));
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
        if (!itfreegift_is_cart_page() && !itfreegift_is_checkout_page()) {
            return;
        }

        if (itfreegift_is_checkout_page() && !$this->is_checkout_popup_allowed()) {
            return;
        }

        iThemeland_enqueue_css_js::enqueue_layout($this->settings['layout_popup'] ?? 'carousel', true);

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
                $template = 'datatable-layout.php';
                break;

            case 'grid':
                $template = 'grid-layout.php';
                break;

            case 'carousel':
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


        $atts = itfreegift_get_gift_products_data_multilevel($atts_rule);
        //echo '<pre>';print_r($atts_rule);die;

        $atts = apply_filters('itfreegift_args_data_gift', $atts);


        if (count($atts['items']) <= 0) {
            return;
        }

        iThemeland_enqueue_css_js::enqueue_layout($this->settings['layout']);

        if ($this->settings['show_description'] == 'true') {
            $description = '';
            foreach ($this->check_rule_condition->getShowGiftItemForCart()['rule_details'] as $itfreegift_rule_item_key => $rule) {
                if (strlen($this->check_rule_condition->getShowGiftItemForCart()['rule_details'][$itfreegift_rule_item_key]['description']) > 0) {
                    $description .= '<div>' . $this->check_rule_condition->getShowGiftItemForCart()['rule_details'][$itfreegift_rule_item_key]['description'] . '</div>';
                }
            }
            $atts['rule_description'] = $description;
        }

        itfreegift_get_template($template, $atts);
    }

    public function filter_shipping_methods($rates, $package)
    {
        // Shipping can run inside auto-add totals. Use a separate evaluator so
        // it cannot overwrite the rule snapshot currently reconciling gifts.
        $shipping_rules = new CheckRuleCondition($this->getCheckRuleConditionData());
        $shipping_rules->pw_get_gift_for_cart_checkout();
        $free_shipping_exists = $shipping_rules->free_shipping_exists();

        if (!itfreegift_check_is_array($free_shipping_exists)) {
            return $rates;
        }

        $unique_array = array_values(array_unique(array_merge(...array_values($free_shipping_exists))));

        foreach ($rates as $key => $rate) {
            $instance_id = itfreegift_get_rate_instance_id($rate);
            if (itfreegift_shipping_method_selected($instance_id, $unique_array)) {
                $rate->set_cost(0);
            }
        }
        return $rates;
    }

    public function display_gifts_click_notice_checkout_popup()
    {
        if (!itfreegift_is_checkout_page()) {
            return;
        }

        $gift_available = itfreegift_check_gift_available($this->check_rule_condition->getShowGiftItemForCart(), $this->check_rule_condition->getGiftItemVariable(), $this->check_rule_condition->getGiftRuleExclude());
        if (!is_array($gift_available['av_gifts']) || count($gift_available['av_gifts']) <= 0) {
            return;
        }

        $notice = '<span class="itg-checkout-notice"></span>' . itfreegift_get_localization('notice_checkout_message', false);

        $popup_link = sprintf('<a class="btn-select-gift-popup-button" href="">%s</a>', itfreegift_get_localization('notice_checkout_message_link_here', false));
        $notice        = str_replace('[popup_link]', $popup_link, $notice);

        $cart_page_url = sprintf('<a href="%s">%s</a>', wc_get_cart_url(), itfreegift_get_localization('notice_checkout_message_link_here', false));
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

        $atts = itfreegift_get_gift_products_data_multilevel($atts);
        itfreegift_get_template('dropdown-layout.php', $atts);
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
            $qty = max(1, absint(wp_unslash($_REQUEST['qty'])));  //phpcs:ignore


        if (!array_key_exists($gift, $this->check_rule_condition->getGiftItemVariable()['all_gifts'])) {
            wp_safe_redirect(get_permalink());
            exit();
        }
        //$retrieved_group_input_value = WC()->session->get('gift_group_order_data');
        //$count_info                 = itfreegift_check_quantity_gift_in_session($retrieved_group_input_value);

        $retrieved_group_input_value = WC()->cart->get_cart();

        $count_info = itfreegift_check_quantity_gift_in_session($retrieved_group_input_value);

        $uid        = $this->check_rule_condition->getGiftItemVariable()['all_gifts'][$gift]['uid'];
        $id_product = $this->check_rule_condition->getGiftItemVariable()['all_gifts'][$gift]['id_product'];

        $gift_lock = itfreegift_acquire_cart_gift_lock($uid);
        if ($gift_lock === '') {
            wp_safe_redirect(get_permalink());
            exit();
        }

        $this->check_rule_condition = new CheckRuleCondition($this->getCheckRuleConditionData());
        if (
            !$this->check_rule_condition->pw_get_gift_for_cart_checkout()
            || !isset($this->check_rule_condition->getGiftItemVariable()['all_gifts'][$gift])
        ) {
            wp_safe_redirect(get_permalink());
            exit();
        }

        $retrieved_group_input_value = WC()->cart->get_cart();
        $count_info = itfreegift_check_quantity_gift_in_session($retrieved_group_input_value);
        $uid = $this->check_rule_condition->getGiftItemVariable()['all_gifts'][$gift]['uid'];
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
        if (!itfreegift_is_product_available_for_gift($product)) {
            Notice::add(__('This gift is currently unavailable.', 'ithemeland-free-gifts-for-woo'), 'error');
            wp_safe_redirect(apply_filters('itfreegift_redirect_link', get_permalink()));
            exit();
        }
        $itfreegift_pr_price = $product->get_price();
        if (in_array($this->check_rule_condition->getGiftItemVariable()[$uid]['method'], array('simplea'), true)) {
            if ($itfreegift_pr_price == '') {
                $itfreegift_pr_price = 0;
            }
            if ($this->check_rule_condition->getGiftItemVariable()['all_gifts'][$gift]['value'] < ($count_info['subtotal_price'] + $itfreegift_pr_price)) {
                wp_safe_redirect(get_permalink());
                exit();
            }
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
                'base_price'      => $itfreegift_pr_price,
                'time_add'   => $this->check_rule_condition->getGiftItemVariable()['rule_time']
            ),
        );

        $cart_item_data = apply_filters('itfreegift_array_addtocart', $cart_item_data);

        $cart_item_key = WC()->cart->add_to_cart($id_product, $qty, 0, array(), $cart_item_data);

        if (!$cart_item_key) {
            Notice::add(__('This gift could not be added to the cart.', 'ithemeland-free-gifts-for-woo'), 'error');
            wp_safe_redirect(apply_filters('itfreegift_redirect_link', get_permalink()));
            exit();
        }

        Notice::add(itfreegift_get_localization('free_gift_added', 'Gift product added successfully'), 'success');

        wp_safe_redirect(apply_filters('itfreegift_redirect_link', get_permalink()));

        //wp_safe_redirect(get_permalink());
        exit();
    }


    public function itg_ajax_add_free_gifts()
    {
        // Add error logging for debugging

        $gift_lock = '';

        // Check nonce with better error handling
        if (!isset($_REQUEST['itg_security']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['itg_security'])), 'jkhKJSdd4576d234Z')) {
            wp_send_json_error(array('error' => 'Security check failed'));
            return;
        }

        try {
            if (!isset($_POST) && !isset($_REQUEST)) {
                throw new exception(__('Cannot process action1', 'ithemeland-free-gifts-for-woo'));
            }

            $itfreegift_gift_product_id = (isset($_REQUEST['gift_product_id'])) ? sanitize_text_field(wp_unslash($_REQUEST['gift_product_id'])) : 0;
            if (empty($itfreegift_gift_product_id)) {
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
                $qty = max(1, absint(wp_unslash($_REQUEST['add_qty'])));
            }


            //wp_send_json_success(array( 'reload' => $this->check_rule_condition->getGiftItemVariable(),'gift_product_id' => $itfreegift_gift_product_id ));			

            if (!array_key_exists($itfreegift_gift_product_id, $this->check_rule_condition->getGiftItemVariable()['all_gifts'])) {
                throw new exception(__('Cannot process action6', 'ithemeland-free-gifts-for-woo'));
            }

            $initial_uid = $this->check_rule_condition->getGiftItemVariable()['all_gifts'][$itfreegift_gift_product_id]['uid'];
            $gift_lock = itfreegift_acquire_cart_gift_lock($initial_uid);
            if ($gift_lock === '') {
                throw new exception(__('Another gift request is already being processed. Please try again.', 'ithemeland-free-gifts-for-woo'));
            }

            // Re-evaluate after acquiring the lock. A concurrent request may
            // have changed the cart between the initial render and this call.
            $this->check_rule_condition = new CheckRuleCondition($this->getCheckRuleConditionData());
            if (
                !$this->check_rule_condition->pw_get_gift_for_cart_checkout()
                || !isset($this->check_rule_condition->getGiftItemVariable()['all_gifts'][$itfreegift_gift_product_id])
            ) {
                throw new exception(__('This gift is no longer available.', 'ithemeland-free-gifts-for-woo'));
            }

            $retrieved_group_input_value = WC()->cart->get_cart();
            $count_info = itfreegift_check_quantity_gift_in_session($retrieved_group_input_value);
            $uid        = $this->check_rule_condition->getGiftItemVariable()['all_gifts'][$itfreegift_gift_product_id]['uid'];
            $id_product = $this->check_rule_condition->getGiftItemVariable()['all_gifts'][$itfreegift_gift_product_id]['id_product'];
            //Number Allow For Simple Method
            $pw_number_gift_allowed = $this->check_rule_condition->getGiftItemVariable()[$uid]['pw_number_gift_allowed'];
            //Number Allow For Other Method
            if (in_array($this->check_rule_condition->getGiftItemVariable()[$uid]['method'], array(
                'buy_x_get_x_repeat'
            ), true) && $this->check_rule_condition->getGiftItemVariable()['all_gifts'][$itfreegift_gift_product_id]['base_q'] == 'ind') {
                $pw_number_gift_allowed = $this->check_rule_condition->getGiftItemVariable()['all_gifts'][$itfreegift_gift_product_id]['q'];
            }

            $individual_repeat = in_array($this->check_rule_condition->getGiftItemVariable()[$uid]['method'], array('buy_x_get_x_repeat'), true)
                && $this->check_rule_condition->getGiftItemVariable()[$uid]['based_on'] == 'ind';
            if (!$individual_repeat) {
                $current_rule_quantity = isset($count_info['count_rule_gift'][$uid]['q'])
                    ? (int) $count_info['count_rule_gift'][$uid]['q']
                    : 0;
                $qty = min($qty, max(0, (int) $pw_number_gift_allowed - $current_rule_quantity));
            }

            if (isset($count_info['count_rule_product'][$itfreegift_gift_product_id]) && $count_info['count_rule_product'][$itfreegift_gift_product_id]['q'] >= $pw_number_gift_allowed) {
                throw new exception(__('Cannot process action8', 'ithemeland-free-gifts-for-woo'));
            }

            if (in_array($itfreegift_gift_product_id, $count_info['gifts_set']) && $this->check_rule_condition->getGiftItemVariable()[$uid]['can_several_gift'] == 'no') {
                throw new exception(__('Cannot process action9', 'ithemeland-free-gifts-for-woo'));
            }
            //Check Qty For Add bu user
            if ($qty > $pw_number_gift_allowed) {
                $qty = $pw_number_gift_allowed;
            }

            $count_selected = 0;
            if (isset($count_info['count_rule_product'][$itfreegift_gift_product_id])) {
                $count_selected = $count_info['count_rule_product'][$itfreegift_gift_product_id]['q'];
            }
            $result = $pw_number_gift_allowed - $count_selected;

            if ($qty > $result) {
                $qty = $result;
            }

            if ($qty <= 0) {
                throw new exception(__('The maximum number of gifts for this rule is already in the cart.', 'ithemeland-free-gifts-for-woo'));
            }

            if ($qty > 1 && $this->check_rule_condition->getGiftItemVariable()[$uid]['can_several_gift'] == 'no') {
                $qty = 1;
            }

            $product = wc_get_product($id_product);
            if (!itfreegift_is_product_available_for_gift($product)) {
                throw new \Exception(__('This gift is currently unavailable.', 'ithemeland-free-gifts-for-woo'));
            }
            $itfreegift_pr_price = $product->get_price();
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
                    'rule_gift_key'    => $itfreegift_gift_product_id,
                    'product_id' => $id_product,
                    'price'      => $_price_for_gift,
                    'base_price'      => $itfreegift_pr_price,
                    'time_add'   => $this->check_rule_condition->getGiftItemVariable()['rule_time']
                ),
            );
            $cart_item_data = apply_filters('itfreegift_array_addtocart', $cart_item_data);

            // Add to cart with error handling
            $notices_before_add = function_exists('wc_get_notices') ? wc_get_notices() : [];
            $cart_item_key = WC()->cart->add_to_cart($id_product, $qty, 0, array(), $cart_item_data);

            if (!$cart_item_key) {
                itfreegift_restore_notices($notices_before_add);
                itfreegift_release_cart_gift_lock($gift_lock);
                $gift_lock = '';
                throw new exception(__('Failed to add gift to cart', 'ithemeland-free-gifts-for-woo'));
            }

            // Create notice
            ob_start();
            Notice::print(itfreegift_get_localization('free_gift_added', 'Gift product added successfully'), 'success');
            $notice = ob_get_clean();

            // Send success response
            $response = array(
                'ok' => 1,
                'qty_add' => $qty,
                'notice' => $notice,
                'cart_item_key' => $cart_item_key
            );

            wp_send_json_success($response);
        } catch (Exception $ex) {
            itfreegift_release_cart_gift_lock($gift_lock);
            wp_send_json_error(array('error' => $ex->getMessage()));
        }
    }

    public function pw_gift_show_popup_checkout_function()
    {
        if (!isset($_REQUEST['security']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['security'])), 'jkhKJSdd4576d234Z')) {
            wp_send_json_error(array('error' => 'Nance'));
        }

        if ($this->is_disabled_checkout_popup_request()) {
            wp_send_json_error(array('error' => 'Checkout gift popup is disabled'));
        }

        // global $woocommerce;
        if (!($this->gift_item_key = $this->check_rule_condition->pw_get_gift_for_cart_checkout())) {
            wp_send_json_error(array('error' => 'item is not available'));
        }
        $settings = $this->get_context_settings();
        $atts_rule = [
            'gift_rule_exclude' => $this->check_rule_condition->getGiftRuleExclude(),
            'quantity_products_in_cart' => $this->check_rule_condition->getProductQtyInCart(),
            'gifts_items_cart' => $this->check_rule_condition->getShowGiftItemForCart(),
            'all_gift_items' => $this->check_rule_condition->getGiftItemVariable(),
            'settings'  => $settings,
            'multi_level' => false,
            'is_child'  => true,
        ];

        $atts = itfreegift_get_gift_products_data_multilevel($atts_rule);

        if (count($atts['items']) <= 0) {
            wp_send_json_error(array('error' => 'item is not available'));
        }
        $items = $atts['items'];
        $layout = itfreegift_get_active_layout_popup_items($this->settings['layout_popup']);

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

    private function is_checkout_popup_allowed()
    {
        return ($this->settings['checkout_position'] ?? 'none') !== 'none';
    }

    private function get_context_settings()
    {
        $settings = $this->settings;
        $request_context = isset($_REQUEST['page_context']) ? sanitize_key(wp_unslash($_REQUEST['page_context'])) : ''; //phpcs:ignore
        if (itfreegift_is_checkout_page() || $request_context === 'checkout') {
            $settings['number_per_page'] = $settings['checkout_number_per_page'] ?? $settings['number_per_page'];
            $settings['show_stock_quantity'] = $settings['checkout_show_stock_quantity'] ?? $settings['show_stock_quantity'];
            $settings['carousel'] = $settings['checkout_carousel'] ?? $settings['carousel'];
        }

        return $settings;
    }

    /**
     * Detect checkout popup AJAX calls, including calls made by stale cached JS.
     */
    private function is_disabled_checkout_popup_request()
    {
        if ($this->is_checkout_popup_allowed()) {
            return false;
        }

        $context = isset($_REQUEST['page_context']) ? sanitize_key(wp_unslash($_REQUEST['page_context'])) : ''; //phpcs:ignore
        if ($context === 'checkout') {
            return true;
        }

        $referer = wp_get_referer();
        $checkout_url = function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : '';
        if (!$referer || !$checkout_url) {
            return false;
        }

        $referer_path = untrailingslashit((string) wp_parse_url($referer, PHP_URL_PATH));
        $checkout_path = untrailingslashit((string) wp_parse_url($checkout_url, PHP_URL_PATH));

        return $checkout_path !== '' && ($referer_path === $checkout_path || strpos($referer_path, $checkout_path . '/') === 0);
    }

    public function reload_item_popup()
    {
        check_ajax_referer('jkhKJSdd4576d234Z', 'itg_security');
        //try {
        if (!($this->gift_item_key = $this->check_rule_condition->pw_get_gift_for_cart_checkout())) {
            wp_send_json_error(array('error' => 'Error !'));
        }
        $settings = $this->get_context_settings();
        $atts_rule = [
            'gift_rule_exclude'         => $this->check_rule_condition->getGiftRuleExclude(),
            'quantity_products_in_cart' => $this->check_rule_condition->getProductQtyInCart(),
            'gifts_items_cart'          => $this->check_rule_condition->getShowGiftItemForCart(),
            'all_gift_items'            => $this->check_rule_condition->getGiftItemVariable(),
            'settings'                  => $settings,
            'multi_level'               => false,
            'is_child'                  => true,
        ];

        $atts = itfreegift_get_gift_products_data_multilevel($atts_rule);

        $items = $atts['items'];
        $flag = true;
        $html = 'unselectable';
        foreach ($items as $key => $itfreegift_gift_product) {
            if (!$itfreegift_gift_product['hide_add_to_cart']) {
                $flag = true;
                break;
            }
            $flag = false;
        }

        if ($flag) {
            $layout = itfreegift_get_active_layout_popup_items($this->settings['layout_popup']);
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
			itfreegift_get_template($template, $atts , 'modal/');
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

        iThemeland_enqueue_css_js::enqueue_core();
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
                'settings'            => $this->get_context_settings(),
                'view'                => 'modal',
            ];

            itfreegift_get_template('modal/variations.php', $atts);

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
}

new iThemeland_front_order();
