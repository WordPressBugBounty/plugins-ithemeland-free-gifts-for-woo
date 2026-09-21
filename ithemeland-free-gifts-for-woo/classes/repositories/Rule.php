<?php

namespace ITFreeGift\classes\repositories;

defined('ABSPATH') || exit(); // Exit if accessed directly

use ITFreeGift\classes\helpers\Sanitizer;

class Rule
{
    const ELIGIBILITY_CACHE_GROUP = 'itfreegift_eligibility';
    const ELIGIBILITY_CACHE_TTL = 43200;
    const ELIGIBILITY_CACHE_SCHEMA = 3;

    private static $instance;
    private static $invalidation_hooks_registered = false;

    private $option_name;
    private $wpdb;
    private $request_option_cache = [];

    public static function register_cache_invalidation_hooks()
    {
        if (self::$invalidation_hooks_registered) {
            return;
        }
        self::$invalidation_hooks_registered = true;

        add_action('save_post_product', [__CLASS__, 'invalidate_for_product_change'], 10, 3);
        add_action('save_post_product_variation', [__CLASS__, 'invalidate_for_product_change'], 10, 3);
        add_action('deleted_post', [__CLASS__, 'invalidate_for_deleted_post'], 10, 2);
        add_action('set_object_terms', [__CLASS__, 'invalidate_for_term_change'], 10, 6);
    }

