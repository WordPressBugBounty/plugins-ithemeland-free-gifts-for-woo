<?php

namespace ITFreeGift\frontend\classes\services\apply_rule;

use ITFreeGift\classes\repositories\Rule;
use ITFreeGift\frontend\classes\services\apply_rule\ApplyRuleService;
use ITFreeGift\frontend\classes\services\apply_rule\CartHandlerService;
use ITFreeGift\frontend\classes\services\apply_rule\CheckConditionsBuyProduct;
use ITFreeGift\frontend\classes\services\apply_rule\helpers\RulesDataBridge;

if (!defined('ABSPATH')) {
    exit;
}

class CheckRuleCondition
{
    private static $request_evaluation_cache = [];
    private $all_rules_loaded = false;
    private $all_rules_cache = false;

    protected $item_cart;
    protected $show_gift_item_for_cart = [];
    protected $product_qty_in_cart;
    protected $gift_item_variable = [];
    protected $gift_rule_exclude = [];
    protected $pw_gifts_cache_simple_variation;
    protected $pw_gifts_cache_simple_childes;
    protected $free_shipping_exists;
    protected $filter_items_by_rules = [];
    protected $data;

    public function __construct(array $data = [])
    {
        $this->item_cart = [];
        $this->data = $data;

        $this->initializeProperties($data);
    }

    /**
     * Initialize class properties using the provided data array
     */
    protected function initializeProperties(array $data): void
    {
        $this->gift_rule_exclude = $data['gift_rule_exclude'] ?? [];
        $this->show_gift_item_for_cart = $data['show_gift_item_for_cart'] ?? [];
        $this->product_qty_in_cart = $data['product_qty_in_cart'] ?? 0;
        $this->gift_item_variable = $data['gift_item_variable'] ?? [];
        $this->pw_gifts_cache_simple_variation = $data['pw_gifts_cache_simple_variation'] ?? null;
        $this->pw_gifts_cache_simple_childes = $data['pw_gifts_cache_simple_childes'] ?? null;
        $this->free_shipping_exists = $data['free_shipping_exists'] ?? [];
        $this->filter_items_by_rules = $data['filter_items_by_rules'] ?? [];
    }

    public function getGiftRuleExclude()
    {
        return $this->gift_rule_exclude;
    }

    public function getProductQtyInCart()
    {
        return $this->product_qty_in_cart;
    }

    public function getShowGiftItemForCart()
    {
        return $this->show_gift_item_for_cart;
    }

    public function getGiftItemVariable()
    {
        return $this->gift_item_variable;
    }

    private function get_condition_type_methods()
    {
        return [
            'date' => 'date_time',
            'time' => 'date_time',
            'date_time' => 'date_time',
            'days_of_week' => 'date_time',
        ];
    }

    public function pw_get_gift_for_cart_checkout()
    {
        $this->item_cart = CartHandlerService::get_cart_contents();

        if (!$this->validate_cart()) return false;

        $rules = $this->get_all_rules();
        if (!$rules) return false;
        if (!is_array($rules)) return false;

        $fingerprint = $this->build_evaluation_fingerprint($rules);
        $memoization_enabled = (bool) apply_filters('itfreegift_enable_rule_evaluation_memoization', true, $fingerprint, $rules);
        if ($memoization_enabled && array_key_exists($fingerprint, self::$request_evaluation_cache)) {
            return $this->restore_evaluation_snapshot(self::$request_evaluation_cache[$fingerprint]);
        }

        $cart_subtotal = $this->get_cart_subtotal();
        $this->product_qty_in_cart = $this->get_cart_item_stock_quantities();

        $check_rules_condition = $this->filter_applicable_rules($rules);
        if (empty($check_rules_condition)) {
            return $memoization_enabled ? $this->cache_evaluation_snapshot($fingerprint, false, null) : false;
        }

        $result = $this->apply_rules($check_rules_condition, $cart_subtotal);
        $applicable_rules = is_array(WC()->session ? WC()->session->get('itg_free_gift_current_applicable_rules', []) : null)
            ? WC()->session->get('itg_free_gift_current_applicable_rules', [])
            : [];
        return $memoization_enabled ? $this->cache_evaluation_snapshot($fingerprint, $result, $applicable_rules) : $result;
    }

