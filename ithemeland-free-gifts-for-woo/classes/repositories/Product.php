<?php

namespace wgbl\classes\repositories;

use wgbl\classes\helpers\Array_Helper;

defined('ABSPATH') || exit();

class Product
{
    private static $instance;

    private $wpdb;
    private $gift_product_ids;
    private $product_category_with_count;

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

    public function get_product($product_id)
    {
        return wc_get_product(intval($product_id));
    }

    public function get_products($args)
    {
        $posts = new \WP_Query($args);
        return $posts;
    }

    public function get_product_object_by_ids($ids)
    {
        if (empty($ids) || !is_array($ids)) {
            return false;
        }

        return wc_get_products([
            'type' => ['simple', 'variable', 'grouped', 'external', 'variation'],
            'include' => array_map('intval', $ids),
            'orderby' => 'include',
            'limit' => -1
        ]);
    }

    public function get_taxonomies()
    {
        return get_object_taxonomies([
            'product'
        ]);
    }

    public function get_taxonomies_by_name($name)
    {
        $output = [];
        $taxonomies = $this->get_taxonomies();
        if (!empty($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                $output[$taxonomy] = get_terms([
                    'taxonomy' => $taxonomy,
                    'hide_empty' => false,
                    'name__like' => strtolower(sanitize_text_field($name))
                ]);
            }
        }
        return $output;
    }

    public function get_tags_by_name($name)
    {
        return get_terms([
            'taxonomy' => 'product_tag',
            'hide_empty' => false,
            'name__like' => strtolower(sanitize_text_field($name))
        ]);
    }

