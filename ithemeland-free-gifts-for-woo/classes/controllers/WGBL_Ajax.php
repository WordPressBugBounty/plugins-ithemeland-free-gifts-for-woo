<?php

namespace wgbl\classes\controllers;

use wgbl\classes\presenters\reports\Report_Presenter;
use wgbl\classes\repositories\Product;
use wgbl\classes\repositories\User;

class WGBL_Ajax
{
    private static $instance;
    private $product_repository;

    public static function register_callback()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->product_repository = Product::get_instance();
        add_action('wp_ajax_wgbl_get_customers', [$this, 'get_customers']);
        add_action('wp_ajax_wgbl_get_user_roles', [$this, 'get_user_roles']);
        add_action('wp_ajax_wgbl_get_user_capabilities', [$this, 'get_user_capabilities']);
        add_action('wp_ajax_wgbl_get_products', [$this, 'get_products']);
        add_action('wp_ajax_wgbl_get_products_variations', [$this, 'get_products_variations']);
        add_action('wp_ajax_wgbl_get_taxonomies', [$this, 'get_taxonomies']);
        add_action('wp_ajax_wgbl_get_variations', [$this, 'get_variations']);
        add_action('wp_ajax_wgbl_get_tags', [$this, 'get_tags']);
        add_action('wp_ajax_wgbl_get_categories', [$this, 'get_categories']);
        add_action('wp_ajax_wgbl_get_attributes', [$this, 'get_attributes']);
        add_action('wp_ajax_wgbl_get_shipping_class', [$this, 'get_shipping_class']);
        add_action('wp_ajax_wgbl_get_coupons', [$this, 'get_coupons']);
        add_action('wp_ajax_wgbl_get_reports', [$this, 'get_reports']);
    }

    public function get_customers()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wgbl_ajax_nonce')) {
            die();
        }

        $user_repository = new User();
        $list = [];
        $customers = $user_repository->get_users_by_name(sanitize_text_field($_POST['search']));
        if (!empty($customers->results)) {
            foreach ($customers->results as $customer) {
                if ($customer instanceof \WP_User) {
                    $list['results'][] = [
                        'id' => $customer->ID,
                        'text' => $customer->user_nicename
                    ];
                }
            }
        }

        $this->make_response($list);
    }

    public function get_user_roles()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wgbl_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        $roles = wp_roles();

        if (!empty($roles)) {
            foreach ($roles->roles as $roleKey => $role) {
                if (isset($role['name']) && strpos($roleKey, strtolower(sanitize_text_field($_POST['search']))) !== false) {
                    $list['results'][] = [
                        'id' => $roleKey,
                        'text' => $role['name']
                    ];
                }
            }
        }

        $this->make_response($list);
    }

    public function get_user_capabilities()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wgbl_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        $capabilities = get_role('administrator')->capabilities;

        if (!empty($capabilities)) {
            foreach ($capabilities as $capability => $value) {
                if (strpos($capability, strtolower(sanitize_text_field($_POST['search']))) !== false) {
                    $list['results'][] = [
                        'id' => $capability,
                        'text' => $capability
                    ];
                }
            }
        }

        $this->make_response($list);
    }

    public function get_products()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wgbl_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        $products = $this->product_repository->get_products([
            'posts_per_page' => '-1',
            'post_status' => 'publish',
            'post_type' => ['product'],
            'wgbl_general_column_filter' => [
                [
                    'field' => 'post_title',
                    'value' => strtolower(sanitize_text_field($_POST['search'])),
                    'operator' => 'like'
                ]
            ]
        ]);

        if (!empty($products->posts)) {
            foreach ($products->posts as $product) {
                $list['results'][] = [
                    'id' => $product->ID,
                    'text' => $product->post_title,
                ];
            }
        }

        $this->make_response($list);
    }

    public function get_products_variations()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wgbl_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        $products = $this->product_repository->get_products([
            'posts_per_page' => '-1',
            'post_status' => 'publish',
            'post_type' => ['product', 'product_variation'],
            'wgbl_general_column_filter' => [
                [
                    'field' => 'post_title',
                    'value' => strtolower(sanitize_text_field($_POST['search'])),
                    'operator' => 'like'
                ]
            ]
        ]);

        if (!empty($products->posts)) {
            foreach ($products->posts as $product) {
                $list['results'][] = [
                    'id' => $product->ID,
                    'text' => $product->post_title,
                ];
            }
        }

        $this->make_response($list);
    }

    public function get_taxonomies()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wgbl_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        $taxonomies = $this->product_repository->get_taxonomies_by_name(sanitize_text_field($_POST['search']));
        if (!empty($taxonomies)) {
            foreach ($taxonomies as $key => $taxonomyItems) {
                if (!empty($taxonomyItems) && !in_array($key, ['product_visibility', 'product_type'])) {
                    foreach ($taxonomyItems as $taxonomyItem) {
                        if ($taxonomyItem instanceof \WP_Term) {
                            $list['results'][] = [
                                'id' => $taxonomyItem->taxonomy . '__' . $taxonomyItem->term_id,
                                'text' => $key . ': ' . $taxonomyItem->name
                            ];
                        }
                    }
                }
            }
        }
        $this->make_response($list);
    }

    public function get_variations()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wgbl_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        $products = $this->product_repository->get_products([
            'posts_per_page' => '-1',
            'post_status' => 'publish',
            'post_type' => ['product_variation'],
            'wgbl_general_column_filter' => [
                [
                    'field' => 'post_title',
                    'value' => strtolower(sanitize_text_field($_POST['search'])),
                    'operator' => 'like'
                ]
            ]
        ]);

        if (!empty($products->posts)) {
            foreach ($products->posts as $product) {
                $list['results'][] = [
                    'id' => $product->ID,
                    'text' => $product->post_title,
                ];
            }
        }

        $this->make_response($list);
    }

    public function get_tags()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wgbl_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        $tags = $this->product_repository->get_tags_by_name(sanitize_text_field($_POST['search']));
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                if ($tag instanceof \WP_Term) {
                    $list['results'][] = [
                        'id' => $tag->term_id,
                        'text' => $tag->name
                    ];
                }
            }
        }
        $this->make_response($list);
    }

    public function get_categories()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wgbl_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        $categories = $this->product_repository->get_categories_by_name(sanitize_text_field($_POST['search']));
        if (!empty($categories)) {
            foreach ($categories as $category) {
                if ($category instanceof \WP_Term) {
                    $list['results'][] = [
                        'id' => $category->term_id,
                        'text' => $category->name
                    ];
                }
            }
        }
        $this->make_response($list);
    }

    public function get_attributes()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wgbl_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        $attributes = $this->product_repository->get_attributes_by_name(sanitize_text_field($_POST['search']));
        if (!empty($attributes)) {
            foreach ($attributes as $key => $attributeItems) {
                if (!empty($attributeItems)) {
                    foreach ($attributeItems as $attributeItem) {
                        if ($attributeItem instanceof \WP_Term) {
                            $list['results'][] = [
                                'id' => $attributeItem->term_id,
                                'text' => $key . ': ' . $attributeItem->name
                            ];
                        }
                    }
                }
            }
        }
        $this->make_response($list);
    }

    public function get_shipping_class()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wgbl_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        $classes = $this->product_repository->get_shipping_classes();
        if (!empty($classes)) {
            foreach ($classes as $class) {
                if ($class instanceof \WP_Term && strpos($class->name, strtolower(sanitize_text_field($_POST['search']))) !== false) {
                    $list['results'][] = [
                        'id' => $class->term_id,
                        'text' => $class->name
                    ];
                }
            }
        }

        $this->make_response($list);
    }

    public function get_coupons()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wgbl_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        $coupons = $this->product_repository->get_products([
            'posts_per_page' => '-1',
            'post_status' => 'publish',
            'post_type' => ['shop_coupon'],
            'wgbl_general_column_filter' => [
                [
                    'field' => 'post_title',
                    'value' => strtolower(sanitize_text_field($_POST['search'])),
                    'operator' => 'like'
                ]
            ]
        ]);

        if (!empty($coupons->posts)) {
            foreach ($coupons->posts as $coupon) {
                if ($coupon instanceof \WP_Post) {
                    $list['results'][] = [
                        'id' => $coupon->ID,
                        'text' => $coupon->post_title
                    ];
                }
            }
        }

        $this->make_response($list);
    }

    public function get_reports()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wgbl_ajax_nonce')) {
            die();
        }
        
        $page_data = [];
        if (!empty($_POST['dates']['from']) && !empty($_POST['dates']['from'])) {
            $page_data = [
                'from' => sanitize_text_field($_POST['dates']['from']),
                'to' => sanitize_text_field($_POST['dates']['to']),
            ];
        }

        // get page params
        if (!empty($_POST['page_params'])) {
            $params_string = str_replace('?', '', $_POST['page_params']);
            $params_array = explode('&', $params_string);
            if (!empty($params_array)) {
                foreach ($params_array as $param) {
                    $param_array = explode('=', $param);
                    if (!empty($param_array[0]) && !empty($param_array[1])) {
                        $page_data[sanitize_text_field($param_array[0])] = sanitize_text_field(urldecode($param_array[1]));
                    }
                }
            }
        }

        // get reports
        $report_presenter = Report_Presenter::get_instance();
        $reports = $report_presenter->get_reports($page_data);

        $this->make_response($reports);
    }

    private function make_response($data)
    {
        echo (is_array($data)) ? wp_json_encode($data) : esc_html($data);
        die();
    }
}
