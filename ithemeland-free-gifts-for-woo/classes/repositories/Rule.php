<?php

namespace wgb\classes\repositories;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wgb\classes\helpers\Sanitizer;

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

        $this->option_name = "wgb_rules";
    }

    public function update($rules)
    {
        if (!empty($rules['option_values'])) {
            $this->update_option_values(Sanitizer::array($rules['option_values']));
        }

        $this->set_option_cache($rules);
        return update_option($this->option_name, $rules);
    }

    public function get()
    {
        $validated_rules = get_option($this->option_name);
        $validated_rules = apply_filters('itfg_validated_rules_filtered', $validated_rules);
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
        $rules = $this->get();
        $this->set_option_cache($rules);
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

        $value_trans = 'gifts';
        $return_query = [];
        $id = $rule['uid'];
        //delete_transient('pw_' . $value_trans . '_cache_simple_variation_' . $id);
        //delete_transient('pw_' . $value_trans . '_cache_simple_childes_' . $id);
        $include_product = isset($rule['include_products']) ? $rule['include_products'] : "";
        $exclude_product = isset($rule['exclude_products']) ? $rule['exclude_products'] : "";
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

        $product_ids = '';
        if (is_array($include_product)) {
            $product_ids = implode(",", $include_product);

            $in_product_condition_1 = " AND pw_posts.ID IN ($product_ids) ";
            $in_product_condition_2 = "  AND (pw_posts.ID IN ($product_ids) OR pw_products.ID IN ($product_ids)) ";
        }

        if ($exclude_product) {
            $product_ids = implode(",", $exclude_product);

            $ex_product_condition_1 = " AND pw_posts.ID NOT IN ($product_ids) ";
            $ex_product_condition_2 = "  AND (pw_posts.ID NOT IN ($product_ids) AND pw_products.ID NOT IN ($product_ids)) ";
        }

        if ($include_taxonomy && !is_array($include_product)) {
            $terms_id = [];
            foreach ($include_taxonomy as $inc_tax) {
                $tax = explode("__", $inc_tax);
                $terms_id[] = $tax[1];
            }
            $terms_id = implode(",", $terms_id);

            $in_tax_condition_1 = " AND ( pw_posts.ID IN ( SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_id) ) ) ";
            $in_tax_condition_2 = " AND ( pw_posts.post_parent IN ( SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_id) ) OR pw_products.ID IN ( SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_id) ) ) ";
        }

        if ($exclude_taxonomy && !is_array($include_product)) {

            $terms_id = [];
            foreach ($exclude_taxonomy as $ex_tax) {
                $tax = explode("__", $ex_tax);
                $terms_id[] = $tax[1];
            }

            $terms_id = implode(",", $terms_id);

            $ex_tax_condition_1 = " AND ( pw_posts.ID NOT IN ( SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_id) ) ) ";
            $ex_tax_condition_2 = " AND ( pw_posts.post_parent NOT IN ( SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_id) ) AND  pw_products.ID NOT IN ( SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_id) )) ";
        }

        $simple_variation = "SELECT pw_posts.post_title as product_name ,pw_posts.post_date as product_date ,pw_posts.post_modified as modified_date ,pw_posts.ID as product_id FROM {$this->wpdb->prefix}posts as pw_posts   WHERE pw_posts.post_type='product' AND pw_posts.post_status = 'publish' $in_tax_condition_1 $in_product_condition_1 $ex_tax_condition_1 $ex_product_condition_1 GROUP BY product_id";

        $result = $this->wpdb->get_results($simple_variation); //phpcs:ignore

        $simple_variation_arrray = [];
        foreach ($result as $items) {
            $simple_variation_arrray[] = $items->product_id;
        }

        if (is_array($include_product)) {
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

        $simple_childes = "SELECT pw_posts.ID as id ,pw_posts.post_title as variation_name	,pw_posts.ID as variation_id ,pw_posts.post_date as product_date ,pw_posts.post_modified as modified_date ,pw_products.ID as product_id ,pw_products.post_title as product_name ,pw_posts.post_parent AS variation_parent_id FROM {$this->wpdb->prefix}posts as pw_posts LEFT JOIN {$this->wpdb->prefix}posts as pw_products ON pw_products.ID = pw_posts.post_parent LEFT JOIN {$this->wpdb->prefix}term_relationships AS term_relationships ON pw_products.ID = term_relationships.object_id LEFT JOIN {$this->wpdb->prefix}term_taxonomy AS term_taxonomy ON term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id LEFT JOIN {$this->wpdb->prefix}terms AS terms ON term_taxonomy.term_id = terms.term_id  WHERE term_taxonomy.taxonomy = 'product_type' AND terms.slug = 'variable' AND pw_posts.post_type='product_variation' AND pw_posts.post_status = 'publish' AND pw_products.post_type='product' AND pw_posts.post_parent > 0 $in_tax_condition_2 $in_product_condition_2  $ex_tax_condition_2 $ex_product_condition_2  GROUP BY pw_posts.ID ORDER BY pw_posts.post_parent ASC, pw_posts.post_title ASC";

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
        return $return_query;
    }

    private function set_option_cache($rules)
    {
        if (!is_array($rules['items']) || count($rules['items']) <= 0) {
            return false;
        }
        $value_trans = 'gifts';

        foreach ($rules['items'] as $rule) {
            $id = $rule['uid'];
            delete_transient('pw_' . $value_trans . '_cache_simple_variation_' . $id);
            delete_transient('pw_' . $value_trans . '_cache_simple_childes_' . $id);

            if ($rule['status'] == 'disable') {
                continue;
            }
            $args = $rule;

            $include_product = isset($args['include_products']) ? $args['include_products'] : "";
            $exclude_product = isset($args['exclude_products']) ? $args['exclude_products'] : "";
            $include_taxonomy = isset($args['include_taxonomy']) ? $args['include_taxonomy'] : "";
            $exclude_taxonomy = isset($args['exclude_taxonomy']) ? $args['exclude_taxonomy'] : "";

            $ex_product_condition_1 = "";
            $ex_product_condition_2 = "";

            $in_product_condition_1 = '';
            $in_product_condition_2 = '';

            $in_tax_condition_1 = '';
            $in_tax_condition_2 = '';

            $ex_tax_condition_1 = "";
            $ex_tax_condition_2 = "";

            $product_ids = '';
            if (is_array($include_product)) {
                $product_ids = implode(",", $include_product);

                $in_product_condition_1 = " AND pw_posts.ID IN ($product_ids) ";
                $in_product_condition_2 = "  AND (pw_posts.ID IN ($product_ids) OR pw_products.ID IN ($product_ids)) ";
            }

            if ($exclude_product) {
                $product_ids = implode(",", $exclude_product);

                $ex_product_condition_1 = " AND pw_posts.ID NOT IN ($product_ids) ";
                $ex_product_condition_2 = "  AND (pw_posts.ID NOT IN ($product_ids) AND pw_products.ID NOT IN ($product_ids)) ";
            }

            if ($include_taxonomy && !is_array($include_product)) {
                $terms_id = [];
                foreach ($include_taxonomy as $inc_tax) {
                    $tax = explode("__", $inc_tax);
                    $terms_id[] = $tax[1];
                }
                $terms_id = implode(",", $terms_id);

                $in_tax_condition_1 = " AND ( pw_posts.ID IN ( SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_id) ) ) ";
                $in_tax_condition_2 = " AND ( pw_posts.post_parent IN ( SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_id) ) OR pw_products.ID IN ( SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_id) ) ) ";
            }

            if ($exclude_taxonomy && !is_array($include_product)) {

                $terms_id = [];
                foreach ($exclude_taxonomy as $ex_tax) {
                    $tax = explode("__", $ex_tax);
                    $terms_id[] = $tax[1];
                }

                $terms_id = implode(",", $terms_id);

                $ex_tax_condition_1 = " AND ( pw_posts.ID NOT IN ( SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_id) ) ) ";
                $ex_tax_condition_2 = " AND ( pw_posts.post_parent NOT IN ( SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_id) ) AND  pw_products.ID NOT IN ( SELECT object_id FROM {$this->wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($terms_id) )) ";
            }

            $simple_variation = "SELECT pw_posts.post_title as product_name ,pw_posts.post_date as product_date ,pw_posts.post_modified as modified_date ,pw_posts.ID as product_id FROM {$this->wpdb->prefix}posts as pw_posts   WHERE pw_posts.post_type='product' AND pw_posts.post_status = 'publish' $in_tax_condition_1 $in_product_condition_1 $ex_tax_condition_1 $ex_product_condition_1 GROUP BY product_id";

            $result = $this->wpdb->get_results($simple_variation); //phpcs:ignore

            $simple_variation_arrray = [];
            foreach ($result as $items) {
                $simple_variation_arrray[] = $items->product_id;
            }

            if (is_array($include_product)) {
                $simple_variation_arrray = array_merge($include_product, $simple_variation_arrray);
                $simple_variation_arrray = array_filter($simple_variation_arrray);
                $simple_variation_arrray = array_unique($simple_variation_arrray);
                set_transient('pw_' . $value_trans . '_cache_simple_variation_' . $id, $simple_variation_arrray);
            } else {
                $simple_variation_arrray = array_unique($simple_variation_arrray);
                set_transient('pw_' . $value_trans . '_cache_simple_variation_' . $id, $simple_variation_arrray);
            }

            $simple_childes = "SELECT pw_posts.ID as id ,pw_posts.post_title as variation_name	,pw_posts.ID as variation_id ,pw_posts.post_date as product_date ,pw_posts.post_modified as modified_date ,pw_products.ID as product_id ,pw_products.post_title as product_name ,pw_posts.post_parent AS variation_parent_id FROM {$this->wpdb->prefix}posts as pw_posts LEFT JOIN {$this->wpdb->prefix}posts as pw_products ON pw_products.ID = pw_posts.post_parent LEFT JOIN {$this->wpdb->prefix}term_relationships AS term_relationships ON pw_products.ID = term_relationships.object_id LEFT JOIN {$this->wpdb->prefix}term_taxonomy AS term_taxonomy ON term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id LEFT JOIN {$this->wpdb->prefix}terms AS terms ON term_taxonomy.term_id = terms.term_id  WHERE term_taxonomy.taxonomy = 'product_type' AND terms.slug = 'variable' AND pw_posts.post_type='product_variation' AND pw_posts.post_status = 'publish' AND pw_products.post_type='product' AND pw_posts.post_parent > 0 $in_tax_condition_2 $in_product_condition_2  $ex_tax_condition_2 $ex_product_condition_2  GROUP BY pw_posts.ID ORDER BY pw_posts.post_parent ASC, pw_posts.post_title ASC";

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
            set_transient('pw_' . $value_trans . '_cache_simple_childes_' . $id, $simple_childes_final_arrray);
        }
    }
}