    public function get_categories_by_name($name)
    {
        return get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'name__like' => strtolower(sanitize_text_field($name))
        ]);
    }

    public function get_attributes_by_name($name)
    {
        $output = [];
        $taxonomies = $this->get_taxonomies();
        if (!empty($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                if (mb_substr($taxonomy, 0, 3) == 'pa_') {
                    $output[mb_substr($taxonomy, 3)] = get_terms([
                        'taxonomy' => $taxonomy,
                        'hide_empty' => false,
                        'name__like' => strtolower(sanitize_text_field($name))
                    ]);
                }
            }
        }
        return $output;
    }

    public function get_shipping_classes()
    {
        return wc()->shipping()->get_shipping_classes();
    }

    public function get_ids_by_custom_query($join = '', $where = [], $types_in = 'all')
    {
        global $wpdb;

        $allowed_types = [
            'all' => "'product','product_variation','shop_coupon'",
            'product' => "'product'",
            'shop_coupon' => "'shop_coupon'",
            'product_variation' => "'product_variation'",
        ];

        $types = $allowed_types[$types_in] ?? $allowed_types['all'];
        $join = trim($join);

        if (empty($where) || !isset($where['clause']) || !isset($where['params']) || empty($where['clause'])) {
            return '';
        }

        $sql = "SELECT posts.ID, posts.post_parent
            FROM {$wpdb->posts} AS posts
            {$join}
            WHERE posts.post_type IN ({$types})
            AND ({$where['clause']})
        ";

        $prepared_sql = $wpdb->prepare($sql, ...$where['params']); //phpcs:ignore
        $results = $wpdb->get_results($prepared_sql, ARRAY_N); //phpcs:ignore

        $ids = array_unique(array_map('intval', wp_list_pluck($results, 0)));
        $key = array_search(0, $ids);
        if ($key !== false) {
            unset($ids[$key]);
        }

        return implode(',', $ids);
    }

    public function get_taxonomies_by_id($taxonomies_ids)
    {
        if (empty($taxonomies_ids)) {
            return null;
        }
        $ids = [];
        foreach (Array_Helper::flatten($taxonomies_ids) as $taxonomy) {
            $ids[] = (explode('__', $taxonomy))[1];
        }
        if (empty($ids)) {
            return false;
        }

        $output = [];
        $taxonomies = get_terms([
            'include' => Array_Helper::flatten($ids),
            'hide_empty' => false,
        ]);
        if (!empty($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                if ($taxonomy instanceof \WP_Term) {
                    $output[$taxonomy->taxonomy . '__' . $taxonomy->term_id] = $taxonomy->taxonomy . ': ' . $taxonomy->name;
                }
            }
        }
        return $output;
    }

    public function get_tags_by_id($tag_ids)
    {
        if (empty($tag_ids)) {
            return null;
        }
        $tags = get_terms([
            'taxonomy' => 'product_tag',
            'include' => Array_Helper::flatten($tag_ids),
            'hide_empty' => false,
            'fields' => 'id=>name'
        ]);
        return $tags;
    }

    public function get_shipping_classes_by_id($shipping_class_ids)
    {
        if (empty($shipping_class_ids)) {
            return null;
        }
        $shipping_classes = get_terms([
            'include' => Array_Helper::flatten($shipping_class_ids),
            'hide_empty' => false,
            'fields' => 'id=>name'
        ]);

        return $shipping_classes;
    }

    public function get_attributes_by_id($attribute_ids)
    {
        if (empty($attribute_ids)) {
            return null;
        }
        $output = [];
        $attributes = get_terms([
            'include' => Array_Helper::flatten($attribute_ids),
            'hide_empty' => false,
        ]);
        if (!empty($attributes)) {
            foreach ($attributes as $attribute) {
                if ($attribute instanceof \WP_Term) {
                    $output[$attribute->term_id] = $attribute->taxonomy . ': ' . $attribute->name;
                }
            }
        }
        return $output;
    }

    public function get_categories_by_id($category_ids)
    {
        if (empty($category_ids)) {
            return null;
        }
        $categories = get_terms([
            'taxonomy' => 'product_cat',
            'include' => Array_Helper::flatten($category_ids),
            'hide_empty' => false,
            'fields' => 'id=>name'
        ]);

        return $categories;
    }

    public function get_variations_by_id($variation_ids)
    {
        if (empty($variation_ids)) {
            return null;
        }

        $output = [];
        $products = wc_get_products([
            'type' => 'variation',
            'include' => Array_Helper::flatten($variation_ids),
        ]);

        if (!empty($products)) {
            foreach ($products as $product) {
                if ($product instanceof \WC_Product_Variation) {
                    $output[$product->get_id()] = $product->get_name();
                }
            }
        }

        return $output;
    }

    public function get_coupons_by_id($coupon_ids)
    {
        if (empty($coupon_ids)) {
            return null;
        }
        $output = [];
        $coupons = new \WP_Query([
            'post_type' => ['shop_coupon'],
            'include' => Array_Helper::flatten($coupon_ids),
        ]);

        if (!empty($coupons->posts)) {
            foreach ($coupons->posts as $coupon) {
                if ($coupon instanceof \WP_Post) {
                    $output[$coupon->ID] = $coupon->post_title;
                }
            }
        }

        return $output;
    }

    public function get_product_name_by_ids($product_ids)
    {
        $output = [];
        $products = $this->get_product_object_by_ids(Array_Helper::flatten($product_ids));

        if (!empty($products)) {
            foreach ($products as $product) {
                if ($product instanceof \WC_Product) {
                    $output[$product->get_id()] = $product->get_name();
                }
            }
        }

        return $output;
    }

    public function get_products_by_item_ids($order_item_ids, $limit = null)
    {
        if (!is_array($order_item_ids) || empty($order_item_ids)) {
            return false;
        }

        if (!is_null($this->gift_product_ids)) {
            return is_null($limit)
                ? $this->gift_product_ids
                : array_slice($this->gift_product_ids, 0, intval($limit));
        }

        global $wpdb;

        $placeholders = implode(',', array_fill(0, count($order_item_ids), '%d'));
        $query = "SELECT 
                IF(itemmeta2.meta_value = 0, itemmeta.meta_value, itemmeta2.meta_value) AS product_id,
                SUM(CAST(itemmeta3.meta_value AS UNSIGNED)) AS product_count,
                products.post_title AS product_name
            FROM {$wpdb->prefix}woocommerce_order_items AS order_items
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS itemmeta 
                ON order_items.order_item_id = itemmeta.order_item_id
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS itemmeta2 
                ON order_items.order_item_id = itemmeta2.order_item_id
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS itemmeta3 
                ON order_items.order_item_id = itemmeta3.order_item_id
            LEFT JOIN {$wpdb->posts} AS products 
                ON IF(itemmeta2.meta_value = 0, itemmeta.meta_value, itemmeta2.meta_value) = products.ID
            WHERE itemmeta.order_item_id IN ($placeholders)
                AND itemmeta.meta_key = '_product_id'
                AND itemmeta2.meta_key = '_variation_id'
                AND itemmeta3.meta_key = '_qty'
            GROUP BY product_id
            ORDER BY product_count DESC
        ";

        $prepared_query = $wpdb->prepare($query, ...array_map('intval', $order_item_ids)); //phpcs:ignore
        $this->gift_product_ids = $wpdb->get_results($prepared_query, ARRAY_A); //phpcs:ignore

        return is_null($limit)
            ? $this->gift_product_ids
            : array_slice($this->gift_product_ids, 0, intval($limit));
    }


    public function get_product_category_with_count_by_order_item_ids($item_ids, $limit = null)
    {
        if (empty($this->product_category_with_count)) {
            $ids = (!is_array($item_ids)) ? array_map('intval', explode(',', $item_ids)) : array_map('intval', $item_ids);
            if (empty($ids)) {
                return [];
            }

            $products = $this->get_products_by_item_ids($ids);
            $product_ids = array_column($products, 'product_id');
            if (!empty($product_ids)) {
                foreach ($product_ids as $product_id) {
                    $terms = get_the_terms(intval($product_id), 'product_cat');
                    if (!empty($terms)) {
                        foreach ($terms as $term) {
                            if ($term instanceof \WP_Term) {
                                $category_ids[] = $term->term_id;
                                $categories[$term->term_id]['object'] = $term;
                            }
                        }
                    }
                }
            }

            $categories_count = array_count_values($category_ids);
            if (!empty($categories_count)) {
                foreach ($categories_count as $category_id => $count) {
                    $categories[$category_id]['count'] = sanitize_text_field($count);
                }
            }

            usort($categories, function ($a, $b) {
                return - ($a['count'] - $b['count']);
            });

            $this->product_category_with_count = $categories;
        }

        return is_null($limit) ? $this->product_category_with_count : array_slice($this->product_category_with_count, 0, intval($limit));
    }

    public function get_gift_products($filters = [])
    {
        $filter_query = '';
        $params = [];

        if (!empty($filters['order_item_ids']) && is_array($filters['order_item_ids'])) {
            $placeholders = implode(',', array_fill(0, count($filters['order_item_ids']), '%d'));
            $filter_query .= " AND itemmeta.order_item_id IN ($placeholders)";
            $params = array_merge($params, array_map('intval', $filters['order_item_ids']));
        }

        if (!empty($filters['product_ids']) && is_array($filters['product_ids'])) {
            $placeholders = implode(',', array_fill(0, count($filters['product_ids']), '%d'));
            $filter_query .= " AND products.ID IN ($placeholders)";
            $params = array_merge($params, array_map('intval', $filters['product_ids']));
        }

        if (!empty($filters['term_ids']) && is_array($filters['term_ids'])) {
            $placeholders = implode(',', array_fill(0, count($filters['term_ids']), '%d'));
            $filter_query .= " AND terms.term_id IN ($placeholders)";
            $params = array_merge($params, array_map('intval', $filters['term_ids']));
        }

        $sql = "SELECT 
                products.ID AS product_id,
                SUM(CAST(itemmeta3.meta_value AS UNSIGNED)) AS product_count,
                products.post_title AS product_name
            FROM {$this->wpdb->prefix}woocommerce_order_items AS order_items
            LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta AS itemmeta 
                ON order_items.order_item_id = itemmeta.order_item_id
            LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta AS itemmeta2 
                ON order_items.order_item_id = itemmeta2.order_item_id
            LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta AS itemmeta3 
                ON order_items.order_item_id = itemmeta3.order_item_id
            LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta AS itemmeta4 
                ON order_items.order_item_id = itemmeta4.order_item_id
            LEFT JOIN {$this->wpdb->posts} AS products 
                ON IF(itemmeta2.meta_value = 0, itemmeta.meta_value, itemmeta2.meta_value) = products.ID
            LEFT JOIN {$this->wpdb->prefix}term_relationships AS term_relation 
                ON products.ID = term_relation.object_id
            LEFT JOIN {$this->wpdb->prefix}terms AS terms 
                ON terms.term_id = term_relation.term_taxonomy_id
            WHERE 
                itemmeta.meta_key = '_product_id'
                AND itemmeta4.meta_key = '_rule_id_free_gift'
                AND itemmeta2.meta_key = '_variation_id'
                AND itemmeta3.meta_key = '_qty'
                AND products.post_type = 'product'
                $filter_query
            GROUP BY product_id
            ORDER BY product_count DESC
        ";

        $prepared_sql = empty($params) ? $sql : $this->wpdb->prepare($sql, ...$params); //phpcs:ignore
        return $this->wpdb->get_results($prepared_sql, ARRAY_A); //phpcs:ignore
    }

    public function get_gotten_gifts_by_customer($filters = [])
    {
        global $wpdb;

        $filter_clauses = [];
        $filter_params = [];

        if (!empty($filters['order_item_ids']) && is_array($filters['order_item_ids'])) {
            $order_item_ids = array_map('intval', $filters['order_item_ids']);
            if (!empty($order_item_ids)) {
                $placeholders = implode(',', array_fill(0, count($order_item_ids), '%d'));
                $filter_clauses[] = "itemmeta.order_item_id IN ({$placeholders})";
                $filter_params = array_merge($filter_params, $order_item_ids);
            }
        }

        if (!empty($filters['date']['from']) && !empty($filters['date']['to'])) {
            $from = sanitize_text_field($filters['date']['from']);
            $to = sanitize_text_field($filters['date']['to']);
            $filter_clauses[] = "orders.post_date BETWEEN %s AND %s";
            $filter_params[] = $from;
            $filter_params[] = $to;
        }

        if (!empty($filters['product_ids'])) {
            if (is_array($filters['product_ids'])) {
                $product_ids = array_map('intval', $filters['product_ids']);
            } else {
                $product_ids = array_map('intval', explode(',', $filters['product_ids']));
            }
            if (!empty($product_ids)) {
                $placeholders = implode(',', array_fill(0, count($product_ids), '%d'));
                $filter_clauses[] = "products.ID IN ({$placeholders})";
                $filter_params = array_merge($filter_params, $product_ids);
            }
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

        if (!empty($filters['customer_email'])) {
            $customer_email = '%' . $wpdb->esc_like(sanitize_text_field($filters['customer_email'])) . '%';
            $filter_clauses[] = "(users.user_email != '' AND users.user_email LIKE %s OR postmeta2.meta_value LIKE %s)";
            $filter_params[] = $customer_email;
            $filter_params[] = $customer_email;
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
                products.post_title as product_name,
                IF(users.user_email != '', users.user_email, postmeta2.meta_value) as user_email,
                itemmeta.order_item_id,
                itemmeta.meta_value as rule_id
            FROM {$wpdb->posts} as orders
            LEFT JOIN {$wpdb->prefix}postmeta as postmeta ON (postmeta.post_id = orders.ID)
            LEFT JOIN {$wpdb->prefix}postmeta as postmeta2 ON (postmeta2.post_id = orders.ID)
            LEFT JOIN {$wpdb->users} as users ON (users.ID = postmeta.meta_value)
            LEFT JOIN {$wpdb->prefix}woocommerce_order_items as order_items ON (order_items.order_id = orders.ID)
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as itemmeta ON (order_items.order_item_id = itemmeta.order_item_id)
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as itemmeta2 ON (order_items.order_item_id = itemmeta2.order_item_id)
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as itemmeta3 ON (order_items.order_item_id = itemmeta3.order_item_id)
            LEFT JOIN {$wpdb->posts} as products ON IF(itemmeta2.meta_value = 0, itemmeta3.meta_value = products.ID,itemmeta2.meta_value = products.ID)
            WHERE
                itemmeta.meta_key = '_rule_id_free_gift'
                AND postmeta.meta_key = '_customer_user'
                AND itemmeta3.meta_key = '_product_id'
                AND postmeta2.meta_key = '_billing_email'
                AND itemmeta2.meta_key = '_variation_id'
                {$filter_query}
        ";

        $prepared_sql = $wpdb->prepare($sql, ...$filter_params); //phpcs:ignore
        return $wpdb->get_results($prepared_sql, ARRAY_A); //phpcs:ignore
    }
}