    private function build_evaluation_fingerprint(array $rules)
    {
        $cart_state = [];
        foreach ($this->item_cart as $cart_item_key => $cart_item) {
            $product = (!empty($cart_item['data']) && $cart_item['data'] instanceof \WC_Product) ? $cart_item['data'] : null;
            $cart_state[] = [
                'key' => (string) $cart_item_key,
                'product_id' => (int) ($cart_item['product_id'] ?? 0),
                'variation_id' => (int) ($cart_item['variation_id'] ?? 0),
                'quantity' => (float) ($cart_item['quantity'] ?? 0),
                'line_subtotal' => (string) ($cart_item['line_subtotal'] ?? ''),
                'line_subtotal_tax' => (string) ($cart_item['line_subtotal_tax'] ?? ''),
                'line_total' => (string) ($cart_item['line_total'] ?? ''),
                'line_tax' => (string) ($cart_item['line_tax'] ?? ''),
                'price' => $product ? (string) $product->get_price() : '',
                'gift' => $cart_item['it_free_gift'] ?? null,
            ];
        }

        $gift_cart_state = [];
        if (function_exists('WC') && WC()->cart) {
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                if (empty($cart_item['it_free_gift'])) {
                    continue;
                }
                $gift_cart_state[] = [
                    'key' => (string) $cart_item_key,
                    'product_id' => (int) ($cart_item['product_id'] ?? 0),
                    'variation_id' => (int) ($cart_item['variation_id'] ?? 0),
                    'quantity' => (float) ($cart_item['quantity'] ?? 0),
                    'gift' => $cart_item['it_free_gift'],
                ];
            }
        }

        $customer = function_exists('WC') ? WC()->customer : null;
        $customer_state = [];
        foreach (['get_billing_country', 'get_billing_state', 'get_billing_postcode', 'get_billing_city', 'get_shipping_country', 'get_shipping_state', 'get_shipping_postcode', 'get_shipping_city'] as $getter) {
            $customer_state[$getter] = ($customer && is_callable([$customer, $getter])) ? (string) $customer->{$getter}() : '';
        }

        $user = wp_get_current_user();
        $session = function_exists('WC') ? WC()->session : null;
        $checkout_state = [];
        if (!empty($_POST['post_data']) && is_string($_POST['post_data'])) { // phpcs:ignore
            parse_str(wp_unslash($_POST['post_data']), $posted_checkout); // phpcs:ignore
            foreach ($posted_checkout as $key => $value) {
                if (strpos($key, 'billing_') === 0 || strpos($key, 'shipping_') === 0 || in_array($key, ['payment_method', 'shipping_method'], true)) {
                    $checkout_state[$key] = is_array($value) ? array_map('sanitize_text_field', $value) : sanitize_text_field($value);
                }
            }
        }
        if (function_exists('itfreegift_get_checkout_billing_email')) {
            $checkout_state['resolved_billing_email'] = (string) itfreegift_get_checkout_billing_email();
        }
        $language = function_exists('determine_locale') ? determine_locale() : get_locale();
        if (defined('WCML_VERSION')) {
            $language = (string) apply_filters('wpml_current_language', $language); //phpcs:ignore
        } elseif (defined('POLYLANG') && function_exists('pll_current_language')) {
            $language = (string) pll_current_language();
        }

        $state = [
            'cart' => $cart_state,
            'gift_cart' => $gift_cart_state,
            'coupons' => (function_exists('WC') && WC()->cart) ? array_values(WC()->cart->get_applied_coupons()) : [],
            'cart_total' => (function_exists('WC') && WC()->cart) ? (string) WC()->cart->total : '',
            'customer_id' => (int) $user->ID,
            'roles' => array_values((array) $user->roles),
            'customer' => $customer_state,
            'checkout' => $checkout_state,
            'shipping_methods' => $session ? (array) $session->get('chosen_shipping_methods', []) : [],
            'payment_method' => $session ? (string) $session->get('chosen_payment_method', '') : '',
            'language' => $language,
            'rules' => md5(maybe_serialize($rules)),
            // Date/time rules must not reuse a result after the current second changes.
            'time' => time(),
        ];

