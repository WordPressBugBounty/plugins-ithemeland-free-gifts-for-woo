<?php

namespace wgbl\classes\repositories;

defined('ABSPATH') || exit();

class Rule
{
    private static $instance;

    private $option_name;
    private $wpdb;

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

        $this->option_name = "wgbl_rules";
    }

    public function update($rules)
    {
        $this->set_option_values($rules['items']);
        $this->set_option_cache($rules);

        return update_option($this->option_name, (esc_sql($rules)));
    }

    public function get()
    {
        return get_option($this->option_name);
    }

    public function get_rule_methods()
    {
        return [
            'simple' => esc_html__('Simple', 'ithemeland-free-gifts-for-woo'),
            'tiered_quantity' => esc_html__('Tiered Quantity - Pro version', 'ithemeland-free-gifts-for-woo'),
            'bulk_quantity' => esc_html__('Bulk Quantity - Pro version', 'ithemeland-free-gifts-for-woo'),
            'bulk_pricing' => esc_html__('Bulk Pricing - Pro version', 'ithemeland-free-gifts-for-woo'),
            'buy_x_get_x' => esc_html__('Buy x get x - Pro version', 'ithemeland-free-gifts-for-woo'),
            'buy_x_get_x_repeat' => esc_html__('Buy x get x repeat - Pro version', 'ithemeland-free-gifts-for-woo'),
            'buy_x_get_y' => esc_html__('Buy x get y - Pro version', 'ithemeland-free-gifts-for-woo'),
            'buy_x_get_y_repeat' => esc_html__('Buy x get y repeat - Pro version', 'ithemeland-free-gifts-for-woo'),
            'subtotal' => esc_html__('Subtotal', 'ithemeland-free-gifts-for-woo'),
            'subtotal_repeat' => esc_html__('Subtotal repeat - Pro version', 'ithemeland-free-gifts-for-woo'),
            'subtotal_repeat' => esc_html__('Subtotal repeat - Pro version', 'ithemeland-free-gifts-for-woo'),
            'cheapest_item_in_cart' => esc_html__('Cheapest item in cart - Pro version', 'ithemeland-free-gifts-for-woo'),
            'free_shipping' => esc_html__('Free shipping - Pro version', 'ithemeland-free-gifts-for-woo'),
        ];
    }

    public function get_rule_method_options()
    {
        $output = '<optgroup label="' . esc_html__('Simple', 'ithemeland-free-gifts-for-woo') . '">
            <option value="simple">' . esc_html__('Simple', 'ithemeland-free-gifts-for-woo') . '</option>
            </optgroup>

            <optgroup label="' . esc_html__('Cart Subtotal', 'ithemeland-free-gifts-for-woo') . '">
            <option value="subtotal">' . esc_html__('Subtotal', 'ithemeland-free-gifts-for-woo') . '</option>
            <option value="subtotal_repeat" style="color: red;">' . esc_html__('Subtotal repeat - Pro version', 'ithemeland-free-gifts-for-woo') . '</option>
            </optgroup>

            <optgroup label="' . esc_html__('Tiered', 'ithemeland-free-gifts-for-woo') . '">
            <option value="tiered_quantity" style="color: red;">' . esc_html__('Tiered Quantity - Pro version', 'ithemeland-free-gifts-for-woo') . '</option>
            </optgroup>

            <optgroup label="' . esc_html__('Bulk', 'ithemeland-free-gifts-for-woo') . '">
            <option value="bulk_quantity" style="color: red;">' . esc_html__('Bulk Quantity - Pro version', 'ithemeland-free-gifts-for-woo') . '</option>
            <option value="bulk_pricing" style="color: red;">' . esc_html__('Bulk Pricing - Pro version', 'ithemeland-free-gifts-for-woo') . '</option>
            </optgroup>

            <optgroup label="' . esc_html__('Buy / Get', 'ithemeland-free-gifts-for-woo') . '">
            <option value="buy_x_get_x" style="color: red;">' . esc_html__('Buy x get x - Pro version', 'ithemeland-free-gifts-for-woo') . '</option>
            <option value="buy_x_get_x_repeat" style="color: red;">' . esc_html__('Buy x get x repeat - Pro version', 'ithemeland-free-gifts-for-woo') . '</option>
            <option value="buy_x_get_y" style="color: red;">' . esc_html__('Buy x get y - Pro version', 'ithemeland-free-gifts-for-woo') . '</option>
            <option value="buy_x_get_y_repeat" style="color: red;">' . esc_html__('Buy x get y repeat - Pro version', 'ithemeland-free-gifts-for-woo') . '</option>
            </optgroup>
            
            <optgroup label="' . esc_html__('Other', 'ithemeland-free-gifts-for-woo') . '">
            <option value="cheapest_item_in_cart" style="color: red;">' . esc_html__('Cheapest item in cart - Pro version', 'ithemeland-free-gifts-for-woo') . '</option>
            <option value="free_shipping" style="color: red;">' . esc_html__('Free shipping - Pro version', 'ithemeland-free-gifts-for-woo') . '</option>
            </optgroup>';

        return $output;
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
                    'subtotal_repeat' => esc_html__('Subtotal repeat - Pro version', 'ithemeland-free-gifts-for-woo'),
                ],
            ],
            'tiered' => [
                'label' => esc_html__('Tiered', 'ithemeland-free-gifts-for-woo'),
                'methods' => [
                    'tiered_quantity' => esc_html__('Tiered Quantity - Pro version', 'ithemeland-free-gifts-for-woo'),
                ],
            ],
            'bulk' => [
                'label' => esc_html__('Bulk', 'ithemeland-free-gifts-for-woo'),
                'methods' => [
                    'bulk_quantity' => esc_html__('Bulk Quantity - Pro version', 'ithemeland-free-gifts-for-woo'),
                    'bulk_pricing' => esc_html__('Bulk Pricing - Pro version', 'ithemeland-free-gifts-for-woo'),
                ],
            ],
            'buy_get' => [
                'label' => esc_html__('Buy / Get', 'ithemeland-free-gifts-for-woo'),
                'methods' => [
                    'buy_x_get_x' => esc_html__('Buy x get x - Pro version', 'ithemeland-free-gifts-for-woo'),
                    'buy_x_get_x_repeat' => esc_html__('Buy x get x repeat - Pro version', 'ithemeland-free-gifts-for-woo'),
                    'buy_x_get_y' => esc_html__('Buy x get y - Pro version', 'ithemeland-free-gifts-for-woo'),
                    'buy_x_get_y_repeat' => esc_html__('Buy x get y repeat - Pro version', 'ithemeland-free-gifts-for-woo'),
                ],
            ],
            'other' => [
                'label' => esc_html__('Other', 'ithemeland-free-gifts-for-woo'),
                'methods' => [
                    'cheapest_item_in_cart' => esc_html__('Cheapest item in cart - Pro version', 'ithemeland-free-gifts-for-woo'),
                    'free_shipping' => esc_html__('Free shipping - Pro version', 'ithemeland-free-gifts-for-woo'),
                ],
            ],
        ];
    }

    public function get_all_options()
    {
        return get_option('wgbl_option_values');
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
                'title' => __('General', 'ithemeland-free-gifts-for-woo'),
                'options' => [
                    'all' => [
                        'title' => __('All shipping methods', 'ithemeland-free-gifts-for-woo')
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
        return update_option('wgbl_option_values', $values);
    }

    public function call_set_option_cache()
    {
        $rules = $this->get();
        $this->set_option_cache($rules);
    }

    public function get_used_rules($from_date = null, $to_date = null)
    {
        global $wpdb;

        $date_query = '';
        $params = [];

        if (!is_null($from_date) && !is_null($to_date)) {
            $from = gmdate('Y-m-d', strtotime($from_date)) . ' 00:00';
            $to = gmdate('Y-m-d', strtotime($to_date)) . ' 23:59';
            $date_query = "AND orders.post_date BETWEEN %s AND %s";
            $params[] = $from;
            $params[] = $to;
        }

        $sql = "SELECT itemmeta.order_item_id, itemmeta.meta_value
            FROM {$wpdb->posts} as orders
            LEFT JOIN {$wpdb->prefix}woocommerce_order_items as order_items ON (order_items.order_id = orders.ID)
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as itemmeta ON (order_items.order_item_id = itemmeta.order_item_id)
            WHERE itemmeta.meta_key = '_rule_id_free_gift'
            {$date_query}
        ";

        if (!empty($params)) {
            $prepared_sql = $wpdb->prepare($sql, ...$params); //phpcs:ignore
        } else {
            $prepared_sql = $sql;
        }

        return $wpdb->get_results($prepared_sql, ARRAY_A); //phpcs:ignore
    }

    public function get_used_rules_with_customer($filters = [])
    {
        global $wpdb;

        $filter_clauses = [];
        $filter_params = [];

        if (!empty($filters['date']['from']) && !empty($filters['date']['to'])) {
            $from = sanitize_text_field($filters['date']['from']);
            $to = sanitize_text_field($filters['date']['to']);
            $filter_clauses[] = "orders.post_date BETWEEN %s AND %s";
            $filter_params[] = $from;
            $filter_params[] = $to;
        }

        if (!empty($filters['order_id'])) {
            $order_id = intval($filters['order_id']);
            $filter_clauses[] = "orders.ID = %d";
            $filter_params[] = $order_id;
        }

        if (!empty($filters['customer_email'])) {
            $customer_email = '%' . $wpdb->esc_like(sanitize_text_field($filters['customer_email'])) . '%';
            $filter_clauses[] = "(users.user_email != '' AND users.user_email LIKE %s OR postmeta2.meta_value LIKE %s)";
            $filter_params[] = $customer_email;
            $filter_params[] = $customer_email;
        }

        if (!empty($filters['customer_ids'])) {
            if (is_array($filters['customer_ids'])) {
                $customer_ids = array_map('intval', $filters['customer_ids']);
            } else {
                $customer_ids = array_map('intval', explode(',', $filters['customer_ids']));
            }
            if (!empty($customer_ids)) {
                $placeholders = implode(',', array_fill(0, count($customer_ids), '%d'));
                $filter_clauses[] = "users.ID IN ({$placeholders})";
                $filter_params = array_merge($filter_params, $customer_ids);
            }
        }

        if (!empty($filters['rule_ids'])) {
            if (is_array($filters['rule_ids'])) {
                $rule_ids = array_map('intval', $filters['rule_ids']);
            } else {
                $rule_ids = array_map('intval', explode(',', $filters['rule_ids']));
            }
            if (!empty($rule_ids)) {
                $placeholders = implode(',', array_fill(0, count($rule_ids), '%d'));
                $filter_clauses[] = "itemmeta.meta_value IN ({$placeholders})";
                $filter_params = array_merge($filter_params, $rule_ids);
            }
        }

        $filter_query = '';
        if (!empty($filter_clauses)) {
            $filter_query = ' AND ' . implode(' AND ', $filter_clauses);
        }

        $sql = "SELECT
                orders.ID as order_id,
                orders.post_date as order_date,
                IF(users.user_login != '', users.user_login, 'Guest') as user_login,
                IF(users.user_email != '', users.user_email, postmeta2.meta_value) as user_email,
                itemmeta.order_item_id,
                itemmeta.meta_value as rule_id
            FROM {$wpdb->posts} as orders
            LEFT JOIN {$wpdb->prefix}postmeta as postmeta ON (orders.ID = postmeta.post_id)
            LEFT JOIN {$wpdb->prefix}postmeta as postmeta2 ON (orders.ID = postmeta2.post_id)
            LEFT JOIN {$wpdb->users} as users ON (users.ID = postmeta.meta_value)
            LEFT JOIN {$wpdb->prefix}woocommerce_order_items as order_items ON (order_items.order_id = orders.ID)
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as itemmeta ON (order_items.order_item_id = itemmeta.order_item_id)
            WHERE
                itemmeta.meta_key = '_rule_id_free_gift'
                AND postmeta.meta_key = '_customer_user'
                AND postmeta2.meta_key = '_billing_email'
                {$filter_query}
        ";

        if (!empty($filter_params)) {
            $prepared_sql = $wpdb->prepare($sql, ...$filter_params); //phpcs:ignore
        } else {
            $prepared_sql = $sql;
        }

        return $wpdb->get_results($prepared_sql, ARRAY_A); //phpcs:ignore
    }


    public function get_total_customers_used_gift($from_date = null, $to_date = null)
    {
        global $wpdb;

        $date_query = '';
        $params = [];

        if (!is_null($from_date) && !is_null($to_date)) {
            $from = gmdate('Y-m-d', strtotime($from_date)) . ' 00:00';
            $to = gmdate('Y-m-d', strtotime($to_date)) . ' 23:59';
            $date_query = "AND orders.post_date BETWEEN %s AND %s";
            $params[] = $from;
            $params[] = $to;
        }

        $sql = "SELECT DISTINCT postmeta.meta_value as customer_id
            FROM {$wpdb->posts} as orders
            LEFT JOIN {$wpdb->prefix}postmeta as postmeta ON (postmeta.post_id = orders.ID)
            LEFT JOIN {$wpdb->prefix}woocommerce_order_items as order_items ON (order_items.order_id = orders.ID)
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as itemmeta ON (order_items.order_item_id = itemmeta.order_item_id)
            WHERE itemmeta.meta_key = '_rule_id_free_gift'
            AND postmeta.meta_key = '_customer_user'
            {$date_query}
            GROUP BY customer_id
        ";

        if (!empty($params)) {
            $prepared_sql = $wpdb->prepare($sql, ...$params); //phpcs:ignore
        } else {
            $prepared_sql = $sql;
        }

        return $wpdb->get_results($prepared_sql, ARRAY_A); //phpcs:ignore
    }

    public function get_option_cache($rule)
    {
        global $wpdb;

        $value_trans = 'gifts';
        $return_query = [];
        $id = $rule['uid'];

        $include_product = isset($rule['include_products']) && is_array($rule['include_products']) ? array_map('intval', $rule['include_products']) : [];
        $exclude_product = isset($rule['exclude_products']) && is_array($rule['exclude_products']) ? array_map('intval', $rule['exclude_products']) : [];
        $include_taxonomy = isset($rule['include_taxonomy']) && is_array($rule['include_taxonomy']) ? $rule['include_taxonomy'] : [];
        $exclude_taxonomy = isset($rule['exclude_taxonomy']) && is_array($rule['exclude_taxonomy']) ? $rule['exclude_taxonomy'] : [];

        $where_clauses_1 = ["pw_posts.post_type = 'product'", "pw_posts.post_status = 'publish'"];
        $where_clauses_2 = ["term_taxonomy.taxonomy = 'product_type'", "terms.slug = 'variable'", "pw_posts.post_type='product_variation'", "pw_posts.post_status = 'publish'", "pw_products.post_type='product'", "pw_posts.post_parent > 0"];

        $params = [];
        $params2 = [];

        if (!empty($include_product)) {
            $placeholders = implode(',', array_fill(0, count($include_product), '%d'));
            $where_clauses_1[] = "pw_posts.ID IN ($placeholders)";
            $where_clauses_2[] = "(pw_posts.ID IN ($placeholders) OR pw_products.ID IN ($placeholders))";
            $params = array_merge($params, $include_product);
            $params2 = array_merge($params2, $include_product, $include_product);
        }

        if (!empty($exclude_product)) {
            $placeholders = implode(',', array_fill(0, count($exclude_product), '%d'));
            $where_clauses_1[] = "pw_posts.ID NOT IN ($placeholders)";
            $where_clauses_2[] = "(pw_posts.ID NOT IN ($placeholders) AND pw_products.ID NOT IN ($placeholders))";
            $params = array_merge($params, $exclude_product);
            $params2 = array_merge($params2, $exclude_product, $exclude_product);
        }

        if (!empty($include_taxonomy)) {
            $terms_id = [];
            foreach ($include_taxonomy as $inc_tax) {
                $tax = explode("__", $inc_tax);
                if (isset($tax[1])) {
                    $terms_id[] = intval($tax[1]);
                }
            }
            if ($terms_id) {
                $placeholders = implode(',', array_fill(0, count($terms_id), '%d'));
                $where_clauses_1[] = "pw_posts.ID IN (SELECT object_id FROM {$wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($placeholders))";
                $where_clauses_2[] = "(pw_posts.post_parent IN (SELECT object_id FROM {$wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($placeholders)) OR pw_products.ID IN (SELECT object_id FROM {$wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($placeholders)))";
                $params = array_merge($params, $terms_id);
                $params2 = array_merge($params2, $terms_id, $terms_id);
            }
        }

        if (!empty($exclude_taxonomy)) {
            $terms_id = [];
            foreach ($exclude_taxonomy as $ex_tax) {
                $tax = explode("__", $ex_tax);
                if (isset($tax[1])) {
                    $terms_id[] = intval($tax[1]);
                }
            }
            if ($terms_id) {
                $placeholders = implode(',', array_fill(0, count($terms_id), '%d'));
                $where_clauses_1[] = "pw_posts.ID NOT IN (SELECT object_id FROM {$wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($placeholders))";
                $where_clauses_2[] = "(pw_posts.post_parent NOT IN (SELECT object_id FROM {$wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($placeholders)) AND pw_products.ID NOT IN (SELECT object_id FROM {$wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($placeholders)))";
                $params = array_merge($params, $terms_id);
                $params2 = array_merge($params2, $terms_id, $terms_id);
            }
        }

        $sql1 = "SELECT pw_posts.post_title as product_name, pw_posts.post_date as product_date, pw_posts.post_modified as modified_date, pw_posts.ID as product_id
            FROM {$wpdb->prefix}posts as pw_posts
            WHERE " . implode(' AND ', $where_clauses_1) . "
            GROUP BY product_id
        ";

        $prepared_sql1 = !empty($params) ? $wpdb->prepare($sql1, ...$params) : $sql1; //phpcs:ignore
        $result1 = $wpdb->get_results($prepared_sql1); //phpcs:ignore

        $simple_variation_array = [];
        foreach ($result1 as $items) {
            $simple_variation_array[] = $items->product_id;
        }

        if (!empty($include_product)) {
            $simple_variation_array = array_merge($include_product, $simple_variation_array);
        }
        $simple_variation_array = array_filter(array_unique($simple_variation_array));
        $return_query['pw_' . $value_trans . '_cache_simple_variation_'] = $simple_variation_array;

        $sql2 = "SELECT pw_posts.ID as id, pw_posts.post_title as variation_name, pw_posts.ID as variation_id, pw_posts.post_date as product_date, pw_posts.post_modified as modified_date,
                pw_products.ID as product_id, pw_products.post_title as product_name, pw_posts.post_parent AS variation_parent_id
            FROM {$wpdb->prefix}posts as pw_posts
            LEFT JOIN {$wpdb->prefix}posts as pw_products ON pw_products.ID = pw_posts.post_parent
            LEFT JOIN {$wpdb->prefix}term_relationships AS term_relationships ON pw_products.ID = term_relationships.object_id
            LEFT JOIN {$wpdb->prefix}term_taxonomy AS term_taxonomy ON term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id
            LEFT JOIN {$wpdb->prefix}terms AS terms ON term_taxonomy.term_id = terms.term_id
            WHERE " . implode(' AND ', $where_clauses_2) . "
            GROUP BY pw_posts.ID
            ORDER BY pw_posts.post_parent ASC, pw_posts.post_title ASC
        ";

        $prepared_sql2 = !empty($params2) ? $wpdb->prepare($sql2, ...$params2) : $sql2; //phpcs:ignore
        $result2 = $wpdb->get_results($prepared_sql2); //phpcs:ignore

        $simple_childes_array = [];
        $simple_childes_parent_array = [];
        $temp_simple = $simple_variation_array;

        foreach ($result2 as $items) {
            $simple_childes_array[] = $items->id;
            $simple_childes_parent_array[] = $items->variation_parent_id;
        }

        if (is_array($simple_childes_parent_array)) {
            $temp_simple = array_diff($temp_simple, $simple_childes_parent_array);
        }

        $simple_childes_final_array = array_unique(array_merge($temp_simple, $simple_childes_array));
        $return_query['pw_' . $value_trans . '_cache_simple_childes_'] = $simple_childes_final_array;

        return $return_query;
    }

    private function set_option_cache($rules)
    {
        if (!is_array($rules['items']) || count($rules['items']) <= 0) {
            return false;
        }

        $value_trans = 'gifts';

        foreach ($rules['items'] as $rule) {
            $id = sanitize_key($rule['uid']);
            delete_transient('pw_' . $value_trans . '_cache_simple_variation_' . $id);
            delete_transient('pw_' . $value_trans . '_cache_simple_childes_' . $id);

            if ($rule['status'] === 'disable') {
                continue;
            }

            $args = $rule;

            $include_product = isset($args['include_products']) ? array_map('absint', (array)$args['include_products']) : [];
            $exclude_product = isset($args['exclude_products']) ? array_map('absint', (array)$args['exclude_products']) : [];

            $include_taxonomy = isset($args['include_taxonomy']) ? $args['include_taxonomy'] : [];
            $exclude_taxonomy = isset($args['exclude_taxonomy']) ? $args['exclude_taxonomy'] : [];

            $in_product_condition_1 = $include_product ? " AND pw_posts.ID IN (" . implode(',', $include_product) . ")" : '';
            $in_product_condition_2 = $include_product ? " AND (pw_posts.ID IN (" . implode(',', $include_product) . ") OR pw_products.ID IN (" . implode(',', $include_product) . "))" : '';

            $ex_product_condition_1 = $exclude_product ? " AND pw_posts.ID NOT IN (" . implode(',', $exclude_product) . ")" : '';
            $ex_product_condition_2 = $exclude_product ? " AND (pw_posts.ID NOT IN (" . implode(',', $exclude_product) . ") AND pw_products.ID NOT IN (" . implode(',', $exclude_product) . "))" : '';

            $in_tax_condition_1 = $in_tax_condition_2 = '';
            $ex_tax_condition_1 = $ex_tax_condition_2 = '';

            if (!empty($include_taxonomy)) {
                $terms_id = array_map(function ($tax) {
                    $parts = explode("__", sanitize_text_field($tax));
                    return isset($parts[1]) ? absint($parts[1]) : null;
                }, $include_taxonomy);
                $terms_id = array_filter($terms_id);

                if (!empty($terms_id)) {
                    $terms_str = implode(',', $terms_id);
                    $in_tax_condition_1 = " AND (pw_posts.ID IN (SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_str)))";
                    $in_tax_condition_2 = " AND (pw_posts.post_parent IN (SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_str)) OR pw_products.ID IN (SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_str)))";
                }
            }

            if (!empty($exclude_taxonomy)) {
                $terms_id = array_map(function ($tax) {
                    $parts = explode("__", sanitize_text_field($tax));
                    return isset($parts[1]) ? absint($parts[1]) : null;
                }, $exclude_taxonomy);
                $terms_id = array_filter($terms_id);

                if (!empty($terms_id)) {
                    $terms_str = implode(',', $terms_id);
                    $ex_tax_condition_1 = " AND (pw_posts.ID NOT IN (SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_str)))";
                    $ex_tax_condition_2 = " AND (pw_posts.post_parent NOT IN (SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_str)) AND pw_products.ID NOT IN (SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_str)))";
                }
            }

            // Fetch simple products
            $query = "SELECT pw_posts.post_title as product_name, pw_posts.post_date as product_date,
                    pw_posts.post_modified as modified_date, pw_posts.ID as product_id
                FROM {$this->wpdb->prefix}posts as pw_posts
                WHERE pw_posts.post_type = %s
                AND pw_posts.post_status = %s
                {$in_tax_condition_1} {$in_product_condition_1} {$ex_tax_condition_1} {$ex_product_condition_1}
                GROUP BY product_id
            ";
            $result = $this->wpdb->get_results($this->wpdb->prepare($query, 'product', 'publish')); //phpcs:ignore

            $simple_variation_arrray = [];
            foreach ($result as $items) {
                $simple_variation_arrray[] = (int)$items->product_id;
            }

            if (!empty($include_product)) {
                $simple_variation_arrray = array_merge($include_product, $simple_variation_arrray);
                $simple_variation_arrray = array_filter($simple_variation_arrray);
                $simple_variation_arrray = array_unique($simple_variation_arrray);
            } else {
                $simple_variation_arrray = array_unique($simple_variation_arrray);
            }

            set_transient('pw_' . $value_trans . '_cache_simple_variation_' . $id, $simple_variation_arrray);

            $query = "SELECT pw_posts.ID as id, pw_posts.post_title as variation_name, pw_posts.ID as variation_id,
                    pw_posts.post_date as product_date, pw_posts.post_modified as modified_date,
                    pw_products.ID as product_id, pw_products.post_title as product_name,
                    pw_posts.post_parent AS variation_parent_id
                FROM {$this->wpdb->prefix}posts as pw_posts
                LEFT JOIN {$this->wpdb->prefix}posts as pw_products ON pw_products.ID = pw_posts.post_parent
                LEFT JOIN {$this->wpdb->prefix}term_relationships AS term_relationships ON pw_products.ID = term_relationships.object_id
                LEFT JOIN {$this->wpdb->prefix}term_taxonomy AS term_taxonomy ON term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id
                LEFT JOIN {$this->wpdb->prefix}terms AS terms ON term_taxonomy.term_id = terms.term_id
                WHERE term_taxonomy.taxonomy = %s
                AND terms.slug = %s
                AND pw_posts.post_type = %s
                AND pw_posts.post_status = %s
                AND pw_products.post_type = %s
                AND pw_posts.post_parent > 0
                {$in_tax_condition_2} {$in_product_condition_2} {$ex_tax_condition_2} {$ex_product_condition_2}
                GROUP BY pw_posts.ID
                ORDER BY pw_posts.post_parent ASC, pw_posts.post_title ASC
            ";
            $result = $this->wpdb->get_results($this->wpdb->prepare($query, 'product_type', 'variable', 'product_variation', 'publish', 'product')); //phpcs:ignore

            $simple_childes_arrray = [];
            $simple_childes_parent_arrray = [];

            foreach ($result as $items) {
                $simple_childes_arrray[] = (int)$items->id;
                $simple_childes_parent_arrray[] = (int)$items->variation_parent_id;
            }

            $temp_simple = $simple_variation_arrray;

            if (!empty($simple_childes_parent_arrray)) {
                $temp_simple = array_diff($temp_simple, $simple_childes_parent_arrray);
            }

            $simple_childes_final_arrray = array_unique(array_merge($temp_simple, $simple_childes_arrray));

            set_transient('pw_' . $value_trans . '_cache_simple_childes_' . $id, $simple_childes_final_arrray);
        }
    }

    private function set_option_values($rules)
    {
        if (!empty($rules)) {
            $output = [];
            $coupon_ids = [];
            $product_ids = [];
            $variation_ids = [];
            $category_ids = [];
            $attribute_ids = [];
            $taxonomies_ids = [];
            $tag_ids = [];
            $shipping_class_ids = [];
            $customer_ids = [];
            $user_role_ids = [];
            $user_capability_ids = [];
            $user_repository = new User();
            $product_repository = Product::get_instance();

            foreach ($rules as $rule) {
                if (!empty($rule['product_buy'])) {
                    foreach ($rule['product_buy'] as $product_buy) {
                        switch ($product_buy['type']) {
                            case 'product':
                                $product_ids[] = !empty($product_buy['products']) ? $product_buy['products'] : [];
                                break;
                            case 'product_variation':
                                $variation_ids[] = !empty($product_buy['variations']) ? $product_buy['variations'] : [];
                                break;
                            case 'product_category':
                                $category_ids[] = !empty($product_buy['categories']) ? $product_buy['categories'] : [];
                                break;
                            case 'product_attribute':
                                $attribute_ids[] = !empty($product_buy['attributes']) ? $product_buy['attributes'] : [];
                                break;
                            case 'product_tag':
                                $tag_ids[] = !empty($product_buy['tags']) ? $product_buy['tags'] : [];
                                break;
                            case 'coupons_applied':
                                $coupon_ids[] = !empty($product_buy['coupons']) ? $product_buy['coupons'] : [];
                                break;
                            case 'product_shipping_classes':
                                $shipping_class_ids[] = !empty($product_buy['shipping_classes']) ? $product_buy['shipping_classes'] : [];
                                break;
                        }
                    }
                }

                if (!empty($rule['condition'])) {
                    foreach ($rule['condition'] as $condition) {
                        $type = sanitize_text_field($condition['type']);
                        switch ($type) {
                            case 'coupons_applied':
                                $coupon_ids[] = !empty($condition['coupons']) ? $condition['coupons'] : [];
                                break;
                            case 'cart_items_products':
                            case 'cart_item_quantity_products':
                            case 'cart_item_subtotal_products':
                            case 'purchased_products':
                            case 'quantity_purchased_products':
                            case 'value_purchased_products':
                                $product_ids[] = !empty($condition['products']) ? $condition['products'] : [];
                                break;
                            case 'cart_items_variations':
                            case 'cart_item_quantity_variations':
                            case 'cart_item_subtotal_variations':
                            case 'purchased_variations':
                            case 'quantity_purchased_variations':
                            case 'value_purchased_variations':
                                $variation_ids[] = !empty($condition['variations']) ? $condition['variations'] : [];
                                break;
                            case 'cart_items_categories':
                            case 'cart_item_quantity_categories':
                            case 'cart_item_subtotal_categories':
                            case 'purchased_categories':
                            case 'quantity_purchased_categories':
                            case 'value_purchased_categories':
                                $category_ids[] = !empty($condition['categories']) ? $condition['categories'] : [];
                                break;
                            case 'cart_items_attributes':
                            case 'cart_item_quantity_attributes':
                            case 'cart_item_subtotal_attributes':
                            case 'purchased_attributes':
                            case 'quantity_purchased_attributes':
                            case 'value_purchased_attributes':
                                $attribute_ids[] = !empty($condition['attributes']) ? $condition['attributes'] : [];
                                break;
                            case 'cart_items_tags':
                            case 'cart_item_quantity_tags':
                            case 'cart_item_subtotal_tags':
                            case 'purchased_tags':
                            case 'quantity_purchased_tags':
                            case 'value_purchased_tags':
                                $tag_ids[] = !empty($condition['tags']) ? $condition['tags'] : [];
                                break;
                            case 'cart_items_shipping_classes':
                            case 'cart_item_quantity_shipping_classes':
                            case 'cart_item_subtotal_shipping_classes':
                                $shipping_class_ids[] = !empty($condition['shipping_classes']) ? $condition['shipping_classes'] : [];
                                break;
                            case 'customer':
                                $customer_ids[] = !empty($condition['customers']) ? $condition['customers'] : [];
                                break;
                            case 'user_role':
                                $user_role_ids[] = !empty($condition['user_roles']) ? $condition['user_roles'] : [];
                                break;
                            case 'user_capability':
                                $user_capability_ids[] = !empty($condition['user_capabilities']) ? $condition['user_capabilities'] : [];
                                break;
                        }
                    }
                }

                if (!empty($rule['include_products'])) {
                    $product_ids[] = !empty($rule['include_products']) ? $rule['include_products'] : [];
                }
                if (!empty($rule['exclude_products'])) {
                    $product_ids[] = !empty($rule['exclude_products']) ? $rule['exclude_products'] : [];
                }
                if (!empty($rule['include_taxonomy'])) {
                    $taxonomies_ids[] = !empty($rule['include_taxonomy']) ? $rule['include_taxonomy'] : [];
                }
                if (!empty($rule['exclude_taxonomy'])) {
                    $taxonomies_ids[] = !empty($rule['exclude_taxonomy']) ? $rule['exclude_taxonomy'] : [];
                }
            }

            $output['coupons'] = (!empty($coupon_ids)) ? $product_repository->get_coupons_by_id($coupon_ids) : [];
            $output['products'] = (!empty($product_ids)) ? $product_repository->get_product_name_by_ids($product_ids) : [];
            $output['variations'] = (!empty($variation_ids)) ? $product_repository->get_variations_by_id($variation_ids) : [];
            $output['categories'] = (!empty($category_ids)) ? $product_repository->get_categories_by_id($category_ids) : [];
            $output['attributes'] = (!empty($attribute_ids)) ? $product_repository->get_attributes_by_id($attribute_ids) : [];
            $output['taxonomies'] = (!empty($taxonomies_ids)) ? $product_repository->get_taxonomies_by_id($taxonomies_ids) : [];
            $output['tags'] = (!empty($tag_ids)) ? $product_repository->get_tags_by_id($tag_ids) : [];
            $output['shipping_classes'] = (!empty($shipping_class_ids)) ? $product_repository->get_shipping_classes_by_id($shipping_class_ids) : [];
            $output['customers'] = (!empty($customer_ids)) ? $user_repository->get_users_by_id($customer_ids) : [];
            $output['user_roles'] = $user_repository->get_user_roles();
            $output['user_capabilities'] = $user_repository->get_user_capabilities();
        }

        return $this->update_option_values($output);
    }
}
