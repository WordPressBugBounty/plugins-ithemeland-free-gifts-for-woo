<?php

namespace wgbl\classes\repositories;

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
            'simple' => esc_html__('Simple', 'ithemeland-free-gifts-for-woocommerce-lite'),
            'tiered_quantity' => esc_html__('Tiered Quantity - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite'),
            'bulk_quantity' => esc_html__('Bulk Quantity - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite'),
            'bulk_pricing' => esc_html__('Bulk Pricing - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite'),
            'buy_x_get_x' => esc_html__('Buy x get x - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite'),
            'buy_x_get_x_repeat' => esc_html__('Buy x get x repeat - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite'),
            'buy_x_get_y' => esc_html__('Buy x get y - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite'),
            'buy_x_get_y_repeat' => esc_html__('Buy x get y repeat - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite'),
            'subtotal' => esc_html__('Subtotal', 'ithemeland-free-gifts-for-woocommerce-lite'),
            'subtotal_repeat' => esc_html__('Subtotal repeat - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite'),
            'subtotal_repeat' => esc_html__('Subtotal repeat - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite'),
            'cheapest_item_in_cart' => esc_html__('Cheapest item in cart - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite'),
        ];
    }

    public function get_rule_method_options()
    {
        $output = '<optgroup label="' . esc_html__('Simple', 'ithemeland-free-gifts-for-woocommerce-lite') . '">
            <option value="simple">' . esc_html__('Simple', 'ithemeland-free-gifts-for-woocommerce-lite') . '</option>
            </optgroup>

            <optgroup label="' . esc_html__('Cart Subtotal', 'ithemeland-free-gifts-for-woocommerce-lite') . '">
            <option value="subtotal">' . esc_html__('Subtotal', 'ithemeland-free-gifts-for-woocommerce-lite') . '</option>
            <option value="subtotal_repeat" style="color: red;">' . esc_html__('Subtotal repeat - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite') . '</option>
            </optgroup>

            <optgroup label="' . esc_html__('Tiered', 'ithemeland-free-gifts-for-woocommerce-lite') . '">
            <option value="tiered_quantity" style="color: red;">' . esc_html__('Tiered Quantity - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite') . '</option>
            </optgroup>

            <optgroup label="' . esc_html__('Bulk', 'ithemeland-free-gifts-for-woocommerce-lite') . '">
            <option value="bulk_quantity" style="color: red;">' . esc_html__('Bulk Quantity - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite') . '</option>
            <option value="bulk_pricing" style="color: red;">' . esc_html__('Bulk Pricing - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite') . '</option>
            </optgroup>

            <optgroup label="' . esc_html__('Buy / Get', 'ithemeland-free-gifts-for-woocommerce-lite') . '">
            <option value="buy_x_get_x" style="color: red;">' . esc_html__('Buy x get x - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite') . '</option>
            <option value="buy_x_get_x_repeat" style="color: red;">' . esc_html__('Buy x get x repeat - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite') . '</option>
            <option value="buy_x_get_y" style="color: red;">' . esc_html__('Buy x get y - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite') . '</option>
            <option value="buy_x_get_y_repeat" style="color: red;">' . esc_html__('Buy x get y repeat - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite') . '</option>
            </optgroup>
            
            <optgroup label="' . esc_html__('Other', 'ithemeland-free-gifts-for-woocommerce-lite') . '">
            <option value="cheapest_item_in_cart" style="color: red;">' . esc_html__('Cheapest item in cart - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite') . '</option>
            </optgroup>';

        return $output;
    }

    public function get_rule_methods_grouped()
    {
        return [
            'simple' => [
                'label' => esc_html__('Simple', 'ithemeland-free-gifts-for-woocommerce-lite'),
                'methods' => [
                    'simple' => esc_html__('Simple', 'ithemeland-free-gifts-for-woocommerce-lite'),
                ],
            ],
            'cart_subtotal' => [
                'label' => esc_html__('Cart Subtotal', 'ithemeland-free-gifts-for-woocommerce-lite'),
                'methods' => [
                    'subtotal' => esc_html__('Subtotal', 'ithemeland-free-gifts-for-woocommerce-lite'),
                    'subtotal_repeat' => esc_html__('Subtotal repeat - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite'),
                ],
            ],
            'tiered' => [
                'label' => esc_html__('Tiered', 'ithemeland-free-gifts-for-woocommerce-lite'),
                'methods' => [
                    'tiered_quantity' => esc_html__('Tiered Quantity - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite'),
                ],
            ],
            'bulk' => [
                'label' => esc_html__('Bulk', 'ithemeland-free-gifts-for-woocommerce-lite'),
                'methods' => [
                    'bulk_quantity' => esc_html__('Bulk Quantity - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite'),
                    'bulk_pricing' => esc_html__('Bulk Pricing - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite'),
                ],
            ],
            'buy_get' => [
                'label' => esc_html__('Buy / Get', 'ithemeland-free-gifts-for-woocommerce-lite'),
                'methods' => [
                    'buy_x_get_x' => esc_html__('Buy x get x - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite'),
                    'buy_x_get_x_repeat' => esc_html__('Buy x get x repeat - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite'),
                    'buy_x_get_y' => esc_html__('Buy x get y - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite'),
                    'buy_x_get_y_repeat' => esc_html__('Buy x get y repeat - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite'),
                ],
            ],
            'other' => [
                'label' => esc_html__('Other', 'ithemeland-free-gifts-for-woocommerce-lite'),
                'methods' => [
                    'cheapest_item_in_cart' => esc_html__('Cheapest item in cart - Pro version', 'ithemeland-free-gifts-for-woocommerce-lite'),
                ],
            ],
        ];
    }

    public function get_all_options()
    {
        return get_option('wgbl_option_values');
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
        $date_query = '';
        if (!is_null($from_date) && !is_null($to_date)) {
            $from = gmdate('Y-m-d H:i:s', strtotime($from_date));
            $to = gmdate('Y-m-d H:i:s', strtotime($to_date));
            $date_query = "AND orders.post_date BETWEEN '{$from}' AND '{$to}'";
        }

        return $this->wpdb->get_results("SELECT itemmeta.order_item_id, itemmeta.meta_value FROM {$this->wpdb->posts} as orders LEFT JOIN {$this->wpdb->prefix}woocommerce_order_items as order_items ON (order_items.order_id = orders.ID) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta as itemmeta ON (order_items.order_item_id = itemmeta.order_item_id) WHERE itemmeta.meta_key = '_rule_id_free_gift' {$date_query}", ARRAY_A); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
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

        return $this->wpdb->get_results("SELECT orders.ID as order_id, orders.post_date as order_date, IF(users.user_login != '', users.user_login, 'Guest') as user_login, IF(users.user_email != '', users.user_email, postmeta2.meta_value) as user_email, itemmeta.order_item_id, itemmeta.meta_value as rule_id FROM {$this->wpdb->posts} as orders LEFT JOIN {$this->wpdb->prefix}postmeta as postmeta ON (orders.ID = postmeta.post_id) LEFT JOIN {$this->wpdb->prefix}postmeta as postmeta2 ON (orders.ID = postmeta2.post_id) LEFT JOIN {$this->wpdb->users} as users ON (users.ID = postmeta.meta_value) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_items as order_items ON (order_items.order_id = orders.ID) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta as itemmeta ON (order_items.order_item_id = itemmeta.order_item_id) WHERE itemmeta.meta_key = '_rule_id_free_gift' AND postmeta.meta_key = '_customer_user' AND postmeta2.meta_key = '_billing_email' {$filter_query}", ARRAY_A); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    }

    public function get_total_customers_used_gift($from_date = null, $to_date = null)
    {
        $date_query = '';
        if (!is_null($from_date) && !is_null($to_date)) {
            $from = gmdate('Y-m-d H:i:s', strtotime($from_date));
            $to = gmdate('Y-m-d H:i:s', strtotime($to_date));
            $date_query = "AND orders.post_date BETWEEN '{$from}' AND '{$to}'";
        }
        return $this->wpdb->get_results("SELECT DISTINCT postmeta.meta_value as customer_id FROM {$this->wpdb->posts} as orders LEFT JOIN {$this->wpdb->prefix}postmeta as postmeta ON (postmeta.post_id = orders.ID) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_items as order_items ON (order_items.order_id = orders.ID) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta as itemmeta ON (order_items.order_item_id = itemmeta.order_item_id) WHERE itemmeta.meta_key = '_rule_id_free_gift' AND postmeta.meta_key = '_customer_user' {$date_query} GROUP BY customer_id", ARRAY_A); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    }

    public function get_option_cache($rule)
    {
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

       // $simple_variation = "SELECT pw_posts.post_title as product_name ,pw_posts.post_date as product_date ,pw_posts.post_modified as modified_date ,pw_posts.ID as product_id FROM {$this->wpdb->prefix}posts as pw_posts   WHERE pw_posts.post_type='product' AND pw_posts.post_status = 'publish' $in_tax_condition_1 $in_product_condition_1 $ex_tax_condition_1 $ex_product_condition_1 GROUP BY product_id";

        $result = $this->wpdb->get_results("SELECT pw_posts.post_title as product_name ,pw_posts.post_date as product_date ,pw_posts.post_modified as modified_date ,pw_posts.ID as product_id FROM {$this->wpdb->prefix}posts as pw_posts   WHERE pw_posts.post_type='product' AND pw_posts.post_status = 'publish' $in_tax_condition_1 $in_product_condition_1 $ex_tax_condition_1 $ex_product_condition_1 GROUP BY product_id"); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

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

        //$simple_childes = "SELECT pw_posts.ID as id ,pw_posts.post_title as variation_name	,pw_posts.ID as variation_id ,pw_posts.post_date as product_date ,pw_posts.post_modified as modified_date ,pw_products.ID as product_id ,pw_products.post_title as product_name ,pw_posts.post_parent AS variation_parent_id FROM {$this->wpdb->prefix}posts as pw_posts LEFT JOIN {$this->wpdb->prefix}posts as pw_products ON pw_products.ID = pw_posts.post_parent LEFT JOIN {$this->wpdb->prefix}term_relationships AS term_relationships ON pw_products.ID = term_relationships.object_id LEFT JOIN {$this->wpdb->prefix}term_taxonomy AS term_taxonomy ON term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id LEFT JOIN {$this->wpdb->prefix}terms AS terms ON term_taxonomy.term_id = terms.term_id  WHERE term_taxonomy.taxonomy = 'product_type' AND terms.slug = 'variable' AND pw_posts.post_type='product_variation' AND pw_posts.post_status = 'publish' AND pw_products.post_type='product' AND pw_posts.post_parent > 0 $in_tax_condition_2 $in_product_condition_2  $ex_tax_condition_2 $ex_product_condition_2  GROUP BY pw_posts.ID ORDER BY pw_posts.post_parent ASC, pw_posts.post_title ASC";

        $result = $this->wpdb->get_results("SELECT pw_posts.ID as id ,pw_posts.post_title as variation_name	,pw_posts.ID as variation_id ,pw_posts.post_date as product_date ,pw_posts.post_modified as modified_date ,pw_products.ID as product_id ,pw_products.post_title as product_name ,pw_posts.post_parent AS variation_parent_id FROM {$this->wpdb->prefix}posts as pw_posts LEFT JOIN {$this->wpdb->prefix}posts as pw_products ON pw_products.ID = pw_posts.post_parent LEFT JOIN {$this->wpdb->prefix}term_relationships AS term_relationships ON pw_products.ID = term_relationships.object_id LEFT JOIN {$this->wpdb->prefix}term_taxonomy AS term_taxonomy ON term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id LEFT JOIN {$this->wpdb->prefix}terms AS terms ON term_taxonomy.term_id = terms.term_id  WHERE term_taxonomy.taxonomy = 'product_type' AND terms.slug = 'variable' AND pw_posts.post_type='product_variation' AND pw_posts.post_status = 'publish' AND pw_products.post_type='product' AND pw_posts.post_parent > 0 $in_tax_condition_2 $in_product_condition_2  $ex_tax_condition_2 $ex_product_condition_2  GROUP BY pw_posts.ID ORDER BY pw_posts.post_parent ASC, pw_posts.post_title ASC"); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
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

            //$simple_variation = "SELECT pw_posts.post_title as product_name ,pw_posts.post_date as product_date ,pw_posts.post_modified as modified_date ,pw_posts.ID as product_id FROM {$this->wpdb->prefix}posts as pw_posts   WHERE pw_posts.post_type='product' AND pw_posts.post_status = 'publish' $in_tax_condition_1 $in_product_condition_1 $ex_tax_condition_1 $ex_product_condition_1 GROUP BY product_id";

            $result = $this->wpdb->get_results("SELECT pw_posts.post_title as product_name ,pw_posts.post_date as product_date ,pw_posts.post_modified as modified_date ,pw_posts.ID as product_id FROM {$this->wpdb->prefix}posts as pw_posts   WHERE pw_posts.post_type='product' AND pw_posts.post_status = 'publish' $in_tax_condition_1 $in_product_condition_1 $ex_tax_condition_1 $ex_product_condition_1 GROUP BY product_id"); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

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

            //$simple_childes = "SELECT pw_posts.ID as id ,pw_posts.post_title as variation_name	,pw_posts.ID as variation_id ,pw_posts.post_date as product_date ,pw_posts.post_modified as modified_date ,pw_products.ID as product_id ,pw_products.post_title as product_name ,pw_posts.post_parent AS variation_parent_id FROM {$this->wpdb->prefix}posts as pw_posts LEFT JOIN {$this->wpdb->prefix}posts as pw_products ON pw_products.ID = pw_posts.post_parent LEFT JOIN {$this->wpdb->prefix}term_relationships AS term_relationships ON pw_products.ID = term_relationships.object_id LEFT JOIN {$this->wpdb->prefix}term_taxonomy AS term_taxonomy ON term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id LEFT JOIN {$this->wpdb->prefix}terms AS terms ON term_taxonomy.term_id = terms.term_id  WHERE term_taxonomy.taxonomy = 'product_type' AND terms.slug = 'variable' AND pw_posts.post_type='product_variation' AND pw_posts.post_status = 'publish' AND pw_products.post_type='product' AND pw_posts.post_parent > 0 $in_tax_condition_2 $in_product_condition_2  $ex_tax_condition_2 $ex_product_condition_2  GROUP BY pw_posts.ID ORDER BY pw_posts.post_parent ASC, pw_posts.post_title ASC";

            $result = $this->wpdb->get_results("SELECT pw_posts.ID as id ,pw_posts.post_title as variation_name	,pw_posts.ID as variation_id ,pw_posts.post_date as product_date ,pw_posts.post_modified as modified_date ,pw_products.ID as product_id ,pw_products.post_title as product_name ,pw_posts.post_parent AS variation_parent_id FROM {$this->wpdb->prefix}posts as pw_posts LEFT JOIN {$this->wpdb->prefix}posts as pw_products ON pw_products.ID = pw_posts.post_parent LEFT JOIN {$this->wpdb->prefix}term_relationships AS term_relationships ON pw_products.ID = term_relationships.object_id LEFT JOIN {$this->wpdb->prefix}term_taxonomy AS term_taxonomy ON term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id LEFT JOIN {$this->wpdb->prefix}terms AS terms ON term_taxonomy.term_id = terms.term_id  WHERE term_taxonomy.taxonomy = 'product_type' AND terms.slug = 'variable' AND pw_posts.post_type='product_variation' AND pw_posts.post_status = 'publish' AND pw_products.post_type='product' AND pw_posts.post_parent > 0 $in_tax_condition_2 $in_product_condition_2  $ex_tax_condition_2 $ex_product_condition_2  GROUP BY pw_posts.ID ORDER BY pw_posts.post_parent ASC, pw_posts.post_title ASC"); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
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
