<?php

namespace wgb\classes\controllers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wgb\classes\helpers\Sanitizer;
use wgb\classes\presenters\reports\Report_Presenter;
use wgb\classes\repositories\Product;
use wgb\classes\repositories\Rule;
use wgb\classes\repositories\User;

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
        add_action('wp_ajax_wgb_get_customers', [$this, 'get_customers']);
        add_action('wp_ajax_wgb_get_payment_methods', [$this, 'get_payment_methods']);
        add_action('wp_ajax_wgb_get_shipping_country', [$this, 'get_shipping_country']);
        add_action('wp_ajax_wgb_get_user_roles', [$this, 'get_user_roles']);
        add_action('wp_ajax_wgb_get_user_capabilities', [$this, 'get_user_capabilities']);
        add_action('wp_ajax_wgb_get_products', [$this, 'get_products']);
        add_action('wp_ajax_wgb_get_products_variations', [$this, 'get_products_variations']);
        add_action('wp_ajax_wgb_get_taxonomies', [$this, 'get_taxonomies']);
        add_action('wp_ajax_wgb_get_variations', [$this, 'get_variations']);
        add_action('wp_ajax_wgb_get_tags', [$this, 'get_tags']);
        add_action('wp_ajax_wgb_get_categories', [$this, 'get_categories']);
        add_action('wp_ajax_wgb_get_attributes', [$this, 'get_attributes']);
        add_action('wp_ajax_wgb_get_shipping_class', [$this, 'get_shipping_class']);
        add_action('wp_ajax_wgb_get_coupons', [$this, 'get_coupons']);
        add_action('wp_ajax_wgb_get_reports', [$this, 'get_reports']);
        add_action('wp_ajax_wgb_get_new_rule_html', [$this, 'get_new_rule_html']);
        add_action('wp_ajax_wgb_get_new_condition_html', [$this, 'get_new_condition_html']);
        add_action('wp_ajax_wgb_get_condition_extra_field_html', [$this, 'get_condition_extra_field_html']);
        add_action('wp_ajax_wgb_get_new_product_buy_html', [$this, 'get_new_product_buy_html']);
        add_action('wp_ajax_wgb_get_product_buy_extra_field_html', [$this, 'get_product_buy_extra_field_html']);
        add_action('wp_ajax_wgb_get_brands', [$this, 'get_brands']);
    }

    public function get_customers()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        $user_repository = new User();
        $list['results'] = [];
        if (!empty($_POST['search'])) {
            $customers = $user_repository->get_users_by_name(sanitize_text_field(wp_unslash($_POST['search'])));
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
        }

        $this->make_response($list);
    }

    public function get_payment_methods()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        global $woocommerce;

        $gateways = $woocommerce->payment_gateways();

        $list['results'] = [];
        foreach ($gateways->payment_gateways() as $gateway_key => $gateway) {
            if ($gateway_key == 'pre_install_woocommerce_payments_promotion') {
                continue;
            }

            $method_title = $gateway->get_method_title();

            if (!empty($gateway->title) && is_string($gateway->title) && $gateway->title !== $method_title) {
                $method_title .= ' (' . $gateway->title . ')';
            }

            if (isset($_POST['search']) && isset($gateway_key) && strpos(strtolower($method_title), strtolower(sanitize_text_field(wp_unslash($_POST['search'])))) !== false) {
                $list['results'][] = [
                    'id' => (string)$gateway_key,
                    'text' => $method_title
                ];
            }
        }

        $this->make_response($list);
    }

    public function get_shipping_country()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        global $woocommerce;

        $countries = WC()->countries->get_countries();
        $list['results'] = [];
        // Iterate over all countries
        foreach ($countries as $code => $country_title) {

            if (isset($_POST['search']) && isset($code) && strpos(strtolower($country_title), strtolower(sanitize_text_field(wp_unslash($_POST['search'])))) !== false) {
                // Add item
                $list['results'][] = array(
                    'id'    => (string) $code,
                    'text'  => $code . ' - (' . $country_title . ')',
                );
            }
        }
        $this->make_response($list);
    }

    public function get_user_roles()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        $roles = wp_roles();

        if (!empty($roles)) {
            foreach ($roles->roles as $roleKey => $role) {
                if (isset($_POST['search']) && isset($role['name']) && strpos($roleKey, strtolower(sanitize_text_field(wp_unslash($_POST['search'])))) !== false) {
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
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        $capabilities = get_role('administrator')->capabilities;

        if (!empty($capabilities)) {
            foreach ($capabilities as $capability => $value) {
                if (isset($_POST['search']) && strpos($capability, strtolower(sanitize_text_field(wp_unslash($_POST['search'])))) !== false) {
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
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        if (!empty($_POST['search'])) {
            $products = $this->product_repository->get_products([
                'posts_per_page' => '-1',
                'post_status' => 'publish',
                'post_type' => ['product'],
                'wgb_general_column_filter' => [
                    [
                        'field' => 'post_title',
                        'value' => strtolower(sanitize_text_field(wp_unslash($_POST['search']))),
                        'operator' => 'like'
                    ]
                ]
            ]);
        }

        if (!empty($products->posts)) {
            foreach ($products->posts as $product) {
                $list['results'][] = [
                    'id' => $product->ID,
                    'text' => Product::get_product_label_for_rule_fields($product->ID),
                ];
            }
        }

        $this->make_response($list);
    }

    public function get_products_variations()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        if (!empty($_POST['search'])) {
            $query = $this->product_repository->get_products([
                'posts_per_page' => '-1',
                'post_status' => 'publish',
                'fields' => 'ids',
                'post_type' => ['product', 'product_variation'],
                'wgb_general_column_filter' => [
                    [
                        'field' => 'post_title',
                        'value' => strtolower(sanitize_text_field(wp_unslash($_POST['search']))),
                        'operator' => 'like'
                    ]
                ]
            ]);

            if (!empty($query->posts)) {
                if (!empty($query->posts)) {
                    foreach ($query->posts as $product_id) {
                        $list['results'][] = [
                            'id' => intval($product_id),
                            'text' => Product::get_product_label_for_rule_fields(intval($product_id)),
                        ];
                    }
                }
            }
        }

        $this->make_response($list);
    }

    public function get_taxonomies()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        if (!empty($_POST['search'])) {
            $taxonomies = $this->product_repository->get_taxonomies_by_name(sanitize_text_field(wp_unslash($_POST['search'])));
            if (!empty($taxonomies)) {
                foreach ($taxonomies as $key => $taxonomyItems) {
                    if (!empty($taxonomyItems) && !in_array($key, ['product_visibility', 'product_type'])) {
                        foreach ($taxonomyItems as $taxonomyItem) {
                            if ($taxonomyItem instanceof \WP_Term) {
                                if ($taxonomyItem->parent == '0') {
                                    $list['results'][] = [
                                        'id' => $taxonomyItem->taxonomy . '__' . $taxonomyItem->term_id,
                                        'text' => $key . ': ' . $taxonomyItem->name
                                    ];
                                    $children = get_terms([
                                        'taxonomy' => $taxonomyItem->taxonomy,
                                        'hide_empty' => false,
                                        'child_of' => intval($taxonomyItem->term_id)
                                    ]);
                                    if (!empty($children)) {
                                        foreach ($children as $child) {
                                            if ($child instanceof \WP_Term) {
                                                $list['results'][] = [
                                                    'id' => $taxonomyItem->taxonomy . '__' . $child->term_id,
                                                    'text' => $key . ': ' . $taxonomyItem->name . ' → ' . $child->name
                                                ];
                                            }
                                        }
                                    }
                                } else {
                                    $parent = get_term_by('term_id', intval($taxonomyItem->parent), $taxonomyItem->taxonomy);
                                    if ($parent instanceof \WP_Term) {
                                        $list['results'][] = [
                                            'id' => $taxonomyItem->taxonomy . '__' . $taxonomyItem->term_id,
                                            'text' => $key . ': ' . $parent->name . ' → ' . $taxonomyItem->name
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $this->make_response($list);
    }

    public function get_variations()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        if (!empty($_POST['search'])) {
            $products = $this->product_repository->get_products([
                'posts_per_page' => '-1',
                'post_status' => 'publish',
                'fields' => 'ids',
                'post_type' => ['product_variation'],
                'wgb_general_column_filter' => [
                    [
                        'field' => 'post_title',
                        'value' => strtolower(sanitize_text_field(wp_unslash($_POST['search']))),
                        'operator' => 'like'
                    ]
                ]
            ]);

            if (!empty($products->posts)) {
                foreach ($products->posts as $variation_id) {
                    $list['results'][] = [
                        'id' => intval($variation_id),
                        'text' => Product::get_product_label_for_rule_fields(intval($variation_id)),
                    ];
                }
            }
        }

        $this->make_response($list);
    }

    public function get_tags()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        if (!empty($_POST['search'])) {
            $tags = $this->product_repository->get_tags_by_name(sanitize_text_field(wp_unslash($_POST['search'])));
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
        }

        $this->make_response($list);
    }

    public function get_categories()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        if (!empty($_POST['search'])) {
            $categories = $this->product_repository->get_categories_by_name(sanitize_text_field(wp_unslash($_POST['search'])));
            if (!empty($categories)) {
                foreach ($categories as $category) {
                    if ($category instanceof \WP_Term) {
                        if ($category->parent == '0') {
                            $list['results'][] = [
                                'id' => $category->term_id,
                                'text' => $category->name
                            ];
                            $children = get_terms([
                                'taxonomy' => 'product_cat',
                                'hide_empty' => false,
                                'child_of' => intval($category->term_id)
                            ]);
                            if (!empty($children)) {
                                foreach ($children as $child) {
                                    if ($child instanceof \WP_Term) {
                                        $list['results'][] = [
                                            'id' => $child->term_id,
                                            'text' => $category->name . ' → ' . $child->name
                                        ];
                                    }
                                }
                            }
                        } else {
                            $parent = get_term_by('term_id', intval($category->parent), 'product_cat');
                            if ($parent instanceof \WP_Term) {
                                $list['results'][] = [
                                    'id' => $category->term_id,
                                    'text' => $parent->name . ' → ' . $category->name
                                ];
                            }
                        }
                    }
                }
            }
        }

        $this->make_response($list);
    }

    public function get_attributes()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        if (!empty($_POST['search'])) {
            $attributes = $this->product_repository->get_attributes_by_name(sanitize_text_field(wp_unslash($_POST['search'])));
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
        }

        $this->make_response($list);
    }

    public function get_shipping_class()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        if (!empty($_POST['search'])) {
            $classes = $this->product_repository->get_shipping_classes();
            if (!empty($classes)) {
                foreach ($classes as $class) {
                    if ($class instanceof \WP_Term && strpos($class->name, strtolower(sanitize_text_field(wp_unslash($_POST['search'])))) !== false) {
                        $list['results'][] = [
                            'id' => $class->term_id,
                            'text' => $class->name
                        ];
                    }
                }
            }
        }

        $this->make_response($list);
    }

    public function get_coupons()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        $list['results'] = [];
        if (!empty($_POST['search'])) {
            $coupons = $this->product_repository->get_products([
                'posts_per_page' => '-1',
                'post_status' => 'publish',
                'post_type' => ['shop_coupon'],
                'wgb_general_column_filter' => [
                    [
                        'field' => 'post_title',
                        'value' => strtolower(sanitize_text_field(wp_unslash($_POST['search']))),
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
        }

        $this->make_response($list);
    }

    public function get_reports()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        $page_data = [];
        if (!empty($_POST['dates']['from']) && !empty($_POST['dates']['to'])) {
            $page_data = [
                'from' => sanitize_text_field(wp_unslash($_POST['dates']['from'])),
                'to' => sanitize_text_field(wp_unslash($_POST['dates']['to'])),
            ];
        }

        // get page params
        if (!empty($_POST['page_params'])) {
            $params_string = str_replace('?', '', sanitize_text_field(wp_unslash($_POST['page_params'])));
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

    public function get_new_rule_html()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        if (empty($_POST['uid'])) {
            return false;
        }

        $rule_item = [
            'rule_name' => 'New Rule',
            'uid' => sanitize_text_field(wp_unslash($_POST['uid'])),
            'method' => 'simple',
            'status' => 'enable',
        ];
        $rule_id = (!empty($_POST['rule_id'])) ? sanitize_text_field(wp_unslash($_POST['rule_id'])) : '';

        $rule_repository = Rule::get_instance();
        $rule_methods = $rule_repository->get_rule_methods();

        ob_start();
        include WGBL_VIEWS_DIR . 'rules/rule-item.php';
        $html = ob_get_clean();

        $this->make_response([
            'success' => true,
            'html' => $html
        ]);
    }

    public function get_new_condition_html()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        if (!isset($_POST['condition_id']) || !isset($_POST['rule_id'])) {
            return false;
        }

        $condition_item = [
            'type' => 'date',
            'method_option' => 'from',
            'value' => '',
        ];
        $condition_id = sanitize_text_field(wp_unslash($_POST['condition_id']));
        $rule_id = sanitize_text_field(wp_unslash($_POST['rule_id']));

        ob_start();
        include WGBL_VIEWS_DIR . 'rules/conditions/row.php';
        $html = ob_get_clean();

        $this->make_response([
            'success' => true,
            'html' => $html
        ]);
    }

    public function get_condition_extra_field_html()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        if (!isset($_POST['condition_id']) || !isset($_POST['rule_id']) || !isset($_POST['condition_type'])) {
            return false;
        }

        $condition_item = [
            'type' => sanitize_text_field(wp_unslash($_POST['condition_type'])),
            'method_option' => '',
            'value' => '',
        ];
        $condition_id = sanitize_text_field(wp_unslash($_POST['condition_id']));
        $rule_id = sanitize_text_field(wp_unslash($_POST['rule_id']));

        ob_start();
        include WGBL_VIEWS_DIR . 'rules/conditions/extra-field.php';
        $html = ob_get_clean();

        $this->make_response([
            'success' => true,
            'html' => $html
        ]);
    }

    public function get_new_product_buy_html()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        if (!isset($_POST['product_buy_id']) || !isset($_POST['rule_id'])) {
            return false;
        }

        $product_buy_item = [
            'type' => 'product',
            'method_option' => 'in_list',
            'value' => '',
        ];
        $product_buy_id = sanitize_text_field(wp_unslash($_POST['product_buy_id']));
        $rule_id = sanitize_text_field(wp_unslash($_POST['rule_id']));

        ob_start();
        include WGBL_VIEWS_DIR . 'rules/product-buy/row.php';
        $html = ob_get_clean();

        $this->make_response([
            'success' => true,
            'html' => $html
        ]);
    }

    public function get_product_buy_extra_field_html()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }

        if (!isset($_POST['product_buy_id']) || !isset($_POST['rule_id']) || !isset($_POST['product_buy_type'])) {
            return false;
        }

        $product_buy_item = [
            'type' => sanitize_text_field(wp_unslash($_POST['product_buy_type'])),
            'method_option' => '',
            'value' => '',
        ];
        $product_buy_id = sanitize_text_field(wp_unslash($_POST['product_buy_id']));
        $rule_id = sanitize_text_field(wp_unslash($_POST['rule_id']));

        ob_start();
        include WGBL_VIEWS_DIR . 'rules/product-buy/extra-field.php';
        $html = ob_get_clean();

        $this->make_response([
            'success' => true,
            'html' => $html
        ]);
    }

    public function get_brands()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wgb_ajax_nonce')) {
            die();
        }
        $search = isset($_POST['search']) ? sanitize_text_field(wp_unslash($_POST['search'])) : '';
        $args = [
            'taxonomy'   => 'product_brand',
            'hide_empty' => false,
            'number'     => 20,
            'search'     => $search,
        ];
        $brands = get_terms($args);
        $list = ['results' => []];
        if (!is_wp_error($brands)) {
            foreach ($brands as $brand) {
                $list['results'][] = [
                    'id'   => $brand->term_id,
                    'text' => $brand->name,
                ];
            }
        }
        $this->make_response($list);
    }

    private function make_response($data)
    {
        echo (is_array($data)) ? json_encode($data) : wp_kses($data, Sanitizer::allowed_html());
        die();
    }
}
