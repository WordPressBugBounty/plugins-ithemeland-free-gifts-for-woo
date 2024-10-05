<?php

namespace wgbl\classes\repositories;

use wgbl\classes\helpers\Array_Helper;

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

    public function get_ids_by_custom_query($join, $where, $types_in = 'all')
    {
        global $wpdb;
        switch ($types_in) {
            case 'all':
                $types = "'product','product_variation','shop_coupon'";
                break;
            case 'product':
                $types = "'product'";
                break;
            case 'shop_coupon':
                $types = "'shop_coupon'";
                break;
            case 'product_variation':
                $types = "'product_variation'";
                break;
        }
        $ids = $wpdb->get_results("SELECT posts.ID, posts.post_parent FROM $wpdb->posts AS posts {$join} WHERE posts.post_type IN ($types) AND ({$where})", ARRAY_N); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $ids = array_unique(Array_Helper::flatten($ids, 'int'));
        if ($key = array_search(0, $ids) !== false) {
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
        if (empty($this->gift_product_ids)) {
            if (empty($order_item_ids) || !is_array($order_item_ids)) {
                return false;
            }
            $order_item_ids = implode(',', array_map('intval', $order_item_ids));
            $this->gift_product_ids = $this->wpdb->get_results("SELECT IF(itemmeta2.meta_value = 0, itemmeta.meta_value, itemmeta2.meta_value) as product_id, SUM(itemmeta3.meta_value) as product_count, products.post_title as product_name FROM {$this->wpdb->prefix}woocommerce_order_items as order_items LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta as itemmeta ON (order_items.order_item_id = itemmeta.order_item_id) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta as itemmeta2 ON (order_items.order_item_id = itemmeta2.order_item_id) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta as itemmeta3 ON (order_items.order_item_id = itemmeta3.order_item_id) LEFT JOIN {$this->wpdb->posts} as products ON IF(itemmeta2.meta_value = 0, itemmeta.meta_value = products.ID,itemmeta2.meta_value = products.ID) WHERE itemmeta.order_item_id IN ({$order_item_ids}) AND itemmeta.meta_key = '_product_id' AND itemmeta2.meta_key = '_variation_id' AND itemmeta3.meta_key = '_qty' GROUP BY product_id ORDER BY product_count DESC", ARRAY_A); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        }

        return is_null($limit) ? $this->gift_product_ids : array_slice($this->gift_product_ids, 0, intval($limit));
    }

    public function get_product_category_with_count_by_order_item_ids($item_ids, $limit = null)
    {
        if (empty($this->product_category_with_count)) {
            $ids = (is_array($item_ids)) ? sanitize_text_field(implode(',', $item_ids)) : sanitize_text_field($item_ids);
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

        if (!empty($filters['order_item_ids']) && is_array($filters['order_item_ids'])) {
            $order_item_ids = implode(',', array_map('intval', $filters['order_item_ids']));
            $filter_query .= " AND itemmeta.order_item_id IN ({$order_item_ids})";
        }

        if (!empty($filters['product_ids'])) {
            $product_ids = sanitize_text_field($filters['product_ids']);
            $filter_query .= " AND products.ID IN ({$product_ids})";
        }

        if (!empty($filters['term_ids'])) {
            $term_ids = sanitize_text_field($filters['term_ids']);
            $filter_query .= " AND terms.term_id IN ({$term_ids})";
        }

        return $this->wpdb->get_results("SELECT products.ID as product_id, SUM(DISTINCT itemmeta3.meta_value) as product_count, products.post_title as product_name FROM {$this->wpdb->prefix}woocommerce_order_items as order_items LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta as itemmeta ON (order_items.order_item_id = itemmeta.order_item_id) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta as itemmeta2 ON (order_items.order_item_id = itemmeta2.order_item_id) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta as itemmeta3 ON (order_items.order_item_id = itemmeta3.order_item_id) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta as itemmeta4 ON (order_items.order_item_id = itemmeta4.order_item_id) LEFT JOIN {$this->wpdb->posts} as products ON IF(itemmeta2.meta_value = 0, itemmeta.meta_value = products.ID,itemmeta2.meta_value = products.ID) LEFT JOIN {$this->wpdb->prefix}term_relationships as term_relation ON (products.ID = term_relation.object_id) LEFT JOIN {$this->wpdb->prefix}terms as terms ON (terms.term_id = term_relation.term_taxonomy_id) WHERE itemmeta.meta_key = '_product_id' AND itemmeta4.meta_key = '_rule_id_free_gift' AND itemmeta2.meta_key = '_variation_id' AND itemmeta3.meta_key = '_qty' {$filter_query} GROUP BY product_id ORDER BY product_count DESC", ARRAY_A); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    }

    public function get_gotten_gifts_by_customer($filters = [])
    {
        $filter_query = '';

        if (!empty($filters['order_item_ids']) && is_array($filters['order_item_ids'])) {
            $order_item_ids = implode(',', array_map('intval', $filters['order_item_ids']));
            $filter_query .= " AND itemmeta.order_item_id IN ({$order_item_ids})";
        }

        if (!empty($filters['date']) && !empty($filters['date']['from']) && !empty($filters['date']['to'])) {
            $from = sanitize_text_field($filters['date']['from']);
            $to = sanitize_text_field($filters['date']['to']);
            $filter_query .= " AND orders.post_date BETWEEN '{$from}' AND '{$to}'";
        }

        if (!empty($filters['product_ids'])) {
            $product_ids = sanitize_text_field($filters['product_ids']);
            $filter_query .= " AND products.ID IN ({$product_ids})";
        }

        if (!empty($filters['customer_ids'])) {
            $customer_ids = sanitize_text_field($filters['customer_ids']);
            $filter_query .= " AND users.ID IN ({$customer_ids})";
        }

        if (!empty($filters['customer_email'])) {
            $customer_email = sanitize_text_field($filters['customer_email']);
            $filter_query .= " AND IF(users.user_email != '', users.user_email LIKE '%{$customer_email}%', postmeta2.meta_value LIKE '%{$customer_email}%')";
        }

        if (!empty($filters['rule_ids'])) {
            $rule_ids = sanitize_text_field($filters['rule_ids']);
            $filter_query .= " AND itemmeta.meta_value IN ({$rule_ids})";
        }

        return $this->wpdb->get_results("SELECT orders.ID as order_id, orders.post_date as order_date, products.post_title as product_name, IF(users.user_email != '', users.user_email, postmeta2.meta_value) as user_email, itemmeta.order_item_id, itemmeta.meta_value as rule_id FROM {$this->wpdb->posts} as orders LEFT JOIN {$this->wpdb->prefix}postmeta as postmeta ON (postmeta.post_id = orders.ID) LEFT JOIN {$this->wpdb->prefix}postmeta as postmeta2 ON (postmeta2.post_id = orders.ID) LEFT JOIN {$this->wpdb->users} as users ON (users.ID = postmeta.meta_value) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_items as order_items ON (order_items.order_id = orders.ID) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta as itemmeta ON (order_items.order_item_id = itemmeta.order_item_id) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta as itemmeta2 ON (order_items.order_item_id = itemmeta2.order_item_id) LEFT JOIN {$this->wpdb->prefix}woocommerce_order_itemmeta as itemmeta3 ON (order_items.order_item_id = itemmeta3.order_item_id) LEFT JOIN {$this->wpdb->posts} as products ON IF(itemmeta2.meta_value = 0, itemmeta3.meta_value = products.ID,itemmeta2.meta_value = products.ID) WHERE itemmeta.meta_key = '_rule_id_free_gift' AND postmeta.meta_key = '_customer_user' AND itemmeta3.meta_key = '_product_id' AND postmeta2.meta_key = '_billing_email' AND itemmeta2.meta_key = '_variation_id' {$filter_query}", ARRAY_A); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    }
}