    public static function invalidate_for_product_change($post_id, $post = null, $update = false)
    {
        if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
            return;
        }
        self::bump_eligibility_cache_version();
    }

    public static function invalidate_for_deleted_post($post_id, $post = null)
    {
        $post_type = is_object($post) ? $post->post_type : get_post_type($post_id);
        if (in_array($post_type, ['product', 'product_variation'], true)) {
            self::bump_eligibility_cache_version();
        }
    }

    public static function invalidate_for_term_change($object_id, $terms, $tt_ids, $taxonomy, $append, $old_tt_ids)
    {
        if (strpos((string) $taxonomy, 'product_') === 0 || in_array($taxonomy, ['product_cat', 'product_tag'], true)) {
            self::bump_eligibility_cache_version();
        }
    }

    private static function bump_eligibility_cache_version()
    {
        $version = (int) get_option('itfreegift_eligibility_cache_version', 1);
        update_option('itfreegift_eligibility_cache_version', $version + 1, false);
    }

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;

        $this->option_name = "wgb_rules";
    }

    public function update($rules)
    {
        if (!empty($rules['option_values'])) {
            $this->update_option_values(Sanitizer::array($rules['option_values']));
        }

        // Clear IDs removed by this update as well as the newly saved IDs.
        $this->invalidate_option_cache($this->get());
        $updated = update_option($this->option_name, $rules);
        $this->invalidate_option_cache($rules);
        self::bump_eligibility_cache_version();
        return $updated;
    }

    public function get()
    {
        $validated_rules = get_option($this->option_name);
        $validated_rules = apply_filters('itfreegift_validated_rules_filtered', $validated_rules);
        return $validated_rules;
    }

    public function maybe_sync()
    {
        $old_rules = get_option('wgbl_rules', []);
        if (!empty($old_rules['items'])) {
            $rules['items'] = $old_rules['items'];
            $new_rules = get_option($this->option_name, []);
            if (!empty($new_rules['items'])) {
                $rules['items'] = array_merge($rules['items'], $new_rules['items']);
            }

            $rules['option_values'] = [];
            if (!empty($old_rules['option_values'])) {
                $rules['option_values'] = $old_rules['option_values'];
                if (!empty($new_rules['option_values'])) {
                    foreach ($new_rules['option_values'] as $key => $ov_items) {
                        if (!empty($ov_items) && is_array($ov_items)) {
                            foreach ($ov_items as $id => $value) {
                                $rules['option_values'][$key][$id] = $value;
                            }
                        }
                    }
                }
            }
            $rules['time'] = time();
            $this->update($rules);
            delete_option('wgbl_rules');
        }

        $option_values = get_option('wgbl_option_values');
        if (!empty($option_values) && is_array($option_values)) {
            $new_option_values = get_option('wgb_option_values', []);
            if (!empty($new_option_values)) {
                foreach ($new_option_values as $key => $items) {
                    if (!empty($items) && is_array($items)) {
                        foreach ($items as $id => $value) {
                            $option_values[$key][$id] = $value;
                        }
                    }
                }
            }

            $this->update_option_values($option_values);
            delete_option('wgbl_option_values');
        }
    }

    public function get_rule_methods()
    {
        return [
            'simple' => esc_html__('Simple', 'ithemeland-free-gifts-for-woo'),
            'subtotal' => esc_html__('Subtotal', 'ithemeland-free-gifts-for-woo'),
            'tiered_quantity' => esc_html__('Tiered Quantity - In Pro version', 'ithemeland-free-gifts-for-woo'),
            'bulk_quantity' => esc_html__('Bulk Quantity - In Pro version', 'ithemeland-free-gifts-for-woo'),
            'bulk_pricing' => esc_html__('Bulk Pricing - In Pro version', 'ithemeland-free-gifts-for-woo'),
            'buy_x_get_x' => esc_html__('Buy x get x - In Pro version', 'ithemeland-free-gifts-for-woo'),
            'buy_x_get_x_repeat' => esc_html__('Buy x get x repeat - In Pro version', 'ithemeland-free-gifts-for-woo'),
            'buy_x_get_y' => esc_html__('Buy x get y - In Pro version', 'ithemeland-free-gifts-for-woo'),
            'buy_x_get_y_repeat' => esc_html__('Buy x get y repeat - In Pro version', 'ithemeland-free-gifts-for-woo'),
            'subtotal_repeat' => esc_html__('Subtotal repeat - In Pro version', 'ithemeland-free-gifts-for-woo'),
            'cheapest_item_in_cart' => esc_html__('Cheapest item in cart - In Pro version', 'ithemeland-free-gifts-for-woo'),
            'free_shipping' => esc_html__('Free shipping - In Pro version', 'ithemeland-free-gifts-for-woo'),
            'get_group_of_products' => esc_html__('Get Group of Products - In Pro version', 'ithemeland-free-gifts-for-woo'),
        ];
    }

    public function get_rule_methods_grouped()
    {
        return [
            'simple' => [
                'label' => esc_html__('Simple', 'ithemeland-free-gifts-for-woo'),
                'methods' => [
                    'simple' => esc_html__('Simple', 'ithemeland-free-gifts-for-woo'),
                ],
            ],
            'cart_subtotal' => [
                'label' => esc_html__('Cart Subtotal', 'ithemeland-free-gifts-for-woo'),
                'methods' => [
                    'subtotal' => esc_html__('Subtotal', 'ithemeland-free-gifts-for-woo'),
                    'subtotal_repeat' => esc_html__('Subtotal repeat - In Pro version', 'ithemeland-free-gifts-for-woo'),
                ],
            ],
            'tiered' => [
                'label' => esc_html__('Tiered', 'ithemeland-free-gifts-for-woo'),
                'methods' => [
                    'tiered_quantity' => esc_html__('Tiered Quantity - In Pro version', 'ithemeland-free-gifts-for-woo'),
                ],
            ],
            'group' => [
                'label' => esc_html__('Group', 'ithemeland-free-gifts-for-woo'),
                'methods' => [
                    'get_group_of_products' => esc_html__('Get Group of Products - In Pro version', 'ithemeland-free-gifts-for-woo'),
                ],
            ],
            'bulk' => [
                'label' => esc_html__('Bulk', 'ithemeland-free-gifts-for-woo'),
                'methods' => [
                    'bulk_quantity' => esc_html__('Bulk Quantity - In Pro version', 'ithemeland-free-gifts-for-woo'),
                    'bulk_pricing' => esc_html__('Bulk Pricing - In Pro version', 'ithemeland-free-gifts-for-woo'),
                ],
            ],
            'buy_get' => [
                'label' => esc_html__('Buy / Get', 'ithemeland-free-gifts-for-woo'),
                'methods' => [
                    'buy_x_get_x' => esc_html__('Buy x get x - In Pro version', 'ithemeland-free-gifts-for-woo'),
                    'buy_x_get_x_repeat' => esc_html__('Buy x get x repeat - In Pro version', 'ithemeland-free-gifts-for-woo'),
                    'buy_x_get_y' => esc_html__('Buy x get y - In Pro version', 'ithemeland-free-gifts-for-woo'),
                    'buy_x_get_y_repeat' => esc_html__('Buy x get y repeat - In Pro version', 'ithemeland-free-gifts-for-woo'),
                ],
            ],
            'other' => [
                'label' => esc_html__('Other', 'ithemeland-free-gifts-for-woo'),
                'methods' => [
                    'cheapest_item_in_cart' => esc_html__('Cheapest item in cart - In Pro version', 'ithemeland-free-gifts-for-woo'),
                    'free_shipping' => esc_html__('Free shipping - In Pro version', 'ithemeland-free-gifts-for-woo'),
                ],
            ],
        ];
    }

    public function get_all_options()
    {
        return get_option('wgb_option_values', []);
    }

    public function get_shipping_methods_options()
    {
        $shipping_zones = \WC_Shipping_Zones::get_zones();
        $shipping_zones[] = new \WC_Shipping_Zone(0);
        $zones_count = count($shipping_zones);
        $options = [];
        foreach ($shipping_zones as $shipping_zone) {
            if (is_array($shipping_zone) && isset($shipping_zone['zone_id'])) {
                $shipping_zone = \WC_Shipping_Zones::get_zone($shipping_zone['zone_id']);
            } else if (! is_object($shipping_zone)) {
                continue;
            }

            $zone_id = $shipping_zone->get_id();

            $options['all'] = [
                'title' => esc_html__('General', 'ithemeland-free-gifts-for-woo'),
                'options' => [
                    'all' => [
                        'title' => esc_html__('All shipping methods', 'ithemeland-free-gifts-for-woo')
                    ]
                ],
            ];

            $options[$zone_id] = array(
                'title' => $shipping_zone->get_zone_name(),
                'options' => array(),
            );

            foreach ($shipping_zone->get_shipping_methods() as $instance_id => $shipping_method) {
                if ($zones_count > 1) {
                    $title = sprintf('%s (%s)', $shipping_method->title, $shipping_zone->get_zone_name());
                } else {
                    $title = $shipping_method->title;
                }
                $options[$zone_id]['options'][$instance_id] = array(
                    'title' => $title,
                );
            }
        }

        $options = array_filter($options, function ($option) {
            return ! empty($option['options']);
        });

        return $options;
    }

    private function update_option_values($values)
    {
        return update_option('wgb_option_values', $values);
    }

    public function call_set_option_cache()
    {
        // Compatibility shim for older integrations and custom code.
        $this->request_option_cache = [];
    }

    public function get_used_rules($from_date = null, $to_date = null)
    {
        $date_query = '';
        if (!is_null($from_date) && !is_null($to_date)) {
            $from = gmdate('Y-m-d H:i:s', strtotime($from_date));
            $to = gmdate('Y-m-d H:i:s', strtotime($to_date));
            $date_query = "AND orders.post_date BETWEEN '{$from}' AND '{$to}'";
        }

        return $this->wpdb->get_results("SELECT itemmeta.order_item_id, itemmeta.meta_value FROM {$this->wpdb->posts} as orders LEFT JOIN {$this->wpdb->prefix}woocommerce_order_items as order_items ON (order_items.order_id = orders.ID) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta as itemmeta ON (order_items.order_item_id = itemmeta.order_item_id) WHERE itemmeta.meta_key = '_rule_id_free_gift' {$date_query}", ARRAY_A);  //phpcs:ignore
    }

    public function get_used_rules_with_customer($filters = [])
    {
        $filter_query = '';

        if (!empty($filters['date']) && !empty($filters['date']['from']) && !empty($filters['date']['to'])) {
            $from = sanitize_text_field($filters['date']['from']);
            $to = sanitize_text_field($filters['date']['to']);
            $filter_query .= " AND orders.post_date BETWEEN '{$from}' AND '{$to}'";
        }

        if (!empty($filters['order_id'])) {
            $order_id = intval(sanitize_text_field($filters['order_id']));
            $filter_query .= " AND orders.ID = {$order_id}";
        }

        if (!empty($filters['customer_email'])) {
            $customer_email = sanitize_text_field($filters['customer_email']);
            $filter_query .= " AND IF(users.user_email != '', users.user_email LIKE '%{$customer_email}%', postmeta2.meta_value LIKE '%{$customer_email}%')";
        }

        if (!empty($filters['customer_ids'])) {
            $customer_ids = sanitize_text_field($filters['customer_ids']);
            $filter_query .= " AND users.ID IN ({$customer_ids})";
        }

        if (!empty($filters['rule_ids'])) {
            $rule_ids = sanitize_text_field($filters['rule_ids']);
            $filter_query .= " AND itemmeta.meta_value IN ({$rule_ids})";
        }

        return $this->wpdb->get_results("SELECT orders.ID as order_id, orders.post_date as order_date, IF(users.user_login != '', users.user_login, 'Guest') as user_login, IF(users.user_email != '', users.user_email, postmeta2.meta_value) as user_email, itemmeta.order_item_id, itemmeta.meta_value as rule_id FROM {$this->wpdb->posts} as orders LEFT JOIN {$this->wpdb->prefix}postmeta as postmeta ON (orders.ID = postmeta.post_id) LEFT JOIN {$this->wpdb->prefix}postmeta as postmeta2 ON (orders.ID = postmeta2.post_id) LEFT JOIN {$this->wpdb->users} as users ON (users.ID = postmeta.meta_value) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_items as order_items ON (order_items.order_id = orders.ID) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta as itemmeta ON (order_items.order_item_id = itemmeta.order_item_id) WHERE itemmeta.meta_key = '_rule_id_free_gift' AND postmeta.meta_key = '_customer_user' AND postmeta2.meta_key = '_billing_email' {$filter_query}", ARRAY_A); //phpcs:ignore
    }

    public function get_total_customers_used_gift($from_date = null, $to_date = null)
    {
        $date_query = '';
        if (!is_null($from_date) && !is_null($to_date)) {
            $from = gmdate('Y-m-d H:i:s', strtotime($from_date));
            $to = gmdate('Y-m-d H:i:s', strtotime($to_date));
            $date_query = "AND orders.post_date BETWEEN '{$from}' AND '{$to}'";
        }
        return $this->wpdb->get_results("SELECT DISTINCT postmeta.meta_value as customer_id FROM {$this->wpdb->posts} as orders LEFT JOIN {$this->wpdb->prefix}postmeta as postmeta ON (postmeta.post_id = orders.ID) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_items as order_items ON (order_items.order_id = orders.ID) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta as itemmeta ON (order_items.order_item_id = itemmeta.order_item_id) WHERE itemmeta.meta_key = '_rule_id_free_gift' AND postmeta.meta_key = '_customer_user' {$date_query} GROUP BY customer_id", ARRAY_A); //phpcs:ignore
    }

    public function get_option_cache($rule)
    {
        // Validate required fields
        if (!isset($rule['uid'])) {
            return [];
        }

        // The same rule can be evaluated by the cart engine and product
        // visibility filters in one request. Preserve the exact output while
        // avoiding duplicate database queries.
        $request_cache_key = (string) $rule['uid'] . ':' . md5(maybe_serialize($rule));
        $request_cache_enabled = (bool) apply_filters('itfreegift_enable_request_eligibility_cache', true, $rule);
        if ($request_cache_enabled && array_key_exists($request_cache_key, $this->request_option_cache)) {
            return $this->request_option_cache[$request_cache_key];
        }

        $persistent_cache_enabled = (bool) apply_filters('itfreegift_enable_persistent_eligibility_cache', true, $rule);
        $persistent_cache_key = '';
        $lock_key = '';
        $has_lock = false;
        if ($persistent_cache_enabled) {
            $persistent_cache_key = $this->get_persistent_eligibility_cache_key($rule);
            $cached = $this->read_persistent_eligibility_cache($persistent_cache_key);
            if ($cached['found']) {
                if ($request_cache_enabled) {
                    $this->request_option_cache[$request_cache_key] = $cached['value'];
                }
                return $cached['value'];
            }

            $lock_key = 'itfreegift_eligibility_lock_' . md5($persistent_cache_key);
            $has_lock = $this->acquire_cache_lock($lock_key);
            if (!$has_lock) {
                for ($attempt = 0; $attempt < 3; $attempt++) {
                    usleep(50000);
                    $cached = $this->read_persistent_eligibility_cache($persistent_cache_key);
                    if ($cached['found']) {
                        if ($request_cache_enabled) {
                            $this->request_option_cache[$request_cache_key] = $cached['value'];
                        }
                        return $cached['value'];
                    }
                }
            }
        }

        $value_trans = 'gifts';
        $return_query = [];
        $id = $rule['uid'];
        //delete_transient('pw_' . $value_trans . '_cache_simple_variation_' . $id);
        //delete_transient('pw_' . $value_trans . '_cache_simple_childes_' . $id);
        $include_product_is_array = isset($rule['include_products']) && is_array($rule['include_products']);
        $include_product = $include_product_is_array ? $this->normalize_ids($rule['include_products']) : "";
        $exclude_product = !empty($rule['exclude_products']) && is_array($rule['exclude_products']) ? $this->normalize_ids($rule['exclude_products']) : [];
        $include_taxonomy = isset($rule['include_taxonomy']) ? $rule['include_taxonomy'] : "";
        $exclude_taxonomy = isset($rule['exclude_taxonomy']) ? $rule['exclude_taxonomy'] : "";

        $ex_product_condition_1 = "";
        $ex_product_condition_2 = "";

        $in_product_condition_1 = '';
        $in_product_condition_2 = '';

        $in_tax_condition_1 = '';
        $in_tax_condition_2 = '';

        $ex_tax_condition_1 = "";
        $ex_tax_condition_2 = "";

        if ($include_product_is_array) {
            if (empty($include_product)) {
                // An explicitly selected product mode with no valid IDs matched
                // no rows previously through invalid IN (). Keep that meaning.
                $in_product_condition_1 = " AND 1 = 0 ";
                $in_product_condition_2 = " AND 1 = 0 ";
            } else {
                $product_ids = $this->prepare_id_list($include_product);
                $in_product_condition_1 = " AND pw_posts.ID IN ($product_ids) ";
                $in_product_condition_2 = " AND (pw_posts.ID IN ($product_ids) OR pw_products.ID IN ($product_ids)) ";
            }
        }

        if (!empty($exclude_product)) {
            $product_ids = $this->prepare_id_list($exclude_product);

            $ex_product_condition_1 = " AND pw_posts.ID NOT IN ($product_ids) ";
            $ex_product_condition_2 = "  AND (pw_posts.ID NOT IN ($product_ids) AND pw_products.ID NOT IN ($product_ids)) ";
        }

        if ($include_taxonomy && !$include_product_is_array) {
            $terms_id = $this->prepare_id_list($this->extract_taxonomy_ids($include_taxonomy));

            if ($terms_id !== '') {
                $in_tax_condition_1 = " AND ( pw_posts.ID IN ( SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_id) ) ) ";
                $in_tax_condition_2 = " AND ( pw_posts.post_parent IN ( SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_id) ) OR pw_products.ID IN ( SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_id) ) ) ";
            }
        }

        if ($exclude_taxonomy && !$include_product_is_array) {
            $terms_id = $this->prepare_id_list($this->extract_taxonomy_ids($exclude_taxonomy));

            if ($terms_id !== '') {
                $ex_tax_condition_1 = " AND ( pw_posts.ID NOT IN ( SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_id) ) ) ";
                $ex_tax_condition_2 = " AND ( pw_posts.post_parent NOT IN ( SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_id) ) AND pw_products.ID NOT IN ( SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_id) )) ";
            }
        }

        // Only the product ID is consumed below. The query has no joins that
        // can duplicate rows, so selecting unused columns and grouping adds work.
        $simple_variation = "SELECT pw_posts.ID as product_id FROM {$this->wpdb->prefix}posts as pw_posts WHERE pw_posts.post_type='product' AND pw_posts.post_status = 'publish' $in_tax_condition_1 $in_product_condition_1 $ex_tax_condition_1 $ex_product_condition_1";

        $result = $this->wpdb->get_results($simple_variation); //phpcs:ignore

        $simple_variation_arrray = [];
        foreach ($result as $items) {
            $simple_variation_arrray[] = $items->product_id;
        }

        if ($include_product_is_array) {
            $simple_variation_arrray = array_merge($include_product, $simple_variation_arrray);
            $simple_variation_arrray = array_filter($simple_variation_arrray);
            $simple_variation_arrray = array_unique($simple_variation_arrray);
            $return_query['pw_' . $value_trans . '_cache_simple_variation_'] = $simple_variation_arrray;
            //set_transient('pw_' . $value_trans . '_cache_simple_variation_' . $id, $simple_variation_arrray);
        } else {
            $simple_variation_arrray = array_unique($simple_variation_arrray);
            $return_query['pw_' . $value_trans . '_cache_simple_variation_'] = $simple_variation_arrray;
            //set_transient('pw_' . $value_trans . '_cache_simple_variation_' . $id, $simple_variation_arrray);
        }

        // Preserve the legacy joins, grouping and ordering until EXPLAIN/parity
        // can run on a real catalog, but transfer only fields consumed below.
        $simple_childes = "SELECT pw_posts.ID as id, pw_posts.post_parent AS variation_parent_id FROM {$this->wpdb->prefix}posts as pw_posts LEFT JOIN {$this->wpdb->prefix}posts as pw_products ON pw_products.ID = pw_posts.post_parent LEFT JOIN {$this->wpdb->prefix}term_relationships AS term_relationships ON pw_products.ID = term_relationships.object_id LEFT JOIN {$this->wpdb->prefix}term_taxonomy AS term_taxonomy ON term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id LEFT JOIN {$this->wpdb->prefix}terms AS terms ON term_taxonomy.term_id = terms.term_id WHERE term_taxonomy.taxonomy = 'product_type' AND terms.slug = 'variable' AND pw_posts.post_type='product_variation' AND pw_posts.post_status = 'publish' AND pw_products.post_type='product' AND pw_posts.post_parent > 0 $in_tax_condition_2 $in_product_condition_2 $ex_tax_condition_2 $ex_product_condition_2 GROUP BY pw_posts.ID ORDER BY pw_posts.post_parent ASC, pw_posts.post_title ASC";

        $result = $this->wpdb->get_results($simple_childes); //phpcs:ignore
        $simple_childes_arrray = [];
        $simple_childes_final_arrray = [];
        $simple_childes_parent_arrray = [];
        $temp_simple = $simple_variation_arrray;
        foreach ($result as $items) {
            $simple_childes_arrray[] = $items->id;
            $simple_childes_parent_arrray[] = $items->variation_parent_id;
        }

        if (is_array($simple_childes_parent_arrray)) {
            $temp_simple = array_diff($temp_simple, $simple_childes_parent_arrray);
        }

        $simple_childes_final_arrray = array_merge($temp_simple, $simple_childes_arrray);
        $simple_childes_final_arrray = array_unique($simple_childes_final_arrray);
        $return_query['pw_' . $value_trans . '_cache_simple_childes_'] = $simple_childes_final_arrray;
        //set_transient('pw_' . $value_trans . '_cache_simple_childes_' . $id, $simple_childes_final_arrray);
        if ($request_cache_enabled) {
            $this->request_option_cache[$request_cache_key] = $return_query;
        }
        if ($persistent_cache_enabled) {
            $this->write_persistent_eligibility_cache($persistent_cache_key, $return_query);
            if ($has_lock) {
                delete_option($lock_key);
            }
        }
        return $return_query;
    }

    private function normalize_ids($values)
    {
        if (!is_array($values)) {
            return [];
        }
        return array_values(array_unique(array_filter(array_map('absint', $values))));
    }

    private function extract_taxonomy_ids($values)
    {
        $ids = [];
        foreach ((array) $values as $value) {
            $parts = explode('__', (string) $value);
            if (isset($parts[1])) {
                $ids[] = $parts[1];
            }
        }
        return $this->normalize_ids($ids);
    }

    private function prepare_id_list(array $ids)
    {
        if (empty($ids)) {
            return '';
        }
        $placeholders = implode(',', array_fill(0, count($ids), '%d'));
        return $this->wpdb->prepare($placeholders, $ids); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
    }

    private function get_persistent_eligibility_cache_key(array $rule)
    {
        $global_version = (int) get_option('itfreegift_eligibility_cache_version', 1);
        $rule_uid = sanitize_key((string) $rule['uid']);
        $rule_hash = md5(maybe_serialize([
            'include_products' => $rule['include_products'] ?? [],
            'exclude_products' => $rule['exclude_products'] ?? [],
            'include_taxonomy' => $rule['include_taxonomy'] ?? [],
            'exclude_taxonomy' => $rule['exclude_taxonomy'] ?? [],
        ]));

        return 'itfg_e_' . self::ELIGIBILITY_CACHE_SCHEMA . '_' . $global_version . '_' . substr($rule_uid, 0, 32) . '_' . $rule_hash;
    }

    private function read_persistent_eligibility_cache($cache_key)
    {
        $found = false;
        $cached = wp_cache_get($cache_key, self::ELIGIBILITY_CACHE_GROUP, false, $found);
        if ($found && is_array($cached) && array_key_exists('value', $cached)) {
            return ['found' => true, 'value' => $cached['value']];
        }

        $cached = get_transient($cache_key);
        if (is_array($cached) && array_key_exists('value', $cached)) {
            wp_cache_set($cache_key, $cached, self::ELIGIBILITY_CACHE_GROUP, self::ELIGIBILITY_CACHE_TTL);
            return ['found' => true, 'value' => $cached['value']];
        }

        return ['found' => false, 'value' => null];
    }

    private function write_persistent_eligibility_cache($cache_key, array $value)
    {
        $payload = ['value' => $value];
        wp_cache_set($cache_key, $payload, self::ELIGIBILITY_CACHE_GROUP, self::ELIGIBILITY_CACHE_TTL);
        set_transient($cache_key, $payload, self::ELIGIBILITY_CACHE_TTL);
    }

    private function acquire_cache_lock($lock_key)
    {
        $now = time();
        if (add_option($lock_key, $now, '', false)) {
            return true;
        }

        $created_at = (int) get_option($lock_key, 0);
        if ($created_at > 0 && ($now - $created_at) > 30) {
            delete_option($lock_key);
            return add_option($lock_key, $now, '', false);
        }

        return false;
    }

    private function invalidate_option_cache($rules = [])
    {
        $this->request_option_cache = [];

        $rule_items = (!empty($rules['items']) && is_array($rules['items'])) ? $rules['items'] : [];
        foreach ($rule_items as $rule) {
            if (empty($rule['uid'])) {
                continue;
            }

            // Remove values left by older releases; current runtime code has
            // never read these transients.
            delete_transient('pw_gifts_cache_simple_variation_' . $rule['uid']);
            delete_transient('pw_gifts_cache_simple_childes_' . $rule['uid']);
        }
    }

}