        return md5(maybe_serialize($state));
    }

    private function cache_evaluation_snapshot($fingerprint, $result, $applicable_rules)
    {
        self::$request_evaluation_cache[$fingerprint] = [
            'result' => $result,
            'show_gift_item_for_cart' => $this->show_gift_item_for_cart,
            'product_qty_in_cart' => $this->product_qty_in_cart,
            'gift_item_variable' => $this->gift_item_variable,
            'gift_rule_exclude' => $this->gift_rule_exclude,
            'free_shipping_exists' => $this->free_shipping_exists,
            'filter_items_by_rules' => $this->filter_items_by_rules,
            'applicable_rules' => $applicable_rules,
        ];
        return $result;
    }

    private function restore_evaluation_snapshot(array $snapshot)
    {
        $this->show_gift_item_for_cart = $snapshot['show_gift_item_for_cart'];
        $this->product_qty_in_cart = $snapshot['product_qty_in_cart'];
        $this->gift_item_variable = $snapshot['gift_item_variable'];
        $this->gift_rule_exclude = $snapshot['gift_rule_exclude'];
        $this->free_shipping_exists = $snapshot['free_shipping_exists'];
        $this->filter_items_by_rules = $snapshot['filter_items_by_rules'];
        if (is_array($snapshot['applicable_rules'])) {
            $this->sync_applicable_rules_session($snapshot['applicable_rules']);
        }
        return $snapshot['result'];
    }

    private function validate_cart()
    {
        return $this->item_cart && is_array($this->item_cart) && count($this->item_cart) > 0;
    }

    private function get_cart_subtotal()
    {
        return itfreegift_get_cart_subtotal($this->item_cart);
    }

    private function get_cart_item_stock_quantities()
    {
        return itfreegift_get_cart_item_stock_quantities($this->item_cart);
    }

    private function filter_applicable_rules($rules)
    {
        $check_rules_condition = [];
        foreach ($rules['items'] as $rule_values) {
            if (!$this->is_rule_applicable($rule_values)) continue;
            $check_rules_condition[] = $rule_values;
        }

        return $check_rules_condition;
    }

    private function is_rule_applicable($rule_values)
    {
        // Validate required fields
        if (!isset($rule_values['status']) || !isset($rule_values['uid']) || !isset($rule_values['method'])) {
            return false;
        }

        if (!$this->status_check($rule_values['status'], $rule_values)) return false;

        $this->check_language($rule_values);

        return $this->check_conditions($rule_values);
    }

    private function check_language($rule_values)
    {
        if (isset($rule_values['language']) && $rule_values['language'] != 'all') {
            $this->is_playlang_current_language($rule_values['language']);
            $this->is_wpml_current_language($rule_values['language']);
        }
    }

    private function check_conditions($rule_values)
    {
        if (empty($rule_values['condition']) || !is_array($rule_values['condition'])) return true;

        $condition_methods = $this->get_condition_type_methods();

        foreach ($rule_values['condition'] as $condition) {
            if (empty($condition['type']) || empty($condition_methods[$condition['type']])) continue;

            $method = $condition_methods[$condition['type']];

            if (is_array($method)) {
                // Handle array method references using loop
                $result = true;
                foreach ($method as $value) {
                    if (is_string($value) && method_exists($value, $condition['type'])) {
                        $result = $value::{$condition['type']}($condition, $rule_values);
                        if (!$result) break;
                    }
                }
                if (!$result) return false;
            } else {
                // Handle string method references
                if (!method_exists($this, $method)) continue;
                if (!$this->{$method}($condition, $rule_values)) return false;
            }
        }
        return true;
    }

    private function apply_rules($check_rules_condition, $cart_subtotal)
    {
        $current_session_gift_rules = [];

        foreach ($check_rules_condition as $rule_value) {
            $this->set_gift_cache($rule_value);
            $filter_items_by_rules = $this->apply_buy_section($rule_value);

            $rules_bridge = $this->create_rules_bridge($rule_value, $filter_items_by_rules, $cart_subtotal);
            ApplyRuleService::applyRuleMethod($rules_bridge);

            $current_session_gift_rules[$rule_value['uid']] = [$rule_value['uid']];
        }

        return $this->finalize_gift_application($current_session_gift_rules);
    }

    private function set_gift_cache($rule_value)
    {
        $return_query = $this->get_gift_rules_cache($rule_value);
        $this->pw_gifts_cache_simple_variation = $return_query['pw_gifts_cache_simple_variation_'];
        $this->pw_gifts_cache_simple_childes = $return_query['pw_gifts_cache_simple_childes_'];
    }

    private function apply_buy_section($rule_value)
    {
        $filter_items_by_rules = [];
        if (in_array($rule_value['method'], [
            'bulk_pricing',
            'bulk_quantity',
            'tiered_quantity',
            'buy_x_get_x',
            'buy_x_get_x_repeat',
            'buy_x_get_y',
            'buy_x_get_y_repeat',
            'cheapest_item_in_cart',
            'free_shipping'
        ])) {
            $filter_items_by_rules = $this->filter_items_by_buy_conditions($rule_value);
        }
        return $filter_items_by_rules;
    }

    private function filter_items_by_buy_conditions($rule_value)
    {
        $conditions = self::get_product_buy_conditions($rule_value);
        $filter_items_by_rules = [];

        foreach ($this->item_cart as $cart_item_key => $cart_item) {
            $flag = true;
            foreach ($conditions as $condition) {
                $flag = CheckConditionsBuyProduct::process_condition($condition, $cart_item);
                if (!$flag) break;
            }
            if ($flag) $filter_items_by_rules[$cart_item_key] = $cart_item;
        }
        return $filter_items_by_rules;
    }

    private function create_rules_bridge($rule_value, $filter_items_by_rules, $cart_subtotal)
    {
        $quantities_based_on = isset($rule_value['quantities_based_on']) ? $rule_value['quantities_based_on'] : 'products';

        // For cheapest_item_in_cart method, pass null to let CheapestItemInCart class handle it
        $cheapest_item_id = ($rule_value['method'] === 'cheapest_item_in_cart')
            ? null
            : $this->get_cheapest_item_id($filter_items_by_rules, $rule_value);

        return new RulesDataBridge(
            $this->pw_gifts_cache_simple_childes,
            $this->pw_gifts_cache_simple_variation,
            $this->gift_item_variable,
            $this->show_gift_item_for_cart,
            $this->item_cart,
            $this->free_shipping_exists,
            $rule_value,
            itfreegift_group_quantities($filter_items_by_rules, $quantities_based_on),
            itfreegift_group_prices($filter_items_by_rules, $quantities_based_on),
            $cheapest_item_id,
            $cart_subtotal['subtotal']
        );
    }

    private function get_cheapest_item_id($filter_items_by_rules, $rule_value = [])
    {
        $min_item = PHP_FLOAT_MAX;
        $cheapest_item_id = null;
        $price_type = isset($rule_value['quantity']['price_type']) ? $rule_value['quantity']['price_type'] : 'cart_price';

        foreach ($filter_items_by_rules as $cart_item) {
            $item_price = $this->get_item_price_by_type($cart_item, $price_type);
            if ($item_price < $min_item) {
                $min_item = $item_price;
                $cheapest_item_id = $cart_item['data']->get_id();
            }
        }
        return $cheapest_item_id;
    }

    private function get_item_price_by_type($cart_item, $price_type)
    {
        $product = $cart_item['data'];

        switch ($price_type) {
            case 'subtotal_price':
                // Sub-total price (total price for all quantities of this item)
                return (float)$cart_item['line_subtotal'];
            case 'regular_price':
                // Regular price of the product
                return $product->get_regular_price();
            case 'sale_price':
                // Sale price of the product (if on sale, otherwise regular price)
                $sale_price = $product->get_sale_price();
                return $sale_price ? $sale_price : $product->get_regular_price();
            case 'cart_price':
            default:
                // Current price in cart (default behavior)
                return $product->get_price();
        }
    }

    private function finalize_gift_application($current_session_gift_rules)
    {
        $rules = $this->get_all_rules();
        $rules_time = isset($rules['time']) ? $rules['time'] : 0;
        if (is_array($this->gift_item_variable) && (count($this->gift_item_variable) > 0 || sizeof($this->gift_item_variable) > 0)) {

            $this->gift_item_variable['rule_time'] = $rules_time;
            $this->sync_applicable_rules_session($current_session_gift_rules);
            return $this->gift_item_variable;
        }
        // if (!empty($this->gift_item_variable)) {
        //     $this->gift_item_variable['rule_time'] = gmdate('Y-m-d H:i:s');
        //     WC()->session->set('itg_free_gift_current_applicable_rules', $current_session_gift_rules);
        //     return $this->gift_item_variable;
        // }
        $this->sync_applicable_rules_session([]);
        itfreegift_unset_removed_automatic_free_gift_products_from_session();
        return false;
    }

    private function sync_applicable_rules_session(array $current_session_gift_rules)
    {
        if (!WC()->session) {
            return;
        }

        $session_key = 'itg_free_gift_current_applicable_rules';
        $stored_rules = WC()->session->get($session_key, []);
        $stored_rules = is_array($stored_rules) ? $stored_rules : [];

        if ($stored_rules === $current_session_gift_rules) {
            return;
        }

        if (empty($current_session_gift_rules)) {
            WC()->session->__unset($session_key);
            return;
        }

        WC()->session->set($session_key, $current_session_gift_rules);
    }

    private function get_product_buy_conditions($rule_value)
    {
        return isset($rule_value['product_buy']) ? $rule_value['product_buy'] : [];
    }

    public function is_playlang_current_language($language)
    {
        if (defined('POLYLANG')) {
            $current_lang = pll_current_language(); // Get current language code

            // Return true if language matches or if 'all' is passed
            return $language === 'all' || $language === $current_lang;
        }

        return; // Return false if Polylang is not active
    }

    public function is_wpml_current_language($language)
    {
        if (defined('WCML_VERSION')) {
            $current_lang = apply_filters('wpml_current_language', null); // phpcs:ignore

            // Return true if language matches or if 'all' is passed
            return $language === 'all' || $language === $current_lang;
        }

        return; // Return false if WPML is not active
    }

    public function free_shipping_exists()
    {
        if (isset($this->free_shipping_exists)) {
            return $this->free_shipping_exists;
        }

        $this->pw_get_gift_for_cart_checkout();

        return $this->free_shipping_exists;
    }

    public function get_all_rules()
    {
        if ($this->all_rules_loaded) {
            return $this->all_rules_cache;
        }

        $this->all_rules_loaded = true;
        $get_instance_rules = Rule::get_instance();
        $rules = $get_instance_rules->get();
        //Check Conditions
        if (!isset($rules['items']) || !is_array($rules['items']) || count($rules['items']) <= 0) {
            $this->all_rules_cache = false;
            return $this->all_rules_cache;
        }
        $this->all_rules_cache = $rules;
        return $this->all_rules_cache;
    }

    public function get_gift_rules_cache($rule_value)
    {
        $get_instance_rules = Rule::get_instance();
        $return_query = $get_instance_rules->get_option_cache($rule_value);
        return $return_query;
    }

    public function date_time($condition, $rule_values)
    {
        switch ($condition['type']) {
            case 'date':
                $value           = itfreegift_it_date_time();
                $condition_value = $condition['value'];
                $condition_value = itfreegift_get_datetime_object($condition_value, false);
                $condition_value->setTime(0, 0, 0);
                break;
            case 'time':
            case 'date_time':
                $value           = itfreegift_date_time_time();
                $condition_value = $condition['value'];
                $condition_value = itfreegift_get_datetime_object($condition_value, false);
                break;
            case 'days_of_week':
                $value           = itfreegift_date_time_weekend();
                $condition_value = $condition['value'];

                return itfreegift_check_simple_operations($condition['method_option'], $value, $condition_value);
                break;

            default:
                return true;
        }

        return itfreegift_check_datetime_operations($condition['method_option'], $value, $condition_value);
    }

    public function status_check($status, $rule_values)
    {
        if ($status == 'disable') {
            return false;
        } else if ($status == 'other_applied') {

            $gifts_in_cart = itfreegift_get_cart_gift_contents();

            foreach ($gifts_in_cart as $gift_item_key => $gift) {
                if ($gift['it_free_gift']['rule_id'] == $rule_values['uid']) {
                    continue;
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    public function handle_load_rules()
    {
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1; //phpcs:ignore
        $per_page = isset($_GET['per_page']) ? intval($_GET['per_page']) : 10; //phpcs:ignore

        $result = $this->pw_get_gift_for_cart_checkout($page, $per_page);

        wp_send_json($result);
    }
}
