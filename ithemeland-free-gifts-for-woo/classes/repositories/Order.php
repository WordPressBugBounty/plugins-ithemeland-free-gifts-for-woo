<?php

namespace wgbl\classes\repositories;

defined('ABSPATH') || exit();

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
        global $wpdb;

        if (empty($item_ids) || !is_array($item_ids)) {
            return [];
        }

        $item_ids = array_filter(array_map('intval', $item_ids));
        if (empty($item_ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($item_ids), '%d'));
        $sql = "SELECT postmeta.meta_value AS country_name, COUNT(*) AS country_count
            FROM {$wpdb->posts} AS order_post
            LEFT JOIN {$wpdb->prefix}woocommerce_order_items AS order_items
                ON order_items.order_id = order_post.ID
            LEFT JOIN {$wpdb->prefix}postmeta AS postmeta
                ON order_post.ID = postmeta.post_id
            WHERE order_items.order_item_id IN ($placeholders)
                AND postmeta.meta_key = '_billing_country'
            GROUP BY country_name
            ORDER BY country_count DESC
            LIMIT 5
        ";

        return $wpdb->get_results($wpdb->prepare($sql, ...$item_ids), ARRAY_A); //phpcs:ignore
    }

    public function get_order_states_with_count_by_order_item_ids($item_ids)
    {
        global $wpdb;

        if (!is_array($item_ids) || empty($item_ids)) {
            return [];
        }

        $item_ids = array_filter(array_map('intval', $item_ids));
        if (empty($item_ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($item_ids), '%d'));
        $sql = "SELECT 
                postmeta.meta_value AS state_name, 
                postmeta2.meta_value AS country_name, 
                COUNT(*) AS state_count
            FROM {$wpdb->posts} AS order_post
            LEFT JOIN {$wpdb->prefix}woocommerce_order_items AS order_items
                ON order_items.order_id = order_post.ID
            LEFT JOIN {$wpdb->prefix}postmeta AS postmeta
                ON order_post.ID = postmeta.post_id
            LEFT JOIN {$wpdb->prefix}postmeta AS postmeta2
                ON order_post.ID = postmeta2.post_id
            WHERE order_items.order_item_id IN ($placeholders)
                AND postmeta.meta_key = '_billing_state'
                AND postmeta2.meta_key = '_billing_country'
            GROUP BY state_name
            ORDER BY state_count DESC
            LIMIT 5
        ";

        return $wpdb->get_results($wpdb->prepare($sql, ...$item_ids), ARRAY_A); //phpcs:ignore
    }

    public function get_chart1_data_by_order_item_ids($item_ids, $period)
    {
        global $wpdb;

        $date_format = esc_sql($this->period_to_date_format($period));

        if (!is_array($item_ids) || empty($item_ids)) {
            return [];
        }

        $item_ids = array_filter(array_map('intval', $item_ids));
        if (empty($item_ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($item_ids), '%d'));
        $sql = "SELECT 
                DATE_FORMAT(order_post.post_date, '{$date_format}') AS category,
                COUNT(*) AS count
            FROM {$wpdb->posts} AS order_post
            LEFT JOIN {$wpdb->prefix}woocommerce_order_items AS order_items 
                ON order_items.order_id = order_post.ID
            WHERE order_items.order_item_id IN ({$placeholders})
            GROUP BY DATE_FORMAT(order_post.post_date, '{$date_format}')
            ORDER BY order_post.post_date ASC
        ";

        $chart_data = $wpdb->get_results($wpdb->prepare($sql, ...$item_ids), ARRAY_A); //phpcs:ignore
        if (empty($chart_data)) {
            return [
                'labels' => $labels,
                'values' => $values,
            ];
        }

        $labels = [];
        $values = [];

        foreach ($chart_data as $item) {
            if (empty($item['category'])) {
                continue;
            }
            $labels[] = $item['category'];
            $values[] = $item['count'];
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    public function get_orders_used_gift($filters = [])
    {
        global $wpdb;

        $where = [
            "itemmeta.meta_key = '_rule_id_free_gift'",
            "postmeta.meta_key = '_customer_user'",
            "itemmeta2.meta_key = '_variation_id'",
            "itemmeta3.meta_key = '_product_id'",
            "postmeta2.meta_key = '_billing_email'"
        ];

        $params = [];

        // Order ID
        if (!empty($filters['order_id'])) {
            $where[] = "orders.ID = %d";
            $params[] = intval($filters['order_id']);
        }

        // Date Range
        if (!empty($filters['date']['from']) && !empty($filters['date']['to'])) {
            $where[] = "DATE(orders.post_date) BETWEEN %s AND %s";
            $params[] = $filters['date']['from'];
            $params[] = $filters['date']['to'];
        }

        // Statuses
        if (!empty($filters['statuses']) && is_array($filters['statuses'])) {
            $placeholders = implode(',', array_fill(0, count($filters['statuses']), '%s'));
            $where[] = "orders.post_status IN ($placeholders)";
            $params = array_merge($params, array_map('sanitize_text_field', $filters['statuses']));
        }

        // Product IDs
        if (!empty($filters['product_ids'])) {
            $ids = array_filter(array_map('intval', explode(',', $filters['product_ids'])));
            if ($ids) {
                $placeholders = implode(',', array_fill(0, count($ids), '%d'));
                $where[] = "products.ID IN ($placeholders)";
                $params = array_merge($params, $ids);
            }
        }

        // Rule IDs
        if (!empty($filters['rule_ids'])) {
            $ids = array_filter(array_map('intval', explode(',', $filters['rule_ids'])));
            if ($ids) {
                $placeholders = implode(',', array_fill(0, count($ids), '%d'));
                $where[] = "itemmeta.meta_value IN ($placeholders)";
                $params = array_merge($params, $ids);
            }
        }

        // Email filter
        if (!empty($filters['customer_email'])) {
            $where[] = "(users.user_email LIKE %s OR postmeta2.meta_value LIKE %s)";
            $params[] = '%' . sanitize_email($filters['customer_email']) . '%';
            $params[] = '%' . sanitize_email($filters['customer_email']) . '%';
        }

        // Customer IDs
        if (!empty($filters['customer_ids'])) {
            $ids = array_filter(array_map('intval', explode(',', $filters['customer_ids'])));
            if ($ids) {
                $placeholders = implode(',', array_fill(0, count($ids), '%d'));
                $where[] = "users.ID IN ($placeholders)";
                $params = array_merge($params, $ids);
            }
        }

        $having = '';
        if (!empty($filters['count'])) {
            $having = 'HAVING order_count = %d';
            $params[] = intval($filters['count']);
        }

        $limit = '';
        if (!empty($filters['limit'])) {
            $limit = 'LIMIT %d';
            $params[] = intval($filters['limit']);
        }

        $group_by = 'order_id';
        if (!empty($filters['group_by'])) {
            $group_by = preg_replace('/[^a-zA-Z0-9_]/', '', $filters['group_by']);
        }

        $sql = "SELECT 
                orders.ID AS order_id,
                orders.post_status AS order_status,
                orders.post_date AS order_date,
                users.ID AS user_id,
                IF(users.ID != '', users.user_email, postmeta2.meta_value) AS user_email,
                IF(users.ID != '', users.user_login, 'Guest') AS user_login,
                IF(users.ID != '', users.display_name, 'Guest') AS display_name,
                GROUP_CONCAT(itemmeta.meta_value) AS rule_ids,
                GROUP_CONCAT(products.post_title) AS product_names,
                COUNT(*) AS order_count
            FROM {$wpdb->posts} AS orders
            LEFT JOIN {$wpdb->prefix}postmeta AS postmeta ON orders.ID = postmeta.post_id
            LEFT JOIN {$wpdb->prefix}postmeta AS postmeta2 ON orders.ID = postmeta2.post_id
            LEFT JOIN {$wpdb->prefix}woocommerce_order_items AS order_items ON orders.ID = order_items.order_id
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS itemmeta ON order_items.order_item_id = itemmeta.order_item_id
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS itemmeta2 ON order_items.order_item_id = itemmeta2.order_item_id
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS itemmeta3 ON order_items.order_item_id = itemmeta3.order_item_id
            LEFT JOIN {$wpdb->posts} AS products ON IF(itemmeta2.meta_value = 0, itemmeta3.meta_value = products.ID, itemmeta2.meta_value = products.ID)
            LEFT JOIN {$wpdb->users} AS users ON postmeta.meta_value = users.ID
            WHERE " . implode(' AND ', $where) . "
            GROUP BY {$group_by}
            {$having}
            ORDER BY order_count DESC
            {$limit}
        ";

        return $wpdb->get_results($wpdb->prepare($sql, ...$params), ARRAY_A); //phpcs:ignore
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
