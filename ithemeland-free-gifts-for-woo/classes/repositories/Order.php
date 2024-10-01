<?php

namespace wgbl\classes\repositories;

class Order
{
    private static $instance;

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
    }

    public function get_order_statuses()
    {
        return wc_get_order_statuses();
    }

    public function get_order_countries_with_count_by_order_item_ids($item_ids)
    {
        $ids = (is_array($item_ids)) ? sanitize_text_field(implode(',', $item_ids)) : sanitize_text_field($item_ids);
        if (empty($ids)) {
            return [];
        }
        return $this->wpdb->get_results("SELECT postmeta.meta_value as country_name, count(*) as country_count FROM {$this->wpdb->posts} as order_post LEFT JOIN {$this->wpdb->prefix}woocommerce_order_items as order_items ON (order_items.order_id = order_post.ID) LEFT JOIN {$this->wpdb->prefix}postmeta as postmeta ON (order_post.ID = postmeta.post_id) WHERE order_items.order_item_id IN ({$ids}) AND postmeta.meta_key = '_billing_country' GROUP BY country_name ORDER BY country_count DESC LIMIT 5", ARRAY_A); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    }

    public function get_order_states_with_count_by_order_item_ids($item_ids)
    {
        $ids = (is_array($item_ids)) ? sanitize_text_field(implode(',', $item_ids)) : sanitize_text_field($item_ids);
        if (empty($ids)) {
            return [];
        }
        return $this->wpdb->get_results("SELECT postmeta.meta_value as state_name, postmeta2.meta_value as country_name, count(*) as state_count FROM {$this->wpdb->posts} as order_post LEFT JOIN {$this->wpdb->prefix}woocommerce_order_items as order_items ON (order_items.order_id = order_post.ID) LEFT JOIN {$this->wpdb->prefix}postmeta as postmeta ON (order_post.ID = postmeta.post_id) LEFT JOIN {$this->wpdb->prefix}postmeta as postmeta2 ON (order_post.ID = postmeta2.post_id) WHERE order_items.order_item_id IN ({$ids}) AND postmeta.meta_key = '_billing_state' AND postmeta2.meta_key = '_billing_country' GROUP BY state_name ORDER BY state_count DESC LIMIT 5", ARRAY_A); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    }

    public function get_chart1_data_by_order_item_ids($item_ids, $period)
    {
        $date_format = $this->period_to_date_format($period);
        $ids = (is_array($item_ids)) ? sanitize_text_field(implode(',', $item_ids)) : sanitize_text_field($item_ids);
        if (empty($ids)) {
            return [];
        }
        return $this->wpdb->get_results("SELECT DATE_FORMAT(order_post.post_date, '{$date_format}') as category ,count(*) as 'count' FROM {$this->wpdb->posts} as order_post LEFT JOIN {$this->wpdb->prefix}woocommerce_order_items as order_items ON (order_items.order_id = order_post.ID) WHERE order_items.order_item_id IN ({$ids}) GROUP BY DATE_FORMAT(order_post.post_date, '{$date_format}') ORDER BY (order_post.post_date) ASC", ARRAY_A); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    }

    public function get_orders_used_gift($filters = [])
    {
        $filter_query = '';
        $having_query = '';
        $limit_query = '';

        if (!empty($filters['order_id'])) {
            $order_id = intval(sanitize_text_field($filters['order_id']));
            $filter_query .= " AND orders.ID = {$order_id}";
        }

        if (!empty($filters['date']) && !empty($filters['date']['from']) && !empty($filters['date']['to'])) {
            $from = sanitize_text_field($filters['date']['from']);
            $to = sanitize_text_field($filters['date']['to']);
            $filter_query .= " AND DATE(orders.post_date) BETWEEN DATE('{$from}') AND DATE('{$to}')";
        }

        if (!empty($filters['statuses']) && is_array($filters['statuses'])) {
            $filter_query .= " AND orders.post_status IN (";
            $length = count($filters['statuses']);
            foreach ($filters['statuses'] as $status) {
                $filter_query .= "'" . sanitize_text_field($status) . "'";
                if (--$length) {
                    $filter_query .= ",";
                }
            }
            $filter_query .= ")";
        }

        if (!empty($filters['product_ids'])) {
            $product_ids = sanitize_text_field($filters['product_ids']);
            $filter_query .= " AND products.ID IN ({$product_ids})";
        }

        if (!empty($filters['rule_ids'])) {
            $rule_ids = sanitize_text_field($filters['rule_ids']);
            $filter_query .= " AND itemmeta.meta_value IN ({$rule_ids})";
        }

        if (!empty($filters['customer_email'])) {
            $customer_email = sanitize_text_field($filters['customer_email']);
            $filter_query .= " AND IF(users.user_email != '', users.user_email LIKE '%{$customer_email}%', postmeta2.meta_value LIKE '%{$customer_email}%')";
        }

        if (!empty($filters['customer_ids'])) {
            $customer_ids = sanitize_text_field($filters['customer_ids']);
            $filter_query .= " AND users.ID IN ({$customer_ids})";
        }

        if (!empty($filters['count'])) {
            $count = intval($filters['count']);
            $having_query .= "HAVING order_count = {$count}";
        }

        if (!empty($filters['limit'])) {
            $limit = intval($filters['limit']);
            $limit_query = "LIMIT {$limit}";
        }

        $group_by = 'order_id';
        if (!empty($filters['group_by'])) {
            $group_by = sanitize_text_field($filters['group_by']);
        }

        return $this->wpdb->get_results("SELECT orders.ID as order_id, orders.post_status as order_status, orders.post_date as order_date, users.ID as user_id, IF(users.ID != '', users.user_email, postmeta2.meta_value) as user_email, IF(users.ID != '', users.user_login, 'Guest') as user_login, IF(users.ID != '', users.display_name, 'Guest') as display_name, GROUP_CONCAT(itemmeta.meta_value) as rule_ids, GROUP_CONCAT(products.post_title) as product_names, count(*) as order_count FROM {$this->wpdb->posts} as orders LEFT JOIN {$this->wpdb->prefix}postmeta as postmeta ON (orders.ID = postmeta.post_id) LEFT JOIN {$this->wpdb->prefix}postmeta as postmeta2 ON (orders.ID = postmeta2.post_id) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_items as order_items ON (orders.ID = order_items.order_id) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta as itemmeta ON (order_items.order_item_id = itemmeta.order_item_id) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta as itemmeta2 ON (order_items.order_item_id = itemmeta2.order_item_id) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta as itemmeta3 ON (order_items.order_item_id = itemmeta3.order_item_id) LEFT JOIN {$this->wpdb->posts} as products ON IF(itemmeta2.meta_value = 0, itemmeta3.meta_value = products.ID, itemmeta2.meta_value = products.ID) LEFT JOIN {$this->wpdb->users} as users ON (postmeta.meta_value = users.ID) WHERE itemmeta.meta_key = '_rule_id_free_gift' AND postmeta.meta_key = '_customer_user' AND itemmeta2.meta_key = '_variation_id' AND itemmeta3.meta_key = '_product_id' AND postmeta2.meta_key = '_billing_email' {$filter_query} GROUP BY {$group_by} {$having_query} ORDER BY order_count DESC {$limit_query}", ARRAY_A); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    }

    public function get_first_order_date()
    {
        $first_order = $this->wpdb->get_row("SELECT post_date FROM {$this->wpdb->posts} WHERE post_type = 'shop_order' ORDER BY ID ASC LIMIT 1", ARRAY_A); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        return (!empty($first_order['post_date'])) ? gmdate('Y/m/d', strtotime($first_order['post_date'])) : false;
    }

    private function period_to_date_format($period)
    {
        $formats = $this->get_date_formats();
        return (!empty($formats[$period])) ? sanitize_text_field($formats[$period]) : '%M';
    }

    private function get_date_formats()
    {
        return [
            'day' => '%d',
            'week' => '%W',
            'month' => '%M',
            'year' => '%Y',
        ];
    }
}
